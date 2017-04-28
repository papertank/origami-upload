<?php namespace Origami\Upload;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

class UploadServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/upload.php' => config_path('upload.php')
        ], 'config');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/upload')
        ], 'public');

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
            __DIR__.'/../config/upload.php', 'upload'
        );

        $this->app->singleton('upload.form', function($app)
        {
            $form = new FormBuilder();

            return $form->setSessionStore($app['session.store']);
        });

        $this->app->alias('upload.form', 'Origami\Upload\FormBuilder');

        $loader = AliasLoader::getInstance();
        $loader->alias('Upload', 'Origami\Upload\UploadFacade');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['upload.form'];
    }

}
