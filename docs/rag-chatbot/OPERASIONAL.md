# Operasional RAG Chatbot — IDIG Health Tech

Dokumen untuk yang merawat chatbot setelah rilis. Untuk keputusan desain lihat
`ARSITEKTUR.md`, untuk menulis dokumen knowledge lihat `FORMAT-KNOWLEDGE.md`, untuk
menyiapkan API key lihat `SETUP-GEMINI.md`.

## Ringkasan komponen

| Bagian                                       | Berkas                                                    |
| -------------------------------------------- | --------------------------------------------------------- |
| Konfigurasi                                  | `config/gemini.php` (seluruh nilai dari `.env`)            |
| Klien HTTP Gemini                            | `app/Services/GeminiClient.php`                            |
| Pembangun indeks                             | `app/Actions/Chatbot/BuildKnowledgeIndexAction.php`         |
| Pencarian                                    | `app/Actions/Chatbot/RetrieveKnowledgeAction.php`           |
| Orkestrasi jawaban                           | `app/Actions/Chatbot/AnswerQuestionAction.php`              |
| Konteks pesanan                              | `app/Actions/Chatbot/BuildUserOrderContextAction.php`       |
| Endpoint                                     | `POST /chatbot/ask` → `ChatbotController`                  |
| Widget                                       | `resources/js/Features/Chatbot/`                            |
| Tabel                                        | `knowledge_chunks`, `chatbot_logs`                          |

## Menjalankan ulang indeks

Terjadwal otomatis setiap hari pukul 03:00 lewat `routes/console.php`. Manual:

```bash
php artisan chatbot:index
```

Perintah ini melewati dokumen yang `source_hash`-nya tidak berubah, jadi menjalankannya
saat tidak ada perubahan **tidak memakai kuota embedding sama sekali**. Jalankan bebas
sesering yang kamu mau.

Membatasi ke satu sumber saat hanya satu yang berubah:

```bash
php artisan chatbot:index --only=md          # hanya knowledge/{id,en}/*.md
php artisan chatbot:index --only=service     # hanya tabel services
php artisan chatbot:index --only=md --only=service
```

Sumber yang tersedia: `md`, `service`, `product`, `event`, `training`, `publication`,
`project`, `page`, `team`.

Membangun ulang dari nol:

```bash
php artisan chatbot:index --fresh
```

`--fresh` menghapus chunk milik sumber terpilih lalu meng-embed ulang semuanya. Ini
memakan kuota penuh, jadi pakai hanya bila memang perlu. Yang benar-benar mewajibkannya:
mengganti `GEMINI_EMBEDDING_MODEL` atau `GEMINI_EMBEDDING_DIMENSIONS`.

Keluaran perintah:

| Kolom                 | Arti                                                                |
| --------------------- | ------------------------------------------------------------------- |
| `Indexed`             | dokumen yang di-embed ulang karena isinya berubah                   |
| `Skipped (unchanged)` | dokumen yang `source_hash`-nya sama — nol biaya                     |
| `Pruned`              | chunk yang dokumen sumbernya hilang (berkas dihapus, produk dinonaktifkan) |
| `Chunks written`      | jumlah potongan baru yang tersimpan                                 |

Setelah setiap run, cache chunk dibersihkan otomatis
(`RetrieveKnowledgeAction::flushCache()`), jadi hasilnya langsung terlihat.

### Kalau ingest kena 429 di tengah jalan

Kuota per menit terlampaui. Naikkan jeda antar panggilan embedding:

```env
RAG_EMBED_DELAY_MS=1000
```

Jalankan lagi perintah yang sama. Dokumen yang sudah selesai dilewati, jadi ingest
melanjutkan per dokumen dari tempat berhenti. Yang dibayar dua kali hanya satu dokumen —
yang sedang diproses saat 429 muncul. Itu disengaja: sebuah dokumen di-embed seluruhnya
sebelum barisnya ditulis, karena penulisan sebagian akan membawa `source_hash` yang baru
dan membuat run berikutnya menganggap dokumen itu tidak berubah, lalu melewatinya
selamanya dengan bagian yang hilang.

Kegagalan saat memperbarui dokumen yang sudah ada juga tidak menghapus versi lamanya —
jawaban lama tetap berdiri sampai penggantinya berhasil di-embed utuh.

### Kalau setelah admin mengubah harga chatbot masih menjawab harga lama

Indeks belum dibangun ulang. Jalankan `php artisan chatbot:index --only=service`. Kalau
sudah dijalankan dan tetap lama, periksa scheduler benar-benar hidup:

```bash
php artisan schedule:list | grep chatbot
```

## Membaca `chatbot_logs` untuk menemukan celah knowledge base

Ini pekerjaan perawatan yang paling menentukan. `top_score` adalah kemiripan potongan
terbaik terhadap pertanyaan; skor rendah berarti tidak ada dokumen yang menjawabnya.

Tinjau mingguan:

```sql
SELECT question, locale, top_score, created_at
FROM chatbot_logs
WHERE answered = 0 OR top_score < 0.6
ORDER BY created_at DESC
LIMIT 50;
```

Cara membaca hasilnya:

| `top_score` | `answered` | Arti                                                                                      |
| ----------- | ---------- | ----------------------------------------------------------------------------------------- |
| `0.0`       | `0`        | tidak ada chunk yang lolos ambang, dan tidak ada konteks pesanan. Gemini tidak dipanggil. |
| `0.55–0.65` | `1`        | terjawab tapi tipis. Dokumennya ada namun tidak menjawab pertanyaan itu secara langsung.  |
| `> 0.8`     | `1`        | sehat.                                                                                    |
| `1.0`       | `0`        | retrieval berhasil tapi Gemini gagal — cek `laravel.log`, biasanya kuota atau 4xx.        |

Baris terakhir itu penting: `answered = 0` tidak selalu berarti celah knowledge.
`top_score` tinggi dengan `answered = 0` adalah kegagalan API, bukan dokumen yang kurang.

Pertanyaan yang paling sering muncul dengan skor rendah:

```sql
SELECT question, COUNT(*) AS jumlah, AVG(top_score) AS rata_skor
FROM chatbot_logs
WHERE top_score < 0.6
GROUP BY question
ORDER BY jumlah DESC
LIMIT 20;
```

**Cara menambalnya.** Ambil pertanyaan itu, tambahkan sebagai `###` di
`knowledge/id/faq-umum.md` **memakai kalimat asli penanyanya**, tulis jawabannya dalam
kalimat lengkap dengan angka konkret, lalu jalankan `php artisan chatbot:index --only=md`.

Memakai kalimat asli penanya bukan gaya penulisan — retrieval bekerja dengan mencocokkan
vektor pertanyaan dengan vektor teks, jadi teks yang mirip cara orang bertanya lebih
mudah ditemukan. "Berapa lama ngeprint" dan "Durasi Pengerjaan" adalah dua vektor yang
berbeda.

Tabel ini tidak menyimpan `user_id`. Ia ada untuk menemukan dokumen yang kurang, bukan
untuk melacak siapa bertanya apa.

## Menyetel ambang kemiripan

```env
RAG_MIN_SCORE=0.55
RAG_TOP_K=5
```

Ambang adalah cosine similarity, rentang efektif kira-kira 0,3 sampai 1,0.

| Gejala                                                              | Tindakan                    |
| ------------------------------------------------------------------- | --------------------------- |
| Chatbot bilang "belum ada informasi" padahal dokumennya jelas ada   | turunkan ke `0.50` atau `0.45` |
| Jawaban menyerempet ke topik lain, mengutip dokumen yang tak relevan | naikkan ke `0.60` atau `0.65` |

Cara menyetelnya dengan bukti, bukan tebakan. Ambil 20 pertanyaan nyata dari
`chatbot_logs`, catat `top_score` masing-masing, lalu letakkan ambang di bawah skor
terendah dari pertanyaan yang **seharusnya** terjawab dan di atas skor tertinggi dari
pertanyaan yang **seharusnya** ditolak. Kalau kedua angka itu bertumpang tindih, ambang
bukan solusinya — dokumennya yang perlu diperbaiki.

Menaikkan `RAG_TOP_K` menambah konteks yang dikirim ke Gemini, jadi menambah token per
pertanyaan. Di atas 5 jarang membantu: potongan keenam biasanya sudah jauh dari topik dan
justru mengencerkan konteks.

Ambang berlaku di kedua arah pencarian. Kalau locale aktif `en` dan tidak ada chunk `en`
yang lolos, pencarian diulang pada locale `id` dan model diinstruksikan tetap menjawab
dalam bahasa Inggris. Jadi ambang yang terlalu tinggi membuat pengunjung Inggris jatuh ke
konteks Indonesia lebih sering daripada perlu.

## Mengganti model

### Model chat

Paling ringan. Verifikasi dulu model yang tersedia untuk key-mu:

```bash
curl -s "https://generativelanguage.googleapis.com/v1beta/models" \
  -H "x-goog-api-key: $GEMINI_API_KEY" \
  | jq -r '.models[] | select(.supportedGenerationMethods[]? == "generateContent") | .name'
```

Ganti `GEMINI_CHAT_MODEL` di `.env`, lalu `php artisan config:clear`. Tidak ada indeks
yang perlu dibangun ulang, tidak ada baris PHP yang berubah.

Jangan memakai alias seperti `gemini-flash-latest`. Perilaku model di belakang alias bisa
berubah tanpa peringatan, dan chatbot yang tadinya patuh pada larangan menebak harga bisa
mulai berhalusinasi setelah Google mengganti targetnya.

**Perhatikan generasi model soal parameter thinking.** Kode ini mengirim
`generationConfig.thinkingConfig.thinkingLevel` berupa string, yang dipakai seri Gemini 3
ke atas. Seri 2.5 memakai `thinkingBudget` berupa angka. Kalau kamu turun ke model seri
2.5, panggilan akan gagal 400 dan `GeminiClient::generate()` perlu disesuaikan — bukan
sekadar ganti nilai `.env`. Baca badan respons error-nya; pesan Google menyebut persis
parameter yang salah.

`GEMINI_THINKING_LEVEL` menerima `low`, `medium`, `high`. Flash dan Flash-Lite generasi 3
tidak bisa mematikan thinking, jadi `low` adalah nilai terendah. Biarkan `low` kecuali
jawaban terbukti sering mengabaikan instruksi "jawab hanya dari konteks".

### Model embedding

Jauh lebih berat. Ruang vektor antar generasi embedding tidak kompatibel, jadi seluruh
indeks harus dibangun ulang.

```bash
curl -s "https://generativelanguage.googleapis.com/v1beta/models" \
  -H "x-goog-api-key: $GEMINI_API_KEY" \
  | jq -r '.models[] | select(.supportedGenerationMethods[]? == "embedContent") | .name'
```

Urutannya:

1. Ganti `GEMINI_EMBEDDING_MODEL` (dan `GEMINI_EMBEDDING_DIMENSIONS` bila perlu) di `.env`
2. `php artisan config:clear`
3. `php artisan chatbot:index --fresh`

Langkah 3 wajib. Vektor berdimensi atau bergenerasi berbeda tidak menghasilkan error saat
dibandingkan — `RetrieveKnowledgeAction` melewati vektor yang lebarnya tidak cocok, tapi
vektor dengan lebar sama dari model berbeda akan tetap dibandingkan dan menghasilkan skor
yang terlihat wajar padahal tak bermakna. Itu bug diam.

Pilih model yang stabil, bukan preview. Keputusan ini lebih final daripada model chat.

## Rate limit endpoint

```env
CHATBOT_THROTTLE_USER=20
CHATBOT_THROTTLE_GUEST=20
```

Per menit; user login dikunci per `user_id`, tamu per IP. Batas ini melindungi kuota
harian Gemini, bukan web server.

Tamu mendapat jatah yang sama dengan user login, disengaja: chatbot ini alat publik, dan
kunci IP dipakai bersama oleh semua orang di belakang satu NAT kampus — batas tamu yang
lebih ketat justru mencekik satu ruangan pengunjung, bukan satu penyalahguna.

Konsekuensinya nyata, jadi buka halaman rate limit di AI Studio dan catat RPD project-mu.
Satu bot bisa membelanjakan 20 permintaan per menit dari kuota harian; kalau kuota itu
hanya beberapa ratus, angka ini terlalu longgar. Turunkan `CHATBOT_THROTTLE_GUEST` — tanpa
perubahan kode.

Kalau nanti pengunjung yang sah tetap kena 429 padahal batasnya sudah longgar, penyebabnya
kunci IP yang dibagi NAT. Alternatifnya mengunci tamu per session id alih-alih IP, yang
membatasi per browser bukan per jaringan. Itu belum diterapkan karena mudah dilewati dengan
menghapus cookie, jadi ia menukar keadilan terhadap pengunjung dengan pertahanan terhadap
bot. Ambil keputusan itu berdasarkan `chatbot_logs`, bukan dugaan.

Widget menampilkan pesan khusus untuk 429 ("terkirim terlalu sering"), berbeda dari 503
("asisten sedang tidak tersedia"). Kalau pengguna melaporkan pesan yang salah satu, itu
membedakan masalah throttle dari masalah API.

## Kuota habis

Kuota harian Gemini reset tengah malam waktu Pasifik — di Surabaya sekitar pukul 14.00–15.00
WIB. Jadi kalau chatbot berhenti menjawab pada pagi hari, penyebabnya bukan bug, dan
menunggu sampai sore memulihkannya sendiri.

Batas berlaku per Google Cloud project, bukan per API key. Dua key dalam satu project
berbagi jatah yang sama.

Saat kuota habis, `AnswerQuestionAction` mencatat baris dengan `answered = 0` dan
`top_score` yang tinggi, dan endpoint mengembalikan 503 — bukan halaman error. Badan
respons Gemini yang utuh masuk ke `storage/logs/laravel.log` dengan prefix
`[GeminiClient]`.

## Diagnosis kegagalan API

Aturannya satu: **baca badan respons utuh, jangan menyimpulkan dari kode status.**
`GeminiClient` mencatat badan respons apa adanya, tanpa dipotong, karena pesan error Google
menyebut persis apa yang salah.

```bash
grep '\[GeminiClient\]' storage/logs/laravel.log | tail -20
```

| Status | Penyebab umum                                                        |
| ------ | -------------------------------------------------------------------- |
| `400`  | bentuk parameter salah — biasanya thinking config vs generasi model  |
| `403`  | API key salah, atau belum aktif untuk project itu                    |
| `404`  | nama model salah, atau tidak tersedia untuk akun ini                 |
| `429`  | kuota habis                                                          |

Respons `200` dengan jawaban kosong ditangani terpisah: `GeminiClient` mencatat
`candidates.0.finishReason`. `SAFETY` atau `PROHIBITED_CONTENT` berarti diblokir filter,
`MAX_TOKENS` berarti token habis. Keduanya butuh perbaikan yang berlawanan, jadi field itu
yang menentukan.

Jangan pernah membungkus respons dengan `grep` saat mendiagnosis satu panggilan, dan jangan
menebak nama parameter dengan coba-coba. `SETUP-GEMINI.md` bagian 4 punya urutan curl
minimal yang mengisolasi parameter penyebab satu per satu.

## Batas kapasitas

Similarity dihitung di PHP atas salinan indeks yang di-cache per `locale:audience`.
Praktisnya nyaman sampai sekitar 5.000 chunk per locale. Cek jumlahnya:

```sql
SELECT locale, audience, COUNT(*) FROM knowledge_chunks GROUP BY locale, audience;
```

Kalau terlampaui, ganti isi `RetrieveKnowledgeAction` dengan pgvector — kontrak publiknya
(`execute(string $question, string $locale, bool $authenticated)`) tidak perlu berubah.
Konsekuensinya: retrieval tidak lagi bisa diuji di SQLite in-memory, yang saat ini adalah
alasan utama pendekatan PHP dipilih.

## Yang tidak boleh diubah tanpa berpikir ulang

Empat batasan ini menahan kelas bug yang mahal, bukan preferensi gaya.

**Data pesanan tidak pernah masuk `knowledge_chunks`.** Konteks pesanan diambil
`BuildUserOrderContextAction` lewat query Eloquent yang difilter `user_id` dari session.
Kalau data semua user berada di satu indeks bersama, satu kesalahan filter berubah jadi
kebocoran antar-user.

**`user_id` tidak pernah diterima dari request.** `ChatbotController` memakai
`$request->user()`. Kalau ini regresi, penyerang membaca pesanan akun mana pun dengan
menebak satu integer.

**Field yang boleh dikirim ke Gemini soal pesanan tetap enam:** nomor invoice, nama
layanan, label tahap pelanggan, persentase progres, status termin pembayaran, tanggal
dibuat. Bukan nama, email, NIM, telepon, alamat, `brief_description`, path berkas, atau
isi `booking_messages`. Pada free tier prompt dapat dipakai Google untuk meningkatkan
produknya.

**Vektor dinormalisasi L2 di `GeminiClient::embed()`.** Model hanya menormalisasi otomatis
pada dimensi penuh, dan kita memangkas ke 768. Tanpa normalisasi, dot product tidak lagi
setara cosine similarity dan urutan hasil salah tanpa error apa pun. Ada tes khusus untuk
ini; jangan hapus.

## Menjalankan tes

```bash
php artisan test --compact tests/Feature/Chatbot
```

Seluruhnya memakai `Http::fake()`, jadi tidak ada panggilan Gemini nyata dan kuota tidak
tersentuh.
