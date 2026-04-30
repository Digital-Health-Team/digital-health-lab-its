# Requirements

## 1. Discovery & Download (Guest Flow)
- **REQ-1:** Masonry Grid layout for product/innovation catalog display.
- **REQ-2:** Interactive 3D Viewer using React Three Fiber.
- **REQ-3:** Implementation of both web-optimized 3D loading and full-quality file downloads.
- **REQ-4:** Download capability for open-source attachments (.stl, .obj).

## 2. Custom Service & Product Ordering (Client Flow)
- **REQ-5:** Custom 3D Printing Service order form with file upload and short description.
- **REQ-6:** Order redirection to WhatsApp for admin negotiation.
- **REQ-7:** Admin dashboard interface to input calculated "Grams" and "Estimated Minutes".
- **REQ-8:** Automated invoice generation based on admin-inputted ranged pricing.
- **REQ-9:** Manual payment upload form and admin verification workflow.
- **REQ-10:** Visual progress tracking indicator for clients (Slicing -> Printing -> Finishing).
- **REQ-11:** Automatic inventory deduction (e.g., filament stock) upon payment validation.

## 3. Work Upload (Creator Flow)
- **REQ-12:** Multi-step upload form for metadata and thumbnail images.
- **REQ-13:** Large file upload directly to AWS S3 / MinIO via pre-signed URLs with progress bar.
- **REQ-14:** Moderation queue for uploaded works (Pending -> Approved/Rejected).

## 4. Administration
- **REQ-15:** Super Admin capability to manage roles and CMS (Hero banners).
- **REQ-16:** Financial report and logistics audit generation.

## 5. Technical & Security constraints
- **REQ-17:** Public frontend must use React 19 + Inertia v3.
- **REQ-18:** Admin frontend must use Livewire v3 + Alpine.js.
- **REQ-19:** Polymorphic file handling system for attachments and orders.
