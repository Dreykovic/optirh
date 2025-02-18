<?php

namespace App\Providers;

use App\Models\Employee;
use App\Observers\EmployeeObserver;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
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
        // Tu peux enregistrer ici des services globaux si nécessaire
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configuration par défaut pour la pagination avec Bootstrap 4
        Paginator::defaultView('pagination::bootstrap-4');
        ResetPassword::toMailUsing(function ($user, $token) {
            return (new MailMessage())
                ->view('emails.reset_password', ['resetLink' => url(config('app.url').route('password.reset', $token, false))])
                ->subject(__('Réinitialisation de mot de passe'));
        });
        // Directives Blade personnalisées
        $this->registerBladeDirectives();
        Employee::observe(EmployeeObserver::class);
    }

    /**
     * Enregistre les directives Blade personnalisées.
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive('formatDate', function ($expression) {
            return "<?php echo \Carbon\Carbon::parse($expression)->locale(app()->getLocale())->isoFormat('DD MMM YYYY HH:mm'); ?>";
        });

        Blade::directive('formatDateOnly', function ($expression) {
            return "<?php echo \Carbon\Carbon::parse($expression)->locale(app()->getLocale())->isoFormat('DD MMM YYYY'); ?>";
        });

        Blade::directive('tempsEcoule', function ($expression) {
            return "<?php echo \Carbon\Carbon::parse($expression)->locale(app()->getLocale())->diffForHumans([
                'parts' => true,
                'join' => true,
                'short' => true,
            ]); ?>";
        });

        Blade::directive('dayOfWeek', function ($expression) {
            return "<?php echo \Carbon\Carbon::parse($expression)->locale(app()->getLocale())->isoFormat('dddd'); ?>";
        });

        Blade::directive('formatPermission', function ($expression) {
            return "<?php echo App\Providers\AppServiceProvider::formatPermission($expression); ?>";
        });
    }

    /**
     * Formate une permission en remplaçant les tirets par des espaces.
     */
    public static function formatPermission(string $string): string
    {
        return str_replace('-', ' ', $string);
    }
}
