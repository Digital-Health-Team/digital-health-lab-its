# **DESIGN SYSTEM: IDIG Health Tech — User Dashboard (Authenticated)**

### _Repository & Publication of Medical Engineering Technology ITS_

> **Scope:** This document is a **supplement** to `landing_page_design_breakdown.md`. It extends the foundation tokens with dashboard-specific patterns. Where this document is silent, defer to the landing page breakdown.
> **Target audience:** UI/UX Designer, Frontend Engineers building under Feature-Based Architecture (React 19 + Inertia.js + TypeScript).

---

## **PART 1 — GLOBAL DESIGN TOKENS (Dashboard Extension)**

### **1.1 Extended Color Palette**

Design direction is **hybrid**: sidebar uses deep navy / futuristic palette (continuity with hero section of landing page), while the content area uses light & clean surfaces to keep medical / academic clarity and prioritize publication thumbnails and 3D imagery.

#### **Sidebar Dark Palette (Extension)**

| Token Name            | Hex Code  | Tailwind Custom Class   | Usage                                          |
| --------------------- | --------- | ----------------------- | ---------------------------------------------- |
| `sidebar-bg`          | `#031026` | `bg-primary-950`        | Sidebar root background (full height)          |
| `sidebar-bg-elev`     | `#062E5C` | `bg-primary-900`        | Active item background gradient stop           |
| `sidebar-divider`     | `#0A3D7A` | `border-primary-800/60` | Sidebar internal dividers (between logo & nav) |
| `sidebar-text`        | `#CBD5E1` | `text-slate-300`        | Default nav label color                        |
| `sidebar-text-muted`  | `#94A3B8` | `text-slate-400`        | Inactive icon color                            |
| `sidebar-text-active` | `#FFFFFF` | `text-white`            | Active nav label                               |
| `sidebar-accent`      | `#00A8B5` | `bg-secondary-500`      | Active indicator bar (left edge)               |
| `sidebar-accent-glow` | `#22D3EE` | `shadow-secondary-400`  | Glow halo on active state                      |

#### **Content Area Palette (Light & Clean)**

| Token Name           | Hex Code  | Tailwind Custom Class | Usage                                              |
| -------------------- | --------- | --------------------- | -------------------------------------------------- |
| `content-bg`         | `#F8FAFC` | `bg-slate-50`         | Dashboard content area background                  |
| `content-surface`    | `#FFFFFF` | `bg-white`            | Card surfaces (article, product, publication card) |
| `content-muted`      | `#F1F5F9` | `bg-slate-100`        | Category tile inactive, search bar bg              |
| `content-border`     | `#E2E8F0` | `border-slate-200`    | Card outline, section dividers                     |
| `content-text`       | `#1E293B` | `text-slate-800`      | Heading / body on light surface                    |
| `content-text-sub`   | `#475569` | `text-slate-600`      | Article meta, secondary description                |
| `content-text-muted` | `#94A3B8` | `text-slate-400`      | Timestamps, footnotes                              |

#### **Status & Interaction Palette**

| Token Name        | Hex Code  | Tailwind Custom Class | Usage                            |
| ----------------- | --------- | --------------------- | -------------------------------- |
| `status-verified` | `#10B981` | `bg-emerald-500`      | Verified badge, success state    |
| `status-pending`  | `#FFC72C` | `bg-accent-400`       | Pending review badge             |
| `status-rejected` | `#EF4444` | `bg-red-500`          | Rejected badge, error state      |
| `link-default`    | `#0D5A9E` | `text-primary-600`    | Article title link (default)     |
| `link-hover`      | `#00A8B5` | `text-secondary-500`  | Article title link (hover)       |
| `link-visited`    | `#7C3AED` | `text-violet-600`     | Visited article links (optional) |

#### **Tailwind v4 Theme Extension (Delta, Quick Reference)**

> Tokens are declared in CSS via `@theme { ... }`. Auto-generated utilities follow `--color-<name>` → `bg-<name>` / `text-<name>` / `border-<name>`. See **Appendix A** for the full file.

```css
/* resources/css/dashboard.css */
@theme {
    /* Sidebar palette */
    --color-sidebar: #031026;
    --color-sidebar-elev: #062e5c;
    --color-sidebar-divider: #0a3d7a;

    /* Status palette */
    --color-status-verified: #10b981;
    --color-status-pending: #ffc72c;
    --color-status-rejected: #ef4444;

    /* Dashboard shadows */
    --shadow-sidebar-active:
        0 0 24px rgba(34, 211, 238, 0.35), inset 2px 0 0 #00a8b5;
    --shadow-card-hover: 0 12px 32px -8px rgba(3, 16, 38, 0.18);
    --shadow-topbar: 0 1px 0 rgba(226, 232, 240, 1);
}
```

---

### **1.2 Typography Scale (Dashboard Context)**

Dashboard typography emphasizes **scannable density** over hero impact. Reduced heading sizes, tighter line-heights, and consistent label patterns.

| Token             | Size / LH   | Weight | Tailwind Class              | Usage Example                               |
| ----------------- | ----------- | ------ | --------------------------- | ------------------------------------------- |
| `dash-section-h`  | 24px / 32px | 700    | `text-2xl font-bold`        | "Explore Our Projects", "Trending Articles" |
| `dash-card-title` | 18px / 26px | 600    | `text-lg font-semibold`     | Card titles inside sections                 |
| `dash-hero-h`     | 32px / 40px | 700    | `text-3xl font-bold`        | "3D Printed Prosthetic" hero card title     |
| `dash-hero-body`  | 14px / 22px | 400    | `text-sm`                   | Hero description paragraph                  |
| `dash-nav-label`  | 14px / 20px | 500    | `text-sm font-medium`       | Sidebar nav labels                          |
| `dash-cat-label`  | 12px / 16px | 500    | `text-xs font-medium`       | Category tile labels                        |
| `dash-product-t`  | 14px / 20px | 600    | `text-sm font-semibold`     | Product card title (single-line truncated)  |
| `dash-product-p`  | 12px / 18px | 500    | `text-xs font-medium`       | "Starts from Rp 30,000" price label         |
| `dash-article-t`  | 15px / 22px | 600    | `text-[15px] font-semibold` | Article title (link style)                  |
| `dash-article-m`  | 12px / 18px | 400    | `text-xs`                   | Article meta (author, date, PMID)           |
| `dash-article-b`  | 13px / 20px | 400    | `text-[13px]`               | Article abstract (3-line clamp)             |
| `dash-badge`      | 11px / 16px | 600    | `text-[11px] font-semibold` | Status badges (uppercase tracking-wide)     |
| `dash-cta`        | 14px / 20px | 600    | `text-sm font-semibold`     | All button labels in dashboard              |

**Font Stack (unchanged from global):**

- Display: `Plus Jakarta Sans` → for section headings & hero card
- Body: `Inter` → for nav, body, meta, badges, buttons

---

### **1.3 Spacing & Sizing Tokens (Dashboard Grid)**

| Token                 | Value | Tailwind      | Usage                                    |
| --------------------- | ----- | ------------- | ---------------------------------------- |
| `dash-shell-x`        | 24px  | `px-6`        | Content area horizontal padding          |
| `dash-shell-y`        | 24px  | `py-6`        | Content area vertical padding            |
| `dash-section-gap`    | 32px  | `gap-8`       | Vertical gap between major sections      |
| `dash-card-gap`       | 16px  | `gap-4`       | Gap between cards in a row               |
| `dash-card-pad`       | 24px  | `p-6`         | Internal padding inside content cards    |
| `dash-card-pad-tight` | 16px  | `p-4`         | Tight card padding (article items)       |
| `sidebar-width`       | 240px | `w-60`        | Expanded sidebar width                   |
| `sidebar-width-mini`  | 72px  | `w-18`        | Collapsed sidebar width (icon-only mode) |
| `topbar-height`       | 72px  | `h-18`        | Top bar fixed height                     |
| `topbar-pad-x`        | 24px  | `px-6`        | Top bar horizontal padding               |
| `card-radius`         | 16px  | `rounded-2xl` | Standard content card radius             |
| `card-radius-lg`      | 24px  | `rounded-3xl` | Hero banner card radius                  |
| `category-tile-size`  | 64px  | `w-16 h-16`   | Category icon tile (square)              |

---

## **PART 2 — LAYOUT SHELL ARCHITECTURE**

### **2.1 Master Layout Grid**

```
┌──────────┬──────────────────────────────────────────────────────┐
│          │  TOP BAR (sticky h-18)                               │
│          ├──────────────────────────────────────────────────────┤
│ SIDEBAR  │                                                      │
│ (w-60)   │  CONTENT AREA                                        │
│ fixed    │  bg-slate-50, px-6 py-6, overflow-y-auto             │
│ h-screen │                                                      │
│          │  [Hero Banner Card]                                  │
│ primary- │  [Category Quick Access Grid]                        │
│ 950 bg   │  [New Products | No Ongoing Events] ← 2-col grid     │
│          │  [Explore Our Projects] ← 3-col showcase             │
│          │  [Trending Articles | PubMed Updates] ← 2-col grid   │
│          │  [Featured Publications]                             │
│          │                                                      │
└──────────┴──────────────────────────────────────────────────────┘
```

**Container Structure:**

```html
<div class="min-h-screen flex bg-slate-50">
    <aside class="fixed inset-y-0 left-0 w-60 bg-primary-950 z-40">
        <!-- Sidebar -->
    </aside>

    <div class="flex-1 ml-60 flex flex-col">
        <header
            class="sticky top-0 h-18 bg-white border-b border-slate-200 z-30"
        >
            <!-- Top Bar -->
        </header>

        <main class="flex-1 px-6 py-6 space-y-8">
            <!-- Sections -->
        </main>
    </div>
</div>
```

### **2.2 Responsive Breakpoints**

| Breakpoint   | Width Range   | Sidebar State                         | Content Layout                          |
| ------------ | ------------- | ------------------------------------- | --------------------------------------- |
| `desktop-lg` | ≥ 1280px      | Full sidebar (w-60)                   | Multi-column grids active               |
| `desktop`    | 1024 – 1279px | Full sidebar (w-60)                   | 2-column reduce to single where tight   |
| `tablet`     | 768 – 1023px  | **Collapsed** (icon-only, w-18)       | Stack 2-col sections into single column |
| `mobile`     | < 768px       | **Drawer** (hidden, opens via burger) | Single column, condensed top bar        |

**Sidebar Collapse Behavior:**

- Below `lg` breakpoint, sidebar auto-collapses to icon-only with `w-18`
- Below `md`, sidebar is hidden by default; a hamburger icon in top bar opens it as overlay drawer with `bg-primary-950/95 backdrop-blur-lg`
- Collapse state persisted in Zustand (`useUiStore`) so user preference survives navigation

---

## **PART 3 — UI COMPONENT ANATOMY (Per-Section)**

### **3.1 Sidebar Navigation**

```
┌─────────────────────────┐
│  ◯ iDIG Health Tech.    │ ← Logo + wordmark
├─────────────────────────┤
│ ◤ ⌂  Home               │ ← active (teal bar + filled bg)
│   📚 Publications        │ ← default
│   📁 Projects            │
│   🛠️ Services            │
│   ⚙ Management          │
│   🛒 Shop               │
│   👤 Profile             │
└─────────────────────────┘
```

**Container:**

```html
<aside class="fixed inset-y-0 left-0 w-60 bg-primary-950 flex flex-col">
    <!-- Brand block -->
    <div class="px-6 py-6 border-b border-primary-800/50">
        <Logo class="h-9 w-auto" />
        <!-- Combined symbol + wordmark, white -->
    </div>

    <!-- Nav block -->
    <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
        <NavItem icon="{Home}" label="Home" href="/dashboard" active />
        <NavItem icon="{BookOpen}" label="Publications" href="/publications" />
        <NavItem icon="{FolderOpen}" label="Projects" href="/projects" />
        <NavItem icon="{Wrench}" label="Services" href="/services" />
        <NavItem icon="{Settings2}" label="Management" href="/management" />
        <NavItem icon="{ShoppingBag}" label="Shop" href="/shop" />
        <NavItem icon="{User}" label="Profile" href="/profile" />
    </nav>
</aside>
```

**Nav Item — State Anatomy:**

| State    | Background                                         | Text/Icon        | Indicator                            |
| -------- | -------------------------------------------------- | ---------------- | ------------------------------------ |
| Default  | `bg-transparent`                                   | `text-slate-300` | none                                 |
| Hover    | `bg-primary-900/50`                                | `text-white`     | none                                 |
| Active   | `bg-gradient-to-r from-primary-900 to-primary-800` | `text-white`     | Left edge `2px` teal bar + soft glow |
| Disabled | `bg-transparent opacity-40`                        | `text-slate-500` | `cursor-not-allowed`                 |

**Active State Specification:**

```html
<a
    class="
  relative flex items-center gap-3 px-4 py-3 rounded-xl
  bg-linear-to-r from-primary-900 to-primary-800
  text-white font-medium text-sm
  before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2
  before:h-8 before:w-0.75 before:rounded-r-full
  before:bg-secondary-500
  before:shadow-[0_0_12px_rgba(34,211,238,0.6)]
"
>
    <Home class="h-5 w-5 text-secondary-400" />
    <span>Home</span>
</a>
```

**Collapsed Mode (≤ md):**

- Show icon only, center-aligned, tooltip on hover
- Active indicator becomes a centered bottom dot instead of left bar
- Brand area shows only the circular symbol (no wordmark)

---

### **3.2 Top Bar (Authenticated)**

```
┌──────────────────────────────────────────────────────────────────────┐
│  Followus [🔵][📷][🐦] [🛒]  [🔍 Search...........]  [🌐 EN ▾] [🔔³] [👤▾] │
└──────────────────────────────────────────────────────────────────────┘
   ↑ Left cluster              ↑ Center search          ↑ Right cluster
```

> ⚠️ **Design correction applied:** Since user is authenticated, the original `Sign up | Log in` buttons are replaced with `Notification + Avatar Dropdown` per project decisions.

**Container:**

```html
<header
    class="
  sticky top-0 z-30 h-18
  bg-white/95 backdrop-blur-md
  border-b border-slate-200
  flex items-center justify-between px-6 gap-4
"
></header>
```

#### **A. Left Cluster — Social + Cart**

```html
<div class="flex items-center gap-4">
    <div class="flex items-center gap-2">
        <span class="text-xs font-medium text-slate-500">Follow us</span>
        <SocialIconButton icon="{Facebook}" />
        <SocialIconButton icon="{Instagram}" />
        <SocialIconButton icon="{Twitter}" />
    </div>
    <div class="h-6 w-px bg-slate-200" />
    <!-- divider -->
    <CartIconButton count="{2}" />
</div>
```

- **Social Icon Button:** `w-8 h-8 rounded-full bg-slate-100 hover:bg-secondary-500 hover:text-white text-slate-600 grid place-items-center transition`
- **Cart Icon Button:** Same shape, with red badge `absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full grid place-items-center` when items > 0

#### **B. Center — Search Bar**

```html
<div class="flex-1 max-w-2xl mx-auto">
    <div class="relative">
        <search
            class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400"
        />
        <input
            type="text"
            placeholder="Search publications, products, services..."
            class="
        w-full h-10 pl-11 pr-4
        bg-slate-100 hover:bg-slate-50 focus:bg-white
        border border-transparent focus:border-secondary-500
        focus:ring-2 focus:ring-secondary-500/20
        rounded-full text-sm text-slate-800 placeholder:text-slate-400
        transition-all
      "
        />
    </div>
</div>
```

#### **C. Right Cluster — Language + Notification + Avatar**

```html
<div class="flex items-center gap-2">
    <!-- Language Switcher (i18n) -->
    <button
        class="flex items-center gap-1.5 px-3 py-2 rounded-lg hover:bg-slate-100 text-sm text-slate-700"
    >
        <Globe class="h-4 w-4" />
        <span class="font-medium">EN</span>
        <ChevronDown class="h-3 w-3" />
    </button>

    <!-- Notification -->
    <button
        class="relative w-10 h-10 rounded-full hover:bg-slate-100 grid place-items-center text-slate-700"
    >
        <Bell class="h-5 w-5" />
        <span
            class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"
        />
    </button>

    <!-- Avatar Dropdown -->
    <button
        class="flex items-center gap-2 pl-1 pr-3 py-1 rounded-full hover:bg-slate-100"
    >
        <img
            src="..."
            class="h-8 w-8 rounded-full object-cover ring-2 ring-secondary-500/20"
        />
        <span class="text-sm font-medium text-slate-800">Bertha A.</span>
        <ChevronDown class="h-3 w-3 text-slate-500" />
    </button>
</div>
```

**Avatar Dropdown Menu (on click):**

- Anchor: top-right of avatar
- Content: `My Profile`, `My Orders`, `My Uploads`, `Settings`, divider, `Sign out`
- Style: `bg-white shadow-card-hover rounded-xl border border-slate-200 p-2 w-56`
- Each item: `flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 text-sm text-slate-700`

**Language Dropdown:**

- Options: `English (EN)`, `Bahasa Indonesia (ID)`
- Style identical to avatar dropdown but narrower (`w-44`)

---

### **3.3 Hero Banner Card — "3D Printed Prosthetic"**

```
┌──────────────────────────────────────────────────────────────┐
│                                                              │
│   3D Printed                                  ╭─────────╮    │
│   Prosthetic                                  │  3D     │    │
│                                               │ Image   │    │
│   Discover sound effects and a unique         │ (right) │    │
│   auditory experience. Has 3 visualization    │         │    │
│   modes that can be freely customized.        ╰─────────╯    │
│                                                              │
│   [ Order Custom →  ]                                        │
│                                                              │
└──────────────────────────────────────────────────────────────┘
```

**Container:**

```html
<section
    class="
  relative overflow-hidden
  bg-linear-to-br from-primary-950 via-primary-900 to-primary-800
  rounded-3xl p-10 pr-0
  min-h-70
  flex items-center
"
>
    <!-- Decorative ECG line (subtle, from landing page system) -->
    <EcgLineSvg class="absolute inset-0 opacity-20 pointer-events-none" />

    <!-- Honeycomb pattern (subtle) -->
    <div class="absolute inset-0 honeycomb-bg opacity-10 pointer-events-none" />

    <!-- Content -->
    <div class="relative z-10 max-w-lg space-y-4">
        <h1 class="font-display text-3xl font-bold text-white leading-tight">
            3D Printed<br />Prosthetic
        </h1>
        <p class="text-sm text-slate-300 leading-relaxed">
            Custom prosthetic and assistive devices manufactured with
            medical-grade 3D printing technology, tailored per patient anatomy.
        </p>
        <button
            class="
      inline-flex items-center gap-2
      px-6 py-3 rounded-xl
      bg-linear-to-b from-secondary-400/30 to-secondary-500/60
      border border-secondary-200/40
      text-white text-sm font-semibold
      shadow-[0_0_24px_rgba(34,211,238,0.4)]
      hover:shadow-[0_0_36px_rgba(34,211,238,0.6)]
      backdrop-blur-sm transition-all
    "
        >
            Order Custom <ArrowRight class="h-4 w-4" />
        </button>
    </div>

    <!-- 3D Image, right-aligned, slightly overflowing top/bottom for floating effect -->
    <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1/2 max-w-md">
        <img
            src="/img/prosthetic-arm.png"
            class="w-full h-auto drop-shadow-2xl"
        />
    </div>
</section>
```

**Anatomy specs:**

- **Padding:** `p-10` left/top/bottom; `pr-0` right (image overflows)
- **Background:** Tri-stop gradient diagonal `from-primary-950 via-primary-900 to-primary-800`
- **Decorative layers:** ECG line + honeycomb pattern from landing page system (low opacity, non-interactive)
- **Title:** `font-display` (Plus Jakarta Sans), `text-3xl font-bold text-white`, allow line break manually
- **Description:** `text-sm text-slate-300`, max-width to keep ~50ch reading line
- **CTA:** Glassmorphic teal-glow button (mini version of landing page "Explore" CTA)
- **Image:** Absolutely positioned right edge, `w-1/2 max-w-md`, with `drop-shadow-2xl` for floating effect

**Variants (for CMS-driven hero rotation):**

- Hero supports multiple background gradients per featured category (3D Printed Prosthetic → navy; Educational Mannequin → emerald-teal; Aid Bands → rose-amber)
- Image asset is configured via CMS (Super Admin) — see PRD § Super Admin tasks

---

### **3.4 Category Quick-Access Grid**

```
┌────┐ ┌────┐ ┌────┐ ┌────┐ ┌────┐ ┌────┐ ┌────┐ ┌────┐ ┌────┐ ┌────┐
│ 🎨 │ │ 🦾 │ │ 🩹 │ │ 📚 │ │ 📄 │ │ 📰 │ │ 📁 │ │ 🛠️ │ │ 🎓 │ │ 🎪 │
│ 3D │ │Pros│ │Aid │ │Educ│ │Pap.│ │Jour│ │Proj│ │Serv│ │Trng│ │Evnt│
└────┘ └────┘ └────┘ └────┘ └────┘ └────┘ └────┘ └────┘ └────┘ └────┘
```

10 category tiles displayed in a single horizontal row (with horizontal scroll fallback on narrow viewports).

**Final category labels (typo corrected: "Aid Bards" → "Aid Bands"):**

| #   | Label                 | Icon source (suggestion) |
| --- | --------------------- | ------------------------ |
| 1   | 3D Designs            | `Box` / `Cube`           |
| 2   | Prosthetics           | `HandMetal` / custom     |
| 3   | Aid Bands             | `Bandage`                |
| 4   | Educational Mannequin | `GraduationCap`          |
| 5   | Papers                | `FileText`               |
| 6   | Journals              | `BookOpen`               |
| 7   | Projects              | `FolderOpen`             |
| 8   | Services              | `Wrench`                 |
| 9   | Training              | `Award`                  |
| 10  | Event                 | `CalendarHeart`          |

**Container:**

```html
<section class="bg-white rounded-2xl border border-slate-200 p-6">
    <div class="flex gap-4 overflow-x-auto pb-1 scrollbar-thin">
        <CategoryTile :for="each category" />
    </div>
</section>
```

**Tile Anatomy:**

```html
<button
    class="
  flex flex-col items-center gap-2
  min-w-18
  group
"
>
    <div
        class="
    w-16 h-16 rounded-2xl
    bg-slate-100 group-hover:bg-secondary-500
    grid place-items-center
    text-slate-600 group-hover:text-white
    transition-colors duration-200
    group-hover:shadow-[0_8px_20px_-4px_rgba(0,168,181,0.4)]
  "
    >
        <Icon class="h-7 w-7" />
    </div>
    <span
        class="text-xs font-medium text-slate-700 group-hover:text-secondary-600 text-center"
    >
        {label}
    </span>
</button>
```

**Tile States:**

| State                                 | Tile Background                       | Icon Color       | Label Color                      |
| ------------------------------------- | ------------------------------------- | ---------------- | -------------------------------- |
| Default                               | `bg-slate-100`                        | `text-slate-600` | `text-slate-700`                 |
| Hover                                 | `bg-secondary-500` + cyan shadow lift | `text-white`     | `text-secondary-600`             |
| Active (when route matches)           | `bg-primary-700`                      | `text-white`     | `text-primary-700 font-semibold` |
| Image-based (first 3 tiles in mockup) | Replace bg with image                 | n/a              | n/a                              |

> **Note on first 3 tiles in mockup image:** The leftmost 3 tiles use product imagery instead of icons (3D printer, prosthetic arm, aid band). This is a CMS-configurable "featured category" override. Default style remains the icon variant; image override is opt-in per category record.

---

### **3.5 Dual Card Section — New Products + No Ongoing Events**

```
┌────────────────────────────────────────┐  ┌──────────────────────┐
│ New Products      [PRODUCTS][SERVICES] │  │   No Ongoing Events  │
├────────────────────────────────────────┤  │                      │
│ ┌────┐  ┌────┐  ┌────┐                 │  │     [Illustration]   │
│ │img │  │img │  │img │                 │  │                      │
│ ├────┤  ├────┤  ├────┤                 │  │                      │
│ │name│  │name│  │name│                 │  └──────────────────────┘
│ │Rp..│  │Rp..│  │Rp..│
│ │⭐4.1│ │⭐4.1│ │⭐4.1│
│ └────┘  └────┘  └────┘
└────────────────────────────────────────┘
```

Two-column layout, left column wider (`col-span-2`), right is empty state.

**Container:**

```html
<section class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <NewProductsCard class="lg:col-span-2" />
    <NoOngoingEventsCard class="lg:col-span-1" />
</section>
```

#### **A. New Products Card**

```html
<div class="bg-white rounded-2xl border border-slate-200 p-6">
  <!-- Header -->
  <header class="flex items-center justify-between mb-5">
    <h2 class="font-display text-xl font-bold text-slate-800">New Products</h2>
    <TabSwitcher options={['Products', 'Services']} default="Products" />
  </header>

  <!-- Product Grid -->
  <div class="grid grid-cols-3 gap-3">
    <ProductCard :for="each product" />
  </div>
</div>
```

**Tab Switcher (Pill Style):**

```html
<div
    class="
  inline-flex items-center p-1
  bg-slate-100 rounded-full
"
>
    <button
        class="
    px-4 py-1.5 rounded-full text-xs font-semibold
    bg-primary-700 text-white shadow-sm
  "
    >
        PRODUCTS
    </button>
    <button
        class="
    px-4 py-1.5 rounded-full text-xs font-semibold
    text-slate-600 hover:text-slate-800
  "
    >
        SERVICES
    </button>
</div>
```

**Product Card Anatomy:**

```html
<article class="group cursor-pointer">
    <div
        class="relative aspect-square rounded-xl overflow-hidden bg-slate-100 mb-3"
    >
        <img
            src="..."
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
        />
    </div>
    <h3 class="text-sm font-semibold text-slate-800 line-clamp-1 mb-1">
        Dragon Model Fantasy Myth...
    </h3>
    <p class="text-xs font-medium text-slate-500 mb-1">
        Starts from
        <span class="text-primary-700 font-semibold">Rp 30,000</span>
    </p>
    <div class="flex items-center gap-1.5">
        <Star class="h-3 w-3 text-accent-400 fill-accent-400" />
        <span class="text-xs font-medium text-slate-600">4.1</span>
        <span class="text-xs text-slate-400">· Innovatech</span>
    </div>
</article>
```

#### **B. No Ongoing Events (Empty State)**

```html
<div
    class="
  bg-white rounded-2xl border border-slate-200 p-6
  flex flex-col items-center justify-center
  min-h-70
"
>
    <h2 class="font-display text-xl font-bold text-slate-800 mb-4 self-start">
        No Ongoing Events
    </h2>
    <img
        src="/img/empty-state-room.svg"
        class="w-48 h-auto"
        alt="No events illustration"
    />
    <p class="text-xs text-slate-500 mt-4 text-center">
        Check back later for upcoming Innovatech events.
    </p>
</div>
```

**Empty State Pattern:**

- Use friendly illustration (line-art style preferred for medical/tech context)
- Maintain card consistency with active sections (same border, radius, padding)
- Title aligned to top-left, illustration centered

---

### **3.6 Explore Our Projects — Featured Publications Showcase**

```
┌────────────────────────────────────────────────────────────┐
│              Explore Our Projects                          │
├──────────────┬──────────────┬──────────────────────────────┤
│              │              │                              │
│   UTERINE    │              │       (image larger)         │
│   BALLOON    │ ETT Holder   │       Craniosytos            │
│  TAMPONADE   │              │                              │
│              │              │                              │
└──────────────┴──────────────┴──────────────────────────────┘
```

3-column **heterogeneous showcase** of admin-curated featured publications.

**Container:**

```html
<section class="space-y-6">
    <h2 class="font-display text-2xl font-bold text-slate-800 text-center">
        Explore Our Projects
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <FeaturedCard size="square" data="{items[0]}" />
        <FeaturedCard size="square" data="{items[1]}" />
        <FeaturedCard size="square" data="{items[2]}" />
    </div>
</section>
```

**Featured Card Anatomy:**

```html
<a
    href="/publications/{slug}"
    class="
  group relative block aspect-square
  rounded-3xl overflow-hidden
  bg-linear-to-br from-slate-100 to-slate-200
  shadow-card-soft hover:shadow-card-hover
  transition-all duration-300
"
>
    <!-- Cover image -->
    <img
        src="{coverUrl}"
        class="
      absolute inset-0 w-full h-full object-cover
      group-hover:scale-105 transition-transform duration-500
    "
    />

    <!-- Gradient overlay (only on hover for clean look) -->
    <div
        class="
    absolute inset-0
    bg-linear-to-t from-primary-950/80 via-primary-950/20 to-transparent
    opacity-0 group-hover:opacity-100
    transition-opacity duration-300
  "
    />

    <!-- Title (visible on default for first card, overlay-style for others) -->
    <div class="absolute inset-x-0 bottom-0 p-6">
        <h3
            class="
      font-display text-2xl font-bold
      text-white drop-shadow-lg
      tracking-tight uppercase
    "
        >
            {title}
        </h3>
    </div>

    <!-- Featured ribbon (top-left) -->
    <span
        class="
    absolute top-4 left-4
    px-2.5 py-1 rounded-full
    bg-white/90 backdrop-blur-sm
    text-[11px] font-semibold text-primary-700
  "
    >
        ★ Featured
    </span>
</a>
```

**Data source:** `publications` table where `is_featured = true`, ordered by `featured_priority ASC`, limit 3 (refer to `database_schema.md`).

**Inertia Props Contract:**

```ts
interface FeaturedPublication {
    id: number;
    slug: string;
    title: string;
    cover_url: string;
    category: string;
    creator_name: string;
}

interface DashboardProps {
    featured_publications: FeaturedPublication[];
    // ... other props
}
```

---

### **3.7 Dual Article Section — Trending Articles + PubMed Updates**

```
┌─────────────────────────────┬─────────────────────────────┐
│ Trending Articles           │ PubMed Updates              │
│ PubMed records with...      │ Feature updates & PubMed... │
├─────────────────────────────┼─────────────────────────────┤
│ Improving mitochondrial...  │ New & Noteworthy RSS...     │
│ Geng B et al. Clin Trans... │ April 16, 2026              │
│ Free PMC article.           │ PubMed's New & Notewor...   │
│                             │                             │
│ Impact of high-power...     │ Article Type Filters...     │
│ Chen WJ et al. BMC Med...   │ March 31, 2026              │
│ Free PMC article.           │ The article type filters... │
│                             │                             │
│ ... (more)                  │ ... (more)                  │
├─────────────────────────────┼─────────────────────────────┤
│   [ See more trending → ]   │                             │
└─────────────────────────────┴─────────────────────────────┘
```

Side-by-side 2-column layout. Each column is its own `<article>` card.

**Container:**

```html
<section class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <TrendingArticlesCard />
    <PubMedUpdatesCard />
</section>
```

#### **A. Trending Articles Card**

> **Source:** Internal articles table — articles linked to publications with high view-count in the last 30 days.

```html
<div class="bg-white rounded-2xl border border-slate-200 p-6">
  <!-- Header -->
  <header class="mb-5 pb-4 border-b border-slate-100">
    <h2 class="font-display text-xl font-bold text-slate-800">Trending Articles</h2>
    <p class="text-xs text-slate-500 mt-1">
      Articles with recent increases in activity
    </p>
  </header>

  <!-- Article list -->
  <ul class="space-y-5">
    <ArticleListItem :for="each article" />
  </ul>

  <!-- Footer CTA -->
  <footer class="mt-6 pt-4 border-t border-slate-100">
    <Link href="/articles/trending" class="
      block w-full text-center
      bg-secondary-500 hover:bg-secondary-600
      text-white text-sm font-semibold
      py-2.5 rounded-xl
      shadow-md shadow-secondary-500/30
      transition
    ">
      See more trending articles
    </Link>
  </footer>
</div>
```

**Article List Item Anatomy:**

```html
<li class="group">
    <a href="/articles/{id}" class="block space-y-1">
        <h3
            class="
      text-[15px] font-semibold leading-snug
      text-primary-600 group-hover:text-secondary-500
      group-hover:underline underline-offset-2
      line-clamp-2
    "
        >
            {title}
        </h3>
        <p class="text-xs text-slate-500">
            {authors_short}. {journal}. {year}. PMID: {pmid}
        </p>
        <p class="text-xs">
            <span class="text-emerald-600 font-medium">{badge_label}</span>
            <span class="text-slate-400"> · {tags}</span>
        </p>
    </a>
</li>
```

**Item meta variants:**

- `Free PMC article.` → emerald badge
- `Clinical Trial.` → blue badge
- `Review.` → violet badge
- `No abstract available.` → slate badge (muted)

#### **B. PubMed Updates Card**

> **Source:** Live RSS feed integration from `https://www.ncbi.nlm.nih.gov/pubmed/feeds/`. Backend caches feed result for 1 hour to reduce upstream load.

```html
<div class="bg-white rounded-2xl border border-slate-200 p-6">
    <!-- Header -->
    <header class="mb-5 pb-4 border-b border-slate-100">
        <h2 class="font-display text-xl font-bold text-slate-800">
            PubMed Updates
        </h2>
        <p class="text-xs text-slate-500 mt-1">
            Feature updates and other PubMed highlights
        </p>
    </header>

    <!-- Feed item list -->
    <ul class="space-y-5">
        <PubMedFeedItem :for="each item" />
    </ul>
</div>
```

**PubMed Feed Item Anatomy:**

```html
<li class="group">
    <a
        href="{item.link}"
        target="_blank"
        rel="noopener"
        class="block space-y-1"
    >
        <h3
            class="
      text-[15px] font-semibold leading-snug
      text-primary-600 group-hover:text-secondary-500
      group-hover:underline underline-offset-2
      line-clamp-2
    "
        >
            {item.title}
        </h3>
        <time class="text-xs text-slate-500 block">
            {format(item.pubDate, 'MMMM d, yyyy')}
        </time>
        <p class="text-[13px] text-slate-600 line-clamp-2 leading-relaxed">
            {item.description}
        </p>
    </a>
</li>
```

**Backend integration note (Laravel Action):**

- Action: `App\Actions\Articles\FetchPubMedFeedAction`
- Cache key: `pubmed.feed.latest`, TTL 60 min
- Fallback: if upstream fails, render last cached version + small "Last updated X hours ago" timestamp

---

### **3.8 Featured Publications Section**

> **Section replaces the original "Join Our Training"** per project decision.

```
┌──────────────────────────────────────────────────────────────────┐
│              Featured Publications                               │
│           ╭─────────────────────────────╮                        │
│           │   [Banner Illustration]     │                        │
│           ╰─────────────────────────────╯                        │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ [thumb] Publication Title One                              │  │
│  │         👤 Creator A     ⏱ 11 days ago   👁 1,234 views     │  │
│  │         📦 3D Model · Prosthetic                  [View → ]│  │
│  ├────────────────────────────────────────────────────────────┤  │
│  │ [thumb] Publication Title Two                              │  │
│  │         👤 Creator B     ⏱ 8 days ago    👁 845 views       │  │
│  │         📦 Paper · Research                       [View → ]│  │
│  ├────────────────────────────────────────────────────────────┤  │
│  │ ... (more)                                                 │  │
│  └────────────────────────────────────────────────────────────┘  │
│                          [   See more!   ]                       │
└──────────────────────────────────────────────────────────────────┘
```

**Container:**

```html
<section class="bg-white rounded-2xl border border-slate-200 p-8">
  <!-- Heading -->
  <header class="text-center mb-8">
    <h2 class="font-display text-2xl font-bold text-slate-800">
      Featured Publications
    </h2>
  </header>

  <!-- Banner Illustration -->
  <div class="relative max-w-2xl mx-auto mb-8">
    <img src="/img/publications-banner.png" class="w-full h-auto" />
  </div>

  <!-- Publication list -->
  <div class="
    bg-white border border-slate-200 rounded-2xl
    divide-y divide-slate-100
    overflow-hidden
  ">
    <PublicationRowItem :for="each item" />
  </div>

  <!-- CTA -->
  <footer class="text-center mt-6">
    <Link href="/publications" class="
      inline-flex items-center gap-2
      px-8 py-3 rounded-full
      bg-secondary-500 hover:bg-secondary-600
      text-white text-sm font-semibold
      shadow-lg shadow-secondary-500/30 hover:shadow-secondary-500/50
      transition
    ">
      See more!
    </Link>
  </footer>
</section>
```

**Publication Row Item Anatomy:**

```html
<article class="
  flex items-center gap-4
  px-5 py-4
  hover:bg-slate-50 transition
">
  <!-- Thumbnail (left) -->
  <div class="
    shrink-0 w-12 h-12 rounded-xl overflow-hidden
    bg-slate-100 grid place-items-center
  ">
    <img src={thumbnail} class="w-full h-full object-cover" />
  </div>

  <!-- Body (center) -->
  <div class="flex-1 min-w-0 space-y-1">
    <h3 class="text-sm font-semibold text-slate-800 line-clamp-1">
      {title}
    </h3>
    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
      <span class="flex items-center gap-1">
        <Clock class="h-3 w-3" /> {time_ago}
      </span>
      <span class="flex items-center gap-1">
        <Eye class="h-3 w-3" /> {views}
      </span>
      <span class="flex items-center gap-1">
        <Tag class="h-3 w-3" /> {category}
      </span>
      <span class="flex items-center gap-1">
        <User class="h-3 w-3" /> {creator}
      </span>
    </div>
  </div>

  <!-- CTA (right) -->
  <Link href="/publications/{slug}" class="
    shrink-0 text-xs font-semibold
    text-secondary-600 hover:text-secondary-700
    inline-flex items-center gap-1
  ">
    View Detail <ArrowRight class="h-3 w-3" />
  </Link>
</article>
```

---

## **PART 4 — VISUAL EFFECTS & MICRO-INTERACTIONS**

### **4.1 Sidebar Active State (Teal Neon Bar)**

```css
.sidebar-nav-active {
    background: linear-gradient(to right, #062e5c, #0a3d7a);
    position: relative;
}
.sidebar-nav-active::before {
    content: "";
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 32px;
    background: #00a8b5;
    border-radius: 0 4px 4px 0;
    box-shadow:
        0 0 12px rgba(34, 211, 238, 0.6),
        0 0 24px rgba(34, 211, 238, 0.3);
}
```

### **4.2 Card Hover Elevation**

```css
.dashboard-card {
    transition:
        transform 200ms ease,
        box-shadow 200ms ease;
}
.dashboard-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px -8px rgba(3, 16, 38, 0.18);
}
```

Apply to: product cards, featured publication cards, publication row items, category tiles.

### **4.3 Search Bar Focus Animation**

```css
.search-input {
    transition: all 200ms ease;
}
.search-input:focus {
    background: #ffffff;
    border-color: #00a8b5;
    box-shadow: 0 0 0 4px rgba(0, 168, 181, 0.12);
}
```

### **4.4 Notification Badge Pulse**

```css
@keyframes badge-pulse {
    0%,
    100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.15);
        opacity: 0.85;
    }
}
.notification-badge {
    animation: badge-pulse 2s ease-in-out infinite;
}
```

### **4.5 Avatar Dropdown Transition**

Use Headless UI `<Transition>` or Framer Motion:

- Enter: `opacity-0 scale-95 translate-y-1` → `opacity-100 scale-100 translate-y-0` (150ms ease-out)
- Leave: reverse (100ms ease-in)

### **4.6 Loading & Skeleton States**

**Skeleton tile (product card):**

```html
<div class="animate-pulse">
    <div class="aspect-square rounded-xl bg-slate-200 mb-3" />
    <div class="h-3.5 rounded bg-slate-200 w-3/4 mb-2" />
    <div class="h-3 rounded bg-slate-200 w-1/2" />
</div>
```

Apply to: New Products grid, Featured publications, Article lists (3-5 skeleton items during Inertia partial reload).

### **4.7 Sidebar Collapse Animation**

```css
.sidebar {
    width: 240px;
    transition: width 220ms cubic-bezier(0.4, 0, 0.2, 1);
}
.sidebar.collapsed {
    width: 72px;
}
.sidebar.collapsed .nav-label {
    opacity: 0;
    transform: translateX(-8px);
    pointer-events: none;
}
```

---

## **PART 5 — COMPONENT STATES MATRIX**

### **5.1 Sidebar Nav Item**

| State    | Visual                                                                        |
| -------- | ----------------------------------------------------------------------------- |
| Default  | `bg-transparent text-slate-300 hover:bg-primary-900/50`                       |
| Hover    | `bg-primary-900/50 text-white`                                                |
| Active   | `bg-gradient-to-r from-primary-900 to-primary-800 text-white` + neon left bar |
| Focus    | `ring-2 ring-secondary-500/40 ring-offset-2 ring-offset-primary-950`          |
| Disabled | `opacity-40 cursor-not-allowed pointer-events-none`                           |

### **5.2 Buttons**

| Variant   | Default                                                      | Hover                                      | Disabled                                         |
| --------- | ------------------------------------------------------------ | ------------------------------------------ | ------------------------------------------------ |
| Primary   | `bg-secondary-500 text-white shadow-secondary-500/30`        | `bg-secondary-600 shadow-secondary-500/50` | `bg-slate-300 text-slate-500 cursor-not-allowed` |
| Glow CTA  | Gradient teal + cyan glow (hero card pattern)                | Larger glow radius                         | Lose glow, desaturate                            |
| Ghost     | `bg-transparent text-slate-700 hover:bg-slate-100`           | `bg-slate-100`                             | `opacity-40`                                     |
| Icon-only | `w-10 h-10 rounded-full bg-slate-100 hover:bg-secondary-500` | Color invert                               | `opacity-40`                                     |

### **5.3 Cards**

| State   | Visual                                                     |
| ------- | ---------------------------------------------------------- |
| Default | `bg-white border border-slate-200 rounded-2xl`             |
| Hover   | `shadow-card-hover -translate-y-0.5`                       |
| Loading | Replace content with skeleton (animate-pulse)              |
| Empty   | Keep card chrome, replace content with illustration + text |
| Error   | Add `border-red-200 bg-red-50` + error icon header         |

### **5.4 Form Inputs (Search bar pattern)**

| State    | Visual                                                       |
| -------- | ------------------------------------------------------------ |
| Default  | `bg-slate-100 border border-transparent`                     |
| Hover    | `bg-slate-50`                                                |
| Focus    | `bg-white border-secondary-500 ring-4 ring-secondary-500/12` |
| Error    | `border-red-400 ring-red-400/20` + error text below          |
| Disabled | `bg-slate-100 opacity-50 cursor-not-allowed`                 |

### **5.5 Toggle / Tab Switcher (Products / Services)**

| State    | Visual                                                 |
| -------- | ------------------------------------------------------ |
| Active   | `bg-primary-700 text-white shadow-sm`                  |
| Inactive | `text-slate-600 hover:text-slate-800` (transparent bg) |
| Wrapper  | `bg-slate-100 rounded-full p-1`                        |

---

## **PART 6 — IMPLEMENTATION NOTES (Feature-Based Architecture)**

### **6.1 Folder Structure Mapping**

```
resources/js/
├── Core/
│   └── ui/                       # Shared primitives (Hero UI / shadcn wrappers)
│       ├── Avatar/
│       ├── Button/
│       ├── Card/
│       ├── DropdownMenu/
│       ├── Input/
│       ├── Tooltip/
│       └── index.ts              # Barrel export
│
├── Features/
│   ├── dashboard/
│   │   ├── components/
│   │   │   ├── DashboardLayout.tsx        # Shell: Sidebar + Topbar + Outlet
│   │   │   ├── Sidebar.tsx
│   │   │   ├── SidebarNavItem.tsx
│   │   │   ├── Topbar.tsx
│   │   │   ├── HeroBannerCard.tsx
│   │   │   ├── CategoryQuickAccess.tsx
│   │   │   └── EmptyStateCard.tsx
│   │   ├── pages/
│   │   │   └── DashboardHome.tsx          # /dashboard route
│   │   ├── types.ts                       # DashboardProps interface
│   │   └── index.ts
│   │
│   ├── publications/
│   │   ├── components/
│   │   │   ├── FeaturedPublicationCard.tsx
│   │   │   ├── PublicationRowItem.tsx
│   │   │   └── FeaturedPublicationsSection.tsx
│   │   ├── hooks/
│   │   │   └── usePublicationFilters.ts
│   │   └── index.ts
│   │
│   ├── products/
│   │   ├── components/
│   │   │   ├── ProductCard.tsx
│   │   │   ├── NewProductsCard.tsx
│   │   │   └── ProductServiceToggle.tsx
│   │   └── index.ts
│   │
│   ├── articles/
│   │   ├── components/
│   │   │   ├── ArticleListItem.tsx
│   │   │   └── TrendingArticlesCard.tsx
│   │   └── index.ts
│   │
│   ├── pubmed/
│   │   ├── components/
│   │   │   ├── PubMedFeedItem.tsx
│   │   │   └── PubMedUpdatesCard.tsx
│   │   └── index.ts
│   │
│   └── shared-state/
│       ├── stores/
│       │   ├── useUiStore.ts              # sidebar collapsed, language
│       │   └── useCartStore.ts            # cart items (persisted)
│       └── index.ts
│
└── app.tsx                        # Inertia bootstrap
```

> ⚠️ **Cross-feature import rule:** `Features/dashboard/pages/DashboardHome.tsx` may import from other features ONLY via their barrel `index.ts`:
>
> ```ts
> // ✅ Allowed
> import { FeaturedPublicationsSection } from "@/Features/publications";
> // ❌ Forbidden
> import { FeaturedPublicationCard } from "@/Features/publications/components/FeaturedPublicationCard";
> ```

### **6.2 Inertia Props Contract**

```ts
// Features/dashboard/types.ts
import type { FeaturedPublication } from "@/Features/publications";
import type { Product } from "@/Features/products";
import type { Article } from "@/Features/articles";
import type { PubMedFeedItem } from "@/Features/pubmed";

export interface DashboardHeroBanner {
    title: string;
    description: string;
    cta_label: string;
    cta_url: string;
    image_url: string;
    gradient_variant: "navy" | "teal" | "rose";
}

export interface DashboardCategory {
    id: number;
    label: string;
    slug: string;
    icon_name: string; // lucide-react icon key
    image_url?: string; // overrides icon for featured categories
}

export interface DashboardHomeProps {
    hero_banners: DashboardHeroBanner[];
    categories: DashboardCategory[];
    new_products: Product[];
    new_services: Product[];
    ongoing_events: Event[]; // empty array → renders empty state
    featured_publications: FeaturedPublication[]; // top 3 curated
    trending_articles: Article[];
    pubmed_updates: PubMedFeedItem[];
    recent_publications: FeaturedPublication[]; // for "Featured Publications" list
    auth: {
        user: { id: number; name: string; avatar_url: string };
        unread_notifications: number;
    };
}
```

### **6.3 Zustand Store (Client State Only)**

```ts
// Features/shared-state/stores/useUiStore.ts
import { create } from "zustand";
import { persist } from "zustand/middleware";

interface UiState {
    sidebarCollapsed: boolean;
    language: "en" | "id";
    toggleSidebar: () => void;
    setLanguage: (lang: "en" | "id") => void;
}

export const useUiStore = create<UiState>()(
    persist(
        (set) => ({
            sidebarCollapsed: false,
            language: "en",
            toggleSidebar: () =>
                set((s) => ({ sidebarCollapsed: !s.sidebarCollapsed })),
            setLanguage: (language) => set({ language }),
        }),
        { name: "idig-ui-preferences" },
    ),
);
```

> Per project rules: Zustand is used ONLY for client state that needs persistence. Server data flows in via Inertia props.

### **6.4 i18n Strategy (Multi-Language)**

Recommended stack: **`react-i18next`** with namespace per feature.

```
resources/lang/
├── en/
│   ├── common.json          # buttons, generic labels
│   ├── dashboard.json       # dashboard-specific copy
│   ├── publications.json
│   └── ...
└── id/
    └── (mirror)
```

**Top bar language switcher** triggers:

```ts
// On selection
i18n.changeLanguage(newLang);
useUiStore.getState().setLanguage(newLang);
router.reload({ only: ["translations"] }); // refresh server-rendered strings
```

> Server-side: Laravel's `__()` helper continues to handle email/PDF strings. Same JSON keys in `resources/lang/` are shared between Laravel and React via build-time export script.

### **6.5 Performance**

- **Image lazy loading:** All thumbnails use `loading="lazy"` + WebP format. Hero banner image uses `fetchpriority="high"`.
- **Code splitting:** Each feature page is lazy-loaded via `React.lazy()`. Dashboard home is eager-loaded (entry point after login).
- **3D Viewer:** Not present on dashboard home, but if added in future cards, MUST use `React.lazy(() => import(...))` wrapped in `<Suspense fallback={<Spinner/>}>` per project rule.
- **Inertia partial reloads:** Use `only: ['pubmed_updates']` when refreshing time-sensitive sections without reloading whole page.
- **PubMed RSS caching:** Backend caches feed for 60 min; frontend additionally caches in TanStack Query / SWR for 5 min if used.

### **6.6 Accessibility**

- **Keyboard navigation:**
    - Sidebar nav items reachable by Tab
    - `Esc` closes Avatar / Language dropdowns
    - `/` shortcut focuses search bar
- **ARIA labels:**
    - Sidebar: `<nav aria-label="Main navigation">`
    - Notification button: `aria-label="Notifications, 3 unread"`
    - Tab switcher: `role="tablist"` with `aria-selected` per tab
- **Focus management:**
    - Visible focus rings on all interactive elements (`focus-visible:ring-2 focus-visible:ring-secondary-500`)
    - Dropdown menus trap focus while open (use Headless UI `<Menu>` for free)
- **Color contrast:** All text on dark sidebar bg verified ≥ 4.5:1 (WCAG AA). `text-slate-300` on `bg-primary-950` = 11.2:1 ✅

---

## **APPENDIX**

### **A. Tailwind v4 Theme Extension (CSS-First, Full Delta)**

> **Note:** This project uses **Tailwind CSS v4**, which has migrated from `tailwind.config.js` to a **CSS-first** configuration model. All theme tokens are declared inside `@theme { ... }` blocks in CSS files. The example below contains **only the delta** for the dashboard — the landing page tokens (`primary-*`, `secondary-*`, `accent-*`, fonts) are assumed to already exist in an upstream CSS file imported earlier.

#### **File: `resources/css/dashboard.css`**

```css
/* ============================================================================
 * IDIG Health Tech — Dashboard Theme Extension (Tailwind v4)
 * Delta on top of the landing page theme.
 * ==========================================================================*/

/* --- 1. Plugins ----------------------------------------------------------- */
@plugin "@tailwindcss/forms";
@plugin "@tailwindcss/typography";
@plugin "@tailwindcss/aspect-ratio";
@plugin "@tailwindcss/container-queries";

/* --- 2. Source detection (Laravel + Livewire) ----------------------------- */
@source "../views/**/*.blade.php";
@source "../views/livewire/**/*.blade.php";
@source "../../app/Livewire/**/*.php";
@source "../js/**/*.{ts,tsx,js,jsx}";

/* --- 3. Dashboard theme tokens (DELTA only) ------------------------------- */
@theme {
    /* Sidebar dark palette */
    --color-sidebar: #031026;
    --color-sidebar-elev: #062e5c;
    --color-sidebar-divider: #0a3d7a;

    /* Status palette (verified / pending / rejected) */
    --color-status-verified: #10b981;
    --color-status-pending: #ffc72c;
    --color-status-rejected: #ef4444;

    /* Dashboard sizing */
    --spacing-sidebar: 15rem; /* 240px expanded sidebar */
    --spacing-sidebar-mini: 4.5rem; /* 72px  collapsed sidebar */
    --spacing-topbar: 4.5rem; /* 72px  top bar height */

    /* Radius */
    --radius-pill: 9999px;

    /* Dashboard shadows */
    --shadow-card-hover: 0 12px 32px -8px rgba(3, 16, 38, 0.18);
    --shadow-sidebar-active:
        0 0 12px rgba(34, 211, 238, 0.6), 0 0 24px rgba(34, 211, 238, 0.3);
    --shadow-topbar: 0 1px 0 rgba(226, 232, 240, 1);
    --shadow-avatar-ring: 0 0 0 2px rgba(0, 168, 181, 0.2);
    --shadow-glow-cyan-sm:
        0 0 16px rgba(34, 211, 238, 0.35), 0 0 28px rgba(34, 211, 238, 0.2);
    --shadow-glow-cyan-md:
        0 0 24px rgba(34, 211, 238, 0.4), 0 0 36px rgba(34, 211, 238, 0.25);

    /* Animations (paired with @keyframes blocks below) */
    --animate-badge-pulse: badge-pulse 2s ease-in-out infinite;
    --animate-fade-in: fade-in 200ms ease-out;
    --animate-slide-down: slide-down 220ms cubic-bezier(0.4, 0, 0.2, 1);
    --animate-skeleton-shimmer: skeleton-shimmer 1.5s ease-in-out infinite;

    /* Custom easing */
    --ease-sidebar: cubic-bezier(0.4, 0, 0.2, 1);
}

/* --- 4. Keyframes (root-level, NOT inside @theme) ------------------------- */
@keyframes badge-pulse {
    0%,
    100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.15);
        opacity: 0.85;
    }
}

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(4px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slide-down {
    from {
        opacity: 0;
        transform: translateY(-8px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes skeleton-shimmer {
    0% {
        background-position: -200% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

/* --- 5. Custom utilities (Tailwind v4 @utility) --------------------------- */

@utility glass-surface {
    background-color: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

@utility glass-surface-light {
    background-color: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border-bottom: 1px solid rgba(226, 232, 240, 1);
}

@utility sidebar-active-indicator {
    position: relative;
    background: linear-gradient(to right, #062e5c, #0a3d7a);
    color: white;

    &::before {
        content: "";
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 2rem;
        background-color: var(--color-secondary-500, #00a8b5);
        border-radius: 0 4px 4px 0;
        box-shadow:
            0 0 12px rgba(34, 211, 238, 0.6),
            0 0 24px rgba(34, 211, 238, 0.3);
    }
}

@utility honeycomb-bg {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='56' height='100' viewBox='0 0 56 100'%3E%3Cpath d='M28 66L0 50V16l28-16 28 16v34zM28 0L0 16l28 16 28-16z' fill='%2300A8B5' fill-opacity='0.08'/%3E%3C/svg%3E");
    background-size: 56px 100px;
}

@utility skeleton {
    background: linear-gradient(
        90deg,
        rgba(226, 232, 240, 0.6) 0%,
        rgba(226, 232, 240, 1) 50%,
        rgba(226, 232, 240, 0.6) 100%
    );
    background-size: 200% 100%;
    animation: skeleton-shimmer 1.5s ease-in-out infinite;
    border-radius: 0.5rem;
}

@utility btn-glow-cyan {
    background: linear-gradient(
        to bottom,
        rgba(103, 232, 249, 0.4),
        rgba(0, 168, 181, 0.6)
    );
    border: 1px solid rgba(165, 243, 252, 0.5);
    box-shadow: var(--shadow-glow-cyan-sm);
    backdrop-filter: blur(4px);
    transition: box-shadow 300ms ease;
    color: white;

    &:hover {
        box-shadow: var(--shadow-glow-cyan-md);
    }
}

@utility scrollbar-thin {
    scrollbar-width: thin;
    scrollbar-color: rgba(0, 168, 181, 0.4) transparent;

    &::-webkit-scrollbar {
        height: 6px;
        width: 6px;
    }
    &::-webkit-scrollbar-track {
        background: transparent;
    }
    &::-webkit-scrollbar-thumb {
        background-color: rgba(0, 168, 181, 0.4);
        border-radius: 9999px;
    }
    &::-webkit-scrollbar-thumb:hover {
        background-color: rgba(0, 168, 181, 0.6);
    }
}

/* --- 6. Custom variants (bound to Zustand UI state via data-attr) --------- */
@custom-variant sidebar-collapsed (&:where([data-sidebar="collapsed"] *));
@custom-variant sidebar-expanded  (&:where([data-sidebar="expanded"]  *));
@custom-variant theme-dark        (&:where([data-theme="dark"]        *));

/* --- 7. Base overrides ---------------------------------------------------- */
@layer base {
    aside[data-component="sidebar"] {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    html {
        scrollbar-gutter: stable;
    }

    *:focus-visible {
        outline: 2px solid var(--color-secondary-500, #00a8b5);
        outline-offset: 2px;
        border-radius: 4px;
    }
}

/* --- 8. Reusable component classes --------------------------------------- */
@layer components {
    .card-surface {
        @apply bg-white border border-slate-200 rounded-2xl;
    }
    .card-interactive {
        @apply card-surface transition-all duration-200;
        &:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-card-hover);
        }
    }
    .section-heading {
        @apply font-display text-2xl font-bold text-slate-800;
    }
    .card-heading {
        @apply font-display text-xl  font-bold text-slate-800;
    }
}
```

#### **Entry point: `resources/css/app.css`**

```css
@import "tailwindcss";

/* Landing page tokens (already shipped) */
@import "./landing.css";

/* Dashboard delta (this appendix) */
@import "./dashboard.css";
```

#### **Vite plugin: `vite.config.ts`**

```ts
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.tsx"],
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
});
```

#### **Install commands**

```bash
npm i -D @tailwindcss/vite \
         @tailwindcss/forms \
         @tailwindcss/typography \
         @tailwindcss/aspect-ratio \
         @tailwindcss/container-queries
```

#### **Key differences from Tailwind v3**

| Concept           | Tailwind v3                                | Tailwind v4                                       |
| ----------------- | ------------------------------------------ | ------------------------------------------------- |
| Config location   | `tailwind.config.js`                       | CSS file with `@theme { ... }`                    |
| Import directive  | `@tailwind base; components; utilities;`   | `@import "tailwindcss";`                          |
| Custom colors     | `theme.extend.colors.primary[700]`         | `--color-primary-700: #00426D;` inside `@theme`   |
| Custom shadows    | `theme.extend.boxShadow['card-hover']`     | `--shadow-card-hover: ...;` inside `@theme`       |
| Custom animations | `theme.extend.keyframes` + `.animation`    | `--animate-*` inside `@theme` + root `@keyframes` |
| Custom utilities  | `@layer utilities { .foo { ... } }`        | `@utility foo { ... }`                            |
| Custom variants   | `addVariant(...)` JS plugin                | `@custom-variant foo (...)`                       |
| Plugins           | `plugins: [require('@tailwindcss/forms')]` | `@plugin "@tailwindcss/forms";`                   |
| Content detection | `content: ['./resources/**/*.blade.php']`  | Automatic + `@source "./path/..."` for extras     |
| Build tool        | PostCSS plugin                             | `@tailwindcss/vite` (Lightning CSS-powered)       |

#### **Usage examples (auto-generated utilities from tokens)**

```html
<!-- Sidebar root -->
<aside class="bg-sidebar w-sidebar h-screen sidebar-expanded:w-sidebar"></aside>

<!-- Collapsed sidebar (data-sidebar="collapsed" on <html>) -->
<aside class="bg-sidebar w-sidebar sidebar-collapsed:w-sidebar-mini"></aside>

<!-- Top bar -->
<header class="h-topbar glass-surface-light"></header>

<!-- Status badge -->
<span class="bg-status-pending text-white">Pending Review</span>

<!-- Dashboard card with hover lift -->
<article class="card-interactive p-6">...</article>

<!-- Notification badge with pulse -->
<span class="animate-badge-pulse bg-red-500 w-2 h-2 rounded-full"></span>

<!-- Hero CTA with neon glow -->
<button class="btn-glow-cyan px-6 py-3 rounded-xl">Order Custom</button>
```

### **B. Component Naming Convention**

| Convention                        | Example                                        |
| --------------------------------- | ---------------------------------------------- |
| PascalCase for components         | `FeaturedPublicationCard.tsx`                  |
| camelCase for hooks               | `usePublicationFilters.ts`                     |
| `<Domain><Element><Type>` pattern | `Sidebar` / `SidebarNavItem` / `SidebarLayout` |
| Avoid generic names               | ❌ `Card.tsx` → ✅ `ProductCard.tsx`           |
| Barrel file per feature folder    | `Features/publications/index.ts`               |

### **C. Decision Log — Section-Level PRD Adjustments**

| #   | Original (in mockup)        | Decision                         | Reason                                  |
| --- | --------------------------- | -------------------------------- | --------------------------------------- |
| 1   | Top bar: Sign up + Log in   | → Avatar + Notification + Logout | User is authenticated                   |
| 2   | Sidebar menu: Training      | → Publications                   | No LMS module in scope                  |
| 3   | Hero CTA: "BE A TIENDA"     | → "Order Custom"                 | Matches PRD custom-order flow           |
| 4   | Category: "Aid Bards"       | → "Aid Bands"                    | Typo correction                         |
| 5   | "Join Our Training" section | → "Featured Publications"        | Replaces with PRD-aligned content       |
| 6   | "PubMed Updates"            | ✅ Kept (real RSS integration)   | Confirmed in scope                      |
| 7   | "Explore Our Projects"      | → Curated featured publications  | Sourced from `publications.is_featured` |
| 8   | "Shop" in sidebar           | ✅ Kept as separate module       | Confirmed scope expansion               |
| 9   | Language selector           | ✅ Kept (i18n: EN / ID)          | Confirmed multi-language scope          |

---

## **IMPLEMENTATION CHECKLIST FOR FRONTEND TEAM**

- [ ] Install Tailwind v4 plugins (`@tailwindcss/vite`, forms, typography, aspect-ratio, container-queries)
- [ ] Create `resources/css/dashboard.css` with Appendix A delta and import it from `app.css`
- [ ] Bind `data-sidebar="expanded"` / `"collapsed"` on `<html>` from Zustand `useUiStore`
- [ ] Create barrel `index.ts` in every feature folder; enforce ESLint rule against deep imports
- [ ] Build `Core/ui/` primitives first (Button, Card, Avatar, DropdownMenu, Input)
- [ ] Build `Features/dashboard/components/DashboardLayout.tsx` shell
- [ ] Implement Sidebar with collapse state (Zustand `useUiStore`)
- [ ] Implement Top bar with all dropdowns (Headless UI for a11y)
- [ ] Build each section component as **pure props consumer** (no fetching inside)
- [ ] All data fetched by Laravel Action, passed via Inertia props (typed via `DashboardHomeProps`)
- [ ] Add skeleton loading states for all dynamic sections
- [ ] Configure `react-i18next` with EN + ID translation files
- [ ] Wire keyboard shortcut: `/` focuses search input
- [ ] Verify color contrast (WCAG AA) on dark sidebar
- [ ] Test responsive breakpoints (≥ 1280 / 1024 / 768 / < 768)

---

> **Phase 1 deliverable:** Dashboard shell + Sidebar + Topbar + Hero card + Category grid → working prototype with mock Inertia props.
> **Phase 2:** Wire backend Actions (`GetDashboardDataAction`, `FetchPubMedFeedAction`) and real data binding.
> **Phase 3:** Polish micro-interactions, i18n, accessibility audit.
