# Database Schema Brief of Project

**Target Audience:** Backend Developer / Fullstack
**Database System:** MySQL

## 1. Detailed Table Specifications

### A. Authentication & Profile (Users)

- `roles`: id (PK), name
- `users`: id (PK), role_id (FK), email (Unique), password, created_at
- `user_profiles`: user_id (PK & FK), full_name, phone, address, nik, nim, department, faculty, university, updated_at

### B. Centralized Media Management

- `attachments`: id (PK), attachable_type (Polymorphic), attachable_id, file_url, file_type, is_primary, sort_order, uploaded_by (FK), created_at

### C. Event & Project Ecosystem (Student Works)

- `events`: id (PK), name, year, theme_title, is_active
- `teams`: id (PK), event_id (FK), name, course_name
- `team_members`: team_id (PK/FK), user_id (PK/FK), role_in_team
- `projects` (Team Works): id (PK), team_id (FK), title, category, status, validated_by (FK)
- `open_source_projects` (Individual Works): id (PK), user_id (FK), title, category, status, validated_by (FK)

### D. Catalog, Services & Inventory

- `products`: id (PK), creator_id (FK), name, description, price_min, price_max, is_active (No stock because it is made-by-order)
- `services`: id (PK), name, description, base_price
- `raw_materials`: id (PK), name, category, unit, current_stock
- `raw_material_movements`: id (PK), raw_material_id (FK), type (in/out), quantity, service_booking_id (FK), notes, created_by (FK)

### E. Transactions & Order Tracking

- `transactions`: id (PK), user_id (FK), total_amount, payment_status
- `service_bookings`: id (PK), transaction_id (FK), user_id (FK), service_id (FK), product_reference_id (FK), brief_description, slicer_weight_grams, slicer_print_time_minutes, agreed_price, current_status
- `service_progress_updates`: id (PK), service_booking_id (FK), status_label, percentage, notes, updated_by (FK)

### F. CMS & Content Management

- `page_sections`: id (PK), page_name, section_key, content
- `structural_members`: id (PK), user_id (FK), name, position, display_order, is_active
