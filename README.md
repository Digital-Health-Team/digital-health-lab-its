Creator: Eka Nata
Inspired by : Sufyan service layer architecture
Title: Gretiva Project Management Template (Laravel 12 + Livewire 3 + I18n + Clean Code)

---

# 🚀 Laravel 12 Hybrid Action Oriented Template

Template aplikasi manajemen proyek berbasis **Laravel 11** dan **Livewire 3**, dirancang dengan arsitektur **Clean Code** (Action/DTO), antarmuka modern menggunakan **Mary UI**, dan sistem **Multi-Bahasa (I18n)** hibrida (Statis & Dinamis) yang canggih.

## ✨ Fitur Utama

- **🔐 Autentikasi & Verifikasi:**
- Login, Register, dan Logout.
- **Wajib Verifikasi Email** (`MustVerifyEmail`) dengan tampilan kustom Mary UI.
- Middleware untuk memastikan user aktif dan terverifikasi.

- **🌍 Sistem Multi-Bahasa Canggih:**
- **UI Statis:** Terjemahan label/menu menggunakan file JSON (`lang/id.json`).
- **Konten Database:** Kolom dinamis (JSON) menggunakan `spatie/laravel-translatable`.
- **Auto-Translation:** Input otomatis diterjemahkan (misal: ID -> EN) menggunakan Google Translate API saat data disimpan.
- **Sinkronisasi:** Bahasa di Navbar dan Settings selalu sinkron (Session + DB).

- **👥 User Management:**
- CRUD User dengan Role (Super Admin, PM, Staff).
- Proteksi akun (tidak bisa menghapus diri sendiri).

- **filers Project Management:**
- CRUD Project dengan input tab (Indonesia | English).
- Status deadline real-time.

- **⚙️ Settings & Preferences:**
- Update Profil & Password.
- Preferensi Notifikasi (Email/WA) disimpan dalam kolom JSON.

- **🧩 Reusable Components:**
- Modal Konfirmasi Hapus (`<x-modal-confirm>`).
- Input Multi-Bahasa (`<x-translatable-input>`).

---

## 🛠️ Tech Stack & Packages

Project ini dibangun menggunakan teknologi terkini:

- **Framework:** Laravel 11
- **Frontend:** Livewire 3 + Alpine.js
- **UI Library:** Mary UI (DaisyUI + TailwindCSS)

### 📦 Key Packages

Berikut adalah package utama yang menopang fitur unik aplikasi ini:

| Package                                                                                                                                                                                                                           | Versi   | Kegunaan                                                                                                                                                            |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **[`spatie/laravel-translatable`](<https://www.google.com/search?q=%5Bhttps://github.com/spatie/laravel-translatable%5D(https://github.com/spatie/laravel-translatable)>)**                                                       | `^6.12` | Menyimpan terjemahan data dinamis (Project Name, Desc) dalam satu kolom database bertipe `JSON`.                                                                    |
| **[`kkomelin/laravel-translatable-string-exporter`](<https://www.google.com/search?q=%5Bhttps://github.com/kkomelin/laravel-translatable-string-exporter%5D(https://github.com/kkomelin/laravel-translatable-string-exporter)>)** | `^1.25` | Memindai file project (`.php`, `.blade.php`) untuk mencari string `__('...')` dan mengekspornya ke file `lang/{code}.json` secara otomatis.                         |
| **[`stichoza/google-translate-php`](<https://www.google.com/search?q=%5Bhttps://github.com/Stichoza/google-translate-php%5D(https://github.com/Stichoza/google-translate-php)>)**                                                 | `^5.3`  | **Engine Auto-Translate**. Digunakan di Backend (Action) untuk menerjemahkan input user secara otomatis jika salah satu bahasa dikosongkan. Gratis & Tanpa API Key. |

---

## ⚙️ Instalasi

1. **Clone Repository**

```bash
git clone https://github.com/username/gretiva-project.git
cd gretiva-project

```

2. **Install Dependencies**

```bash
composer install
npm install && npm run build

```

3. **Setup Environment**

```bash
cp .env.example .env
php artisan key:generate

```

4. **Konfigurasi Database (.env)**
   Pastikan database sudah dibuat di MySQL.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=gretiva_db
DB_USERNAME=root
DB_PASSWORD=

```

5. **Setup Email (Penting untuk Verifikasi)**
   Gunakan Mailtrap atau `log` untuk testing lokal.

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
# ... credentials ...

```

6. **Migrasi & Seeding**
   Ini akan membuat User (Admin, PM, Staff) dan Data Project Dummy.

```bash
php artisan migrate --seed

```

7. **Jalankan Server**

```bash
php artisan serve

```

> **Akun Login Default:**
>
> - **Email:** `admin@gretiva.com`
> - **Password:** `password`

---

## 🏛️ Arsitektur Aplikasi

Kami memisahkan logika bisnis dari Controller (Livewire) agar kode tetap bersih dan mudah ditest.

### 1. Actions & DTOs

Livewire Component hanya bertugas menerima input dan menampilkan output. Logika penyimpanan ada di Action.

- `App\DTOs\Project\ProjectData`: Memvalidasi bentuk data transfer.
- `App\Actions\Project\CreateProjectAction`: Menangani penyimpanan ke DB + Auto Translate.

### 2. Service: Auto-Translation

Terletak di `App\Services\AutoTranslationService.php`.
Service ini menggunakan `stichoza/google-translate-php` untuk mengecek:

- Jika Input ID ada tapi EN kosong Translate ID ke EN.
- Jika Input EN ada tapi ID kosong Translate EN ke ID.

---

## 🌐 Panduan Terjemahan (Translation Workflow)

### A. Mengelola Teks UI (Menu, Tombol, Pesan Error)

Teks ini bersifat statis.

1. Tulis di kode: `{{ __('Dashboard') }}`.
2. Jalankan perintah eksportir (Package `kkomelin`):

```bash
# Scan dan update file JSON bahasa
php artisan translatable:export id
php artisan translatable:export en

```

3. Buka file `lang/id.json` atau `lang/en.json` dan edit terjemahannya.

### B. Mengelola Data Database (Project Name, Description)

Data ini bersifat dinamis per input user (Package `spatie`).

- **Database:** Kolom harus tipe `json`.
- **Model:** Gunakan Trait `HasTranslations`.
- **View:** Gunakan komponen `<x-translatable-input>` untuk menampilkan Tab ID/EN.

---

## 🧩 Dokumentasi Komponen

### 1. Modal Konfirmasi Hapus (`<x-modal-confirm>`)

Gunakan ini untuk semua aksi hapus agar seragam.

```blade
<x-modal-confirm
    wire:model="deleteModalOpen"
    title="Hapus Project?"
    text="Data yang dihapus tidak dapat dikembalikan."
    confirm-text="Ya, Hapus"
    method="delete"
/>

```

### 2. Input Multi-Bahasa (`<x-translatable-input>`)

Otomatis membuat Tab ID dan EN.

```blade
<x-translatable-input
    label="Nama Project"
    model="name"  {{-- Model Livewire harus array ['id'=>'', 'en'=>''] --}}
/>

```

---

## 🛡️ Keamanan & Validasi

- **MustVerifyEmail:** User baru wajib verifikasi email sebelum bisa akses Dashboard.
- **Role Based:** Super Admin, PM, Staff.
- **Self-Delete Protection:** User tidak bisa menghapus akun sendiri via Admin Panel.
- **Unique Email:** Validasi email unik saat Create/Update user (mengabaikan ID sendiri saat edit).

---

## 📝 License

Project ini bersifat open-source di bawah lisensi [MIT license](https://opensource.org/licenses/MIT).
