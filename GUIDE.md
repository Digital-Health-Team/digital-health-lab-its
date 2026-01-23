# MBKM Internship Logbook System - Technical Architecture Guide

> **Author Perspective:** Senior Principal Laravel Architect  
> **Purpose:** Production-grade internship management system with real-time capabilities

## 📋 Table of Contents

- [System Overview](#system-overview)
- [Architectural Decisions](#architectural-decisions)
- [Tech Stack](#tech-stack)
- [Database Architecture](#database-architecture)
- [Domain Model & Business Logic](#domain-model--business-logic)
- [User Roles & Access Control](#user-roles--access-control)
- [Installation & Setup](#installation--setup)
- [Project Structure](#project-structure)
- [Critical Implementation Details](#critical-implementation-details)

---

## 🎯 System Overview

The MBKM (Merdeka Belajar Kampus Merdeka) Internship Logbook System is architected as a **domain-driven, event-sourced application** that manages the complete lifecycle of student internships—from period registration through daily activity logging to supervisor validation.

### Core Objectives
1. **Prevent Temporal Conflicts:** Enforce business rule preventing overlapping internship periods
2. **Enable Real-time Communication:** Leverage WebSockets for instant validation notifications
3. **Maintain Data Integrity:** Strict database constraints and service-layer validation
4. **Support Role-based Workflows:** Clear separation of concerns between students, lecturers, and administrators

### Key Architectural Patterns
- **Service Layer Pattern:** Business logic isolated in dedicated service classes
- **Repository Pattern (via Eloquent):** Data access abstraction
- **Event-Driven Architecture:** Decoupled notification system using Laravel Broadcasting
- **CQRS Principles:** Separation of read/write operations in critical paths

---

## 🏗 Architectural Decisions

### Why Livewire 3 (Class-based)?
**Decision:** Use standard Livewire 3 class-based components instead of Volt's functional API.

**Rationale:**
- **Explicit State Management:** Class properties provide clear visibility of component state
- **Better IDE Support:** Full autocomplete and type hinting in modern IDEs
- **Easier Testing:** PHPUnit can mock and test class methods directly
- **Team Familiarity:** Most Laravel teams are comfortable with OOP patterns
- **Lifecycle Hooks:** Explicit `mount()`, `updated()`, `hydrate()` methods improve debugging

### Why MaryUI over DaisyUI?
**Decision:** Implement MaryUI as the primary component library.

**Rationale:**
- **Livewire-Native:** Components designed specifically for Livewire, not generic HTML/Alpine
- **Reduced Boilerplate:** `<x-input>`, `<x-select>`, `<x-table>` eliminate repetitive Blade code
- **Built-in Validation:** Components automatically wire to Livewire validation system
- **Consistent API:** Unified prop naming across all components
- **Active Development:** Purpose-built for modern TALL stack applications

**Documentation:** https://mary-ui.com/

### Why No Laravel Sail?
**Decision:** Standard local development environment (XAMPP/Laragon/native stack).

**Rationale:**
- **Team Infrastructure:** Existing team workflows use local PHP/MySQL installations
- **Simpler Onboarding:** No Docker knowledge required for new developers
- **Faster Iteration:** No container rebuild cycles during development
- **Resource Efficiency:** Lower memory footprint on developer machines

---

## 🛠 Tech Stack

### Backend Layer
| Component | Technology | Version | Purpose |
|-----------|------------|---------|---------|
| **Framework** | Laravel | 12.x | Core application framework |
| **Language** | PHP | 8.2+ | Server-side logic |
| **Database** | MySQL | 8.0 | Relational data storage |
| **ORM** | Eloquent | - | Database abstraction |
| **Queue** | Database Driver | - | Async job processing |

### Frontend Layer
| Component | Technology | Version | Purpose |
|-----------|------------|---------|---------|
| **UI Framework** | Livewire | 3.x | Reactive components |
| **Component Library** | MaryUI | Latest | Pre-built UI components |
| **CSS Framework** | Tailwind CSS | 3.x | Utility-first styling |
| **JavaScript** | Alpine.js | 3.x | Client-side interactivity |
| **Build Tool** | Vite | Latest | Asset bundling & HMR |

### Real-time & Broadcasting
| Component | Technology | Purpose |
|-----------|------------|---------|
| **WebSocket Server** | Laravel Reverb | First-party broadcasting server |
| **Client Library** | Laravel Echo | WebSocket client |
| **Event Bus** | Laravel Events | Event dispatching |

### Additional Libraries
| Library | Purpose |
|---------|---------|
| `barryvdh/laravel-dompdf` | PDF report generation |
| `phpoffice/phpword` | DOCX export functionality |

---

## 🗄 Database Architecture

### Schema Philosophy
The database design follows **Third Normal Form (3NF)** with strategic denormalization for performance. All foreign keys use `ON DELETE CASCADE` to maintain referential integrity.

### Entity Relationship Diagram

```
┌──────────────────┐
│      users       │
│                  │
│ PK: id           │
│    name          │
│    email (UQ)    │
│    password      │
│    avatar_path   │
│    role (ENUM)   │◄──────┐
│    is_active     │       │
│    timestamps    │       │
└──────────────────┘       │
         △                 │
         │                 │
    ┌────┴────┐            │
    │         │            │
┌───▼──────┐ ┌▼─────────────┐
│ student_ │ │  lecturer_   │
│ profiles │ │  profiles    │
│          │ │              │
│ user_id  │ │  user_id     │
│ nim (UQ) │ │  nidn (UQ)   │
│ comp...  │ │  position    │
│ phone    │ │  timestamps  │
└──────────┘ └──────────────┘


┌────────────────────────────┐
│   internship_periods       │
│                            │
│ PK: id                     │
│ FK: student_id   ──────────┼───► users.id
│ FK: lecturer_id  ──────────┼───► users.id
│     company_name           │
│     start_date (DATE)      │
│     end_date (DATE)        │
│     status (ENUM)          │
│     timestamps             │
│                            │
│ INDEX: (student_id,status) │
└────────────────────────────┘
              │
              │ 1:N
              ▼
┌────────────────────────────┐
│       logbooks             │
│                            │
│ PK: id                     │
│ FK: internship_period_id   │
│     date (DATE)            │
│     activity (TEXT)        │
│     proof_file_path        │
│     status (ENUM)          │
│     feedback (TEXT)        │
│     timestamps             │
│                            │
│ UNIQUE: (period_id, date)  │
└────────────────────────────┘
```

### Table Specifications

#### 1. **users** (Authentication & Authorization Hub)

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->string('avatar_path')->nullable();
    $table->enum('role', ['super_admin', 'mahasiswa', 'dosen']);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

**Design Notes:**
- `role` enum ensures type safety at database level
- `is_active` enables soft-deletion without data loss
- No separate `roles` table—YAGNI principle for fixed role set

#### 2. **student_profiles** (Extended Student Information)

```php
Schema::create('student_profiles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('nim', 20)->unique(); // Student ID Number
    $table->string('competency'); // Study program
    $table->string('phone', 20)->nullable();
    $table->timestamps();
});
```

**Design Notes:**
- One-to-one relationship with `users`
- `nim` uniqueness enforced at DB level
- Cascade delete ensures orphan prevention

#### 3. **lecturer_profiles** (Extended Lecturer Information)

```php
Schema::create('lecturer_profiles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('nidn', 20)->unique(); // National Lecturer ID
    $table->string('position')->nullable(); // Academic rank
    $table->timestamps();
});
```

#### 4. **internship_periods** (Temporal Boundary Definition)

```php
Schema::create('internship_periods', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
    $table->string('company_name');
    $table->date('start_date');
    $table->date('end_date');
    $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
    $table->timestamps();
    
    // Performance optimization for common query pattern
    $table->index(['student_id', 'status']);
});
```

**Design Notes:**
- **Critical Business Rule:** Enforced via `InternshipService::createPeriod()`
- Composite index on `(student_id, status)` optimizes overlap detection queries
- `status` transitions: `active` → `completed` (auto on end_date) or `cancelled` (manual)

#### 5. **logbooks** (Daily Activity Journal)

```php
Schema::create('logbooks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('internship_period_id')->constrained()->cascadeOnDelete();
    $table->date('date');
    $table->text('activity');
    $table->string('proof_file_path')->nullable();
    $table->enum('status', ['pending', 'validated', 'rejected'])->default('pending');
    $table->text('feedback')->nullable();
    $table->timestamps();
    
    // Enforce: One logbook entry per day per internship period
    $table->unique(['internship_period_id', 'date']);
});
```

**Design Notes:**
- **Unique constraint** prevents duplicate daily entries (business rule enforcement)
- `proof_file_path` stores relative path from `storage/app/public/proofs/`
- `feedback` populated only when `status` changes to `validated` or `rejected`

---

## 🧠 Domain Model & Business Logic

### Service Layer Architecture

The application uses a **Service Layer** to encapsulate complex business logic, keeping controllers and Livewire components thin.

```
┌─────────────────────────────────────────┐
│         Livewire Component              │
│  (Presentation & User Interaction)      │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│         Service Layer                   │
│  ┌────────────────────────────────┐    │
│  │  InternshipService             │    │
│  │  • createPeriod()              │    │
│  │  • checkOverlap()              │    │
│  │  • completePeriod()            │    │
│  └────────────────────────────────┘    │
│                                         │
│  ┌────────────────────────────────┐    │
│  │  LogbookService                │    │
│  │  • createEntry()               │    │
│  │  • validateLog()               │    │
│  │  • rejectLog()                 │    │
│  └────────────────────────────────┘    │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│      Eloquent Models (Data Layer)       │
└─────────────────────────────────────────┘
```

### Critical Business Rules

#### Rule 1: Internship Overlap Prevention

**Service:** `App\Services\InternshipService`

**Method Signature:**
```php
public function createPeriod(User $student, array $data): InternshipPeriod
```

**Algorithm (Pseudo-code):**
```
FUNCTION createPeriod(student, data):
    // Step 1: Validate input dates
    IF data.end_date <= data.start_date:
        THROW ValidationException("End date must be after start date")
    
    // Step 2: Query active/completed periods (exclude cancelled)
    existing_periods = InternshipPeriod.where(
        student_id = student.id,
        status IN ['active', 'completed']
    ).get()
    
    // Step 3: Check for temporal collision
    FOR EACH period IN existing_periods:
        IF (data.start_date <= period.end_date) AND 
           (data.end_date >= period.start_date):
            THROW ValidationException([
                'date' => 'Anda memiliki magang aktif di tanggal ini.'
            ])
    
    // Step 4: Create new period
    RETURN InternshipPeriod.create({
        student_id: student.id,
        lecturer_id: data.lecturer_id,
        company_name: data.company_name,
        start_date: data.start_date,
        end_date: data.end_date,
        status: 'active'
    })
```

**Collision Detection Formula:**
```
Two periods [A_start, A_end] and [B_start, B_end] overlap if:
    (A_start <= B_end) AND (A_end >= B_start)
```

**Why This Matters:**
- Prevents students from "double-booking" internship periods
- Maintains realistic scheduling constraints
- Ensures data integrity for reporting and analytics

---

#### Rule 2: Logbook Validation with Real-time Notification

**Service:** `App\Services\LogbookService`

**Method Signature:**
```php
public function validateLog(Logbook $logbook, string $status, ?string $feedback = null): void
```

**Implementation Flow:**
```
FUNCTION validateLog(logbook, status, feedback):
    // Step 1: Update logbook status
    logbook.update({
        status: status,              // 'validated' or 'rejected'
        feedback: feedback,
        validated_at: now()
    })
    
    // Step 2: Dispatch broadcast event
    EVENT LogbookValidated {
        logbook: logbook,
        student_id: logbook.period.student_id,
        status: status,
        feedback: feedback
    }
    
    // Step 3: (Optional) Send email notification
    IF status == 'validated':
        DISPATCH Job: SendLogbookValidatedEmail(logbook)
```

**Broadcasting Configuration:**

```php
// app/Events/LogbookValidated.php
class LogbookValidated implements ShouldBroadcast
{
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.' . $this->studentId)
        ];
    }
    
    public function broadcastAs(): string
    {
        return 'logbook.validated';
    }
}
```

**Frontend Listener (Livewire Component):**
```javascript
Echo.private(`App.Models.User.${userId}`)
    .listen('.logbook.validated', (event) => {
        @this.call('refreshLogbooks');
        // Show toast notification
    });
```

---

## 👥 User Roles & Access Control

### Role Hierarchy

```
┌─────────────────────┐
│    Super Admin      │  ← Full system access
└──────────┬──────────┘
           │
    ┌──────┴──────┐
    │             │
┌───▼───┐   ┌────▼────┐
│ Dosen │   │Mahasiswa│
└───────┘   └─────────┘
```

### 1. Super Admin (`super_admin`)

**Responsibilities:**
- System-wide configuration management
- User lifecycle management (CRUD operations)
- Access to audit logs and analytics
- Emergency data recovery operations

**Capabilities Matrix:**

| Resource | Create | Read | Update | Delete |
|----------|--------|------|--------|--------|
| Users | ✅ | ✅ | ✅ | ✅ |
| Student Profiles | ✅ | ✅ | ✅ | ✅ |
| Lecturer Profiles | ✅ | ✅ | ✅ | ✅ |
| Internship Periods | ✅ | ✅ | ✅ | ✅ |
| Logbooks | ✅ | ✅ | ✅ | ✅ |

**Policy Example:**
```php
// app/Policies/UserPolicy.php
public function viewAny(User $user): bool
{
    return $user->role === 'super_admin';
}
```

---

### 2. Lecturer (`dosen`)

**Responsibilities:**
- Student supervision and mentorship
- Logbook validation and feedback provision
- Progress monitoring and reporting

**Capabilities Matrix:**

| Resource | Create | Read | Update | Delete |
|----------|--------|------|--------|--------|
| Own Profile | ❌ | ✅ | ✅ | ❌ |
| Supervised Students | ❌ | ✅ (own) | ❌ | ❌ |
| Student Logbooks | ❌ | ✅ (supervised) | ✅ (validation) | ❌ |

**Policy Example:**
```php
// app/Policies/LogbookPolicy.php
public function validate(User $user, Logbook $logbook): bool
{
    return $user->role === 'dosen' 
        && $logbook->period->lecturer_id === $user->id;
}
```

**Dashboard Features:**
- **Student Cards Grid:** Visual overview of all supervised students
- **Pending Validations Badge:** Count of unvalidated logbooks
- **Quick Validation Modal:** Inline validation without page reload
- **Progress Charts:** Visual representation of student activity

---

### 3. Student (`mahasiswa`)

**Responsibilities:**
- Internship period registration
- Daily logbook entry submission
- Documentation upload and management

**Capabilities Matrix:**

| Resource | Create | Read | Update | Delete |
|----------|--------|------|--------|--------|
| Own Profile | ❌ | ✅ | ✅ | ❌ |
| Own Internship Periods | ✅ | ✅ (own) | ✅ (own) | ❌ |
| Own Logbooks | ✅ | ✅ (own) | ✅ (pending) | ✅ (pending) |

**Policy Example:**
```php
// app/Policies/InternshipPeriodPolicy.php
public function create(User $user): bool
{
    return $user->role === 'mahasiswa';
}

public function update(User $user, InternshipPeriod $period): bool
{
    return $user->id === $period->student_id 
        && $period->status === 'active';
}
```

**Dashboard States:**

**State 1: No Active Internship**
```
┌───────────────────────────────────┐
│  Setup Your Internship Period    │
│                                   │
│  [Company Name Input]             │
│  [Start Date] [End Date]          │
│  [Select Supervisor (Dosen)]      │
│                                   │
│  [Create Internship Period]       │
└───────────────────────────────────┘
```

**State 2: Active Internship**
```
┌───────────────────────────────────┐
│  GoTo Financial                   │
│  Progress: ████████░░ 80% (40/50) │
│                                   │
│  [+ Add Today's Logbook]          │
│                                   │
│  Recent Entries:                  │
│  ├─ 2024-01-20 ✅ Validated       │
│  ├─ 2024-01-19 ⏳ Pending         │
│  └─ 2024-01-18 ✅ Validated       │
│                                   │
│  [📄 Export PDF] [📝 Export DOCX] │
└───────────────────────────────────┘
```

---

## 🚀 Installation & Setup

### Prerequisites

**Required Software:**
- PHP >= 8.2
- Composer >= 2.5
- MySQL >= 8.0
- Node.js >= 18.x
- NPM >= 9.x

**Recommended Development Environment:**
- Laragon (Windows)
- Herd (macOS)
- Native LAMP/LEMP stack (Linux)

---

### Step-by-Step Installation

#### 1. Clone Repository

```bash
git clone https://github.com/your-org/mbkm-logbook.git
cd mbkm-logbook
```

#### 2. Install PHP Dependencies

```bash
composer install
```

#### 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```env
APP_NAME="MBKM Logbook"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mbkm_logbook
DB_USERNAME=root
DB_PASSWORD=

# Broadcasting & Queue
BROADCAST_CONNECTION=reverb
QUEUE_CONNECTION=database

# Laravel Reverb Configuration
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=database
```

#### 4. Database Initialization

**Create Database:**
```sql
CREATE DATABASE mbkm_logbook 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;
```

**Run Migrations:**
```bash
php artisan migrate
```

**Seed Sample Data:**
```bash
php artisan db:seed
```

**Seeded Users:**

| Role | Email | Password | Name | Details |
|------|-------|----------|------|---------|
| Super Admin | admin@mbkm.test | password | Super Admin | Full access |
| Lecturer | pakbudi@mbkm.test | password | Pak Budi | NIDN: 0012345678 |
| Student | andi@mbkm.test | password | Andi Prasetyo | NIM: 21010001, Active internship at GoTo Financial |

**Seeded Data Includes:**
- Andi has 1 active internship period (GoTo Financial, 50 days)
- 5 logbook entries: 3 validated, 2 pending
- Proof files attached to validated entries

#### 5. Storage Configuration

```bash
# Create symbolic link for public storage
php artisan storage:link

# Ensure correct permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### 6. Install Frontend Dependencies

```bash
npm install
```

#### 7. Install MaryUI

```bash
composer require robsontenorio/mary
php artisan mary:install
```

This will:
- Install MaryUI components
- Publish configuration files
- Add necessary Alpine.js plugins

#### 8. Build Assets

**Development (with HMR):**
```bash
npm run dev
```

**Production:**
```bash
npm run build
```

#### 9. Start Services

**Terminal 1 - Application Server:**
```bash
php artisan serve
```

**Terminal 2 - Laravel Reverb (WebSocket):**
```bash
php artisan reverb:start
```

**Terminal 3 - Queue Worker:**
```bash
php artisan queue:work
```

#### 10. Access Application

Open browser: [http://localhost:8000](http://localhost:8000)

**Login Credentials:**
- **Student:** andi@mbkm.test / password
- **Lecturer:** pakbudi@mbkm.test / password
- **Admin:** admin@mbkm.test / password

---

## 📁 Project Structure

```
mbkm-logbook/
│
├── app/
│   ├── Events/
│   │   └── LogbookValidated.php          # Broadcast event
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Auth/                     # Authentication controllers
│   │   │
│   │   └── Livewire/                     # Livewire components
│   │       ├── Student/
│   │       │   ├── Dashboard.php         # Main student view
│   │       │   ├── LogbookForm.php       # Entry creation modal
│   │       │   └── ExportReport.php      # PDF/DOCX generation
│   │       │
│   │       └── Lecturer/
│   │           ├── Supervision.php       # Student overview
│   │           └── ValidationModal.php   # Quick validation UI
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── StudentProfile.php
│   │   ├── LecturerProfile.php
│   │   ├── InternshipPeriod.php
│   │   └── Logbook.php
│   │
│   ├── Policies/
│   │   ├── UserPolicy.php
│   │   ├── InternshipPeriodPolicy.php
│   │   └── LogbookPolicy.php
│   │
│   └── Services/                         # Business logic layer
│       ├── InternshipService.php         # Overlap detection
│       └── LogbookService.php            # Validation workflow
│
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_create_users_table.php
│   │   ├── 2024_01_02_create_student_profiles_table.php
│   │   ├── 2024_01_03_create_lecturer_profiles_table.php
│   │   ├── 2024_01_04_create_internship_periods_table.php
│   │   └── 2024_01_05_create_logbooks_table.php
│   │
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── DemoDataSeeder.php
│
├── resources/
│   ├── views/
│   │   ├── components/
│   │   │   └── layouts/
│   │   │       └── app.blade.php         # Main layout with sidebar
│   │   │
│   │   ├── livewire/
│   │   │   ├── student/
│   │   │   │   ├── dashboard.blade.php
│   │   │   │   └── logbook-form.blade.php
│   │   │   │
│   │   │   └── lecturer/
│   │   │       ├── supervision.blade.php
│   │   │       └── validation-modal.blade.php
│   │   │
│   │   └── pdf/
│   │       └── logbook-report.blade.php  # PDF template
│   │
│   ├── css/
│   │   └── app.css
│   │
│   └── js/
│       ├── app.js
│       └── echo.js                       # WebSocket configuration
│
├── routes/
│   ├── web.php                           # HTTP routes
│   └── channels.php                      # Broadcasting channels
│
├── public/
│   └── storage/                          # Symlink to storage/app/public
│
├── storage/
│   └── app/
│       └── public/
│           └── proofs/                   # Uploaded proof files
│
├── .env.example
├── composer.json
├── package.json
└── vite.config.js
```

---

## ⚙️ Critical Implementation Details

### 1. File Upload Handling

**Livewire Component Validation:**
```php
use Livewire\WithFileUploads;

class LogbookForm extends Component
{
    use WithFileUploads;
    
    #[Validate('required|file|max:10240|mimes:jpg,jpeg,png,pdf')]
    public $proof_file;
    
    public function save()
    {
        $this->validate();
        
        $path = $this->proof_file->store('proofs', 'public');
        
        // Create logbook entry...
    }
}
```

**Storage Path Structure:**
```
storage/app/public/proofs/
├── 2024/
│   └── 01/
│       ├── student_123_20240115_abc123.pdf
│       └── student_123_20240116_def456.jpg
```

**Security Considerations:**
- Max file size: 10MB (configurable in `php.ini`)
- Allowed MIME types: jpg, jpeg, png, pdf only
- Random filename generation prevents overwrites
- Files stored outside public root (requires symlink)

---

### 2. PDF Generation Architecture

**Template Structure:**
```blade
{{-- resources/views/pdf/logbook-report.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 2cm; }
        body { font-family: 'DejaVu Sans', sans-serif; }
        .header { text-align: center; border-bottom: 2px solid #000; }
        .signature { margin-top: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN LOGBOOK MAGANG</h2>
        <p>{{ $student->name }} - {{ $period->company_name }}</p>
    </div>
    
    <table>
        @foreach($logbooks as $log)
        <tr>
            <td>{{ $log->date }}</td>
            <td>{{ $log->activity }}</td>
            <td>{{ $log->status }}</td>
        </tr>
        @endforeach
    </table>
    
    <div class="signature">
        <p>Supervisor: {{ $lecturer->name }}</p>
    </div>
</body>
</html>
```

**Generation Method:**
```php
use Barryvdh\DomPDF\Facade\Pdf;

public function downloadPdf()
{
    $data = [
        'student' => auth()->user(),
        'period' => $this->activePeriod,
        'logbooks' => $this->logbooks,
        'lecturer' => $this->activePeriod->lecturer
    ];
    
    return Pdf::loadView('pdf.logbook-report', $data)
        ->download('logbook-' . now()->format('Y-m-d') . '.pdf');
}
```

---

### 3. Real-time Broadcasting Setup

**Channel Authorization:**
```php
// routes/channels.php
use App\Models\User;

Broadcast::channel('App.Models.User.{id}', function (User $user, int $id) {
    return (int) $user->id === (int) $id;
});
```

**Echo Configuration:**
```javascript
// resources/js/echo.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
```

**Livewire Component Listener:**
```php
// app/Http/Livewire/Student/Dashboard.php
class Dashboard extends Component
{
    protected $listeners = ['logbook-validated' => 'refreshLogbooks'];
    
    public function mount()
    {
        $this->dispatch('echo-listen', [
            'channel' => 'App.Models.User.' . auth()->id(),
            'event' => '.logbook.validated'
        ]);
    }
    
    public function refreshLogbooks()
    {
        $this->logbooks = $this->loadLogbooks();
        $this->dispatch('notify', ['message' => 'Logbook validated!']);
    }
}
```

---

### 4. MaryUI Component Usage Examples

**Input Components:**
```blade
{{-- Text Input with Validation --}}
<x-input 
    label="Company Name" 
    wire:model="company_name" 
    placeholder="e.g., GoTo Financial"
    hint="Enter the official company name"
    error-field="company_name"
/>

{{-- Date Picker --}}
<x-datetime 
    label="Start Date" 
    wire:model="start_date" 
    icon="o-calendar"
/>

{{-- Select with Search --}}
<x-select 
    label="Supervisor (Dosen)" 
    wire:model="lecturer_id"
    :options="$lecturers"
    option-value="id"
    option-label="name"
    searchable
/>
```

**Table Component:**
```blade
<x-table :headers="$headers" :rows="$logbooks" striped>
    @scope('cell_date', $log)
        {{ $log->date->format('d M Y') }}
    @endscope
    
    @scope('cell_status', $log)
        <x-badge 
            :value="$log->status" 
            :class="$log->status === 'validated' ? 'badge-success' : 'badge-warning'"
        />
    @endscope
    
    @scope('actions', $log)
        <x-button icon="o-eye" wire:click="viewDetail({{ $log->id }})" />
    @endscope
</x-table>
```

**Modal Component:**
```blade
<x-modal wire:model="showModal" title="Add Logbook Entry">
    <x-form wire:submit="save">
        <x-textarea 
            label="Activity Description" 
            wire:model="activity"
            rows="5"
        />
        
        <x-file 
            label="Proof Attachment" 
            wire:model="proof_file"
            accept="image/*,application/pdf"
        />
        
        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.showModal = false" />
            <x-button label="Save" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</x-modal>
```

---

### 5. Testing Strategy

**Unit Tests (Service Layer):**
```php
// tests/Unit/InternshipServiceTest.php
public function test_prevents_overlapping_periods()
{
    $student = User::factory()->mahasiswa()->create();
    
    // Create existing period: Jan 1 - Jan 31
    InternshipPeriod::factory()->create([
        'student_id' => $student->id,
        'start_date' => '2024-01-01',
        'end_date' => '2024-01-31',
        'status' => 'active'
    ]);
    
    // Attempt to create overlapping period: Jan 15 - Feb 15
    $this->expectException(ValidationException::class);
    
    app(InternshipService::class)->createPeriod($student, [
        'company_name' => 'Test Corp',
        'start_date' => '2024-01-15',
        'end_date' => '2024-02-15',
        'lecturer_id' => 1
    ]);
}
```

**Feature Tests (Livewire):**
```php
// tests/Feature/Livewire/LogbookFormTest.php
public function test_student_can_create_logbook_entry()
{
    $student = User::factory()->mahasiswa()->create();
    $period = InternshipPeriod::factory()->create(['student_id' => $student->id]);
    
    Livewire::actingAs($student)
        ->test(LogbookForm::class, ['periodId' => $period->id])
        ->set('date', now()->toDateString())
        ->set('activity', 'Testing API endpoints')
        ->call('save')
        ->assertDispatched('logbook-created');
        
    $this->assertDatabaseHas('logbooks', [
        'internship_period_id' => $period->id,
        'activity' => 'Testing API endpoints'
    ]);
}
```

---

### 6. Performance Optimization

**Eager Loading:**
```php
// app/Http/Livewire/Lecturer/Supervision.php
public function render()
{
    $students = User::where('role', 'mahasiswa')
        ->whereHas('supervisedPeriods', function($q) {
            $q->where('lecturer_id', auth()->id())
              ->where('status', 'active');
        })
        ->with([
            'supervisedPeriods' => fn($q) => $q->where('status', 'active'),
            'supervisedPeriods.logbooks' => fn($q) => $q->latest()->limit(5)
        ])
        ->get();
        
    return view('livewire.lecturer.supervision', compact('students'));
}
```

**Database Indexes:**
```php
// Already defined in migrations
$table->index(['student_id', 'status']); // internship_periods
$table->unique(['internship_period_id', 'date']); // logbooks
```

**Query Optimization:**
```php
// Bad: N+1 Query Problem
foreach ($students as $student) {
    echo $student->activePeriod->company_name; // Fires separate query per student
}

// Good: Eager Loading
$students = User::with('activePeriod')->get();
foreach ($students as $student) {
    echo $student->activePeriod->company_name; // Uses already loaded relationship
}
```

---

## 🔐 Security Checklist

- [x] **Authentication:** Laravel Breeze/Fortify implementation
- [x] **Authorization:** Policy-based access control on all resources
- [x] **CSRF Protection:** Automatic on all POST/PUT/DELETE requests
- [x] **XSS Prevention:** Blade `{{ }}` escaping enabled
- [x] **SQL Injection:** Eloquent ORM with parameter binding
- [x] **File Upload Validation:** MIME type and size restrictions
- [x] **Mass Assignment Protection:** `$fillable` defined on all models
- [x] **Environment Variables:** Sensitive data in `.env` (gitignored)
- [x] **Rate Limiting:** Throttle middleware on authentication routes

---

## 📊 Additional Commands

### Development
```bash
# Clear all caches
php artisan optimize:clear

# Run migrations with fresh database
php artisan migrate:fresh --seed

# Generate IDE helper (for autocomplete)
composer require --dev barryvdh/laravel-ide-helper
php artisan ide-helper:generate
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter=InternshipServiceTest

# Run with coverage report
php artisan test --coverage
```

### Production
```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets
npm run build
```

---

## 🎓 Learning Resources

**Laravel 12 Documentation:** https://laravel.com/docs/12.x  
**Livewire 3 Documentation:** https://livewire.laravel.com/docs  
**MaryUI Documentation:** https://mary-ui.com  
**Laravel Reverb Documentation:** https://reverb.laravel.com  
**Tailwind CSS Documentation:** https://tailwindcss.com/docs

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 🤝 Contributing

We follow the **GitFlow** workflow:

1. Create feature branch: `git checkout -b feature/your-feature`
2. Commit changes: `git commit -m 'Add your feature'`
3. Push to branch: `git push origin feature/your-feature`
4. Create Pull Request

**Code Standards:**
- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation accordingly

---

## 📧 Support & Contact

**Issues:** Submit via GitHub Issues  
**Questions:** Create a Discussion thread  
**Security:** Email security@example.com

---

**Built with precision by Senior Laravel Architects**  
*Leveraging TALL Stack best practices for production-grade applications*