<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureRateLimiting();
    }

    /**
     * The `chatbot` limiter guards POST /chatbot/ask.
     *
     * It exists to protect the Gemini daily quota rather than the web server, which is why
     * the numbers live in config/gemini.php: the free-tier RPD differs per project, and
     * lowering the limits must not need a code change.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('chatbot', fn (Request $request): Limit => $request->user()
            ? Limit::perMinute((int) config('gemini.throttle.user'))->by('chatbot:'.$request->user()->id)
            // Guests are keyed by IP, which everyone behind one campus NAT shares. They get
            // the same budget as signed-in users because the chatbot is a public tool, and a
            // tighter guest limit would throttle a room of visitors, not an abuser.
            : Limit::perMinute((int) config('gemini.throttle.guest'))->by('chatbot:'.$request->ip()));
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(
            fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );

        Relation::morphMap([
            'revision' => 'App\Models\RevisionThread',
            'report' => 'App\Models\JobdeskReport',
            // 'jobdesk' => 'App\Models\Jobdesk', // Jika jobdesk punya attachment langsung
        ]);
    }
}
