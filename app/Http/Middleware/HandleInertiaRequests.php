<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'email_verified_at' => $request->user()->email_verified_at,
                    'avatar' => $request->user()->profile_photo
                        ? Storage::disk('public')->url($request->user()->profile_photo)
                        : null,
                    'roles' => $request->user()->loadMissing('roles')->roles->pluck('name')->toArray(),
                    'active_role' => $request->user()->activeRoleName(),
                ] : null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'showWelcome' => fn () => (bool) $request->session()->get('show_welcome', false),
            ],
            'locale' => app()->getLocale(),
            // Keys ARE the English source strings and t() falls back to the key, so the
            // fallback locale needs no payload at all — lang/en.json is a pure identity map.
            // ponytail: no cache — a ~45KB json_decode is ~0.2ms and caching only buys stale-copy bugs in dev.
            'translations' => fn () => app()->getLocale() === config('app.fallback_locale')
                ? (object) []
                : File::json(lang_path(app()->getLocale().'.json')),
        ];
    }
}
