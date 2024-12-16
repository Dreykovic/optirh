<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('pagination::bootstrap-4');
        Blade::directive('formatDate', function ($expression) {
            return "<?php echo Carbon\Carbon::parse($expression)->locale(app()->getLocale())->isoFormat('DD MMM YYYY HH:mm '); ?>";
});
Blade::directive('formatDateOnly', function ($expression) {
return "<?php echo Carbon\Carbon::parse($expression)->locale(app()->getLocale())->isoFormat('DD MMM YYYY'); ?>";
});
Blade::directive('tempsEcoule', function ($expression) {
return "<?php echo Carbon\Carbon::parse($expression)->locale(app()->getLocale())->diffForHumans([
                'parts' => true,
                'join' => true,
                'short' => true,
            ]); ?>";
});
}
}