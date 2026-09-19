# Prompt untuk Claude Code

Jalankan setelah `GEMINI_API_KEY` ada di `.env` dan nama model sudah kamu
verifikasi lewat `docs/rag-chatbot/SETUP-GEMINI.md`.

Persiapan:

```bash
git checkout dev-sufyan && git checkout -b feat/rag-chatbot
mkdir -p docs/rag-chatbot knowledge/id knowledge/en
# salin ARSITEKTUR.md, FORMAT-KNOWLEDGE.md, SETUP-GEMINI.md ke docs/rag-chatbot/
# salin contoh knowledge ke knowledge/id/
git add . && git commit -m "docs: spesifikasi chatbot RAG"
```

Salin seluruh isi di bawah garis sebagai pesan pertama ke Claude Code.

---

Aku mau menambahkan chatbot RAG ke repo ini. Chatbot menjawab pertanyaan tentang
Laboratorium Teknologi Kesehatan IDIG: layanan cetak dan scan 3D, produk, agenda
pameran dan workshop, publikasi riset, serta status pesanan milik user yang login.

Sebelum menulis kode apa pun, baca berkas ini dan patuhi seluruh konvensinya:
`CLAUDE.md`, `AGENTS.md`, `docs/rag-chatbot/ARSITEKTUR.md`, dan
`docs/rag-chatbot/FORMAT-KNOWLEDGE.md`. Dua berkas terakhir adalah spesifikasi
yang mengikat. Kalau ada yang bertentangan dengan instruksi di sini, berhenti dan
tanyakan — jangan memilih sendiri.

## Soal nama model — baca ini lebih dulu

Jangan pernah menuliskan nama model Gemini dari ingatanmu. Katalog model Google
berubah cepat dan `gemini-2.5-flash` sudah menolak akun baru dengan 404. Nama
model hanya boleh dibaca dari `.env` lewat `config/gemini.php`, tanpa nilai
default yang di-hardcode ke dalam kode pemanggil.

Aku sudah memverifikasi model yang tersedia untuk project-ku dan mengisinya di
`.env`. Kalau kamu perlu tahu nilainya, baca `.env` — jangan menebak.

Parameter thinking juga sudah berubah. Model seri Gemini 3 memakai
`thinkingConfig.thinkingLevel` dengan nilai string, bukan
`thinkingConfig.thinkingBudget` yang berupa angka dan hanya berlaku untuk seri
2.5. Mengirim keduanya sekaligus menghasilkan error 400. Gemini 3 Flash dan
Flash-Lite juga tidak bisa mematikan thinking sepenuhnya, jadi nilai terendah
yang tersedia adalah `low`, bukan nol.

## Prinsip yang tidak boleh dilanggar

Data pribadi tidak pernah masuk indeks vektor. Tabel `knowledge_chunks` hanya
berisi pengetahuan publik. Data pesanan diambil lewat query Eloquent yang
difilter `user_id` dari session, tidak pernah dari input request.

Chatbot hanya menjawab dari konteks yang diberikan. Kalau tidak ada chunk yang
lolos ambang kemiripan dan tidak ada konteks user, kembalikan jawaban fallback
tanpa memanggil Gemini sama sekali.

Yang dikirim ke Gemini soal pesanan hanya: nomor invoice, nama layanan, label
tahap pelanggan, persentase progres, status termin pembayaran, dan tanggal.
Jangan pernah mengirim nama, email, NIM, nomor telepon, alamat,
`brief_description`, path berkas, atau isi `booking_messages`.

## Batasan teknis dari konvensi repo

Logika bisnis di `app/Actions/`, bukan di controller maupun `app/Services/`.
`app/Services/` khusus integrasi eksternal, jadi klien HTTP Gemini boleh di sana.
Payload antar-lapisan lewat `app/DTOs/`.

Komponen React dilarang memakai tag HTML native. Pakai `Box`, `Text`, `Heading`,
`Image`, `Container` dari `@/Core/Components/Common/`. Untuk elemen form dan
tombol, pakai komponen dari `@/Core/Components/Shared/` bila sudah ada.

Modul fitur tidak boleh saling impor. Kode chatbot hidup di
`resources/js/Features/Chatbot/` dan hanya boleh mengimpor dari `@/Core/`.

Pakai Wayfinder untuk pemanggilan route dari TypeScript, jangan URL hardcode.

Setiap perubahan PHP butuh tes Pest v4 baru atau yang diperbarui. Jalankan
`vendor/bin/pint --dirty --format agent` setelah setiap perubahan PHP dan
`php artisan test --compact` sebelum menyatakan sebuah fase selesai.

Middleware didaftarkan di `bootstrap/app.php`, bukan `app/Http/Kernel.php`.

## Konfigurasi Gemini

Buat `config/gemini.php` yang seluruh nilainya berasal dari env:

- `api_key` dari `GEMINI_API_KEY`
- `base_url` default `https://generativelanguage.googleapis.com/v1beta`
- `chat_model` dari `GEMINI_CHAT_MODEL`
- `embedding_model` dari `GEMINI_EMBEDDING_MODEL`
- `embedding_dimensions` dari `GEMINI_EMBEDDING_DIMENSIONS`, default 768
- `retrieval.top_k` default 5, `retrieval.min_score` default 0.55,
  `retrieval.cache_ttl` default 300
- `generation.temperature` 0.2, `generation.max_output_tokens` 900,
  `generation.thinking_level` dari `GEMINI_THINKING_LEVEL` default `low`
- `history_turns` default 3

Beri komentar pada `embedding_dimensions` bahwa mengubahnya setelah ingest
pertama mewajibkan `--fresh`, karena vektor berdimensi berbeda tidak bisa
dibandingkan.

Beri komentar pada `thinking_level` bahwa Flash-Lite tidak bisa mematikan
thinking dan tanpa parameter ini default-nya `medium`, yang memboroskan kuota
untuk pertanyaan FAQ sederhana.

## Fase 1 — Fondasi indeks

Buat migrasi untuk dua tabel sesuai skema di `ARSITEKTUR.md`: `knowledge_chunks`
dan `chatbot_logs`. Kolom `embedding` bertipe `binary`.

Buat `app/Services/GeminiClient.php` dengan dua method publik.

`embed(string $text, string $taskType): array` memanggil endpoint `:embedContent`
dengan `outputDimensionality` dari config. Gunakan `RETRIEVAL_DOCUMENT` saat
mengindeks dan `RETRIEVAL_QUERY` saat memproses pertanyaan. Ini bukan detail
opsional — memakai tipe yang salah menurunkan akurasi pencarian secara nyata.

Method ini wajib menormalisasi vektor hasilnya ke panjang satu. Model embedding
hanya menormalisasi otomatis pada dimensi penuh; karena kita memangkas ke 768,
normalisasi L2 harus dilakukan sendiri. Kalau langkah ini terlewat, dot product
tidak lagi setara cosine similarity dan urutan hasil pencarian salah tanpa
memunculkan error apa pun. Buat tes unit khusus yang memverifikasi panjang vektor
keluaran mendekati 1.0.

`generate(string $systemInstruction, array $history, string $prompt): string`
memanggil endpoint `:generateContent`. Peta peran: `assistant` di sisi kita
menjadi `model` di sisi Gemini. Susun `generationConfig` sebagai
`{"temperature":..., "maxOutputTokens":..., "thinkingConfig":{"thinkingLevel":...}}`.
Jangan mengirim `thinkingBudget` dalam bentuk apa pun.

Saat respons gagal atau kosong, catat `candidates.0.finishReason` ke log. Respons
kosong biasanya berarti diblokir safety filter atau token habis, dan tanpa field
itu penyebabnya tidak bisa dilacak.

Buat `app/Actions/Chatbot/BuildKnowledgeIndexAction.php` yang membangun indeks
dari dua sumber sesuai `FORMAT-KNOWLEDGE.md`: berkas markdown di
`knowledge/{id,en}/` dan model Eloquent (`Service`, `Product`, `Event`,
`Training`, `Publication`, `OpenSourceProject`, `PageSection`, `LabTeamPerson`).

Semua field teks model dibaca lewat `localized()` dari trait `HasEnglishOverlay`.
Tiap model menghasilkan satu dokumen per locale. Model yang punya `is_active`
difilter aktif saja; `Event` dibatasi yang `ends_at` belum lewat sebulan.

Pecah dokumen per heading level dua. Teks yang di-embed berbentuk
`"{judul} — {heading}\n{kata_kunci}\n{isi}"`. Simpan vektor dengan `pack('g*', ...)`.

Lewati dokumen yang `source_hash`-nya tidak berubah — ini yang menjaga kuota
embedding tetap aman saat indeks dijalankan harian. Beri jeda antar panggilan
embedding yang bisa dikonfigurasi lewat env, default 250 ms.

Buat perintah `php artisan chatbot:index` dengan opsi `--fresh` dan `--only=`.

Tes yang aku harapkan: parsing frontmatter, pemecahan per heading, pemotongan
bagian yang melebihi 1.500 karakter, pelewatan dokumen tak berubah, dan
normalisasi vektor.

## Fase 2 — Retrieval

Buat `app/Actions/Chatbot/RetrieveKnowledgeAction.php`. Terima pertanyaan,
locale, dan flag apakah user terautentikasi. Embed pertanyaan dengan
`RETRIEVAL_QUERY`, muat chunk yang cocok locale dan audience dari cache, hitung
dot product, buang yang di bawah ambang, kembalikan lima teratas terurut.

Karena semua vektor sudah dinormalisasi, dot product langsung setara cosine
similarity — jangan menghitung ulang magnitudo saat query.

Kalau locale aktif `en` dan hasilnya kosong, ulangi pencarian pada locale `id`
dan tandai hasilnya sebagai fallback agar `AnswerQuestionAction` bisa
menyesuaikan instruksi bahasa.

Cache daftar chunk yang sudah di-unpack, dan sediakan cara membersihkannya yang
dipanggil `BuildKnowledgeIndexAction` setiap selesai mengindeks.

Tes: chunk locale lain tidak ikut terambil, chunk `authenticated` tidak bocor ke
mode publik, ambang benar-benar menyaring, dan jalur fallback en ke id bekerja.
Semua tes ini harus lolos di SQLite in-memory.

## Fase 3 — Orkestrasi jawaban

Buat `app/DTOs/Chatbot/ChatRequestData.php` berisi pertanyaan, riwayat, locale,
dan user opsional.

Buat `app/Actions/Chatbot/BuildUserOrderContextAction.php` yang mengambil lima
`ServiceBooking` terbaru milik user. Kecualikan booking berstatus
`BookingStatus::Consultation` karena itu utas konsultasi, bukan pesanan. Untuk
tiap booking hasilkan satu baris ringkas berisi nomor invoice berformat
`INV-0001`, nama layanan lewat `localized('name')`, label tahap dari
`current_status->customerStage()->label()`, persentase progres terakhir bila ada,
status termin pembayaran dari relasi `payments`, dan tanggal pembuatan.

Buat `app/Actions/Chatbot/AnswerQuestionAction.php` yang menjalankan alur di
`ARSITEKTUR.md`. System instruction harus menyatakan: jawab hanya dari KONTEKS,
akui terus terang bila informasi tidak ada lalu arahkan ke admin lab, jangan
pernah menebak harga atau tanggal, jangan memberi nasihat medis atau diagnosis,
jangan mengarang tautan, dan jawab dalam bahasa sesuai locale aktif dengan
maksimal tiga paragraf pendek.

Klausa medis itu wajib ada. Nama labnya mengandung "teknologi kesehatan", jadi
cepat atau lambat akan ada yang bertanya hal medis.

Catat setiap pertanyaan ke `chatbot_logs` termasuk `top_score`. Kegagalan
pencatatan tidak boleh membuat user kehilangan jawabannya.

Tes: fallback dipakai saat tidak ada chunk relevan dan `GeminiClient` sama sekali
tidak dipanggil, konteks pesanan hanya muncul untuk user terautentikasi, dan
booking milik user lain tidak pernah ikut terbawa.

## Fase 4 — Endpoint

Buat `app/Http/Controllers/ChatbotController.php` sebagai controller tipis:
validasi, bentuk DTO, panggil action, kembalikan JSON berisi jawaban, daftar
sumber, dan flag `answered`.

Validasi: `message` wajib, string, 2 sampai 500 karakter; `history` opsional,
array, maksimal 12 entri dengan `role` hanya `user` atau `assistant`.

User diambil dari `$request->user()`. Jangan pernah menerima `user_id` dari
request body.

Daftarkan route `POST /chatbot/ask` di `routes/web.php` dengan middleware `web`
dan `throttle:chatbot`. Definisikan limiter `chatbot` di
`AppServiceProvider::boot()`: 20 per menit per user untuk yang login, 8 per menit
per IP untuk tamu. Ambil angkanya dari config agar mudah diturunkan kalau kuota
free tier ternyata lebih ketat.

Tes feature: validasi menolak input kosong dan terlalu panjang, throttle bekerja,
tamu mendapat jawaban tanpa konteks pesanan.

## Fase 5 — Widget React

Buat modul `resources/js/Features/Chatbot/` berisi komponen widget, hook
pemanggilan API, dan definisi tipe. Hanya impor dari `@/Core/`.

Dua varian tampilan. Varian mengambang untuk landing page: tombol bulat di kanan
bawah yang membuka panel. Varian panel untuk dashboard user.

Sertakan tiga sampai empat saran pertanyaan sebagai chip yang bisa diklik saat
percakapan masih kosong — ini mengurangi kebingungan pengunjung yang tidak tahu
harus bertanya apa. Saran untuk landing page berbeda dari dashboard.

Tampilkan tautan sumber di bawah jawaban. Tampilkan indikator saat menunggu.
Tangani status 429 dengan pesan yang menjelaskan bahwa permintaan terlalu sering,
bukan pesan galat generik.

Ikuti arahan `PRODUCT.md`: nada bicara terukur dan pakar, bukan chatty. Warna dan
tipografi mengikuti sistem yang sudah ada di `resources/css/public.css`, jangan
memperkenalkan palet baru.

Pasang varian mengambang di `resources/js/Features/Landing/Layouts/MainLayout.tsx`
dan varian panel di `resources/js/Features/Dashboard/Layouts/DashboardLayout.tsx`.

Jangan render widget untuk peran `super_admin`, `admin_lab`, dan `admin_gudang`.
Mereka sudah punya `GlobalSearch`, dan membiarkan chatbot menyentuh area
operasional memperluas permukaan risiko tanpa manfaat.

Pastikan aksesibilitas dasar: fokus keyboard terlihat, panel punya label yang
bisa dibaca pembaca layar, area jawaban memakai `aria-live`, dan animasi
dinonaktifkan saat `prefers-reduced-motion`.

## Fase 6 — Operasional

Jadwalkan `chatbot:index` harian pukul tiga pagi di `routes/console.php`.

Tambahkan semua variabel `GEMINI_*`, `RAG_*`, dan batas throttle ke `.env.example`
tanpa nilai.

Tulis `docs/rag-chatbot/OPERASIONAL.md` berisi cara menjalankan indeks ulang,
cara membaca `chatbot_logs` untuk menemukan celah knowledge base, cara menyetel
ambang kemiripan, dan cara mengganti model bila Google mempensiunkan yang sekarang.

## Cara aku ingin kamu bekerja

Kerjakan satu fase, jalankan tes, lalu berhenti dan laporkan sebelum lanjut ke
fase berikutnya. Jangan mengerjakan enam fase sekaligus.

Kalau asumsi skema di prompt ini tidak cocok dengan kondisi sebenarnya di repo —
misalnya nama kolom berbeda, relasi tidak ada, atau `ServiceProgressUpdate` tidak
menyimpan persentase seperti yang aku kira — berhenti dan tanyakan. Jangan
membuat migrasi baru untuk menyesuaikan skema yang sudah ada, dan jangan menebak
nama kolom.

Kalau sebuah panggilan API Gemini gagal, tampilkan badan respons utuh kepadaku.
Jangan menyimpulkan penyebabnya dari kode status saja, dan jangan mencoba
menebak nama parameter yang benar dengan coba-coba.

Jangan menyentuh berkas di luar cakupan chatbot. Jangan mengubah skema tabel yang
sudah ada. Jangan menambah dependensi Composer atau npm tanpa bertanya lebih
dulu — semua yang dibutuhkan sudah tersedia lewat `Http` facade dan React yang
terpasang.

Mulai dari Fase 1.
