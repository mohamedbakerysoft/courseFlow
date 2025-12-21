<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use App\Policies\CoursePolicy;
use App\Policies\LessonPolicy;
use App\Policies\PaymentPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        if (app()->environment(['local', 'demo']) && config('database.default') === 'sqlite') {
            $databasePath = config('database.connections.sqlite.database');
            if ($databasePath && $databasePath !== ':memory:' && ! file_exists($databasePath)) {
                @touch($databasePath);
            }
        }

        Gate::policy(Course::class, CoursePolicy::class);
        Gate::policy(Lesson::class, LessonPolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
        Gate::policy(User::class, \App\Policies\UserPolicy::class);

        $defaults = [
            'primary' => '#4F46E5',
            'secondary' => '#334155',
            'accent' => '#10B981',
            'bg' => '#F8FAFC',
            'text' => '#0F172A',
            'text_muted' => '#64748B',
            'primary_hover' => '#4338CA',
        ];
        $theme = $defaults;
        try {
            foreach (['theme.primary' => 'primary', 'theme.secondary' => 'secondary', 'theme.accent' => 'accent'] as $key => $map) {
                $row = Setting::query()->where('key', $key)->first();
                if ($row && is_string($row->value) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $row->value)) {
                    $theme[$map] = $row->value;
                }
            }
        } catch (\Throwable $e) {
            $theme = $defaults;
        }
        View::share('theme', $theme);
    }
}
