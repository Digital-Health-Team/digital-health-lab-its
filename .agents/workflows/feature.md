---
description: Execute a complete development cycle to add a new feature, page, or component across the Hybrid Stack.
---

# Workflow: Feature Development

**Trigger:** When the user asks to add a new feature, page, or API.
**Execution Order:** @pm -> (Wait for User) -> @developer -> @qa

**Steps:**

1. **@pm** analyzes the request, decides the routing (Livewire vs Inertia), and drafts the backend Action architecture and frontend state structure in `.artifacts/technical_spec_review.md`.
2. **@pm** explicitly pauses and asks for user approval.
3. Upon approval, **@developer** implements the feature based on the spec, ensuring strict separation of concerns (Actions/DTOs and isolated React Features).
4. **@qa** audits the code for TypeScript/PHP errors, missing imports, and architectural violations.
5. **@qa** writes the execution log into `.artifacts/logs/` and hands it back to the user.
