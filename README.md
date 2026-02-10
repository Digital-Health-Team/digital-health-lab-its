Berikut adalah **Full `README.md**` yang lengkap, profesional, dan mencakup semua fitur serta konfigurasi teknis (termasuk Scheduler) yang telah kita bangun.

Anda bisa menyalin seluruh kode di bawah ini dan menimpanya ke file `README.md` di root project Anda.

```markdown
# 📰 NewsPortal (Modern News CMS)

![Laravel](https://img.shields.io/badge/Laravel-11-red?style=flat-square&logo=laravel)
![Livewire](https://img.shields.io/badge/Livewire-3-purple?style=flat-square&logo=livewire)
![TailwindCSS](https://img.shields.io/badge/Tailwind-CSS-blue?style=flat-square&logo=tailwindcss)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange?style=flat-square&logo=mysql)

A high-performance, SEO-friendly news portal system designed for speed and ease of management. Built with **Laravel 11** and **Livewire**, it offers a seamless Single Page Application (SPA) feel without the complexity of a separate frontend framework.

## ✨ Key Features

### 🚀 Public (Visitors)
- **Smart Filtering:**
    - 🔥 **Trending Today:** News sorted by `daily_views` (auto-reset at 00:00).
    - 📅 **Top of The Month:** Most popular news this month (auto-reset on 1st).
    - ⭐ **Editor's Choice:** Curated headlines selected by admins.
- **Read Counter:** Atomic increment logic using Session blocking (prevents spam refresh).
- **Responsive UI:** Optimized for Mobile and Desktop reading experiences.
- **Interactive:** Comment system and search functionality.

### 🛠 Admin (Back-Office)
- **Dashboard Analytics:** Real-time stats for Total Views, Drafts, and User Registrations.
- **Content Management:**
    - Full CRUD for News, Categories, and Tags.
    - Rich Text Editor for news content.
    - Image Upload with preview.
- **Role Management:** Super Admin vs Standard Users.
- **Service Layer Pattern:** Clean code architecture separating business logic from controllers.

## 🛠 Tech Stack

- **Framework:** Laravel 11
- **Fullstack Component:** Livewire 3
- **UI Components:** MaryUI + Tailwind CSS
- **Database:** MySQL 8.0
- **Icons:** Heroicons

## ⚙️ Installation Guide

Follow these steps to setup the project locally:

### 1. Prerequisites
Ensure you have the following installed:
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

### 2. Setup Project

```bash
# Clone the repository
git clone [https://github.com/yourusername/news-portal.git](https://github.com/yourusername/news-portal.git)
cd news-portal

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate Application Key
php artisan key:generate

```

Open `.env` file and configure your database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=news_portal_db
DB_USERNAME=root
DB_PASSWORD=

```

### 4. Database Migration & Seeding

This will create tables and populate them with dummy data (50 News items with random views).

```bash
php artisan migrate:fresh --seed

```

### 5. Run the Application

Open two terminal tabs/windows:

```bash
# Terminal 1: Start Laravel Server
php artisan serve

# Terminal 2: Compile Assets (Tailwind/Vite)
npm run dev

```

Access the app at `http://localhost:8000`.

---

## 🔐 Default Credentials

After running the seeder, use these credentials to access the Admin Dashboard:

| Role | Email | Password |
| --- | --- | --- |
| **Super Admin** | `admin@news.test` | `password` |
| **Regular User** | (Randomly generated via Factory) | `password` |

---

## 📅 Scheduler Setup (Auto-Reset Views)

This project relies on Laravel's Task Scheduling to automatically reset view counters. Without this, the "Trending Today" and "Top Monthly" features will not update correctly.

### 1. Logic Location

The scheduling logic is defined in **`routes/console.php`**. It contains commands to reset specific columns in the `news` table.

```php
// Reset Daily Views (Every day at 00:00)
Schedule::call(function () {
    DB::table('news')->update(['daily_views' => 0]);
})->daily();

// Reset Monthly Views (Every 1st of month at 00:00)
Schedule::call(function () {
    DB::table('news')->update(['monthly_views' => 0]);
})->monthly();

```

### 2. Running on Localhost (Development)

Since your local machine doesn't have a background Cron job running, you must keep a terminal window open running the scheduler worker:

```bash
php artisan schedule:work

```

> **Note:** As long as this command is running, Laravel will check every minute if a task needs to be executed.

### 3. Running on Production (VPS / cPanel)

On a live server, you must add a single Cron entry to your server configuration.

1. Open your server terminal or cPanel **Cron Jobs** menu.
2. Add the following entry to run every minute:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1

```

---

## 🗃️ Architecture Overview

This project implements the **Service Repository Pattern** to keep the code clean and maintainable.

* **Models:** `News`, `Category`, `Tag`, `User`, `NewsImage`.
* **Services:** `NewsService`, `CategoryService`, `UserService`.
* Handles database logic, file uploads, and complex queries.


* **Livewire Components:** Handles UI state and user interaction.
* Located in `app/Livewire/Admin`.



## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

```

```
