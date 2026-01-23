# MBKM Internship Logbook System

A production-grade internship management system built with the TALL Stack (Tailwind CSS, Alpine.js, Laravel 12, Livewire 3) for managing student internship activities, logbooks, and lecturer supervision.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Database Architecture](#database-architecture)
- [User Roles & Permissions](#user-roles--permissions)
- [Installation & Setup](#installation--setup)
- [Project Structure](#project-structure)
- [Key Business Logic](#key-business-logic)
- [License](#license)

---

## 🎯 Overview

The MBKM (Merdeka Belajar Kampus Merdeka) Internship Logbook System is designed to streamline the management of student internships, daily activity logging, and lecturer supervision. The system prevents internship period overlaps, provides real-time notifications using Laravel Reverb, and generates professional PDF/DOCX reports.

### Key Capabilities
- **Internship Period Management** with automatic overlap detection
- **Daily Logbook Entries** with file attachment support
- **Real-time Validation Notifications** via WebSockets (Laravel Reverb)
- **Role-based Access Control** (Super Admin, Lecturer, Student)
- **Document Export** (PDF & DOCX reports)

---

## ✨ Features

### For Students (Mahasiswa)
- ✅ Create and manage internship periods
- ✅ Submit daily logbook entries with proof attachments
- ✅ Real-time validation status updates
- ✅ Progress tracking with visual indicators
- ✅ Export logbooks to PDF/Word documents
- ✅ View lecturer feedback on submissions

### For Lecturers (Dosen)
- ✅ Supervise multiple students
- ✅ Review and validate logbook entries
- ✅ Provide feedback on student activities
- ✅ Monitor student progress in real-time
- ✅ Dashboard with student overview cards

### For Super Admins
- ✅ Full system access and management
- ✅ User management (create, edit, delete users)
- ✅ System configuration and monitoring
- ✅ Access to all data across the platform

---

## 🛠 Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend Framework** | Laravel 12 (Latest Stable) |
| **Frontend** | Livewire 3 (Class-based Components) |
| **UI Library** | MaryUI (Livewire-specific, built on DaisyUI) |
| **Styling** | Tailwind CSS |
| **Interactivity** | Alpine.js |
| **Database** | MySQL 8.0 |
| **Real-time** | Laravel Reverb (WebSocket Server) |
| **Development Environment** | Local (XAMPP/WAMP/Laragon) or Docker |
| **Asset Bundling** | Vite |
| **PDF Generation** | barryvdh/laravel-dompdf |
| **File Storage** | Laravel Storage (Local/S3) |

---

## 🗄 Database Architecture

### ERD Overview

```
┌─────────────┐         ┌──────────────────┐         ┌─────────────┐
│    Users    │◄───┐    │ Internship       │    ┌───►│  Logbooks   │
│             │    │    │ Periods          │    │    │             │
│ - id        │    └────┤ - id             ├────┘    │ - id        │
│ - name      │         │ - student_id (FK)│         │ - period_id │
│ - email     │    ┌────┤ - lecturer_id(FK)│         │ - date      │
│ - role      │    │    │ - company_name   │         │ - activity  │
│ - is_active │    │    │ - start_date     │         │ - status    │
└─────────────┘    │    │ - end_date       │         │ - feedback  │
       │           │    │ - status         │         └─────────────┘
       ├───────────┤    └──────────────────┘
       │           │
┌──────▼──────┐   │
│  Student    │   │
│  Profiles   │   │
│             │   │
│ - user_id   │   │
│ - nim       │   │
│ - competency│   │
│ - phone     │   │
└─────────────┘   │
                  │
┌──────▼──────────┐
│   Lecturer      │
│   Profiles      │
│                 │
│ - user_id       │
│ - nidn          │
│ - position      │
└─────────────────┘
```

### Table Specifications

#### 1. **users**
Core authentication and role management table.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | User identifier |
| `name` | VARCHAR(255) | NOT NULL | Full name |
| `email` | VARCHAR(255) | UNIQUE, NOT NULL | Email address |
| `password` | VARCHAR(255) | NOT NULL | Hashed password |
| `avatar_path` | VARCHAR(255) | NULLABLE | Profile picture path |
| `role` | ENUM | NOT NULL | super_admin, mahasiswa, dosen |
| `is_active` | BOOLEAN | DEFAULT TRUE | Account status |
| `created_at` | TIMESTAMP | | |
| `updated_at` | TIMESTAMP | | |

#### 2. **student_profiles**
Extended profile information for students.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | |
| `user_id` | BIGINT UNSIGNED | FK → users.id, CASCADE DELETE | |
| `nim` | VARCHAR(20) | UNIQUE, NOT NULL | Student ID Number |
| `competency` | VARCHAR(255) | NOT NULL | Study program/major |
| `phone` | VARCHAR(20) | NULLABLE | Contact number |

#### 3. **lecturer_profiles**
Extended profile information for lecturers.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | |
| `user_id` | BIGINT UNSIGNED | FK → users.id, CASCADE DELETE | |
| `nidn` | VARCHAR(20) | UNIQUE, NOT NULL | National Lecturer ID |
| `position` | VARCHAR(255) | NULLABLE | Academic position |

#### 4. **internship_periods**
Defines WHERE and WHEN a student interns.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | |
| `student_id` | BIGINT UNSIGNED | FK → users.id, CASCADE DELETE | Student user |
| `lecturer_id` | BIGINT UNSIGNED | FK → users.id, CASCADE DELETE | Supervisor |
| `company_name` | VARCHAR(255) | NOT NULL | Internship company |
| `start_date` | DATE | NOT NULL | Internship start |
| `end_date` | DATE | NOT NULL | Internship end |
| `status` | ENUM | NOT NULL | active, completed, cancelled |
| `created_at` | TIMESTAMP | | |
| `updated_at` | TIMESTAMP | | |

**Indexes:**
- Composite index on `(student_id, status)` for efficient querying

#### 5. **logbooks**
Daily activity logs submitted by students.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | |
| `internship_period_id` | BIGINT UNSIGNED | FK → internship_periods.id, CASCADE DELETE | |
| `date` | DATE | NOT NULL | Activity date |
| `activity` | TEXT | NOT NULL | Description of work |
| `proof_file_path` | VARCHAR(255) | NULLABLE | Attachment path |
| `status` | ENUM | DEFAULT 'pending' | pending, validated, rejected |
| `feedback` | TEXT | NULLABLE | Lecturer comments |
| `created_at` | TIMESTAMP | | |
| `updated_at` | TIMESTAMP | | |

**Constraints:**
- Unique constraint on `(internship_period_id, date)` - ensures one log per day per period

---

## 👥 User Roles & Permissions

### 1. **Super Admin** (`super_admin`)
**Purpose:** Full system administration and oversight

**Capabilities:**
- Create, read, update, delete all users
- Manage lecturer and student profiles
- Access all internship periods and logbooks
- System configuration and settings
- View system-wide analytics and reports

**Access Level:** Unrestricted

---

### 2. **Lecturer** (`dosen`)
**Purpose:** Student supervision and logbook validation

**Capabilities:**
- View assigned students' internship details
- Review and validate/reject logbook entries
- Provide feedback on student activities
- Monitor student progress in real-time
- Access personal profile management

**Access Restrictions:**
- Can only view/manage students they supervise
- Cannot access other lecturers' students
- Cannot manage system users

**Dashboard Features:**
- Student overview cards
- Pending validation notifications
- Quick validation interface
- Progress tracking per student

---

### 3. **Student** (`mahasiswa`)
**Purpose:** Internship and logbook management

**Capabilities:**
- Create internship period (with overlap validation)
- Submit daily logbook entries
- Upload proof/documentation files
- View validation status and feedback
- Export logbooks to PDF/DOCX
- Receive real-time validation notifications

**Access Restrictions:**
- Can only view/manage own internship data
- Cannot access other students' information
- Cannot validate own logbooks

**Dashboard Features:**
- Internship setup wizard (if no active internship)
- Daily logbook submission form
- Progress bar (days completed)
- Logbook history with status indicators
- Export functionality

---

## 🚀 Installation & Setup

### Prerequisites

Ensure you have the following installed:
- **PHP** (v8.2 or higher)
- **Composer**
- **MySQL** (v8.0 or higher)
- **Node.js & NPM** (v18+ recommended)
- **Git**
- **Web Server** (Apache/Nginx or use `php artisan serve`)

### Step 1: Clone the Repository

```bash
git clone https://github.com/your-username/mbkm-logbook.git
cd mbkm-logbook
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Environment Configuration

```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Environment Variables

Edit `.env` file with your settings:

```env
APP_NAME="MBKM Logbook"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mbkm_logbook
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_CONNECTION=reverb
QUEUE_CONNECTION=database

REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
```

### Step 5: Create Database

Create a new MySQL database:

```sql
CREATE DATABASE mbkm_logbook CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Or use your preferred database management tool (phpMyAdmin, MySQL Workbench, etc.)

### Step 6: Run Migrations & Seeders

```bash
# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed
```

**Default Seeded Users:**

| Role | Email | Password | Name |
|------|-------|----------|------|
| Super Admin | admin@mbkm.test | password | Super Admin |
| Lecturer | pakbudi@mbkm.test | password | Pak Budi |
| Student | andi@mbkm.test | password | Andi Prasetyo |

### Step 7: Install Frontend Dependencies

```bash
# Install npm packages
npm install

# Build assets (development)
npm run dev

# Or for production
npm run build
```

### Step 8: Create Storage Symlink

```bash
php artisan storage:link
```

### Step 9: Start Laravel Reverb (WebSocket Server)

```bash
# In a separate terminal
php artisan reverb:start
```

### Step 10: Start Development Server

```bash
# Start Laravel development server
php artisan serve
```

### Step 11: Access the Application

Open your browser and navigate to:
- **Application:** [http://localhost:8000](http://localhost:8000)

---

## 📁 Project Structure

```
mbkm-logbook/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Livewire/           # Livewire components
│   │       ├── Student/
│   │       │   ├── Dashboard.php
│   │       │   └── LogbookForm.php
│   │       └── Lecturer/
│   │           └── Supervision.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── StudentProfile.php
│   │   ├── LecturerProfile.php
│   │   ├── InternshipPeriod.php
│   │   └── Logbook.php
│   ├── Services/               # Business logic layer
│   │   ├── InternshipService.php
│   │   └── LogbookService.php
│   ├── Events/
│   │   └── LogbookValidated.php
│   └── Policies/               # Authorization policies
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── components/
│   │   │   └── layouts/
│   │   │       └── app.blade.php
│   │   ├── livewire/          # Livewire views
│   │   │   ├── student/
│   │   │   └── lecturer/
│   │   └── pdf/
│   │       └── logbook-report.blade.php
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php
│   └── channels.php           # Broadcasting channels
├── docker-compose.yml
└── README.md
```

---

## 🧠 Key Business Logic

### 1. Internship Overlap Prevention

**Service:** `App\Services\InternshipService`

**Method:** `createPeriod(User $student, array $data)`

**Algorithm:**
```
1. Query existing internship periods for student
   WHERE status NOT IN ('cancelled')

2. For each existing period:
   IF (new_start_date <= existing_end_date) 
      AND (new_end_date >= existing_start_date)
   THEN
      THROW ValidationException("Internship dates overlap")

3. IF no collision detected:
   CREATE new internship period
```

**Why It Matters:** Prevents students from having multiple active internships simultaneously, ensuring data integrity and realistic scheduling.

---

### 2. Logbook Validation & Real-time Notification

**Service:** `App\Services\LogbookService`

**Method:** `validateLog(Logbook $logbook, string $status, ?string $feedback)`

**Flow:**
```
1. UPDATE logbook SET status = $status, feedback = $feedback

2. DISPATCH Event: LogbookValidated
   ├─ Implements ShouldBroadcast
   └─ Channel: private-App.Models.User.{studentId}

3. Frontend (Livewire) receives event via Echo
   └─ Updates UI in real-time without page refresh
```

**Technologies Used:**
- **Laravel Reverb** for WebSocket server
- **Laravel Echo** for client-side listening
- **Livewire** for reactive UI updates

---

### 3. One Log Per Day Constraint

**Database Constraint:** Unique index on `(internship_period_id, date)`

**Behavior:**
- Students can only create ONE logbook entry per day per internship period
- Attempting duplicate entry throws database exception
- Caught and displayed as user-friendly validation error

---

## 🔐 Security Features

- **Authentication:** Laravel Breeze/Fortify
- **Authorization:** Policy-based access control
- **File Upload Validation:** Max 10MB, restricted MIME types (jpg, png, pdf)
- **SQL Injection Prevention:** Eloquent ORM with parameterized queries
- **XSS Protection:** Blade template escaping
- **CSRF Protection:** Laravel's built-in token verification
- **Password Hashing:** Bcrypt

---

## 📊 Additional Commands

### Running Tests
```bash
php artisan test
```

### Queue Worker (for jobs)
```bash
php artisan queue:work
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Database Reset
```bash
php artisan migrate:fresh --seed
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

---

## 📧 Support

For issues and questions, please create an issue in the GitHub repository or contact the development team.

---

**Built with ❤️ using the TALL Stack**