# TASK: Implement User Dashboard Page — IDIG Health Tech

## 🎯 Objective

Implement the authenticated **User Dashboard** page for the IDIG Health Tech project, strictly following the visual mockup and specifications defined in `dashboard_design_breakdown.md`.

## 📚 Required References (READ BEFORE WRITING ANY CODE)

1. `./agents/app/dashboard_design_breakdown.md` — Dashboard visual specs & component anatomy
2. `./agents/app/landing_page_design_breakdown.md` — Foundation design tokens
3. `./agents/app/design_system.md` — Color palette, typography, visual effects
4. `./agents/app/database_schema.md` — Table structures (`publications`, `products`, `events`, etc.)
5. `./agents/app/system_architecture.md` — Action-based pattern & DTO conventions
6. `./agents/app/product_requirements.md` — Business context & user roles
7. `./agents/examples/Dashboard Final.png` — Final visual mockup

## ⚙️ Tech Stack & Strict Rules

- **Frontend:** React 19 + TypeScript + Inertia.js + TailwindCSS
- **Backend:** Laravel 11 with Action-Based Architecture
- **Architecture:** Feature-Based — all dashboard components live under `resources/js/Features/dashboard/`
- **State:** Server state via Inertia props only; Zustand ONLY for `sidebarCollapsed` & `language` persistence
- **Forms:** `react-hook-form` + `zod` for any client-side validation
- **FORBIDDEN:** Cross-imports between feature folders except through barrel `index.ts`
- **FORBIDDEN:** Business logic inside Controllers — use Action classes + DTOs

## 🎨 Components to Build

Based on the mockup and breakdown document, the dashboard consists of:

1. **Layout Shell** — Fixed Sidebar (dark navy `#031026`, `w-60`) + Sticky Topbar (`h-18`) + Scrollable Content area (`bg-slate-50`)
2. **Hero Banner Card** — Diagonal gradient (navy), glassmorphic neon-glow CTA button, floating 3D image right-aligned
3. **Category Quick-Access Grid** — 10 horizontal tiles (3D Designs, Prosthetics, Aid Bands, Educational, Papers, Journals, Projects, Services, Training, Events)
4. **New Products + No Ongoing Events** — 2-column grid (Products/Services tab toggle on left panel; empty state illustration on right)
5. **Explore Our Projects** — 3-column `aspect-square` featured publications with hover scale + overlay effect
6. **Trending Articles + PubMed Updates** — 2-column article list cards
7. **Featured Publications** — Vertical list with banner illustration + "See more!" CTA
8. **Join Our Training** — Training course list section

## 🚦 MANDATORY WORKFLOW — PLANNING FIRST

### ⛔ DO NOT START CODING. Follow this sequence exactly:

**STEP 1 — Read & Clarify**
After reading all reference files, ask clarifying questions for any ambiguities, such as:

- Are the Inertia routes already defined in `routes/web.php`?
- Does `Core/ui/` already have shared primitives (Button, Card, Avatar, DropdownMenu)?
- Should the Hero Banner use hardcoded mock data first, or wire directly to CMS props?
- Does a `FetchPubMedFeedAction` already exist, or does it need to be created?
- What is the expected mobile sidebar behavior — drawer overlay or icon-collapse?

**STEP 2 — Present an Implementation Roadmap**
Structure the plan as follows:

```
## Implementation Roadmap

### Phase 1: Backend Foundation
- [ ] Route definition in routes/web.php (Inertia render)
- [ ] DashboardController (thin) → calls GetDashboardDataAction
- [ ] DashboardDataDTO
- [ ] GetDashboardDataAction (aggregates all section data)
- [ ] (complete sub-task list...)

### Phase 2: Frontend Architecture Setup
- [ ] Folder structure under Features/dashboard/
- [ ] TypeScript interfaces in types.ts (DashboardHomeProps, etc.)
- [ ] Zustand store for sidebar & language state
- [ ] Barrel exports (index.ts) for all feature folders
- [ ] CSS theme delta in resources/css/dashboard.css (@theme tokens)

### Phase 3: Layout Shell
- [ ] DashboardLayout.tsx (Sidebar + Topbar + scrollable content outlet)
- [ ] Sidebar.tsx + SidebarNavItem.tsx (active state, collapse behavior)
- [ ] Topbar.tsx (logo, social links, search, language toggle, auth avatar)

### Phase 4: Content Sections (one component at a time)
- [ ] HeroBannerCard.tsx
- [ ] CategoryQuickAccess.tsx
- [ ] NewProductsCard.tsx + EmptyStateCard.tsx
- [ ] FeaturedPublicationsSection.tsx (Explore Our Projects)
- [ ] TrendingArticlesCard.tsx + PubMedUpdatesCard.tsx
- [ ] FeaturedPublicationsList.tsx
- [ ] TrainingCoursesSection.tsx

### Phase 5: Page Assembly & Polish
- [ ] DashboardHome.tsx (compose all sections with Inertia props)
- [ ] Skeleton loading states for all dynamic sections
- [ ] Responsive breakpoints (drawer < md, icon-only md–lg, full ≥ lg)
- [ ] Image lazy loading (loading="lazy" + fetchpriority="high" on hero)
- [ ] Verify WCAG AA contrast on dark sidebar
```

**STEP 3 — WAIT FOR APPROVAL**
🛑 After presenting the roadmap, **STOP and wait for my confirmation** before writing any implementation code. I may request revisions to the phase order or task granularity.

**STEP 4 — Execute Phase by Phase**
Once approved, work through **one Phase at a time**. After each Phase:

- List all files created or modified
- Wait for my sign-off before proceeding to the next Phase

## ✅ Definition of Done (per component)

- Strictly follows design tokens (colors, spacing, border-radius, shadows) from breakdown
- Typography: `Plus Jakarta Sans` for headings, `Inter` for body text
- Visual effects: neon glow on primary CTA, glassmorphism on Hero card, `opacity`/`transform`-only transitions
- TypeScript strict mode — absolutely no `any`
- Single Responsibility Principle — one component, one concern
- All props interfaces explicitly typed in `types.ts`
- Image lazy loading applied throughout
- Responsive across all four breakpoints (mobile / tablet / desktop / desktop-lg)

## 🚫 Hard Rules

- **DO NOT ASSUME** — if PRD or breakdown is ambiguous, ask first
- **DO NOT** write monolithic or spaghetti components
- **DO NOT** skip the planning step, even for seemingly simple tasks
- **DO NOT** move to the next Phase before the current one is confirmed by me

---

**Begin with STEP 1: read all reference files, then surface your clarifying questions.**
