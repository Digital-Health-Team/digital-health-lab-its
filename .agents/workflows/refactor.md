---
description: Audit and refactor existing code to improve performance and adhere to Action/Feature-Based architecture without altering UI/UX.
---

# Workflow: Code Refactoring

**Trigger:** When the user asks to clean up code, extract Actions, or reorganize React Features.
**Execution Order:** @pm -> (Wait for User) -> @backend AND/OR @frontend -> @qa

**Steps:**

1. **@pm** identifies architectural debt (e.g., Fat controllers, messy React props) and writes a refactoring strategy in `.artifacts/technical_spec_review.md`.
2. **@pm** pauses for user approval.
3. Upon approval, **@backend** meticulously executes backend cleanup (moving logic to `app/Actions/`), while **@frontend** handles React component extraction (`Features/`).
4. **@qa** aggressively audits to ensure zero changes to external functionality or UI behavior.
5. **@qa** writes the change log into `.artifacts/logs/` and completes the cycle.
