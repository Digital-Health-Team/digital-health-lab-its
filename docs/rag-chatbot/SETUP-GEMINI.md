# Setup Google AI Studio

Kerjakan seluruh dokumen ini sampai selesai sebelum menjalankan
`PROMPT-CLAUDE-CODE.md`. Langkah 4 menghasilkan dua nilai yang harus kamu
tempelkan ke prompt itu.

## 1. Membuat API key

Buka `aistudio.google.com`, masuk dengan akun Google. Kalau ini proyek lab yang
akan diserahkan ke tim, pakai akun institusi atau akun bersama milik lab — API key
terikat ke akun pembuatnya, dan memindahkannya nanti berarti membangun ulang.

Klik **Get API key**, lalu **Create API key**. Pilih Google Cloud project, atau
buat baru bila belum ada. Tidak perlu kartu kredit; akun baru otomatis berada di
Free Tier.

Salin key-nya sekarang — setelah dialog ditutup nilainya tidak ditampilkan penuh
lagi. Catat juga nama project-nya, karena kuota dihitung per project, bukan per
key. Dua key dalam satu project berbagi jatah yang sama.

## 2. Menyimpan key dengan benar

```bash
export GEMINI_API_KEY="tempel_key_di_sini"
```

Lalu di `.env` repo:

```env
GEMINI_API_KEY=key_kamu
```

Verifikasi bahwa key tidak akan ikut ter-commit:

```bash
grep -n '^\.env$' .gitignore     # harus ada
git check-ignore -v .env          # harus mengembalikan baris .gitignore
```

Key hanya boleh dibaca sisi server. Jangan pernah mem-passing-kannya lewat Inertia
props atau `import.meta.env` — kalau masuk bundle Vite, siapa pun bisa
membacanya dari devtools browser.

## 3. Menemukan model yang benar-benar tersedia

Jangan memakai nama model dari tutorial mana pun, termasuk dokumen ini. Google
mempensiunkan model dengan cepat, dan model yang masih hidup untuk akun lama bisa
mengembalikan 404 untuk akun baru.

Model chat yang tersedia untuk key-mu:

```bash
curl -s "https://generativelanguage.googleapis.com/v1beta/models" \
  -H "x-goog-api-key: $GEMINI_API_KEY" \
  | jq -r '.models[] | select(.supportedGenerationMethods[]? == "generateContent") | .name'
```

Model embedding:

```bash
curl -s "https://generativelanguage.googleapis.com/v1beta/models" \
  -H "x-goog-api-key: $GEMINI_API_KEY" \
  | jq -r '.models[] | select(.supportedGenerationMethods[]? == "embedContent") | .name'
```

Tanpa `jq`, hapus pipe-nya dan cari manual field `"name"`.

**Memilih model chat.** Untuk chatbot ini, pilih varian Flash-Lite bila tersedia,
bukan Flash penuh. Tugasnya menjawab pertanyaan dari konteks yang sudah disediakan
retrieval — pekerjaan ringan yang tidak butuh reasoning kuat. Model lebih kecil
juga biasanya mendapat kuota free tier lebih longgar, dan kuota itulah kendala
utamamu. Naik ke Flash penuh hanya kalau jawaban Flash-Lite terbukti sering
mengabaikan instruksi "jawab hanya dari konteks".

**Memilih model embedding.** Keputusan ini lebih final daripada model chat: ruang
vektor antar generasi embedding tidak kompatibel, sehingga menggantinya di kemudian
hari mewajibkan seluruh indeks dibangun ulang dari nol. Pilih yang stabil, bukan
preview.

Jangan memakai alias seperti `gemini-flash-latest`. Perilaku model bisa berubah
tanpa peringatan, dan chatbot yang tadinya patuh pada larangan menebak harga bisa
mulai berhalusinasi setelah Google mengganti target aliasnya.

## 4. Menguji sebelum menyentuh kode

Lakukan ini dengan urutan berikut. Kirim permintaan paling minimal dulu, baca
respons utuh, lalu tambahkan satu parameter per langkah. Begitu ada yang pecah,
kamu tahu persis parameter mana penyebabnya.

Jangan pernah membungkus respons dengan `grep` saat mendiagnosis — pesan error
Google biasanya menyebut persis apa yang salah, dan `grep` membuang informasi itu.

Ganti `MODEL_CHAT` dan `MODEL_EMBEDDING` dengan hasil langkah 3.

**Uji A — model chat, tanpa parameter tambahan.**

```bash
curl -s "https://generativelanguage.googleapis.com/v1beta/models/MODEL_CHAT:generateContent" \
  -H "x-goog-api-key: $GEMINI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"contents":[{"parts":[{"text":"Sebutkan tiga warna dalam bahasa Indonesia."}]}]}'
```

Harapan: ada `candidates[0].content.parts[0].text`. Kalau `403`, key salah atau
belum aktif. Kalau `404`, nama model salah. Kalau `429`, kuota habis.

**Uji B — thinking level.**

```bash
curl -s "https://generativelanguage.googleapis.com/v1beta/models/MODEL_CHAT:generateContent" \
  -H "x-goog-api-key: $GEMINI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "contents":[{"parts":[{"text":"Halo"}]}],
    "generationConfig":{"thinkingConfig":{"thinkingLevel":"low"}}
  }'
```

Tiga hal yang perlu kamu pahami soal parameter ini. `thinkingLevel` dipakai model
Gemini 3 ke atas, sementara seri 2.5 memakai `thinkingBudget` — keduanya tidak
boleh dikirim bersamaan karena menghasilkan 400. Model Flash dan Flash-Lite
generasi 3 tidak bisa mematikan thinking sepenuhnya, jadi `low` adalah nilai
terendah yang tersedia. Dan bila parameter ini tidak dikirim, model memakai default
yang lebih tinggi, yang memboroskan kuota untuk menjawab FAQ sederhana.

Kalau uji B gagal sementara uji A berhasil, catat pesan error mentahnya dan
sesuaikan bentuk parameter sesuai pesan itu — jangan menebak.

**Uji C — model embedding dengan dimensi yang akan dipakai.**

```bash
curl -s "https://generativelanguage.googleapis.com/v1beta/models/MODEL_EMBEDDING:embedContent" \
  -H "x-goog-api-key: $GEMINI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model":"models/MODEL_EMBEDDING",
    "content":{"parts":[{"text":"Layanan cetak 3D di laboratorium IDIG"}]},
    "taskType":"RETRIEVAL_DOCUMENT",
    "outputDimensionality":768
  }' | head -c 300
```

Harapan: ada `embedding.values` berisi array angka.

**Uji D — Bahasa Indonesia pada embedding.** Ini uji yang paling sering
dilewatkan dan paling menentukan. Embed dua kalimat yang bermakna mirip tapi
berbeda kata, lalu bandingkan hasilnya secara kasar. Kalau model embedding yang
kamu pilih lemah di Bahasa Indonesia, seluruh chatbot akan buruk dan tidak ada
prompt engineering yang bisa menyelamatkannya. Uji ini lebih baik dilakukan
sekarang, dengan dua panggilan curl, daripada ditemukan setelah lima fase
implementasi.

## 5. Mencatat kuota proyekmu

Buka halaman rate limit di AI Studio untuk project yang kamu pilih, dan catat RPM,
TPM, dan RPD-mu sendiri. Angka free tier berubah beberapa kali sejak akhir 2025 dan
berbeda antar akun serta region, jadi angka dari artikel mana pun tidak bisa
dipercaya untuk proyekmu.

Dua hal tentang cara kuota bekerja. Batas berlaku per Google Cloud project, bukan
per API key. Dan kuota harian reset tengah malam waktu Pasifik — di Surabaya itu
jatuh sekitar pukul dua sampai tiga siang WIB. Jadi kalau chatbot berhenti
menjawab di pagi hari, penyebabnya bukan bug, dan menunggu sampai sore akan
memulihkannya sendiri.

Angka RPD-mu menentukan satu keputusan desain: batas rate limiter di route. Kalau
proyekmu hanya dapat beberapa ratus permintaan per hari, batas 8 per menit untuk
tamu terlalu longgar — satu bot bisa menghabiskannya dalam setengah jam. Sampaikan
angka RPD-mu ke Claude Code di Fase 4 agar batasnya disesuaikan.

Angka RPM-mu menentukan jeda antar panggilan saat ingest. Bila RPM rendah, jeda
satu detik yang dispesifikasikan masih terlalu cepat dan ingest pertama akan kena
429 di tengah jalan.

## 6. Keputusan privasi yang perlu kamu ambil sadar

Pada Free Tier, prompt dan respons dapat dipakai Google untuk meningkatkan
produknya. Untuk memastikan itu tidak terjadi, kamu perlu menautkan billing account
dan pindah ke Paid Tier.

Untuk pertanyaan publik seperti "berapa harga cetak 3D", ini tidak masalah. Yang
perlu dijaga adalah mode dashboard, karena menyangkut data mahasiswa. Inilah alasan
`BuildUserOrderContextAction` dibatasi hanya mengirim nomor invoice, nama layanan,
tahap, dan tanggal — cukup untuk menjawab "pesanan saya sampai mana" tanpa
memindahkan identitas siapa pun ke pihak ketiga. Pertahankan batasan itu.

Perlu diketahui juga bahwa kredit Welcome atau free-trial yang diberikan setelah
2 Maret 2026 tidak bisa dipakai membayar penggunaan Gemini API atau AI Studio, jadi
jangan merencanakan produksi "gratis" di atas kredit Cloud biasa.

## 7. Checklist sebelum menjalankan Claude Code

- [ ] `GEMINI_API_KEY` terisi di `.env`, dan `.env` terkonfirmasi ter-gitignore
- [ ] Uji A sampai D berhasil semua
- [ ] Nama model chat dan embedding dicatat, keduanya stabil bukan preview
- [ ] RPM, TPM, RPD project dicatat
- [ ] Nilai model di `PROMPT-CLAUDE-CODE.md` sudah diganti dengan hasil verifikasi
- [ ] Tujuh berkas knowledge minimum di `FORMAT-KNOWLEDGE.md` sudah mulai diisi

Butir terakhir sering dilewatkan padahal paling menentukan. Kode bisa selesai dalam
sehari; knowledge base yang lengkap dan spesifik itulah yang membedakan chatbot
berguna dari demo.
