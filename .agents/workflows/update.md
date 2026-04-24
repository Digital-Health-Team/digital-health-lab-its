---
description: Perform minor fixes or changes to existing code to improve performance and adhere to Action/Feature-Based architecture without altering UI/UX.
---

# Workflow: Minor Update & Refactor

**Objective:** To perform minor bug fixes, dependency updates, UI tweaks, or code refactoring without dismantling the core architecture.
**Trigger:** When the user reports a bug, requests a minor visual tweak, or asks for code cleanup on an existing feature.
**Execution Order:** @frontend / @backend -> @qa -> (Wait for User)

**Steps:**

1. The assigned specialist (**@frontend** or **@backend**, depending on the domain) analyzes the existing code and identifies the bug or optimization opportunity.
2. The specialist applies the changes adhering to Clean Code principles, modern PHP 8.4 features, or React best practices.
3. The specialist ensures the refactoring does not break other dependent components or alter the data contract unexpectedly.
4. Execution is handed over to **@qa** to run existing Pest tests or verify UI integrity before finalizing the task with the user.
