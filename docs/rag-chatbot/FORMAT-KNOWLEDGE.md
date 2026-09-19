# Format Knowledge Base — Chatbot IDIG

Dokumen ini adalah kontrak. `BuildKnowledgeIndexAction` mem-parsing sesuai aturan
di sini, jadi menyimpang dari format berarti dokumen tidak terindeks dengan benar.

## Prinsip yang mendasari format ini

Retrieval bekerja dengan mencocokkan **vektor pertanyaan** dengan **vektor
potongan teks**. Konsekuensinya menentukan seluruh gaya penulisan:

- Teks yang mirip dengan cara orang bertanya akan lebih mudah ditemukan.
  Karena itu bagian FAQ ditulis memakai pertanyaan persis seperti pengunjung
  mengetiknya, bukan versi formalnya.
- Kalimat lengkap mengalahkan bullet telegrafis. "Rp 5.000 per gram" sulit
  dicocokkan; "Biaya cetak 3D dihitung Rp 5.000 per gram bahan" mudah.
- Sinonim harus muncul di badan teks. Orang menulis "print 3D", "cetak 3D",
  "3D printing", dan "ngeprint" untuk hal yang sama.
- Satu potongan harus bisa dijawab berdiri sendiri. Potongan yang bergantung
  pada kalimat di bagian lain akan menghasilkan jawaban setengah.

## Sumber A — markdown statis

### Lokasi

```
knowledge/
  id/                        Bahasa Indonesia (wajib)
    profil-lab.md
    prosedur-pemesanan-cetak-3d.md
    kebijakan-pembayaran.md
    syarat-berkas-3d.md
    tahapan-status-pesanan.md
    faq-umum.md
    kontak-dan-jam-operasional.md
  en/                        Bahasa Inggris (opsional per berkas)
    profil-lab.md
    ...
```

Nama berkas di `en/` harus sama persis dengan pasangannya di `id/`. Berkas yang
tidak punya pasangan Inggris otomatis di-fallback ke versi Indonesia saat locale
`en` tidak menemukan hasil.

### Frontmatter

```yaml
---
id: prosedur-pemesanan-cetak-3d
judul: Prosedur Pemesanan Cetak 3D
kategori: layanan
audience: public
url: /services
kata_kunci: [cetak 3d, print 3d, pesan cetak, order, cara pesan]
terakhir_ditinjau: 2026-07-30
---
```

| Field               | Wajib | Keterangan                                                                                                                              |
| ------------------- | ----- | --------------------------------------------------------------------------------------------------------------------------------------- |
| `id`                | ya    | Identitas stabil. Sama di `id/` dan `en/`. Jangan pernah diubah — ini yang dipakai `source_key`.                                        |
| `judul`             | ya    | Ikut di-embed bersama tiap potongan sebagai konteks.                                                                                    |
| `kategori`          | ya    | Salah satu: `layanan`, `produk`, `agenda`, `publikasi`, `proyek`, `profil`, `kebijakan`, `faq`                                          |
| `audience`          | ya    | `public` atau `authenticated`. Isi `public` kecuali benar-benar hanya relevan bagi user login.                                          |
| `url`               | tidak | Path relatif untuk tautan "selengkapnya". Kosongkan bila tidak ada halaman terkait.                                                     |
| `kata_kunci`        | tidak | Ditambahkan ke teks yang di-embed, tidak ditampilkan ke user. Gunakan untuk menampung sinonim yang canggung bila ditulis di badan teks. |
| `terakhir_ditinjau` | ya    | Tanggal manusia terakhir memverifikasi isinya. Bukan tanggal edit.                                                                      |

### Aturan badan dokumen

**Heading level dua adalah unit potongan.** Satu `##` menjadi satu chunk. Isi
tiap `##` harus menjawab satu topik secara utuh.

**Batas panjang 1.500 karakter per `##`.** Kalau lebih, pecah jadi dua `##`.
Model embedding memotong diam-diam di 2.048 token, jadi bagian yang terlalu
panjang akan kehilangan ekornya tanpa peringatan.

**Heading level tiga untuk pertanyaan FAQ.** Tulis persis seperti orang bertanya:
`### Berapa lama proses cetak 3D?`, bukan `### Durasi Pengerjaan`.

**Angka harus konkret.** Tulis "Rp 5.000 per gram", bukan "harga bervariasi
tergantung bahan". Chatbot dilarang menebak angka, jadi angka yang tidak tertulis
di sini akan berujung pada jawaban "belum ada informasi".

**Jangan pakai tabel markdown.** Tabel kehilangan makna saat diratakan jadi teks
untuk embedding. Tulis sebagai kalimat: "Material PLA dikenai tarif Rp 5.000 per
gram, PETG Rp 7.000 per gram, dan resin Rp 12.000 per gram."

**Jangan pakai kata rujukan lintas bagian.** "Seperti dijelaskan di atas" menjadi
tidak bermakna begitu potongan berdiri sendiri.

### Contoh

Lihat `knowledge/id/prosedur-pemesanan-cetak-3d.md` dan
`knowledge/id/faq-umum.md` di paket ini.

## Sumber B — dokumen dari database

Dibangkitkan `BuildKnowledgeIndexAction`, tidak ditulis tangan. Tiap model
menghasilkan satu dokumen per locale dengan template tetap di bawah.

Semua field teks dibaca lewat `localized()`. `source_key` berpola
`db:{model}:{id}:{locale}`.

### Service

```
source_key : db:service:{id}:{locale}
kategori   : layanan
audience   : public
url        : route('services.show', $service->id)
judul      : {name}

## Tentang layanan {name}
{description}

## Biaya layanan {name}
Layanan {name} memiliki harga dasar Rp {base_price}. Harga akhir ditentukan
setelah tim lab meninjau berkas dan kebutuhanmu.

## Cara memesan layanan {name}
{teks tetap yang mengarahkan ke /orders, plus whatsapp_number bila ada}
```

### Training

```
source_key : db:training:{id}:{locale}
kategori   : agenda
url        : route('training.show', $training->slug)
judul      : {title}

## Tentang workshop {title}
{subtitle}. {description}

## Jadwal dan lokasi workshop {title}
Workshop ini berlangsung pada {date format panjang} di {location}.
Tingkat {level}, durasi {duration}, bahasa pengantar {language}.
Kuota peserta {max_participants} orang.

## Biaya dan pendaftaran workshop {title}
{is_paid ? "Biaya pendaftaran Rp {price}." : "Workshop ini gratis."}
Pendaftaran dilakukan lewat halaman detail workshop setelah masuk ke akun.

## Materi yang dipelajari di workshop {title}
{what_you_will_learn digabung jadi kalimat}

## Instruktur workshop {title}
{instructor_name}, {instructor_title}. {instructor_bio}
```

### Event

```
source_key : db:event:{id}:{locale}
kategori   : agenda
url        : route('events.show', $event->slug)
judul      : {name}

## Tentang {category} {name}
{theme_title}. {subtitle}. {description}

## Jadwal dan lokasi {name}
Kegiatan ini berlangsung {starts_at}–{ends_at} di {location}.
{registration_url ? "Pendaftaran dibuka di {registration_url}." : ""}
```

### Publication

```
source_key : db:publication:{id}:{locale}
kategori   : publikasi
url        : route('publications.show', $publication->id)
judul      : {title}

## Publikasi {title}
Publikasi berjudul "{title}" ditulis oleh {author} dan terbit pada
{published_at:Y} di {journal}. {doi ? "DOI {doi}." : ""}
Kategori {category}. {is_free_access ? "Akses terbuka." : "Akses terbatas."}

## Abstrak publikasi {title}
{abstract dipotong 1200 karakter}
```

### Product, OpenSourceProject, LabTeamPerson, PageSection

Pola sama: satu `##` per aspek yang bisa ditanyakan berdiri sendiri, judul
diulang di tiap heading agar potongan tetap membawa konteksnya.

Untuk `PageSection`, kelompokkan per `page_name` menjadi satu dokumen dengan satu
`##` per `section_key`.

## Teks yang benar-benar di-embed

Untuk tiap potongan, yang dikirim ke model embedding adalah:

```
{judul} — {heading}
{kata_kunci digabung koma, bila ada}
{isi potongan}
```

Judul disertakan karena potongan sering kehilangan konteks tanpanya. Kalimat
"Biaya dihitung per gram" tidak bisa dibedakan antara layanan cetak dan scan
sampai judulnya ikut.

## Dokumen minimum sebelum rilis

Jangan menayangkan chatbot sebelum tujuh berkas ini terisi lengkap di `knowledge/id/`:

1. `profil-lab.md` — apa itu IDIG, di mana, melayani siapa
2. `prosedur-pemesanan-cetak-3d.md` — alur dari berkas sampai barang jadi
3. `syarat-berkas-3d.md` — format, ukuran maksimum, ketebalan minimum
4. `kebijakan-pembayaran.md` — termin DP dan pelunasan, cara unggah bukti
5. `tahapan-status-pesanan.md` — arti tiap tahap `CustomerStatus`
6. `faq-umum.md` — minimal 15 pertanyaan nyata
7. `kontak-dan-jam-operasional.md` — ke mana user diarahkan saat chatbot menyerah

Berkas nomor 5 dan 7 sering dilewatkan dan keduanya penting. Nomor 5 menjawab
pertanyaan paling sering muncul di dashboard ("warehouse check itu apa?"), dan
nomor 7 adalah tujuan setiap jawaban fallback — tanpanya chatbot menolak menjawab
tanpa memberi jalan keluar.

## Cara merawat

Tabel `chatbot_logs` mencatat skor tertinggi tiap pertanyaan. Tinjau mingguan:

```sql
SELECT question, locale, top_score, created_at
FROM chatbot_logs
WHERE answered = 0 OR top_score < 0.6
ORDER BY created_at DESC
LIMIT 50;
```

Tiap baris adalah pertanyaan nyata yang tidak terjawab baik. Tambahkan sebagai
`###` di `faq-umum.md` memakai kalimat asli penanyanya, lalu jalankan ulang
`php artisan chatbot:index`. Inilah mekanisme yang membuat chatbot makin akurat
tanpa melatih model sama sekali.
