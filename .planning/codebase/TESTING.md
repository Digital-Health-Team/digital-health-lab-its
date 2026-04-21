# Testing

## Frameworks & Tooling
- **Pest PHP 4**: The primary testing framework. Replaces traditional PHPUnit syntax with a more expressive closure-based setup (`it()`, `test()`).
- **PHPUnit**: Underlying foundation for Pest.
- **Factories / Seeders**: Extensive usage encouraged for database state generation.

## Conventions
- **Feature Tests**: Located in `tests/Feature`. Should test HTTP routes, Livewire component interactions, and Inertia responses.
- **Unit Tests**: Located in `tests/Unit`. Used for standalone classes/actions.
- **Code Formatting**: Ensure `pint --test` passes before checking into CI.
- **Running Tests**: Run via `php artisan test` or `./vendor/bin/pest`.
