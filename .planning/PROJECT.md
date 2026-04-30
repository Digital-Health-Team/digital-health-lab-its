# ITS Medical Technology Digital Repository & Innovation Hub

## What This Is
An interactive web platform serving a dual purpose: a digital repository for archiving student innovations (open-source archives, applications, 3D files) and a custom e-commerce platform for 3D printing/design services.

## Core Value
Bridging the innovative works of ITS Medical Technology students with the general public and industry by centralizing data, providing interactive public access, and creating measurable monetization channels.

## Requirements

### Validated
- ✓ Laravel Backend Core
- ✓ Livewire Admin Frontend Setup
- ✓ React 19 + Inertia v3 Public Frontend Setup

### Active
- [ ] **Masonry Product Grid:** Responsive catalog for innovation discovery.
- [ ] **Interactive 3D Viewer:** React Three Fiber integration with web-optimized models and loading strategies for heavy files.
- [ ] **Polymorphic File Handling:** Support downloads for open-source attachments (.stl, .obj).
- [ ] **Client Ordering Flow:** Custom 3D printing requests linked directly to WhatsApp for negotiation.
- [ ] **Manual Payment & Invoicing:** Admin calculates pricing ranges, issues invoice, and verifies manual transfer uploads.
- [ ] **Inventory & Progress Tracking:** Real-time stock deduction and visual service progress indicators.
- [ ] **Creator Upload Flow:** Multi-step upload process (metadata, thumbnail, raw files via S3 pre-signed URLs).
- [ ] **Admin Moderation:** Approval/rejection workflow for student publications.

### Out of Scope
- [ ] Integrated on-site chat room (Using WhatsApp).
- [ ] Automated Payment Gateway integration (Using manual bank transfers).

## Key Decisions

| Decision | Rationale | Outcome |
|----------|-----------|---------|
| WhatsApp Negotiation | Reduces scope complexity while utilizing a ubiquitous communication channel. | — Pending |
| Manual Payments | Simplifies initial launch requirements without relying on third-party gateways. | — Pending |
| Hybrid 3D Loading | Prevents browser crashes by combining optimization with robust loading strategies. | — Pending |
| Admin-Decided Ranged Pricing | Provides flexibility to calculate accurate costs based on slicer software estimates. | — Pending |

---
*Last updated: 2026-04-23 after initialization*

## Evolution

This document evolves at phase transitions and milestone boundaries.

**After each phase transition** (via `/gsd-transition`):
1. Requirements invalidated? → Move to Out of Scope with reason
2. Requirements validated? → Move to Validated with phase reference
3. New requirements emerged? → Add to Active
4. Decisions to log? → Add to Key Decisions
5. "What This Is" still accurate? → Update if drifted

**After each milestone** (via `/gsd-complete-milestone`):
1. Full review of all sections
2. Core Value check — still the right priority?
3. Audit Out of Scope — reasons still valid?
4. Update Context with current state
