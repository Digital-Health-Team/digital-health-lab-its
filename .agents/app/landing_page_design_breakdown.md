# **DESIGN SYSTEM: IDIG Health Tech — Landing Page**

### _Repository & Publication of Medical Engineering Technology ITS_

---

## **PART 1 — GLOBAL DESIGN TOKENS**

### **1.1 Color Palette**

Based on visual analysis of the design image, there is a shift in tone from the original brief (which uses a brighter ITS Deep Blue) toward a **deep navy futuristic aesthetic** to create a more premium medical laboratory impression. I recommend the following extended palette:

#### **Primary Palette (Brand Core)**

| Token Name    | Hex Code  | Tailwind Custom Class | Usage                                     |
| ------------- | --------- | --------------------- | ----------------------------------------- |
| `primary-950` | `#031026` | `bg-primary-950`      | Footer background, deep section overlay   |
| `primary-900` | `#062E5C` | `bg-primary-900`      | Hero overlay, "Sharing Wisdom" section bg |
| `primary-800` | `#0A3D7A` | `bg-primary-800`      | Card gradient base (Research card)        |
| `primary-700` | `#00426D` | `bg-primary-700`      | **ITS Deep Blue** — Primary CTA, accents  |
| `primary-600` | `#0D5A9E` | `bg-primary-600`      | Hover states on primary surfaces          |

#### **Secondary Palette (Tech / Medical Cyan)**

| Token Name      | Hex Code  | Tailwind Custom Class  | Usage                                       |
| --------------- | --------- | ---------------------- | ------------------------------------------- |
| `secondary-500` | `#00A8B5` | `bg-secondary-500`     | **Medical Teal** — buttons, glow effects    |
| `secondary-400` | `#22D3EE` | `text-secondary-400`   | "Sharing Wisdom" highlight text, ECG line   |
| `secondary-300` | `#67E8F9` | `text-secondary-300`   | Light glow / hover on cyan elements         |
| `secondary-200` | `#A5F3FC` | `border-secondary-200` | Subtle borders, button stroke (Explore CTA) |

#### **Accent Palette**

| Token Name   | Hex Code  | Tailwind Custom Class | Usage                                     |
| ------------ | --------- | --------------------- | ----------------------------------------- |
| `accent-400` | `#FFC72C` | `bg-accent-400`       | ITS Gold — secondary CTA, status badges   |
| `accent-300` | `#FCD34D` | `text-accent-300`     | Notification highlights, "Pending" status |

#### **Neutral & Surface Palette**

| Token Name        | Hex Code  | Tailwind Custom Class | Usage                                |
| ----------------- | --------- | --------------------- | ------------------------------------ |
| `surface-base`    | `#F8F9FA` | `bg-surface-base`     | **Off-White** — Org chart section bg |
| `surface-card`    | `#FFFFFF` | `bg-white`            | Card containers, modals              |
| `surface-muted`   | `#F1F5F9` | `bg-slate-100`        | Inactive nav items, dividers         |
| `text-primary`    | `#1E293B` | `text-slate-800`      | Headings on light bg                 |
| `text-secondary`  | `#475569` | `text-slate-600`      | Body text on light bg                |
| `text-on-dark`    | `#FFFFFF` | `text-white`          | Text on Hero & dark sections         |
| `text-muted-dark` | `#94A3B8` | `text-slate-400`      | Footer secondary text                |

#### **Tailwind Config (Recommended)**

```js
// tailwind.config.js
extend: {
  colors: {
    primary: {
      950: '#031026', 900: '#062E5C', 800: '#0A3D7A',
      700: '#00426D', 600: '#0D5A9E'
    },
    secondary: {
      500: '#00A8B5', 400: '#22D3EE',
      300: '#67E8F9', 200: '#A5F3FC'
    },
    accent: { 400: '#FFC72C', 300: '#FCD34D' }
  }
}
```

---

### **1.2 Typography Scale**

Following the brief: **Plus Jakarta Sans** for Headings, **Inter** for Body. Based on the visual image, typography features strong contrast between display (italic, bold) and body (regular, justified).

#### **Font Family Tokens**

```js
fontFamily: {
  display: ['"Plus Jakarta Sans"', 'sans-serif'],   // Headings
  body: ['Inter', 'sans-serif'],                    // Paragraphs & UI
}
```

#### **Type Scale**

| Token        | Size / Line Height | Weight     | Tailwind Class                   | Usage Example                      |
| ------------ | ------------------ | ---------- | -------------------------------- | ---------------------------------- |
| `display-xl` | 72px / 80px        | 800 italic | `text-7xl font-extrabold italic` | "IDIG Laboratory" hero title       |
| `display-lg` | 56px / 64px        | 700        | `text-5xl font-bold`             | "Welcome to" subtitle              |
| `heading-1`  | 48px / 56px        | 700        | `text-5xl font-bold`             | "We believe in the art of"         |
| `heading-2`  | 36px / 44px        | 700        | `text-4xl font-bold`             | Section titles ("About us")        |
| `heading-3`  | 24px / 32px        | 700        | `text-2xl font-bold`             | Card titles ("Research", "Events") |
| `heading-4`  | 18px / 28px        | 600        | `text-lg font-semibold`          | Team name labels ("Tim Cook")      |
| `body-lg`    | 18px / 28px        | 400        | `text-lg`                        | Hero description                   |
| `body-base`  | 16px / 26px        | 400        | `text-base`                      | Default paragraph                  |
| `body-sm`    | 14px / 22px        | 400        | `text-sm`                        | Footer info, card descriptions     |
| `body-xs`    | 12px / 18px        | 500        | `text-xs font-medium`            | Copyright, role labels             |
| `quote-lg`   | 24px / 36px        | 400 italic | `text-2xl italic`                | Djoko Kuswanto quote               |

#### **Special Typography Treatments**

- **Hero Title ("IDIG Laboratory"):** `font-display font-extrabold italic tracking-tight` with subtle white **text-shadow glow**
- **"Sharing Wisdom":** `text-secondary-400 italic` — italicized for elegant & inspirational impression
- **About Us paragraph:** `text-justify` — aligns left-and-right per visual design

---

## **PART 2 — UI COMPONENT ANATOMY**

### **2.1 Navigation Bar**

```
[Logo iDIG]     [Discover] [Categories]     [Sign In Button]
   ←—— Left          ←— Center pill nav         Right ——→
```

**Specification:**

- **Container:** `fixed top-0 w-full px-12 py-5 bg-transparent z-50`
- **Logo:** White SVG, height 40px (`h-10`), combination of circular symbol + wordmark "iDIG Health Tech."
- **Center Nav (Pill Style):**
    - Background: `bg-white/10 backdrop-blur-md border border-white/20 rounded-full`
    - Padding: `px-2 py-2`
    - Items: `px-6 py-2 text-white text-sm font-medium`
    - Active state ("Discover"): underline `border-b-2 border-white`
- **Sign In Button:**
    - `bg-primary-700 hover:bg-primary-600 text-white px-6 py-2.5 rounded-lg`
    - `border border-secondary-400/30` for subtle glow effect
    - Font: `text-sm font-semibold`

---

### **2.2 Buttons**

#### **A. Primary CTA — "Explore" (Hero Button)**

The hero button features a distinctive **neon teal glow** — this is a signature element.

```html
<button
    class="
  px-12 py-4 rounded-2xl
  bg-linear-to-b from-secondary-300/40 to-secondary-500/60
  border border-secondary-200/60
  text-white text-lg font-semibold
  shadow-[0_0_40px_rgba(34,211,238,0.5)]
  hover:shadow-[0_0_60px_rgba(34,211,238,0.7)]
  backdrop-blur-sm
  transition-all duration-300
"
>
    Explore
</button>
```

Characteristics:

- **Glow Effect:** outer shadow cyan (`shadow-cyan-400/50`)
- **Inner Highlight:** top-to-bottom gradient creates an impression of a "glowing glass button"
- **Border:** `border-secondary-200/60` creates a subtle outline

#### **B. Secondary CTA — "Explore Our Innovations"**

```html
<button
    class="
  px-10 py-4 rounded-xl
  bg-secondary-500 hover:bg-secondary-600
  border border-secondary-300
  text-white font-semibold
  shadow-lg shadow-secondary-500/30
  inline-flex items-center gap-2
"
>
    Explore Our Innovations <ArrowRight />
</button>
```

#### **C. Card Inner Button — "Explore" (inside Service Cards)**

```html
<button
    class="
  px-8 py-2.5 rounded-full
  bg-white/20 backdrop-blur-sm
  border border-white/30
  text-white text-sm
  hover:bg-white/30
"
>
    Explore
</button>
```

---

### **2.3 Service Cards (Research / Products & Services / Events)**

Three cards arranged side-by-side with **glassmorphism gradient** style — the **center card is taller** for visual hierarchy.

#### **Card Structure**

```html
<div
    class="
  relative overflow-hidden rounded-3xl
  px-8 py-10
  bg-linear-to-br from-[colorA] to-[colorB]
  shadow-2xl
  flex flex-col items-center text-center
  text-white
"
>
    <h3 class="text-2xl font-bold mb-3">{title}</h3>
    <p class="text-sm text-white/80 mb-6 max-w-xs">{description}</p>
    <button class="...explore button..."></button>
    <div class="mt-8 w-full"><!-- Visual / image --></div>
</div>
```

#### **Per-Card Gradient Specs**

| Card                                     | Gradient                                            | Visual Element                                  |
| ---------------------------------------- | --------------------------------------------------- | ----------------------------------------------- |
| **Research**                             | `from-teal-700 to-slate-800`                        | Stack of journal papers in inverted perspective |
| **Products & Services** (center, taller) | `from-primary-800 via-primary-700 to-secondary-500` | 3D rendered hand model on grid                  |
| **Events**                               | `from-rose-900 to-purple-900`                       | Exhibition booth "Global Events Co."            |

**Layout Note:** Center card uses `md:scale-105` or `md:-mt-8 md:mb-8` to stand out — notice in the design that "Products & Services" is taller than the side cards.

---

### **2.4 Quote Component (Djoko Kuswanto Section)**

```html
<blockquote class="relative">
    <!-- Giant quote mark -->
    <span class="absolute -top-6 -left-2 text-6xl text-secondary-400 font-serif"
        >"</span
    >

    <p class="text-2xl italic text-white/90 leading-relaxed">
        "If you are planning for a year, sow rice; if you are planning for a
        decade, plant trees; if you are planning for a lifetime, educate
        people."
    </p>

    <div class="mt-8 flex items-center gap-4">
        <img class="w-12 h-12 rounded-full ring-2 ring-secondary-400/40" />
        <div>
            <p class="text-white font-semibold">
                Djoko Kuswanto, S.T., M.Biotech.
            </p>
            <p class="text-sm text-secondary-400">
                Teaching Professor Medical Technology Course
            </p>
        </div>
    </div>
</blockquote>
```

---

### **2.5 Team Member Card (Org Chart)**

Profile cards for the organizational hierarchy with simple yet consistent structure.

```html
<div class="flex flex-col items-center">
    <!-- Avatar -->
    <div class="w-24 h-24 rounded-full p-1 bg-white shadow-lg">
        <img class="w-full h-full rounded-full object-cover" />
    </div>

    <!-- Name -->
    <p class="mt-3 font-semibold text-slate-800">Tim Cook</p>

    <!-- Role Badge -->
    <div
        class="mt-2 px-6 py-1.5 rounded-lg bg-primary-700 text-white text-xs font-semibold tracking-wide"
    >
        CEO
    </div>
</div>
```

**Connector Lines:** Use SVG paths or pseudo-elements `::before` with `border-l-2 border-primary-700` to draw hierarchy lines from CEO to 6 subordinates, with **arrowheads** at each endpoint.

---

### **2.6 Footer**

4-column layout with `primary-950` background.

```
[Logo Card]  |  [Contact Info]  |  [Social Icons]  |  [Visitor Stats]
```

- **Logo Card:** White rounded box `bg-white p-4 rounded-xl` containing iDIG logo
- **Contact Info:** `text-sm text-white/80 leading-relaxed`
- **Social Icons:** YouTube + Instagram, `w-8 h-8 text-white/70 hover:text-white`
- **Stats:** Right-aligned, 2 rows (Today: 0 / Total: 45,007)
- **Bottom strip:** `border-t border-white/10 py-4 text-xs text-white/50` — copyright

---

## **PART 3 — LAYOUT & SECTION ANATOMY**

### **3.1 Section Sequence (Top to Bottom)**

```
1. NAV BAR              → fixed, transparent over hero
2. HERO SECTION         → 100vh, full-bleed lab photo
3. ABOUT US             → light-on-dark, 2-column
4. SERVICE CARDS        → 3 cards row
5. SHARING WISDOM       → quote + ECG visual
6. CTA BANNER           → "Explore Our Innovations"
7. ORGANIZATION CHART   → light bg with hexagon pattern
8. FOOTER               → dark navy, 4-column grid
```

---

### **3.2 Section Specifications**

#### **🔹 Section 1: Hero**

- **Container:** `relative h-screen min-h-[800px] w-full overflow-hidden`
- **Background:**
    ```html
    <div
        class="absolute inset-0 bg-cover bg-center"
        style="background-image: url('lab-photo.jpg')"
    ></div>
    <div class="absolute inset-0 bg-primary-900/60 backdrop-blur-[2px]"></div>
    ```
- **Content positioning:** `flex items-center justify-center text-center`
- **Padding:** `pt-24 pb-32` (account for fixed nav)
- **Title hierarchy:**
    - "Welcome to" → `text-4xl md:text-5xl font-medium`
    - "IDIG Laboratory" → `text-6xl md:text-8xl font-extrabold italic mt-2`
- **Description max-width:** `max-w-xl mx-auto text-lg text-white/85 mt-6`
- **CTA:** `mt-10` margin-top from description

---

#### **🔹 Section 2: About Us**

- **Container:** `bg-primary-900 py-24 px-12`
- **Layout:** Grid `grid-cols-1 lg:grid-cols-2 gap-16 max-w-7xl mx-auto`
- **Left Column:**
    - Title: `text-4xl font-bold text-white mb-8` ("About us")
    - Paragraphs: `text-base text-white/85 leading-relaxed text-justify space-y-4`
- **Right Column:**
    - Large iDIG logo, `flex items-center justify-center`
    - Add subtle **sparkle/star icons** around logo (`text-secondary-400 animate-pulse`)

---

#### **🔹 Section 3: Service Cards**

- **Container:** `relative -mt-12 px-12 pb-24` _(negative margin for overlap with About bg)_
- **Grid:** `grid grid-cols-1 md:grid-cols-3 gap-6 max-w-7xl mx-auto`
- **Center Card Elevation:** Second card (Products & Services) uses `md:scale-105 md:-mt-8 md:mb-8` to stand out taller
- **Card Height:** Side cards `h-[480px]`, center card `h-[540px]`

---

#### **🔹 Section 4: Sharing Wisdom Quote**

- **Container:** `relative bg-primary-900 py-24 overflow-hidden`
- **Background Effects:**
    1. **ECG Line:** SVG horizontal line with heartbeat animation behind text (see section 4.2)
    2. **Hexagon Pattern:** Repeating honeycomb on bottom right (`opacity-10`)
- **Layout:** Grid `grid-cols-1 lg:grid-cols-2 gap-12 max-w-7xl mx-auto px-12`
- **Left:** Heading "We believe in the art of **Sharing Wisdom.**"
    - "Sharing Wisdom" uses `text-secondary-400 italic`
- **Right:** Quote block (see 2.4)

---

#### **🔹 Section 5: Innovation CTA Banner**

- **Container:** `relative -my-8 z-10 flex justify-center`
- **Floating button** positioned exactly at the **boundary between Sharing Wisdom and Org Chart sections**, appearing to "float" across two different backgrounds

---

#### **🔹 Section 6: Organization Chart**

- **Container:** `relative bg-surface-base py-24 px-12`
- **Background:** Hexagon honeycomb pattern, `opacity-30`, soft blue tint
- **CEO Position:** `flex justify-center mb-16`
- **Subordinates Row:** `grid grid-cols-6 gap-8 max-w-6xl mx-auto`
- **Connector System:** SVG overlay (`absolute inset-0 pointer-events-none`) with paths from CEO to each subordinate

---

#### **🔹 Section 7: Footer**

- **Container:** `bg-primary-950 pt-16 pb-6 px-12`
- **Grid:** `grid grid-cols-1 md:grid-cols-4 gap-12 max-w-7xl mx-auto`
- **Column dividers:** `border-r border-white/10` between columns (except last)
- **Bottom bar:** `mt-12 pt-6 border-t border-white/10 text-center`

---

### **3.3 Spacing Tokens**

| Token                 | Value | Tailwind | Usage                             |
| --------------------- | ----- | -------- | --------------------------------- |
| `space-section`       | 96px  | `py-24`  | Vertical padding between sections |
| `space-section-tight` | 64px  | `py-16`  | Shorter sections                  |
| `space-content-x`     | 48px  | `px-12`  | Horizontal container padding      |
| `space-card-gap`      | 24px  | `gap-6`  | Gap between cards                 |
| `space-element`       | 16px  | `gap-4`  | Gap between grouped elements      |

---

## **PART 4 — VISUAL EFFECTS**

### **4.1 Hexagon Honeycomb Pattern**

The honeycomb pattern appears in **two locations**: bottom corner of Sharing Wisdom section and as background of Organization Chart section. This reinforces the "molecular / scientific structure" theme.

**Implementation (SVG inline or background-image):**

```css
.honeycomb-bg {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='56' height='100' viewBox='0 0 56 100'%3E%3Cpath d='M28 66L0 50V16l28-16 28 16v34zM28 0L0 16l28 16 28-16z' fill='%2300A8B5' fill-opacity='0.08'/%3E%3C/svg%3E");
    background-size: 56px 100px;
}
```

**Usage variations:**

- **Sharing Wisdom section:** `opacity-15`, hexagon color `secondary-500` over dark navy bg
- **Org Chart section:** `opacity-25`, hexagon color `primary-700` over off-white bg

---

### **4.2 ECG / Heartbeat Line**

Horizontal heartbeat line behind Sharing Wisdom — the most distinctive visual element reinforcing the **HEALTH-TECH** identity.

**SVG Implementation:**

```html
<svg
    class="absolute left-0 right-0 top-1/2 -translate-y-1/2 w-full h-32 opacity-40"
    viewBox="0 0 1200 100"
    preserveAspectRatio="none"
>
    <path
        d="M0,50 L200,50 L220,50 L230,20 L245,80 L260,30 L275,50 L500,50 L520,50 L530,15 L545,85 L560,25 L575,50 L1200,50"
        stroke="url(#ecgGradient)"
        stroke-width="2"
        fill="none"
        stroke-linecap="round"
    />
    <defs>
        <linearGradient id="ecgGradient" x1="0%" x2="100%">
            <stop offset="0%" stop-color="#22D3EE" stop-opacity="0" />
            <stop offset="50%" stop-color="#22D3EE" stop-opacity="1" />
            <stop offset="100%" stop-color="#22D3EE" stop-opacity="0" />
        </linearGradient>
    </defs>
</svg>
```

**Animation (optional but recommended):**

```css
@keyframes heartbeat-pulse {
    0%,
    100% {
        stroke-dashoffset: 0;
        opacity: 0.4;
    }
    50% {
        stroke-dashoffset: -100;
        opacity: 0.7;
    }
}
.ecg-path {
    stroke-dasharray: 1200;
    animation: heartbeat-pulse 4s ease-in-out infinite;
}
```

---

### **4.3 Glow Effects**

#### **A. Hero Button Glow (Cyan Neon)**

```css
box-shadow:
    0 0 20px rgba(34, 211, 238, 0.4),
    0 0 40px rgba(34, 211, 238, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 0.3);
```

#### **B. Hero Title Text Glow**

```css
text-shadow:
    0 0 20px rgba(255, 255, 255, 0.3),
    0 0 40px rgba(34, 211, 238, 0.2);
```

#### **C. Logo Sparkle Effect**

Add 2-3 `✦` (sparkle) icons around About Us logo with `animate-pulse` and staggered delays (`animation-delay: 0s, 0.7s, 1.4s`) for alternating twinkle effect.

---

### **4.4 Glassmorphism (Service Cards Inner Buttons & Nav)**

```css
.glass-element {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
```

Tailwind equivalent: `bg-white/10 backdrop-blur-md border border-white/20`

---

### **4.5 Gradient Overlays**

| Section           | Gradient                                                                    |
| ----------------- | --------------------------------------------------------------------------- |
| **Hero overlay**  | `bg-gradient-to-b from-primary-900/40 via-primary-900/60 to-primary-900/80` |
| **Card Research** | `bg-gradient-to-br from-teal-800 via-slate-700 to-slate-900`                |
| **Card Products** | `bg-gradient-to-br from-primary-800 via-primary-600 to-secondary-500`       |
| **Card Events**   | `bg-gradient-to-br from-rose-900 via-purple-800 to-fuchsia-900`             |

---

### **4.6 Border-Radius Scale**

| Token         | Value  | Tailwind       | Usage                    |
| ------------- | ------ | -------------- | ------------------------ |
| `radius-sm`   | 8px    | `rounded-lg`   | Small badges (CEO label) |
| `radius-md`   | 12px   | `rounded-xl`   | Buttons, inputs          |
| `radius-lg`   | 16px   | `rounded-2xl`  | Hero CTA                 |
| `radius-xl`   | 24px   | `rounded-3xl`  | Service cards            |
| `radius-full` | 9999px | `rounded-full` | Avatars, pill nav        |

---

### **4.7 Shadow Tokens**

```js
boxShadow: {
  'card-soft': '0 10px 40px -10px rgba(3, 16, 38, 0.2)',
  'card-elevated': '0 20px 60px -15px rgba(3, 16, 38, 0.4)',
  'glow-cyan': '0 0 40px rgba(34, 211, 238, 0.5)',
  'glow-cyan-lg': '0 0 80px rgba(34, 211, 238, 0.6)',
  'avatar': '0 4px 20px rgba(0, 66, 109, 0.15)',
}
```

---

## **IMPLEMENTATION NOTES FOR FRONTEND TEAM**

1. **Font Loading:** Use `next/font` (if Next.js) or `@fontsource/plus-jakarta-sans` + `@fontsource/inter` to avoid FOUT.
2. **Background Image Optimization:** Hero photo must be compressed to WebP and served via `next/image` with `priority` flag.
3. **Honeycomb Pattern:** Store as SVG component rather than inline data-URI for better maintainability.
4. **ECG Animation Performance:** Use `transform` and `opacity` only (GPU-accelerated). Avoid animating `stroke-dashoffset` if performance issues arise — use `translate-x` on gradient mask as alternative.
5. **Org Chart Connector Lines:** Consider libraries like `react-archer` or `react-flow` for dynamic lines, especially if structural members change via CMS.
6. **Center Card Elevation:** On mobile (`<md`), reset elevation to flat (`md:scale-105` only) to prevent overlap with stacked cards.
7. **Color Mode:** Currently, the design system provides dark hero/light content only. No dark mode toggle — confirmed with brief.

This design system is ready to serve as the foundation for building Phase 1 (Master Data CRUD) components and the public landing page. The team can directly extend `tailwind.config.js` with the tokens above and begin building a component library in `/components/ui/` following the breakdown provided.
