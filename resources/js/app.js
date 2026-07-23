/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

// ── Role-based guided tours (driver.js) ──────────────────────────────────
import 'driver.js/dist/driver.css';
import { startAdminTour, maybeAutoStart } from './tours/adminTours';

// Exposed so the navbar "Tutorial" button can replay the tour on demand.
window.startRoleTour = () => {
    const ctx = window.__TOUR_CONTEXT__ || {};
    startAdminTour(ctx.role, ctx.locale);
};

// Auto-start once per role on first visit to that role's dashboard.
const bootTour = () => {
    const ctx = window.__TOUR_CONTEXT__ || {};
    if (ctx.role) maybeAutoStart(ctx.role, ctx.locale);
};

document.addEventListener('DOMContentLoaded', bootTour);
document.addEventListener('livewire:navigated', bootTour);
