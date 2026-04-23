# Phase 1: Hybrid Architecture & Foundation - Context

**Gathered:** 2026-04-23
**Status:** Ready for planning
**Source:** PRD and Initial Discussion

<domain>
## Phase Boundary

Establish the base application with React 19 + Inertia v3 for the public frontend and Livewire for the admin backend.
</domain>

<decisions>
## Implementation Decisions

### Frontend Architecture
- **Public Frontend:** Must use React 19 + Inertia.js v3.
- **Admin Frontend:** Uses existing Livewire v3 + Alpine.js.
- **Styling:** Tailwind CSS + Hero UI for public frontend.
- **Routing:** Laravel Wayfinder for strict, type-safe communication between frontend and backend.

### Initialization Tasks
- Set up React 19 + Inertia.js v3 on `GET /`.
- Configure Vite for building React alongside Livewire.
- Set up root layout structure and basic navigation for the public side.

### the agent's Discretion
- The exact structure of the `resources/js/Pages` and component hierarchy.
- Integration of Hero UI theme settings.
- Vite build configurations for multiple entry points (`app.js` and `app.tsx`).

</decisions>

<canonical_refs>
## Canonical References

**Downstream agents MUST read these before planning or implementing.**

### Project Specs
- `.artifacts/technical_spec_review.md` — Detailed instructions on dependency installation and Vite config.
- `.agents/app/product_requirements.md` — Overall product requirements.

</canonical_refs>

<specifics>
## Specific Ideas
- React 19 with Inertia v3 requires setting up a new Blade component (`app.tsx` entry) while preserving the Livewire (`app.js`) flow.
</specifics>

<deferred>
## Deferred Ideas
- Masonry Product Grid (Phase 2).
- React Three Fiber integration (Phase 2).
</deferred>

---

*Phase: 01-hybrid-architecture-and-foundation*
*Context gathered: 2026-04-23*
