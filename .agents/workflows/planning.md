---
description: Analyze requirements, design database schemas, and establish strict data contracts between Backend and Frontend before execution.
---

# Workflow: Planning

**Objective:** To define feature specifications, design database architecture, and establish strict data contracts between Backend and Frontend.
**Trigger:** When the user requests a new complex feature, database setup, or major system change.
**Execution Order:** @pm -> (Wait for User) -> @backend

**Steps:**

1. **@pm** analyzes the user request and defines the detailed feature requirements.
2. **@pm** designs the database schema (drafting tables and relationships) and defines the Inertia data contract (the exact JSON/Array structure Laravel will send to React).
3. **@pm** drafts the execution plan and divides the tasks into backend and frontend tickets inside `.artifacts/technical_spec_review.md`.
4. **@pm** explicitly pauses and asks for user approval on the spec.
5. Upon approval, **@pm** hands over the execution to **@backend** to begin the data foundation.
