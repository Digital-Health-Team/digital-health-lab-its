---
description: Investigate, diagnose, and fix reported bugs, errors, or visual glitches.
---

# Workflow: Bug Fixing

**Trigger:** When the user reports an error, bug, or unexpected behavior.
**Execution Order:** @qa -> @backend OR @frontend -> @qa

**Steps:**

1. **@qa** analyzes the bug report, audits the stack trace, and writes a brief, direct fix-plan in `.artifacts/technical_spec_review.md` (Approval is skipped for rapid hotfixes).
2. **@qa** delegates the task to **@backend** (if it's a logic/database issue) OR **@frontend** (if it's a UI/state issue).
3. The assigned specialist (**@backend** or **@frontend**) executes the fix-plan exactly, ensuring no regression in the Hybrid stack.
4. **@qa** verifies the fix, ensures Vite builds correctly, audits any linting fixes, generates a summary in `.artifacts/logs/`, and notifies the user.
