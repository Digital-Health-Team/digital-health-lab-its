---
description: Execute a complete development cycle to add a new feature, page, or component across the Hybrid Stack.
---

# Workflow: Feature Development

**Trigger:** When the user asks to add a new feature, page, or API.
**Execution Order:** @pm -> (Wait for User) -> @backend -> @frontend -> @qa

**Steps:**

1. **@pm** analyzes the request, decides the routing (Livewire vs Inertia), and drafts the backend Action architecture and frontend state structure in `.artifacts/technical_spec_review.md`.
2. **@pm** explicitly pauses and asks for user approval.
3. Upon approval, **@backend** implements the data foundation, migrations, and logic inside `app/Actions/`, ensuring strict DTO usage.
4. Once the backend endpoint is ready, **@frontend** builds the isolated React Features/Pages based strictly on the backend data contract.
5. **@qa** audits the code for TypeScript/PHP errors, missing imports, verifies integration, and writes the execution log into `.artifacts/logs/`.
