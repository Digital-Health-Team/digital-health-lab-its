# 📰 NewsPortal (Project Name)

![Laravel](https://img.shields.io/badge/Laravel-11-red) ![TailwindCSS](https://img.shields.io/badge/Tailwind-CSS-blue) ![MySQL](https://img.shields.io/badge/Database-MySQL-orange) ![Status](https://img.shields.io/badge/Status-Development-yellow)

A modern news portal designed with a primary focus on **Performance (Speed)**, **Visual Aesthetics (UI/UX)**, and **Ease of Management (User-Friendly CMS)**.

This system is built to handle fast news updates (Breaking News) with a very simple admin interface that can be operated by non-technical users (e.g., non-tech staff).

## ✨ Main Features

### 🚀 Public (Visitors)

- **High Performance:** Extremely fast page loading, optimized for mobile devices.
- **Attractive Visual Design:** Clean layout, comfortable-to-read typography.
- **Smart Filtering:**
    - 🔥 **Hot/Trending:** News with highest views today.
    - ⭐ **Editor's Choice:** News manually selected by admin.
    - 📅 **Top of The Month:** Most popular news this month.
    - ⚡ **Latest:** Real-time news feed.
- **Rich Media:** Support for photo galleries (slider) within articles.
- **Interactive:** Comment features and login for visitors.

### 🛠 Admin (Back-Office)

- **Simple Interface:** Very straightforward dashboard with large buttons and minimal distractions.
- **Easy CRUD:** Adding news is as simple as updating a social media post.
- **Multi-Image Upload:** Drag & drop multiple photos at once, auto resize/compress.
- **One-Click Actions:** Simple buttons to mark news as "Headline" or "Draft".

## 🛠 Technologies Used

- **Backend:** Laravel 11 (PHP)
- **Frontend:** Blade Templates + Livewire (for dynamic interaction without reload)
- **Styling:** Tailwind CSS (Custom Design)
- **Database:** MySQL
- **Optimization:** Redis (Optional, for caching Trending News)

## 📦 System Requirements

Before starting, ensure your server/local machine has:

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

## ⚙️ Installation Guide

Follow these steps to run the project on your local machine:

1.  **Clone Repository**

    ```bash
    git clone [https://github.com/username/nama-project-berita.git](https://github.com/username/nama-project-berita.git)
    cd nama-project-berita
    ```

2.  **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

3.  **Setup Environment**
    Copy `.env.example` to `.env`:

    ```bash
    cp .env.example .env
    ```

    Open `.env` file and adjust your database configuration:

    ```env
    DB_DATABASE=your_database_name
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Generate Key & Migrate**

    ```bash
    php artisan key:generate
    php artisan migrate --seed
    ```

    _(Use `--seed` if you want to populate initial dummy data)_

5.  **Run Project**
    Open two separate terminals:

    ```bash
    # Terminal 1 (Laravel Server)
    php artisan serve

    # Terminal 2 (Vite for frontend assets)
    npm run dev
    ```

## 🗃️ Database Schema (Summary)

The database structure is designed for high performance with indexing on main filter columns.

- `users`: Admin & Visitors.
- `news`: Main news table (stores daily/monthly view counts).
- `categories`: News categories (Politics, Sports, etc.).
- `news_images`: Stores one-to-many relationship for news photos.
- `comments`: Visitor comments.

> _See `database/migrations` files for full details._

## 📅 "Trending" & "Top Monthly" Logic

The system uses **Task Scheduling** to reset view counters for relevant data:

1.  **Hot/Trending (Daily):** `daily_views` column resets to 0 every day at 00:00.
2.  **Top Monthly:** `monthly_views` column resets to 0 on the 1st of each month.
3.  **All Time:** `views_count` continues to increment (never reset).

To run the scheduler locally:

```bash
php artisan schedule:work
```
