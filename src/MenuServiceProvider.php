<?php

namespace MeaCms\Menu;

use MeaCms\Menu\Interfaces\Repositories\PictureRepositoryInterface;
use MeaCms\Menu\Repositories\PictureRepository;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MenuRegistry::class, fn () => new MenuRegistry);
        $this->app->bind(PictureRepositoryInterface::class, PictureRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/Routes/menu.php');

        $this->registerMenu();

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\SpinoffPageCommand::class,
                Console\Commands\InstallCommand::class,
            ]);

            // Dashboard publish tag
            $this->publishes([
                __DIR__.'/../resources/js/Pages/Dashboard.vue' => resource_path('js/Pages/Dashboard.vue'),
            ], 'meacms-menu-dashboard');

            // Website Layouts publish tag
            $this->publishes([
                __DIR__.'/../resources/js/Layouts' => resource_path('js/Layouts'),
            ], 'meacms-menu-layouts');

            // WebController publish tag
            $this->publishes([
                __DIR__.'/../stubs/Controllers/WebController.stub' => app_path('Http/Controllers/WebController.php'),
            ], 'meacms-menu-controller');

            // Shared components, composables, layouts, & controller publish tag
            $this->publishes([
                __DIR__.'/../resources/js/Components' => resource_path('js/Components'),
                __DIR__.'/../resources/js/Composables' => resource_path('js/Composables'),
                __DIR__.'/../resources/js/Layouts' => resource_path('js/Layouts'),
                __DIR__.'/../stubs/Controllers/WebController.stub' => app_path('Http/Controllers/WebController.php'),
            ], 'meacms-menu-shared');

            // Group tag
            $this->publishes([
                __DIR__.'/../resources/js' => resource_path('js/vendor/meacms/menu'),
            ], 'meacms-menu');
        }
    }

    protected function registerMenu(): void
    {
        if (! $this->app->bound(MenuRegistry::class)) {
            return;
        }

        $registry = $this->app->make(MenuRegistry::class);

        // Register Dashboard admin menu item
        $registry->register('admin', new MenuItem(
            name: 'dashboard',
            caption: 'Dashboard',
            icon: 'home',
            route: 'dashboard',
            order: 10,
        ));
    }
}
