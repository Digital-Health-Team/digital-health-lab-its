# ITS Medical Technology Digital Repository - Technical Architecture Guide

> **Author Perspective:** Senior Principal Laravel Architect  
> **Purpose:** Production-ready digital repository and made-by-order e-commerce platform

## 📋 Table of Contents

- [System Overview](https://www.google.com/search?q=%23system-overview)
- [Architectural Decisions](https://www.google.com/search?q=%23architectural-decisions)
- [Tech Stack](https://www.google.com/search?q=%23tech-stack)
- [Database Architecture](https://www.google.com/search?q=%23database-architecture)
- [Domain Model & Business Logic](https://www.google.com/search?q=%23domain-model--business-logic)
- [User Roles & Access Control](https://www.google.com/search?q=%23user-roles--access-control)
- [Installation & Setup](https://www.google.com/search?q=%23installation--setup)
- [Project Structure](https://www.google.com/search?q=%23project-structure)
- [Critical Implementation Details](https://www.google.com/search?q=%23critical-implementation-details)

---

## 🎯 System Overview

The ITS Medical Technology Digital Repository & Innovation Hub is a dual-purpose platform designed as a **Modern Monolith**. It serves as an open-source digital archive for student innovations and a specialized e-commerce hub for made-by-order products and 3D printing/design services.

### Core Objectives

1.  **Centralized Archiving:** Secure storage and public showcasing of student projects, including large 3D models (`.stl`, `.obj`).
2.  **High-Performance Public Access:** Deliver an SPA-like experience for the public to browse catalogs and interact with 3D previews without latency.
3.  **Monetization & Service Workflow:** Streamline custom orders with dynamic pricing (e.g., slicer weight calculation) and raw material inventory tracking.
4.  **Rapid Administrative Control:** Provide lab admins with a robust, traditional server-rendered dashboard for complex data moderation and operational management.

### Key Architectural Patterns

- **Hybrid Frontend Strategy:** React + Inertia.js for high-interactivity public pages, and Livewire for rapid development of complex administrative CRUD operations.
- **Action-Oriented Backend:** Business logic isolated into highly cohesive, single-responsibility `Action` classes (e.g., `ApprovePublicationAction`, `ProcessServiceCheckoutAction`) rather than fat controllers.
- **Feature-Based React Architecture:** Strict domain isolation in the frontend (`Features/Ordering`, `Features/Repository`) to prevent spaghetti code as the application scales.

---

## 🏗️ Architectural Decisions

### 1\. Why Hybrid Frontend (React/Inertia + Livewire)?

- **The Problem:** 3D rendering (React Three Fiber) and highly interactive catalogs require a robust client-side framework (React). However, building complex admin tables and forms in React from scratch significantly slows down development.
- **The Solution:** We use **Inertia.js** as the bridge for public routes, passing data from Laravel directly to React components. For the Admin panel, we use **Livewire**, leveraging Blade's rapid prototyping capabilities for data-heavy views without needing a separate API layer.

### 2\. Why Action-Oriented Architecture?

- **The Problem:** In a system handling both e-commerce transactions and digital repository validations, controllers quickly become bloated and difficult to unit-test.
- **The Solution:** Every significant business operation is extracted into `app/Actions`. Controllers act solely as HTTP dispatchers, passing validated Data Transfer Objects (DTOs) to Actions.

### 3\. Polymorphic File Handling

- **The Problem:** Products, Open Source Projects, and Service Bookings all require file attachments (images, documents, large 3D files). Creating separate attachment tables for each entity violates DRY principles.
- **The Solution:** A centralized `attachments` table utilizing Laravel's polymorphic relationships (`attachable_type`, `attachable_id`). This allows a single `S3StorageService` to handle all file uploads uniformly.

---

## 🛠️ Tech Stack

### Core Technologies

- **Backend:** Laravel 11/12 (PHP 8.4+)
- **Database:** PostgreSQL (Optimal for JSON fields and complex relational integrity)
- **Public Frontend:** React 19 + TypeScript + Inertia.js v3
- **Admin Frontend:** Livewire v3 + Alpine.js
- **Styling:** Tailwind CSS v4 + HeroUI (for React)
- **3D Rendering:** React Three Fiber (`@react-three/fiber`)

### Key Packages

- `laravel/wayfinder`: Strict type safety from PHP routes/actions to TypeScript.
- `react-hook-form` + `zod`: Client-side form validation.
- `zustand`: Lightweight global client state management (UI only).

---

## 🗄️ Database Architecture

The schema is heavily normalized to support the platform's dual nature.

### Core Tables Structure

1.  **Users & Authentication:**
    - `users`: Core authentication (Email, Password, Role).
    - `user_profiles`: Detailed metadata (NIM, NIK, Faculty) linked 1-to-1.

2.  **Repository & Showcase:**
    - `open_source_projects`: Individual student works requiring validation.
    - `events`, `teams`, `projects`: Architecture for group-based event submissions (e.g., Innovatech).

3.  **E-Commerce & Services:**
    - `products`: Made-by-order catalog items (No inventory count).
    - `services`: Base pricing definitions (e.g., 3D Print base cost).
    - `service_bookings`: Core operational table capturing client briefs, `slicer_weight_grams`, and `agreed_price`.
    - `service_progress_updates`: One-to-Many relation to track operational milestones (Slicing -\> Printing -\> Finishing).

4.  **Inventory & Financials:**
    - `raw_materials` & `raw_material_movements`: Tracks filament/resin usage tied to specific `service_bookings`.
    - `transactions`: Central ledger handling payments for both products and services.

5.  **Global System:**
    - `attachments`: Polymorphic table for handling all media and 3D files.

---

## 🧠 Domain Model & Business Logic

### Action Mapping (Examples)

All core logic resides in `app/Actions`:

- **`CreateServiceBookingAction`**: Validates the DTO, initializes a pending transaction, creates the booking record, and triggers an initial progress state.
- **`UpdateServiceProgressAction`**: Updates the progress percentage. If a raw material ID is provided, it triggers `DeductRawMaterialAction`.
- **`ApprovePublicationAction`**: Changes the project status to 'Approved', logs the validator ID, and dispatches an event to notify the student.

---

## 👥 User Roles & Access Control

Role management is strictly enforced via Middleware.

| Role                      | Interface | Key Permissions                                                                      |
| :------------------------ | :-------- | :----------------------------------------------------------------------------------- |
| **Guest**                 | React     | Browse catalog, view 3D models, download public open-source attachments.             |
| **User (Student/Client)** | React     | Submit works, book services, negotiate pricing, view transaction history.            |
| **Admin Lab**             | Livewire  | Validate submissions, update service progress, deduct raw materials, manage catalog. |
| **Super Admin**           | Livewire  | Full system configuration, role management, financial reporting.                     |

---

## 📁 Project Structure

### Backend (Laravel - Action Oriented)

```text
app/
├── Actions/          # Pure business logic (e.g., CheckoutServiceAction.php)
├── DTOs/             # Strongly typed data transfer objects (e.g., StoreBookingData.php)
├── Services/         # External integration logic (e.g., S3StorageService.php)
├── Livewire/         # Admin panel components (e.g., Admin/InventoryManager.php)
└── Http/Controllers/ # Thin controllers for Inertia routing
```

### Frontend (React/Inertia - Feature Based)

```text
resources/js/
├── Core/             # Foundational, logic-agnostic setups
│   ├── Components/   # common (Box, Text) & ui (HeroUI wrappers)
│   ├── Hooks/        # Global utilities (use-mobile.ts)
│   ├── Store/        # Zustand global state
│   └── Types/        # Global TS interfaces
│
├── Features/         # Isolated Business Domains
│   ├── Repository/   # 3D Viewer canvas, Masonry Grid, filtering logic
│   ├── Ordering/     # Checkout logic, negotiation UI, price calculations
│   └── Uploads/      # Multi-step dropzone, S3 pre-signed URL handlers
│
├── Pages/            # Inertia Entry Points
└── app.tsx           # Inertia v3 initialization
```

---

## 🚀 Installation & Setup

### Requirements

- PHP 8.4+
- Node.js 20+
- PostgreSQL 15+
- Redis (Optional, for queues/caching)

### Setup Steps

```bash
# 1. Install PHP dependencies
composer install

# 2. Install Node dependencies
npm install

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# 4. Database Setup
# Configure DB_ connection in .env
php artisan migrate --seed

# 5. Build Assets (Vite handles both React & Livewire CSS)
npm run build

# 6. Storage Link
php artisan storage:link
```

---

## ⚠️ Critical Implementation Details

### 1\. 3D Model Rendering Performance

The `React Three Fiber` canvas **must** be lazy-loaded using `React.lazy` and `<Suspense>`. Loading massive Three.js libraries on initial page load will destroy the platform's Lighthouse performance score.

### 2\. File Uploads (S3 Integration)

Do not pass large `.stl` files through the Laravel server's RAM.

- **Frontend:** Request a Pre-signed URL from Laravel.
- **Frontend:** Upload the file directly to AWS S3 using the Pre-signed URL.
- **Backend:** Save the resulting S3 path into the `attachments` table.

### 3\. Component Primitives

Never use native HTML tags (`<div>`, `<p>`, `<h1>`) in React features. Always use the mapped generic components (`<Box>`, `<Text>`, `<Heading>`) located in `Core/Components/common/` to ensure design system consistency.

---

**Built with precision by Autonomous AI Agents** _Leveraging Hybrid Architecture and Action-Oriented patterns for maximum maintainability._
