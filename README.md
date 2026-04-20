Creator: Digital Health Team ITS<br>
Inspired by : Action-Oriented Backend & Feature-Based Hybrid Frontend<br>
Title: ITS Medical Technology Digital Repository & Innovation Hub

---

# 🚀 ITS Medical Technology Digital Repository & Innovation Hub

Platform web interaktif berskala produksi yang berfungsi ganda sebagai **Repositori Digital** untuk pengarsipan inovasi mahasiswa (arsip _open-source_, aplikasi, file 3D) dan sebagai platform **E-Commerce Made-by-Order** (layanan cetak 3D dan desain kustom).

Sistem ini dibangun dengan arsitektur **Modern Monolith** yang menggabungkan **Action-Oriented Backend (Laravel)** dengan **Hybrid Frontend (React + Inertia.js & Livewire)** untuk performa, skalabilitas, dan kecepatan _development_ maksimal.

---

## ✨ Fitur Utama

- **📦 Repositori Digital & 3D Showcase (React)**
    - Tampilan pencarian dan penjelajahan katalog menggunakan _Masonry Grid_.
    - **Interactive 3D Viewer:** Menggunakan `React Three Fiber` untuk merender file `.stl` dan `.obj` secara _real-time_ langsung di _browser_.
    - Sistem unduhan publik untuk karya _open-source_.

- **🛒 E-Commerce & Pemesanan Jasa Lab**
    - Layanan pemesanan cetak 3D berdasarkan kalkulasi berat gram dari aplikasi _slicer_.
    - Negosiasi harga untuk produk kustom antara klien dan Admin.
    - _Dynamic Progress Tracking_ (Slicing -> Printing -> Finishing) di dasbor klien.

- **🗄️ Panel Manajemen Admin (Livewire)**
    - Operasi CRUD super cepat untuk validasi karya (Approve/Reject).
    - Manajemen logistik dan pemotongan stok inventaris bahan mentah (Filamen, Resin, Silikon).
    - Dasbor pelaporan operasional terpusat tanpa API eksternal.

- **☁️ Polymorphic File Management & Cloud Storage**
    - Satu tabel relasi logis (`attachments`) untuk menangani gambar produk, _thumbnail_ proyek, dan file 3D raksasa.
    - Integrasi AWS S3 (atau MinIO) via _Pre-signed URL_ untuk mencegah beban memori server saat unggahan file 3D.

---

## 🏗️ Arsitektur Sistem

Proyek ini menerapkan pemisahan kekhawatiran (_Separation of Concerns_) yang sangat ketat:

### 1. Action-Oriented Backend (Laravel)

Tidak ada logika bisnis di dalam Controller atau komponen Livewire.

- **`app/Actions/`**: Kelas dengan satu tanggung jawab (Single Responsibility) seperti `CreateServiceBookingAction.php` atau `ApprovePublicationAction.php`.
- **`app/DTOs/`**: Objek transfer data yang kuat (Strongly Typed) untuk memvalidasi _payload_ dari _request_ atau antar-lapisan.
- **`laravel/wayfinder`**: Menjamin _type safety_ secara otomatis dari rute PHP ke kode TypeScript klien.

### 2. Hybrid Frontend

- **Public & User Portal (React 19 + Inertia v3):** Memberikan pengalaman _Single Page Application_ (SPA) ultra-cepat yang diperlukan untuk manipulasi keranjang dan rendering grafis 3D.
- **Admin Dashboard (Livewire 3 + Alpine.js):** Digunakan untuk formulir operasional data kompleks yang mempercepat RAD (_Rapid Application Development_).

### 3. Feature-Based React Structure

Kode React dipisahkan secara tegas antara infrastruktur UI dan logika domain:

- **`Core/`**: Komponen UI primitif (`<Box>`, `<Text>`, `<Heading>`), konfigurasi, dan utilitas agnostik.
- **`Features/`**: Modul terisolasi per domain bisnis (contoh: `Features/Repository/`, `Features/Ordering/`). Modul fitur tidak boleh saling impor secara langsung (mencegah _spaghetti code_).

---

## 💻 Tech Stack

- **Core:** Laravel 11/12 (PHP 8.4)
- **Database:** MySQL
- **Frontend Publik:** React 19, TypeScript, Inertia.js v3, Tailwind CSS v4, HeroUI
- **Frontend Admin:** Livewire 3, Alpine.js, Tailwind CSS v4
- **State Management (Client):** Zustand
- **Form Handling:** React-Hook-Form + Zod
- **3D Rendering:** React Three Fiber (`@react-three/fiber`)

---

## 🧩 Standar Komponen Frontend (React)

Untuk menjaga konsistensi Sistem Desain, penggunaan tag HTML _native_ dilarang keras untuk tata letak dan tipografi dasar. Wajib menggunakan abstraksi komponen primitif dari folder `Core/Components/common/`.

| Native HTML                 | Gunakan Komponen Primitif Ini |
| :-------------------------- | :---------------------------- |
| `<div>`, `<section>`, dll   | `<Box>`                       |
| `<h1>` sampai `<h6>`        | `<Heading level={1-6}>`       |
| `<p>`, `<span>`             | `<Text>`                      |
| `<img>`                     | `<Image>`                     |
| `<div>` dengan `.container` | `<Container>`                 |

Dilarang menggunakan tag HTML native (`<div>`, `<p>`, `<h1>`) di dalam fitur React untuk menjaga konsistensi _Design System_. Gunakan komponen pembungkus dari `Core/Components/common/`.

```tsx
<Container>
    <Box className="flex flex-col gap-4">
        <Heading level={1}>Katalog Inovasi</Heading>
        <Text>Temukan berbagai inovasi teknologi medis ITS.</Text>
    </Box>
</Container>
```

## Interactive 3D Viewer Canvas

Untuk memastikan skor performa (Lighthouse) tetap tinggi, komponen 3D berbasis `Three.js` wajib menggunakan metode _lazy loading_.

```tsx
import { lazy, Suspense } from "react";
import { Spinner } from "@/Core/Components/ui/spinner";

const ModelViewer = lazy(
    () => import("@/Features/Repository/components/ModelViewer"),
);

// Di dalam render:
<Suspense fallback={<Spinner label="Memuat aset 3D..." />}>
    <ModelViewer fileUrl={attachment.file_url} />
</Suspense>;
```

---

## 🚀 Instalasi & Setup Lingkungan

### Prasyarat

- PHP 8.4+
- Node.js 20+
- MySQL 8+

### Langkah-langkah

1. **Kloning Repositori:**

    ```bash
    git clone https://github.com/Digital-Health-Team/digital-health-lab-its
    cd digital-health-lab-its
    ```

2. **Instalasi Dependensi:**

    ```bash
    composer install
    npm install
    ```

3. **Konfigurasi Environment:**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    _Penting: Sesuaikan konfigurasi `DB_CONNECTION` (MySQL) dan kredensial S3 di dalam `.env`._

4. **Setup Database & Storage:**

    ```bash
    php artisan migrate --seed
    php artisan storage:link
    ```

5. **Build Aset Hybrid (Vite):**
   Vite akan memproses modul React (Inertia) sekaligus file CSS (Livewire).
    ```bash
    npm run dev
    # atau untuk production: npm run build
    ```

---

## 👥 Pengelolaan Akses (User Roles)

Sistem memberlakukan kontrol akses (_middleware_) berdasarkan tabel `roles`:

1. **Guest:** Hanya akses baca ke rute publik, lihat 3D, dan unduh repositori.
2. **Registered User (Klien/Kreator):** Akses pemesanan jasa kustom, _checkout_, dan dasbor kreator.
3. **Admin Lab:** Mengelola validasi proyek, pesanan, progres layanan, dan stok inventaris.
4. **Super Admin:** Kontrol penuh atas halaman CMS dan pelaporan sistem.

---

**Built with precision by Autonomous AI Agents** _Leveraging Hybrid Architecture and Action-Oriented patterns for production-grade applications._
