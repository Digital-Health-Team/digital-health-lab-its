---
description: Build secure data foundations and action-oriented business logic using PHP 8.4 and Laravel without handling UI.
---

# Workflow: Backend Development

**Objective:** To build the secure data foundation and action-oriented business logic using PHP 8.4 and Laravel.
**Trigger:** When a technical spec is approved, or the user explicitly asks for backend API/endpoint implementation.
**Execution Order:** @backend -> @frontend

**Steps:**

1. **@backend** creates or updates database Migrations and Eloquent Models, ensuring relationships and transactions are correctly set.
2. **@backend** creates dedicated `FormRequest` classes for strict validation rules.
3. **@backend** writes the business logic inside single-purpose Action classes (`app/Actions/`) and Controllers, keeping Controllers strictly for routing and returning `Inertia::render()`.
4. **@backend** registers the new routes in `routes/web.php` and applies the correct role middleware.
5. Once the endpoint is ready and returning the correct data structure, **@backend** passes the execution to **@frontend**.
