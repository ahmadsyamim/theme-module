<?php

namespace Modules\Theme\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use TCG\Voyager\Facades\Voyager;
use Igaster\LaravelTheme\Facades\Theme as LaravelTheme;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\AliasLoader;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Theme';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'theme';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(Kernel $kernel)
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        // $kernel->pushMiddleware(\Modules\Theme\Http\Middleware\SetThemeFromSession::class);
        $this->app->router->pushMiddlewareToGroup('web', \Modules\Theme\Http\Middleware\SetThemeFromSession::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        // Voyager::addAction(\Modules\Theme\Http\Actions\Modules\ThemeInstallAction::class);
        Voyager::addAction(\Modules\Theme\Http\Actions\Modules\ThemeActivateAction::class);
        Voyager::addAction(\Modules\Theme\Http\Actions\Modules\ThemeUpdateAction::class);
        
        $this->app->register(\Orchestra\Asset\AssetServiceProvider::class);
        $this->app->register(\Collective\Html\HtmlServiceProvider::class);
        $loader = AliasLoader::getInstance();
        $loader->alias('Asset', \Orchestra\Support\Facades\Asset::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
