<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        if (! app()->runningInConsole()) {
            Paginator::useBootstrapFive();

            Schema::defaultStringLength(191);

            Blade::directive('money', function ($number = 0) {
                return "<?php echo number_format($number, 2, ',', '.'); ?>";
            });

            // $minutes = app()->isProduction() ? 60 * 1 : 5 * 1;

            $appSetting = Cache::rememberForever('appSetting', function () {
                try {
                    return DB::table('app_settings')->first();
                } catch (\Exception $ex) {
                    return collect();
                }
            });

            View::share('appSetting', $appSetting);
        }
    }
}
