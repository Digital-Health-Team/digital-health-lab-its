---
name: IDIG Health Tech
description: Medical Technology Repository & Innovation Hub — Institut Teknologi Sepuluh Nopember
colors:
  midnight-abyss: "#031026"
  institute-navy: "#062E5C"
  laboratory-blue: "#0A3D7A"
  its-deep-blue: "#00426D"
  its-blue-hover: "#0D5A9E"
  biomedical-current: "#00A8B5"
  electric-teal: "#22D3EE"
  teal-light: "#67E8F9"
  teal-mist: "#A5F3FC"
  its-gold: "#FFC72C"
  its-gold-light: "#FCD34D"
  surface-canvas: "#F8F9FA"
  surface-card: "#FAFAFA"
  surface-muted: "#F1F5F9"
  text-heading: "#1E293B"
  text-body: "#475569"
  text-on-dark: "#F8FAFC"
  text-muted-dark: "#94A3B8"
typography:
  display:
    fontFamily: "'Plus Jakarta Sans', sans-serif"
    fontSize: "clamp(3rem, 8vw, 5rem)"
    fontWeight: 800
    lineHeight: 1.05
    letterSpacing: "-0.02em"
  headline:
    fontFamily: "'Plus Jakarta Sans', sans-serif"
    fontSize: "clamp(2rem, 5vw, 3.5rem)"
    fontWeight: 700
    lineHeight: 1.15
    letterSpacing: "-0.01em"
  title:
    fontFamily: "'Plus Jakarta Sans', sans-serif"
    fontSize: "1.5rem"
    fontWeight: 700
    lineHeight: 1.3
  body:
    fontFamily: "'Inter', ui-sans-serif, system-ui, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.625
  label:
    fontFamily: "'Inter', ui-sans-serif, system-ui, sans-serif"
    fontSize: "0.75rem"
    fontWeight: 500
    letterSpacing: "0.05em"
rounded:
  sm: "8px"
  md: "12px"
  lg: "16px"
  xl: "24px"
  full: "9999px"
spacing:
  element: "16px"
  card-gap: "24px"
  content-x: "48px"
  section: "96px"
  section-tight: "64px"
components:
  button-hero:
    backgroundColor: "#22D3EE26"
    textColor: "{colors.text-on-dark}"
    rounded: "{rounded.lg}"
    padding: "16px 48px"
  button-hero-hover:
    backgroundColor: "#22D3EE40"
    textColor: "{colors.text-on-dark}"
    rounded: "{rounded.lg}"
    padding: "16px 48px"
  button-primary:
    backgroundColor: "{colors.biomedical-current}"
    textColor: "{colors.text-on-dark}"
    rounded: "{rounded.md}"
    padding: "16px 40px"
  button-primary-hover:
    backgroundColor: "#00909B"
    textColor: "{colors.text-on-dark}"
    rounded: "{rounded.md}"
    padding: "16px 40px"
  button-signin:
    backgroundColor: "{colors.its-deep-blue}"
    textColor: "{colors.text-on-dark}"
    rounded: "{rounded.md}"
    padding: "10px 24px"
  button-signin-hover:
    backgroundColor: "{colors.its-blue-hover}"
    textColor: "{colors.text-on-dark}"
    rounded: "{rounded.md}"
    padding: "10px 24px"
  nav-pill:
    backgroundColor: "#FFFFFF1A"
    textColor: "{colors.text-on-dark}"
    rounded: "{rounded.full}"
    padding: "8px 8px"
  card-service:
    backgroundColor: "{colors.laboratory-blue}"
    textColor: "{colors.text-on-dark}"
    rounded: "{rounded.xl}"
    padding: "40px 32px"
---

# Design System: IDIG Health Tech

## 1. Overview

**Creative North Star: "The ITS Research Atlas"**

IDIG Health Tech is the canonical record of what Institut Teknologi Sepuluh Nopember's Medical Technology program has built, and is building. The interface carries the same authority as a well-produced atlas: a reference you trust, navigate confidently, and return to. Not a startup landing page chasing conversion. Not a hospital portal cataloguing procedures. An institution speaking for itself through the weight of its work.

The ground state is Institute Midnight, a deep navy that reads as authority, not theater. Biomedical Current (medical teal) is the signal color: it marks live systems and active calls-to-action without collapsing into the generic "healthcare blue" reflex. ITS Gold appears rarely, reserved for moments of institutional distinction. Light surfaces exist in exactly two places: the Organization Chart section and white anchor elements within dark layouts (the footer logo card, the sign-in button background). Everywhere else, the darkness is structural, not decorative.

Motion and texture earn their presence. The ECG heartbeat line exists because the lab works in medical technology; it is a domain reference, not ornament. The hexagonal honeycomb pattern invokes molecular structure and materials science. Every visual effect must clear this test: can a first-year medical engineering student explain why this element is here? If not, it does not belong.

**Key Characteristics:**
- Dark-first: Institute Midnight is the ground state, not a theme option
- Signal hierarchy: teal for active state, gold for distinction, navy for institution
- Domain-referential texture: ECG, honeycomb, and controlled glow each justify their presence
- Bilingual by structure: Indonesian primary, English secondary, both treated as equal
- All motion suppressed under `prefers-reduced-motion`; effects are enhancement, never content

## 2. Colors: The Institute Midnight Palette

Three color families with fixed roles. The families do not substitute for each other.

### Primary

- **Institute Midnight** (`#031026`): The deepest surface. Footer background, deepest section overlays. The color the eye rests in.
- **Institute Navy** (`#062E5C`): Hero overlay, "Sharing Wisdom" section background. The dominant surface color across the dark register.
- **Laboratory Blue** (`#0A3D7A`): Research service card gradient base. Mid-depth structural surfaces.
- **ITS Deep Blue** (`#00426D`): Primary CTA buttons, Sign In button, team role badges. The official ITS brand blue; the institutional accent on dark surfaces.
- **ITS Blue Hover** (`#0D5A9E`): Hover state for ITS Deep Blue surfaces. Same hue axis, lighter.

### Secondary

- **Biomedical Current** (`#00A8B5`): The signal color. Hero CTA glow source, icon backgrounds, secondary CTAs, service card gradient accent. Belongs to active and interactive elements. Never used as a fill on large passive surfaces.
- **Electric Teal** (`#22D3EE`): "Sharing Wisdom" highlight text, ECG line stroke, hero text glow, active nav underline. Lighter and more electric than Biomedical Current. Used for text emphasis and glow effects on dark backgrounds.
- **Teal Light** (`#67E8F9`): Hover glow on teal elements, gradient highlights within components.
- **Teal Mist** (`#A5F3FC`): Subtle borders on the hero CTA button outline, fine structural strokes over dark backgrounds.

### Tertiary

- **ITS Gold** (`#FFC72C`): Secondary CTAs, status badges, institutional callouts. Appears on 5% or less of any screen. Its rarity is the point.
- **ITS Gold Light** (`#FCD34D`): Notification highlights, "Pending" status indicators. A softer variant for secondary gold contexts.

### Neutral

- **Surface Canvas** (`#F8F9FA`): Off-white, used only in the Organization Chart section. Never as a page background.
- **Surface Card** (`#FAFAFA`): White card containers, modals, footer logo card.
- **Surface Muted** (`#F1F5F9`): Inactive navigation items, dividers within light sections.
- **Text Heading** (`#1E293B`): Headings on light backgrounds (Organization Chart section only).
- **Text Body** (`#475569`): Body text on light backgrounds. Slightly lighter than heading for hierarchy without size change.
- **Text On Dark** (`#F8FAFC`): All text on Institute Navy and deeper surfaces. Not pure white.
- **Text Muted Dark** (`#94A3B8`): Footer secondary text, captions, and supporting information on dark surfaces.

### Named Rules

**The Institute Midnight Rule.** Dark surfaces are the ground state of this system. Light surfaces appear in exactly two structural contexts: the Organization Chart section and white anchor elements within dark layouts. Never apply light surfaces speculatively. If the reason is not structural, the answer is no.

**The Signal Hierarchy Rule.** Teal marks active systems and live calls-to-action. Gold marks institutional distinction. Navy is the institution itself. Do not reassign these roles. A gold primary button breaks the scarcity contract; a teal role badge drains meaning from the signal color.

**The Anti-Reflex Rule.** This palette reads deep navy plus medical teal, which overlaps the healthcare category reflex. The distinction is in the specifics: the navy (#031026) is darker than any clinical palette, the ECG motif is specific to medical engineering rather than generic wellness, and ITS Gold anchors the system to one institution. If any new design choice reads as "healthcare website," reject it and trace which element made it generic.

## 3. Typography

**Display Font:** Plus Jakarta Sans (with `sans-serif` fallback)
**Body Font:** Inter (with `ui-sans-serif, system-ui, sans-serif` fallback)

**Character:** Plus Jakarta Sans reads as institutional and contemporary without the startup affect of Circular or the anonymous neutrality of Helvetica Neue. At extrabold italic for the hero display, it carries the same assurance as a well-set academic title. Inter handles body text and UI labels with quiet precision; it never competes with the content it carries.

### Hierarchy

- **Display** (800, italic, clamp(3rem, 8vw, 5rem), 1.05 line-height, -0.02em letter-spacing): Hero title only. "IDIG Laboratory." Always italic at this weight. This is the single typographic gesture that separates the hero from every other heading on the page.
- **Headline** (700, clamp(2rem, 5vw, 3.5rem), 1.15 line-height, -0.01em letter-spacing): Section titles. "About us." "We believe in the art of Sharing Wisdom." Not italic.
- **Title** (700, 1.5rem, 1.3 line-height): Card titles ("Research," "Products & Services," "Events"), section sub-headings, named positions in the Org Chart.
- **Body** (400, 1rem, 1.625 line-height): Paragraph text, card descriptions, about-section copy. Maximum 72ch line length on light surfaces. On dark surfaces, columns naturally compress to approximately 60ch.
- **Label** (500, 0.75rem, 1.5 line-height, 0.05em letter-spacing): Role badges, navigation item text, copyright, status chips. Uppercase only for role badges; sentence case elsewhere.

### Named Rules

**The Italic Display Rule.** The display scale is always set italic at extrabold weight. This is not a variant: it is the only treatment for hero-level text. Do not set display text in roman at this size; the visual authority collapses.

**The Scale Separation Rule.** Minimum 1.3 ratio between adjacent hierarchy steps on the same screen. If Headline and Title feel too close together, drop Title to 1.25rem before adding any other visual differentiation (color, weight, decoration).

## 4. Elevation

A hybrid model: structural shadows for surfaces that need to separate from their background, and glow for interactive elements that signal their responsiveness. Flat surfaces are the default. Depth is earned, not applied.

### Shadow Vocabulary

- **card-soft** (`0 10px 40px -10px rgba(3, 16, 38, 0.20)`): Default elevation for service cards. Diffuse and barely perceptible against dark backgrounds; its function is separation, not floating.
- **card-elevated** (`0 20px 60px -15px rgba(3, 16, 38, 0.40)`): Hover state and the featured center service card. Longer vertical offset, larger spread.
- **glow-cyan** (`0 0 40px rgba(34, 211, 238, 0.50)`): Default state of the hero CTA button. The signature interactive shadow of this system.
- **glow-cyan-lg** (`0 0 80px rgba(34, 211, 238, 0.60)`): Hover state of the hero CTA. Larger spread, slightly more opaque.
- **avatar** (`0 4px 20px rgba(0, 66, 109, 0.15)`): Team member avatar rings in the Organization Chart. Tight, institutional.

### Named Rules

**The Glow-as-Signal Rule.** Cyan glow belongs to the hero CTA and responsive interactive elements on the deepest surfaces. It does not appear on decorative elements, passive cards, or section dividers. If an element glows, it responds. If it does not respond, it does not glow.

**The Flat-by-Default Rule.** Surfaces are flat at rest. `card-soft` applies to service cards as structural separation, not as a hover state. `card-elevated` only appears in response to state change or as the explicit treatment for the center featured card.

## 5. Components

### Navigation Bar

Fixed, transparent over the hero section. Transitions to `bg-white/10 backdrop-blur-md` with scroll.

- **Logo:** White iDIG wordmark, 40px height, left-aligned.
- **Center Nav Pill:** `bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-2 py-2`. Items: `px-6 py-2 text-sm font-medium text-white/90`. Active item: `border-b-2 border-white`. This is one of two justified uses of glassmorphism in this system; it floats over the hero photo without blocking it.
- **Sign In Button:** ITS Deep Blue (`#00426D`) fill, white text, 12px radius, `px-6 py-2.5`, with `border border-electric-teal/30` for a faint institutional glow. Hover: ITS Blue Hover (`#0D5A9E`).

### Buttons

- **Hero CTA "Explore":** Glass panel over the hero image. `bg-electric-teal/15 backdrop-blur-sm border border-teal-mist/60 rounded-2xl px-12 py-4`. Glow: `glow-cyan` at rest, `glow-cyan-lg` on hover. Font: Plus Jakarta Sans, 600, 1.125rem. Text shadow carries a faint white-cyan warmth (`0 0 20px rgba(255,255,255,0.3), 0 0 40px rgba(34,211,238,0.2)`).
- **Primary CTA "Explore Our Innovations":** Biomedical Current fill, white text, 16px radius, `px-10 py-4`, arrow icon inline at 20px. Hover: `#00909B`, `translateY(-2px)`. Shadow: `0 8px 24px rgba(0,168,181,0.3)`.
- **Card Inner "Explore":** `bg-white/20 backdrop-blur-sm border border-white/30 rounded-full px-8 py-2.5`. Text: white, 0.875rem. Hover: `bg-white/30`. Used only inside gradient service cards.

**The Glow Button Rule.** Only the hero CTA carries a cyan glow shadow. Every other button uses conventional shadow or none. More than one glowing element per viewport dilutes the hero CTA's signal value to zero.

### Service Cards

Three cards displayed side by side. Center card is deliberately taller and scaled up.

- **Shape:** `rounded-3xl` (24px), `overflow-hidden`
- **Layout:** `flex flex-col items-center text-center px-8 py-10`
- **Side card height:** 480px. Center card: 540px, `md:scale-105 md:-mt-8 md:mb-8`
- **Gradient backgrounds:**
  - Research: `from-teal-700 to-slate-800` (cool, editorial)
  - Products & Services (center): `from-laboratory-blue via-its-deep-blue to-biomedical-current`
  - Events: `from-rose-900 to-purple-900` (distinct; pulls outside the primary palette)
- **Shadow:** `card-soft` on side cards, `card-elevated` on center card

**The Three-Card Rule.** Each service card uses a visually distinct gradient. They are asymmetric by design; the center card's height and scale carry structural information about priority. Do not homogenize them.

### Quote Block

Used in the "Sharing Wisdom" section against `institute-navy` background.

- Opening quote mark: `text-6xl text-electric-teal font-serif`, `absolute -top-6 -left-2`
- Quote text: `text-2xl italic text-white/90 leading-relaxed`, max 65ch
- Attribution: 48px avatar with `rounded-full ring-2 ring-electric-teal/40`, name in 600 weight white, role in `text-sm text-electric-teal`

### Organization Chart

Light surface section. Background: `surface-canvas` with hexagonal honeycomb SVG pattern at 25% opacity.

- **CEO avatar:** 96px `rounded-full p-1 bg-white shadow-avatar`
- **Role badge:** `rounded-lg bg-its-deep-blue text-white text-xs font-semibold px-6 py-1.5 tracking-wide uppercase`
- **Subordinate row:** Six equal columns, SVG connector paths from CEO downward, `stroke: #00426D, stroke-width: 2`

### Signature Component: ECG Heartbeat Line

The most distinctive visual element. Appears in the "Sharing Wisdom" section only.

- Full-width SVG, 128px height, centered vertically in the section, `position: absolute`, `pointer-events: none`
- Stroke gradient: transparent at edges, Electric Teal at center (via `linearGradient`)
- Animation: `stroke-dasharray: 1200; animation: heartbeat-pulse 4s ease-in-out infinite` cycling opacity between 0.4 and 0.7
- Reduced motion: suppress animation entirely; render at fixed 0.3 opacity
- **Appears in this section only.** Do not reuse in other sections. Do not apply a similar pulse to any other element.

## 6. Do's and Don'ts

### Do:

- **Do** use `midnight-abyss` (#031026) for the deepest surfaces: footer background and modal overlay. Nowhere else.
- **Do** tint every neutral toward the brand hue. Use `#F8FAFC` (not `#FFFFFF`) for text on dark; use `#F8F9FA` (not `#F5F5F5`) for the surface canvas.
- **Do** keep body copy at 65-72ch maximum on light surfaces. Uncapped on dark, where column structure handles this naturally.
- **Do** apply the ECG animation only in the "Sharing Wisdom" section and suppress it under `prefers-reduced-motion: reduce`.
- **Do** set hero display text in Plus Jakarta Sans at extrabold italic always. This is the identity gesture of the hero.
- **Do** reserve Electric Teal (`#22D3EE`) for text emphasis and glow on dark surfaces only. It is not a fill color.
- **Do** treat ITS Gold as a scarcity color: secondary CTAs, status badges, institutional callouts on 5% or less of any screen.
- **Do** ease all transitions with `cubic-bezier(0.25, 1, 0.5, 1)`. No bounce, no elastic, no linear.
- **Do** pair every color-coded status or badge with a text label. Color alone is prohibited for communicating state.
- **Do** write Bahasa Indonesia copy as the primary layer, with English as structural parallel, not a fallback.

### Don't:

- **Don't** produce generic AI-generated patterns: icon grids, rainbow-gradient blobs, "innovation" stock illustration, or identical rounded cards repeated across a section. PRODUCT.md names these explicitly. Reject them at the sketch stage, not after implementation.
- **Don't** use glassmorphism outside the navigation pill and card inner buttons. These are the two justified uses. Any other glassmorphism element is decoration without domain warrant.
- **Don't** apply gradient text (`background-clip: text` with a background-image gradient). Color emphasis in this system comes from solid teal or gold on a dark background, never from text gradients.
- **Don't** use side-stripe borders (border-left or border-right greater than 1px as a colored accent). Use full borders, background tints, or leading icons instead.
- **Don't** apply the hero-metric template: big number, small label, supporting stats, gradient accent. The system's identity is the archive and the capabilities, not dashboard metrics.
- **Don't** produce sterile hospital whites with teal accents. The system distinguishes itself through the depth of the navy, the specificity of the ECG motif, and ITS Gold. Any design that could describe a clinic has lost the brief.
- **Don't** import Vercel / Linear / Stripe aesthetic cues: monospace labels, hairline grid overlays, neon-on-true-black. This system is institution-rooted, not tech-startup.
- **Don't** apply the center card elevation treatment (`scale-105 / -mt-8`) to more than one card. Visual hierarchy requires a single dominant element; two elevated cards produce no hierarchy at all.
- **Don't** animate CSS layout properties. Transitions use `opacity` and `transform` (translate, scale) only.
- **Don't** use `#000000` or `#ffffff` anywhere. Every value is tinted.
