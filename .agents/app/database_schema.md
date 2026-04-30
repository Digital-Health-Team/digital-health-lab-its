***

# Database Schema: Interactive 3D Hub & Custom Manufacture

## 1. USERS & PROFILES

### `roles`
Master table for managing user access rights within the system.

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `int` | **PK**, Auto Increment | |
| `name` | `varchar` | | super_admin, admin_lab, mahasiswa, user_publik |

### `users`
Main authentication table.

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `int` | **PK**, Auto Increment | |
| `role_id` | `int` | **FK** | References `roles.id` |
| `email` | `varchar` | Unique | |
| `password` | `varchar` | | |
| `created_at` | `timestamp` | | |

### `user_profiles`
Detailed user data using 1-to-1 normalization.

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `user_id` | `int` | **PK**, **FK** | References `users.id` |
| `full_name` | `varchar` | | |
| `phone` | `varchar` | | |
| `address` | `text` | | |
| `nik` | `varchar` | Nullable | National ID Number |
| `nim` | `varchar` | Nullable | Student ID Number |
| `department` | `varchar` | | |
| `faculty` | `varchar` | | |
| `university` | `varchar` | | |
| `updated_at` | `timestamp` | | |

---

## 2. CENTRALIZED MEDIA MANAGEMENT

### `attachments`
Centralized file management (*Polymorphic Table*) to handle all attachments (3D files, images, documents).

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `int` | **PK**, Auto Increment | |
| `attachable_type` | `varchar` | | Related entity (e.g., Product, Project) |
| `attachable_id` | `int` | | ID of the related entity |
| `file_url` | `varchar` | | |
| `file_type` | `varchar` | | MIME Type |
| `is_primary` | `boolean` | Default: `false` | Determines the main thumbnail/image |
| `sort_order` | `int` | Default: `0` | Gallery display order |
| `uploaded_by` | `int` | **FK** | References `users.id` |
| `created_at` | `timestamp` | | |

---

## 3. EVENT ECOSYSTEM & PROJECT PUBLICATION

### `events`
Master data for exhibitions/events (e.g., Innovatech).

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `int` | **PK**, Auto Increment | |
| `name` | `varchar` | | |
| `year` | `int` | | |
| `theme_title` | `varchar` | | |
| `is_active` | `boolean` | Default: `true` | |

### `teams`
Student groups within a specific event.

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `int` | **PK**, Auto Increment | |
| `event_id` | `int` | **FK** | References `events.id` |
| `name` | `varchar` | | |
| `course_name` | `varchar` | | |
| `created_at` | `timestamp` | | |

### `team_members`
Pivot table (*Many-to-Many*) linking students to their respective groups.

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `team_id` | `int` | **PK**, **FK** | References `teams.id` |
| `user_id` | `int` | **PK**, **FK** | References `users.id` |
| `role_in_team` | `varchar` | | |
> **Index:** Composite Primary Key on `(team_id, user_id)`

### `projects`
Projects/innovations produced by a group within a specific event.

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `int` | **PK**, Auto Increment | |
| `team_id` | `int` | **FK** | References `teams.id` |
| `title` | `varchar` | | |
| `category` | `varchar` | | |
| `status` | `varchar` | | pending, approved, rejected |
| `validated_by` | `int` | **FK**, Nullable | References `users.id` (Admin) |
| `created_at` | `timestamp` | | |

### `open_source_projects`
Individual public projects outside of official events.

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `int` | **PK**, Auto Increment | |
| `user_id` | `int` | **FK** | References `users.id` |
| `title` | `varchar` | | |
| `category` | `varchar` | | |
| `status` | `varchar` | | pending, approved, rejected |
| `validated_by` | `int` | **FK**, Nullable | References `users.id` (Admin) |
| `created_at` | `timestamp` | | |

---

## 4. PORTFOLIO CATALOG & RAW MATERIALS

### `products`
Portfolio catalog acting as a reference for *Made-by-Order* requests.

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `int` | **PK**, Auto Increment | |
| `creator_id` | `int` | **FK** | References `users.id` |
| `name` | `varchar` | | |
| `description` | `text` | | |
| `price_min` | `int` | | Minimum estimated price |
| `price_max` | `int` | | Maximum estimated price |
| `is_active` | `boolean` | Default: `true` | |

### `raw_materials`
Master inventory data for lab raw materials.

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `int` | **PK**, Auto Increment | |
| `name` | `varchar` | | Example: White PLA Filament |
| `category` | `varchar` | | filament, silicon, resin, etc. |
| `unit` | `varchar` | | gram, ml, pcs |
| `current_stock` | `int` | | Current total stock |
| `created_at` | `timestamp` | | |

### `raw_material_movements`
Log of stock mutations (incoming/outgoing).

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `int` | **PK**, Auto Increment | |
| `raw_material_id` | `int` | **FK** | References `raw_materials.id` |
| `type` | `varchar` | | `in` (restock) / `out` (usage) |
| `quantity` | `int` | | Amount of material moved |
| `service_booking_id` | `int` | **FK**, Nullable | Related service booking (if used) |
| `notes` | `text` | | Reason for restock or production |
| `created_by` | `int` | **FK** | References `users.id` |
| `created_at` | `timestamp` | | |

### `services`
Master data for lab services (e.g., 3D Printing Service).

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `int` | **PK**, Auto Increment | |
| `name` | `varchar` | | |
| `description` | `text` | | |
| `base_price` | `int` | | Base price (e.g., IDR 2000/gram) |

---

## 5. TRANSACTIONS & CUSTOM ORDERS

### `transactions`
Transaction header and client payment management.

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `int` | **PK**, Auto Increment | |
| `user_id` | `int` | **FK** | References `users.id` |
| `total_amount` | `int` | | |
| `payment_status` | `varchar` | | |
| `payment_proof` | `varchar` | Nullable | URL/Path of the transfer receipt |
| `expired_at` | `timestamp` | Nullable | Payment expiration deadline |
| `created_at` | `timestamp` | | |

### `service_bookings`
Specific details for print service orders or custom orders.

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `int` | **PK**, Auto Increment | |
| `transaction_id` | `int` | **FK**, Nullable | References `transactions.id` |
| `user_id` | `int` | **FK** | References `users.id` (Client) |
| `service_id` | `int` | **FK**, Nullable | References `services.id` |
| `product_reference_id`| `int` | **FK**, Nullable | References `products.id` |
| `brief_description` | `text` | | |
| `slicer_weight_grams` | `int` | Nullable | Admin input from Slicer (grams) |
| `slicer_print_time_minutes`| `int`| Nullable | Estimated print time from Slicer |
| `agreed_price` | `int` | Nullable | Final negotiated/calculated price |
| `current_status` | `varchar` | | pending, negotiating, in_progress, completed, cancelled |
| `created_at` | `timestamp` | | |

### `service_progress_updates`
Timeline tracking history of the order fulfillment.

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `int` | **PK**, Auto Increment | |
| `service_booking_id` | `int` | **FK** | References `service_bookings.id` |
| `status_label` | `varchar` | | Example: 3D Modeling, Printing |
| `percentage` | `int` | | UI progress indicator (0-100) |
| `notes` | `text` | | |
| `updated_by` | `int` | **FK** | References `users.id` (Technician/Admin) |
| `created_at` | `timestamp` | | |

---

## 6. DYNAMIC CONTENT (CMS) & LAB STRUCTURE

### `page_sections`
Key-Value system for dynamic text/content on the Landing Page.

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `int` | **PK**, Auto Increment | |
| `page_name` | `varchar` | | |
| `section_key` | `varchar` | | |
| `content` | `text` | | Text, Markdown, or JSON payload |
| `updated_by` | `int` | **FK** | References `users.id` |
| `updated_at` | `timestamp` | | |
> **Index:** Unique Index on `(page_name, section_key)`

### `structural_members`
Lab management/personnel data for the publication page.

| Field | Type | Attributes | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `int` | **PK**, Auto Increment | |
| `user_id` | `int` | **FK**, Nullable | References `users.id` |
| `name` | `varchar` | | |
| `position` | `varchar` | | |
| `display_order` | `int` | | Manages display hierarchy (top to bottom) |
| `is_active` | `boolean` | Default: `true` | |
| `created_at` | `timestamp` | | |
