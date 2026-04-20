# System Architecture Brief of Project

**Target Audience:** Frontend & Backend Developers, Software Architect
**Architecture Type:** Hybrid Frontend (React + Inertia Monolith & Livewire Blade) integrated with an Action-Based Backend.

## 1. Communication Concepts & Main Paradigms

This project uses a dual architecture within a single Laravel codebase to achieve the highest development time optimization (RAD) and strict Separation of Concerns.

- **Public & User Frontend (Inertia.js + React):** Laravel controllers return interaction-rich React components (including complex states for carts and the 3D Viewer).
- **Admin Frontend (Livewire + Blade):** The control panel (CRUD, work validation, stock inventory) uses Livewire so developers don't need to build external APIs and client state management for complex administrative forms.

## 2. Backend Architecture (Action-Based Architecture)

To maintain clean code as feature complexity grows (especially e-commerce and repository), the Laravel Backend side will not burden Controllers or Livewire components with business logic. The project adopts an Action-Oriented Architecture centered on the Single Responsibility Principle.

Main directory structure on the Laravel application side (`app/`):

- `app/Actions/` (Core Business Logic): PHP classes that have only one defined purpose (usually having a single method like `execute()` or `handle()`). Example: `CreateServiceBookingAction`, `ApprovePublicationAction`, `ProcessMidtransPaymentAction`. Controllers and Livewire components are only responsible for calling these actions.
- `app/DTOs/` (Data Transfer Objects): Used to encapsulate data sent from Requests before passing it into Actions. This ensures any data entering the logic layer is strongly typed, for example, `StoreServiceBookingDTO`.
- `app/Services/`: Used to house helper classes interacting with third-party services or common infrastructure logic. Example: `PaymentGatewayService`, `S3StorageService`.
- `app/Livewire/`: Contains all visual Backend classes for the Admin Dashboard, which only function to gather data from models and call Action classes when an interface is triggered (e.g., an approve button is pressed).

## 3. Public Frontend Architecture (Feature-Based Architecture)

The React-based frontend (in `resources/js/`) completely separates base/global code from business-specific domain code, preventing overlapping component dependencies (spaghetti code). The directory structure is strictly separated into two main pillars:

### 3.1. Core/ (Foundation & Agnostic)

Contains elements with no ties to any specific business logic. This part is pure UI infrastructure.

- `Components/`: Custom primitive wrappers (e.g., `Box.tsx`, `Text.tsx`, `Heading.tsx`) for standardization.
- `ui/`: Design system components (HeroUI/shadcn) that are completely dumb components (e.g., `Button.tsx`, `Card.tsx`, `Input.tsx`).
- `Hooks/`: Global hooks like screen size detection (`use-mobile.ts`).
- `Types/`: Global TypeScript definitions (`api.types.ts`, `global.d.ts`).
- `Utils/`: Pure helper functions like currency formatters and Tailwind class merging (`utils.ts`).

### 3.2. Features/ (Isolated Business Domains)

All application functions (Catalog, Auth, Ordering) are treated as standalone "mini-apps" within the `Features/` folder.
**Golden Rule:** Files within `Core/` must not call anything from `Features/`, and each feature folder must not import each other directly unless specifically defined.

Each business domain folder (e.g., `Features/Ordering/` or `Features/Repository/`) has an internal structure:

- `components/`: Presentational components relevant only to the feature (e.g., `PosMockupGrid.tsx`, `3DViewerCanvas.tsx`).
- `hooks/`: Feature-specific logic hooks (e.g., `useCheckout.ts`, `useCalculateSlicerWeight.ts`).
- `pages/`: Inertia.js entry points (Smart Components) that assemble all elements into a complete page (e.g., `LandingPage.tsx`, `CatalogPage.tsx`).
- `schemas/`: Shape validation using zod for the feature (e.g., `booking.schema.ts`).
- `types/`: Specific TypeScript data types for the feature.
- `index.ts`: Barrel pattern to export only APIs (components/hooks) allowed to be used by other features if necessary.

## 4. Integrated Frontend Best Practices

- **State Management (Zustand & Inertia Props):** Main data (Server State) is supplied directly from the Backend using Inertia props. Do not create additional JSON endpoints (REST API) unless strictly necessary. Use Zustand exclusively for persistent client-side state (like cart item management or 3D camera rotation configuration).
- **Form Handling:** All public forms and multi-step user uploads use `react-hook-form` paired with `zod` schemas located in the `Features/.../schemas/` directory for client-side validation before firing Inertia's `router.post()` method to the Backend.
- **3D Rendering:** The 3D canvas component using React Three Fiber must use lazy loading (`React.lazy()` + `<Suspense>`) so the massive 3D library (Three.js) does not block the main interface load rate when visitors first open the Landing Page or Catalog.
