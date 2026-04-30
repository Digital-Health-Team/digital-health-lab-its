---
description: Wire frontend UI with backend endpoints to ensure type-safe props, correct routing, and seamless error handling.
---

# Workflow: Integration & Wiring

**Objective:** To ensure a seamless, type-safe connection between frontend interfaces and backend endpoints with no data loss or routing errors.
**Trigger:** When both frontend and backend implementations for a specific feature are completed.
**Execution Order:** @qa -> (Wait for User)

**Steps:**

1. **@qa** verifies that the properties sent from the PHP `Inertia::render()` perfectly match the TypeScript interfaces defined in the React component.
2. **@qa** checks all `route('name')` calls in the frontend to ensure they match actual named routes in Laravel's `web.php`.
3. **@qa** simulates form submissions to validate that Laravel `FormRequest` validation errors are successfully caught and displayed by Inertia's error props in the UI.
4. **@qa** resolves any mismatches, writes the integration log into `.artifacts/logs/`, and reports the final status back to the user.
