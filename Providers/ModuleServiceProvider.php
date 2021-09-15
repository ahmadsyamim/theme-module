<?php

namespace Modules\Theme\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\File;

class ModuleServiceProvider
{
    protected $moduleName;

    public function __construct($moduleName)
    {
        $this->moduleName = $moduleName;
    }
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function postInstall()
    {
        return true;
    }

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function postEnable()
    {
        // Check for theme resources
        File::copyDirectory(module_path($this->moduleName, 'Resources/themes'), storage_path('themes'));
        return true;
    }
}
