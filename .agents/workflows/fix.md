---
description: Investigate, diagnose, and fix reported bugs, errors, or visual glitches.
---

# Workflow: Bug Fixing

**Trigger:** When the user reports an error, bug, or unexpected behavior.
**Execution Order:** @qa -> @developer -> @qa

**Steps:**

1. **@qa** analyzes the bug report, audits the stack trace, and writes a brief, direct fix-plan in `.artifacts/technical_spec_review.md` (Approval is skipped for rapid hotfixes unless architecture changes are needed).
2. **@developer** executes the fix-plan exactly, ensuring no regression in the Hybrid stack.
3. **@qa** verifies the fix, ensures Vite builds correctly, and audits any linting fixes.
4. **@qa** generates a summary in `.artifacts/logs/` and notifies the user.
