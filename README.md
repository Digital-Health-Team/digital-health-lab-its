Creator: Eka Nata
Inspired by : Sufyan service layer architecture

---

# 🚀 Gretiva Laravel 12 Hybrid Action Oriented Architecture Template

Template aplikasi manajemen proyek berbasis **Laravel 12** dan **Livewire 3**, dirancang dengan arsitektur yang bersih (**Actions & DTOs**), antarmuka modern menggunakan **Mary UI**, dan dukungan **Multi-Bahasa (I18n)** tingkat lanjut baik untuk UI statis maupun konten dinamis database.

## ✨ Fitur Utama

- **Arsitektur Clean Code:** Menggunakan pattern `Actions` dan `Data Transfer Objects (DTO)` untuk memisahkan logika bisnis dari Controller/Livewire.
- **Full Multi-Language Support:**
- **Static UI:** Menggunakan file JSON Laravel (`id.json`, `en.json`).
- **Dynamic Content:** Menggunakan `spatie/laravel-translatable` untuk kolom database (JSON).
- **Auto-Translation:** Fitur otomatis menerjemahkan input (ID ↔ EN) menggunakan Google Translate API (gratis) jika salah satu kolom kosong.

- **User Management:** CRUD lengkap dengan Role (Super Admin, PM, Staff) dan proteksi akun sendiri.
- **Project Management:** CRUD dengan input multi-bahasa (Tabbed Input) dan status deadline real-time.
- **Settings & Preferences:** Sinkronisasi bahasa (Navbar ↔ Settings), update profil, dan preferensi notifikasi berbasis JSON.
- **Reusable Components:** Komponen Blade siap pakai untuk Modal Konfirmasi, Input Translatable, dan Notifikasi.

## 🛠️ Tech Stack

- **Framework:** Laravel 11
- **Frontend:** Livewire 3 + Alpine.js
- **UI Library:** Mary UI (DaisyUI + TailwindCSS)
- **Packages:**
- `spatie/laravel-translatable` (Database Translation)
- `stichoza/google-translate-php` (Auto Translation Service)

---

## ⚙️ Instalasi

Ikuti langkah-langkah ini untuk menjalankan project di lokal:

1. **Clone Repository**

```bash
git clone https://github.com/username/project-name.git
cd project-name

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

4. **Konfigurasi Database**
   Buat database baru, lalu sesuaikan `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=

```

5. **Migrasi & Seeding**

```bash
php artisan migrate --seed

```

6. **Jalankan Server**

```bash
composer run dev

```

---

## 📂 Struktur & Arsitektur

Project ini tidak menaruh logika berat di dalam Livewire Component.

```text
app/
├── Actions/           # Logika Bisnis (Create, Update, Delete)
│   ├── User/
│   └── Project/
├── DTOs/              # Data Transfer Objects (Validasi bentuk data)
├── Services/          # Service tambahan (misal: AutoTranslationService)
├── Livewire/          # Controller UI (Hanya menghubungkan View ke Action)
└── Models/            # Eloquent Models

```

---

## 📖 Dokumentasi Modul

### 1. User Management

Modul untuk mengelola pengguna aplikasi.

- **Fitur:** Create, Read, Update, Delete (CRUD).
- **Validasi:** Email unik, Password opsional saat edit.
- **Proteksi:** User tidak bisa menghapus akunnya sendiri yang sedang login.
- **Lokasi Code:** `App\Livewire\Admin\User\Index.php`

### 2. Project Management (Multi-Language)

Modul inti untuk manajemen proyek dengan fitur terjemahan canggih.

- **Input Tab:** Input nama dan deskripsi memiliki tab (🇮🇩 Indonesia | 🇺🇸 English).
- **Auto-Translate:** Jika Admin hanya mengisi Bahasa Indonesia, sistem otomatis mengisi Bahasa Inggris (dan sebaliknya) saat disimpan.
- **Database:** Data disimpan dalam kolom JSON (`name->en`, `name->id`).
- **Lokasi Code:** `App\Livewire\Admin\Project\Index.php`

### 3. Settings (Sinkronisasi Bahasa)

Halaman pengaturan profil dan preferensi aplikasi.

- **Language Sync:** Mengganti bahasa di Navbar akan mengubah dropdown di Settings, dan sebaliknya. Status bahasa disimpan di **Session** dan **Database User**.
- **Notification Preferences:** Checkbox notifikasi (Email/WA) disimpan dalam kolom JSON `preferences` di tabel users.

---

## 🧩 Komponen Reusable (Wajib Tahu)

Gunakan komponen ini untuk mempercepat development fitur baru.

### A. Modal Konfirmasi Hapus

Jangan buat modal manual. Gunakan komponen ini untuk konsistensi.

```blade
<x-modal-confirm
    wire:model="deleteModalOpen"
    title="Hapus Data?"
    text="Data yang dihapus tidak dapat dikembalikan."
    confirm-text="Ya, Hapus"
    method="delete"
/>

```

### B. Input Multi-Bahasa (Translatable)

Membuat input text/textarea dengan tab switcher otomatis.

```blade
<x-translatable-input
    label="Nama Project"
    model="name"  {{-- Properti Livewire harus array ['id'=>'', 'en'=>''] --}}
/>

{{-- Untuk Textarea --}}
<x-translatable-input label="Deskripsi" model="description" type="textarea" />

```

---

## 🌐 Alur Kerja Terjemahan (Translation Workflow)

### 1. UI Statis (Menu, Label, Tombol)

Gunakan helper `__('...')`.

- Jalankan scanner jika ada teks baru:

```bash
php artisan translatable:export id
php artisan translatable:export en

```

- Edit file di folder `/lang`.

### 2. Konten Dinamis (Database)

Project menggunakan `spatie/laravel-translatable`.

- **Model:**

```php
public $translatable = ['name', 'description'];

```

- **Livewire:**
  Gunakan `getTranslations('field')` saat edit data untuk mengambil _raw array_.

---

## 🛡️ License

Project ini bersifat open-source di bawah lisensi [MIT license](https://opensource.org/licenses/MIT).

---

> **Catatan Developer:**
> Pastikan menjalankan `php artisan optimize:clear` jika terjadi isu pada cache konfigurasi bahasa saat berpindah environment.
