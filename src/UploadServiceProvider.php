<?php

namespace Origami\Upload;

use Illuminate\Support\ServiceProvider;

class UploadServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../views' => base_path('resources/views/vendor/upload')
        ], 'views');

        $this->loadViewsFrom(__DIR__.'/../views', 'upload');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/upload.php',
            'upload'
        );

        $this->app->singleton(UploadHelper::class, function($app) {
            return new UploadHelper;
        });

        $this->app->alias(UploadHelper::class, 'origami-upload.helper');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['origami-upload.helper'];
    }

}
