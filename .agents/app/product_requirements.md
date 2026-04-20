# Product Requirements Document (PRD): ITS Medical Technology Digital Repository & Innovation Hub

**Platform:** Web Application (Responsive)
**Tech Stack:** React.js 19 + TypeScript (Public Frontend), Inertia.js v3 (Frontend-Backend Bridge), Livewire v3 + Alpine.js (Admin Frontend), Zustand (Client State), React-Hook-Form + Zod (Form Validation), Laravel 11 (Backend Core), MySQL (Database), Tailwind CSS + Hero UI (Styling), React Three Fiber (3D Rendering), AWS S3 / MinIO (Storage).

## 1. Product Overview

This application is an interactive web platform serving a dual purpose as a digital repository for archiving student innovations (open-source archives, applications, 3D files) and as a custom e-commerce service platform (made-by-order products and 3D printing/design services). It is designed to bridge the innovative works of ITS Medical Technology students with the general public and industry.

### Project Objectives:

- **Data Centralization:** Become a structured digital archive center for all student projects and products (including events like Innovatech).
- **Public Accessibility:** Make it easy for the general public, researchers, or other institutions to explore the innovation catalog, view interactive 3D previews, and download attachment files.
- **Monetization & Measurable Services:** Facilitate custom 3D printing service orders with transparent calculations (based on gram weight from the slicer application) and custom product price negotiation.
- **Quality Control & Logistics Management:** Ensure publications are validated through Admin moderation, and monitor raw material inventory movements (filament, silicone) in real-time.

## 2. User Personas & Roles

### A. Guest User (Public / Unauthenticated)

- **Description:** General public, researchers, or prospective clients.
- **Permissions:** View Landing Page, browse publication/product catalogs, render interactive 3D previews, and download open-source work attachments.
- **Main Tasks:** Innovation search and lab portfolio exploration.

### B. Registered User (Student Creator / Client)

- **Description:** ITS students creating works or public clients who want to transact.
- **Permissions:** Access to User Dashboard.
- **Main Tasks:**
    - **Creator:** Upload multi-step publication works (details, thumbnails, giant 3D files) and track validation status.
    - **Client:** Make custom orders, negotiate prices via chat/WhatsApp, monitor order progress tracking, and make payments.

### C. Lab Admin (Moderator & Technician)

- **Description:** Lab staff managing daily operations.
- **Permissions:** Access to Backend dashboard (Livewire-based).
- **Main Tasks:** Validate student works (Approve/Reject), calculate estimated slicer weight for billing, update service progress, and manage incoming-outgoing raw materials.

### D. Super Admin (Full System Control)

- **Description:** Lab head or main administrator.
- **Permissions:** Full access to all features and system settings.
- **Main Tasks:** Manage user roles, web CMS configuration (Hero banners, structural members), and extract financial reports as well as inventory logistics audits.

## 3. System Workflows

### Flow 1: Discovery & Download (Guest Flow)

1. Users are greeted by a Hero Banner with a large Search Bar.
2. Users type keywords or select categories (e.g., "3D Prosthetics").
3. The system displays search results in a Masonry Grid format.
4. Users click on a work, and the system renders an interactive 3D Viewer on the browser.
5. Users click "Download Attachments" to download files (.stl, .obj).

### Flow 2: Custom Service & Product Ordering (Client Flow)

1. Users choose custom products from the catalog or enter the 3D Printing Service page.
2. Users click the "Order Custom" button, fill out a short description form, and upload reference files.
3. Users are directed to a negotiation room (Chat/WhatsApp) with the Lab Admin.
4. The Admin checks the file in the Slicer application, inputting "Grams" and "Estimated Minutes" into the admin dashboard.
5. The system automatically calculates the price (e.g., Grams x Rp 2,000) and issues an invoice.
6. Users pay for the order.
7. The Admin deducts filament stock in the Inventory module and updates the Tracking Bar (Slicing -> Printing 50% -> Finishing).

### Flow 3: Work Upload (Creator Flow)

1. Students log in and click "Upload New Publication".
2. **Step 1:** Fill in metadata (Title, Category, Description).
3. **Step 2:** Upload Thumbnail (Image).
4. **Step 3:** Upload Raw Files (3D/Zip) via dropzone with a real-time progress bar (uploaded directly to AWS S3 via Pre-signed URL).
5. The work enters a Pending status. The Admin performs validation (Approve/Reject).

## 4. Detailed Feature Specs

- **Masonry Product Grid:** Responsive catalog layout adjusting to image dimensions, loading visual labels, creators, and estimated price ranges.
- **Interactive 3D Viewer:** Utilizes React Three Fiber on the client side to render 3D objects asynchronously (with full screen and orbit controls) without burdening the main UI render.
- **Polymorphic File Handling:** Single file management serving attachments for Publications, Custom Products, and Service order files.
- **Dynamic Progress Tracking:** Visual service progress indicators from the client side, with label and percentage updates supplied by the admin.
