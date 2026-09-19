# Arsitektur RAG Chatbot — IDIG Health Tech

Disesuaikan dengan codebase `web-publikasi-its` (Laravel 12, Inertia v3 + React 19,
Livewire v4, MySQL, `HasEnglishOverlay`, pola Actions/DTO).

## Ringkasan keputusan

| Keputusan                 | Pilihan                                               | Alasan                                                                       |
| ------------------------- | ----------------------------------------------------- | ---------------------------------------------------------------------------- |
| Tempat logika RAG         | Di dalam Laravel                                      | Knowledge base kecil; tidak perlu service kedua                              |
| Vector store              | Kolom BLOB di MySQL, similarity di PHP                | Bekerja identik di SQLite in-memory, jadi retrieval bisa diuji Pest          |
| Model generasi            | `gemini-3.5-flash-lite` (verifikasi dulu)             | Tugasnya menjawab dari konteks, bukan reasoning berat                        |
| Model embedding           | `gemini-embedding-001`, 768 dimensi (verifikasi dulu) | Satu vendor satu key; dukungan 100+ bahasa                                   |
| Thinking level            | `low`, disetel eksplisit                              | Flash-Lite tidak bisa mematikan thinking; default `medium` memboroskan kuota |
| Bilingual                 | Indeks ganda + filter locale                          | Situs sudah ID/EN lewat `HasEnglishOverlay`                                  |
| Widget publik & dashboard | React di `Features/Chatbot/`                          | Keduanya Inertia, bukan Blade                                                |
| Widget admin              | Tidak dibuat                                          | Admin punya `GlobalSearch`; chatbot tidak menambah nilai                     |

### Nama model wajib diverifikasi sebelum ditulis ke config

Google mempensiunkan model Gemini dengan cepat, dan model yang masih hidup untuk
akun lama bisa mengembalikan 404 untuk akun baru. Sebelum menetapkan nilai apa pun,
jalankan:

```bash
curl -s "https://generativelanguage.googleapis.com/v1beta/models" \
  -H "x-goog-api-key: $GEMINI_API_KEY" \
  | jq -r '.models[] | select(.supportedGenerationMethods[]? == "generateContent") | .name'
```

Ganti `generateContent` dengan `embedContent` untuk daftar model embedding. Hasil
perintah itu adalah satu-satunya daftar yang berlaku untuk project-mu.

Dua konsekuensi desain. Pertama, ID model hidup di `.env`, bukan di kode, supaya
penggantian nanti tidak menyentuh satu baris PHP pun. Kedua, jangan memakai alias
seperti `gemini-flash-latest` — perilaku model bisa berubah tanpa peringatan, dan
chatbot yang tadinya patuh pada larangan menebak harga bisa mulai berhalusinasi
setelah Google mengganti target aliasnya.

Mengganti model embedding punya konsekuensi lebih berat daripada mengganti model
chat: ruang vektor antar generasi embedding tidak kompatibel, sehingga seluruh
indeks harus dibangun ulang dari nol.

## Peta lapisan

```
resources/js/Features/Chatbot/          React — widget, hook, tipe
        │  POST (Wayfinder)
        ▼
routes/web.php → ChatbotController      Controller tipis, validasi + throttle
        │  ChatRequestData (DTO)
        ▼
app/Actions/Chatbot/AnswerQuestionAction        orkestrasi
        ├── RetrieveKnowledgeAction               cosine similarity
        │        └── App\Services\GeminiClient    embed pertanyaan
        ├── BuildUserOrderContextAction           data pesanan (khusus login)
        └── App\Services\GeminiClient             generate jawaban

app/Actions/Chatbot/BuildKnowledgeIndexAction    dipanggil artisan chatbot:index
        ├── sumber A: knowledge/{locale}/*.md
        └── sumber B: model Eloquent → dokumen
```

Penempatan tiap kelas mengikuti `CLAUDE.md`: logika bisnis di `app/Actions/`,
integrasi eksternal di `app/Services/`, payload antar-lapisan lewat `app/DTOs/`.

## Kenapa similarity dihitung di PHP

Knowledge base lab ini diperkirakan 400–900 chunk per locale. Memindai seluruhnya
dengan dot product memakan waktu di bawah 20 ms — jauh lebih murah daripada
menambah pgvector atau Qdrant.

Ada alasan kedua yang lebih menentukan: `AGENTS.md` menetapkan tes berjalan di
SQLite in-memory. Ekstensi pgvector tidak tersedia di sana, sehingga retrieval
berbasis pgvector tidak bisa diuji tanpa layanan eksternal. Pendekatan BLOB + PHP
berjalan identik di MySQL produksi dan SQLite tes, jadi `RetrieveKnowledgeAction`
bisa diuji Pest sepenuhnya.

Batas praktisnya sekitar 5.000 chunk. Kalau terlampaui, ganti isi
`RetrieveKnowledgeAction` dengan pgvector — kontrak publiknya tidak berubah.

## Penanganan bilingual

Setiap dokumen diindeks dua kali, sekali per locale, dengan kolom `locale` pada
`knowledge_chunks`. Saat query, filter `where('locale', app()->getLocale())`.

Model embedding mendukung kedua bahasa dalam satu ruang vektor, jadi secara teknis
pencarian lintas bahasa berfungsi. Tapi hasilnya buruk dalam praktik: konteks jadi
campur bahasa, model ikut menjawab campur bahasa, dan tautan sumber mengarah ke
versi bahasa yang salah. Filter locale menghilangkan seluruh kelas masalah itu.

Aturan fallback: kalau locale aktif `en` dan tidak ada chunk yang lolos ambang,
ulangi pencarian pada locale `id`, lalu instruksikan model menjawab dalam bahasa
Inggris berdasarkan konteks Indonesia. Ini lebih baik daripada menyerah, karena
`HasEnglishOverlay` memang membiarkan baris setengah-terjemah jatuh balik ke
Indonesia.

## Dua mode akses

Endpoint tunggal `POST /chatbot/ask` dengan middleware `web`. Perbedaan mode
ditentukan `$request->user()`, bukan route terpisah.

**Mode publik** (landing page, tamu). Hanya chunk dengan `audience = public`.
Tidak ada akses ke data akun mana pun.

**Mode terautentikasi** (dashboard user). Chunk publik, ditambah ringkasan pesanan
milik user dari `BuildUserOrderContextAction`. Ringkasan itu diambil lewat query
Eloquent biasa yang difilter `user_id` dari session — bukan lewat pencarian vektor.

Aturan yang tidak boleh dilanggar: **data pesanan tidak pernah masuk
`knowledge_chunks`**. Kalau data semua user berada di satu indeks bersama, satu
kesalahan filter berubah jadi kebocoran antar-user. Memisahkan jalurnya membuat
kelas bug itu mustahil terjadi.

Untuk peran admin (`super_admin`, `admin_lab`, `admin_gudang`), widget tidak
dirender. Mereka sudah punya `GlobalSearch` yang lebih tepat, dan membiarkan
chatbot menyentuh data operasional memperluas permukaan risiko tanpa manfaat.

## Data yang boleh dikirim ke Gemini

`BuildUserOrderContextAction` hanya mengirim: nomor invoice (`INV-0001`), nama
layanan, tahap pelanggan dari `BookingStatus::customerStage()->label()`, persentase
progres terakhir, status termin pembayaran, dan tanggal.

Yang tidak pernah dikirim: nama, email, NIM, nomor telepon, alamat, isi
`brief_description`, path berkas model, dan seluruh isi `booking_messages`.

Alasannya konkret: pada free tier Google AI Studio, prompt dapat dipakai untuk
peningkatan layanan. Nomor invoice dan status cukup untuk menjawab "pesanan saya
sampai mana", tanpa memindahkan data pribadi mahasiswa ke pihak ketiga.

## Sumber knowledge

**Sumber A — markdown statis** di `knowledge/{id,en}/`. Untuk hal yang tidak ada
di database: prosedur pemesanan, kebijakan pembayaran dan pembatalan, syarat
berkas 3D, cara kerja tahapan status, FAQ, profil lab, kontak.

**Sumber B — dibangkitkan dari database** oleh `BuildKnowledgeIndexAction`:

| Model               | Isi dokumen                                             | URL sumber                       |
| ------------------- | ------------------------------------------------------- | -------------------------------- |
| `Service`           | Nama, tipe, deskripsi, harga dasar                      | `route('services.show', id)`     |
| `Product`           | Nama, deskripsi, rentang harga                          | `route('products.show', id)`     |
| `Event`             | Nama, kategori, tema, jadwal, lokasi, pendaftaran       | `route('events.show', slug)`     |
| `Training`          | Judul, jadwal, level, durasi, instruktur, materi, harga | `route('training.show', slug)`   |
| `Publication`       | Judul, penulis, jurnal, DOI, abstrak                    | `route('publications.show', id)` |
| `OpenSourceProject` | Judul, kategori, deskripsi, lisensi, versi              | `route('projects.show', id)`     |
| `PageSection`       | Konten CMS per halaman                                  | halaman terkait                  |
| `LabTeamPerson`     | Nama, peran, profil                                     | `route('team.show', slug)`       |

Setiap model dibaca lewat `localized()` untuk locale `id` dan `en`. Model dengan
`is_active` difilter aktif saja; `Event` dibatasi yang belum lewat sebulan.

Konsekuensi penting: begitu admin mengubah harga layanan lewat panel admin,
jalankan ulang indeks dan chatbot langsung ikut terbarui. Tidak ada pekerjaan
manual menyalin konten.

## Skema `knowledge_chunks`

```
id
source_key      string  index   'md:id:faq-pemesanan' | 'db:service:12:en'
source_hash     string(64)      sha256 isi dokumen, untuk melewati yang tak berubah
locale          string(2) index
audience        string(16) index  'public' | 'authenticated'
category        string  index
title           string
heading         string  nullable
content         text
url             string  nullable
embedding       binary          pack('g*') float32, sudah dinormalisasi
dimensions      smallint
timestamps
```

Tabel kedua `chatbot_logs` mencatat `question`, `locale`, `scope`, `top_score`,
`answered`. Kolom `top_score` adalah alat diagnostik utama: baris dengan skor
rendah menandakan celah knowledge base yang perlu ditambal.

## Parameter thinking

Model seri Gemini 3 memakai `generationConfig.thinkingConfig.thinkingLevel`
bernilai string. Seri 2.5 memakai `thinkingBudget` bernilai angka. Mengirim
keduanya dalam satu request menghasilkan error 400.

Gemini 3 Flash dan Flash-Lite tidak bisa mematikan thinking sepenuhnya, jadi
nilai terendah yang tersedia adalah `low`. Menyetelnya eksplisit tetap penting:
tanpa parameter itu default-nya `medium`, dan setiap jawaban FAQ sederhana akan
memakai token berpikir lebih banyak dari yang dibutuhkan — langsung menggerus
kuota harian yang justru jadi kendala utama proyek ini.

## Normalisasi vektor

`gemini-embedding-001` hanya menormalisasi otomatis pada dimensi penuh 3072.
Karena kita memangkas ke 768, normalisasi L2 harus dilakukan di `GeminiClient`.

Kalau langkah ini terlewat, dot product tidak lagi setara cosine similarity dan
urutan hasil pencarian salah — tanpa error apa pun. Ini bug diam yang sangat
mahal dilacak, jadi ia harus punya tes unit sendiri.

## Alur eksekusi satu pertanyaan

1. Widget React mengirim `{ message, history, locale }`.
2. `ChatbotController` memvalidasi, membentuk `ChatRequestData`.
3. `AnswerQuestionAction` memanggil `RetrieveKnowledgeAction`.
4. Pertanyaan di-embed dengan `taskType: RETRIEVAL_QUERY`.
5. Chunk difilter locale dan audience, di-dot-product, diambil 5 teratas di atas
   ambang 0.55.
6. Kalau kosong dan tidak ada konteks user: kembalikan fallback tanpa memanggil
   model sama sekali. Ini menghemat kuota sekaligus menutup jalur halusinasi
   paling umum — model yang diberi konteks kosong tetap akan mengarang jawaban
   yang terdengar meyakinkan.
7. Kalau ada: rakit blok KONTEKS, tambahkan DATA AKUN bila user login, kirim ke
   Gemini bersama system instruction.
8. Catat ke `chatbot_logs`, kembalikan jawaban dengan daftar sumber.

## Urutan pengerjaan

Fase 1–2 menentukan kualitas seluruh sistem. Sisanya perakitan.

1. Migrasi, `GeminiClient`, `BuildKnowledgeIndexAction`, perintah `chatbot:index`.
2. Tulis knowledge markdown, jalankan indeks, uji retrieval lewat tinker dengan
   20 pertanyaan nyata. Perbaiki dokumen sampai retrieval konsisten benar.
3. `RetrieveKnowledgeAction` + `AnswerQuestionAction` + tes Pest.
4. `ChatbotController`, route, rate limiter.
5. Widget React, pasang di `MainLayout`.
6. Mode dashboard: `BuildUserOrderContextAction`, pasang di `DashboardLayout`.
7. Penjadwalan `chatbot:index` harian, tinjau `chatbot_logs` mingguan.
