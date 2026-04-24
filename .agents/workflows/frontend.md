---
description: Develop minimalist, Notion-style React interfaces and Inertia pages based strictly on established backend data contracts.
---

# Workflow: Frontend Development

**Objective:** To build the React and Inertia-based user interfaces based on the agreed Backend data contracts using a minimalist, Notion-style aesthetic.
**Trigger:** When the backend foundation is ready, or the user specifically requests UI/UX implementations.
**Execution Order:** @frontend -> @integration

**Steps:**

1. **@frontend** creates new `.tsx` files in `resources/js/Pages/` or `Features/` following a Feature-Based architecture.
2. **@frontend** defines strict TypeScript interfaces for the exact props promised by the Backend's `Inertia::render()`.
3. **@frontend** builds the UI structure. **CRITICAL RULE:** Custom `Link` and `Box` components MUST be used as-is and NEVER converted into pure Tailwind utility classes.
4. **@frontend** integrates Inertia's `useForm` for data submission, loading states, and error handling.
5. Once the UI is built and hooked to the Inertia page, **@frontend** passes execution to **@integration** (or @qa) to wire things up.
