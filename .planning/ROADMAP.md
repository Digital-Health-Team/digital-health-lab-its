# Roadmap

## Phase 1: Hybrid Architecture & Foundation
**Goal:** Establish the base application with React/Inertia for the public frontend and Livewire for the admin backend.
**Canonical refs:** @.agents/app/product_requirements.md, @.artifacts/technical_spec_review.md
- [ ] Initialize React 19 + Inertia.js v3 setup on `GET /`
- [ ] Configure Tailwind CSS + Hero UI for public frontend
- [ ] Set up layout structure and basic navigation

## Phase 2: Discovery & 3D Viewer (Guest Flow)
**Goal:** Implement the masonry grid catalog and interactive 3D viewer for public users.
**Canonical refs:** @.agents/app/product_requirements.md
- [ ] Build Masonry Product Grid for search and discovery
- [ ] Integrate React Three Fiber for 3D file rendering
- [ ] Implement web optimization and loading strategies for 3D models
- [ ] Implement Polymorphic file download functionality (.stl, .obj)

## Phase 3: Creator Upload Flow & S3 Integration
**Goal:** Enable students to upload their innovations with large 3D files directly to S3.
**Canonical refs:** @.agents/app/product_requirements.md
- [ ] Build multi-step publication upload form (metadata, thumbnail)
- [ ] Integrate AWS S3 / MinIO pre-signed URLs for direct large file uploads
- [ ] Implement real-time upload progress bar
- [ ] Create Admin moderation queue (Approve/Reject)

## Phase 4: Custom Service & Ordering (Client Flow)
**Goal:** Build the custom 3D printing request pipeline and WhatsApp negotiation link.
**Canonical refs:** @.agents/app/product_requirements.md
- [ ] Create Custom Service order form (description + file upload)
- [ ] Implement WhatsApp redirection with pre-filled order context
- [ ] Build Admin interface to input "Grams", "Minutes", and calculate ranged pricing
- [ ] Generate and display automated invoices to the client

## Phase 5: Payment Verification & Logistics Management
**Goal:** Handle manual payment verifications and inventory deductions.
**Canonical refs:** @.agents/app/product_requirements.md
- [ ] Create client interface for manual payment receipt upload
- [ ] Build Admin verification workflow for payments
- [ ] Implement visual progress tracking (Slicing -> Printing -> Finishing)
- [ ] Integrate real-time inventory deduction logic (filament/silicone stock)

## Phase 6: Super Admin & CMS
**Goal:** Implement full system control, reporting, and landing page CMS.
**Canonical refs:** @.agents/app/product_requirements.md
- [ ] Build Super Admin role management
- [ ] Create CMS for dynamic Landing Page elements (Hero banners, categories)
- [ ] Implement financial report and inventory logistics auditing tools
