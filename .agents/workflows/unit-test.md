---
description: Write and execute Pest tests to guarantee application stability, prevent regressions, and secure business logic.
---

# Workflow: Unit & Feature Testing

**Objective:** To guarantee application stability, prevent regressions, and secure business logic before deployment.
**Trigger:** After a feature is fully integrated, or when the user explicitly requests test coverage.
**Execution Order:** @qa -> @backend (if fixes needed) -> (Wait for User)

**Steps:**

1. **@qa** writes functional and unit tests for the Backend logic exclusively using the Pest framework.
2. **@qa** ensures test coverage for both Happy Paths (successful execution) and Sad Paths (validation errors, unauthorized access, wrong roles).
3. **@qa** utilizes the `RefreshDatabase` trait to ensure a clean database state for every test case.
4. If a test fails, **@qa** flags **@backend** to fix the logic. If all pass, **@qa** writes the test report into `.artifacts/logs/` and notifies the user.
