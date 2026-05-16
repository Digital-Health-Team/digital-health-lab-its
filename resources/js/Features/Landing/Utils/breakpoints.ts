export const DESKTOP_MIN_WIDTH = 768;
export const MEDIA_DESKTOP = `(min-width: ${DESKTOP_MIN_WIDTH}px) and (prefers-reduced-motion: no-preference)`;
export const MEDIA_MOBILE = `(max-width: ${DESKTOP_MIN_WIDTH - 1}px), (prefers-reduced-motion: reduce)`;
export const MEDIA_MOBILE_MOTION_OK = `(max-width: ${DESKTOP_MIN_WIDTH - 1}px) and (prefers-reduced-motion: no-preference)`;
