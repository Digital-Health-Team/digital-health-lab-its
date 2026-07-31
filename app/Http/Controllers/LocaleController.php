<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Switch the active locale for the current session (and persist it on the
     * user, when signed in). SetLocale middleware reads both on the next request.
     */
    public function update(Request $request)
    {
        $locale = $request->validate([
            'locale' => 'required|in:en,id',
        ])['locale'];

        $request->session()->put('locale', $locale);
        $request->user()?->update(['locale' => $locale]);

        return back();
    }
}
