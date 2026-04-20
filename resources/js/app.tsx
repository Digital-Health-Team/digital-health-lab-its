import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot, hydrateRoot } from 'react-dom/client';

const appName = import.meta.env.VITE_APP_NAME || 'ITS Medical Technology';

createInertiaApp({
  title: (title) => (title ? `${title} - ${appName}` : appName),
  resolve: (name) =>
    resolvePageComponent(
      `./pages/${name}.tsx`,
      import.meta.glob('./pages/*.tsx'),
    ) as any,
  progress: {
    color: '#4B5563',
  },
  setup({ el, App, props }) {
    if (import.meta.env.SSR) {
      hydrateRoot(
        el!,
        <App {...props} />,
      );

      return;
    }

    createRoot(el!).render(
      <App {...props} />,
    );
  },
});
