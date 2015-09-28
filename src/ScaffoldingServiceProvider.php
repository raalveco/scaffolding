<?php namespace Scaffolding;

use Illuminate\Support\ServiceProvider;

class ScaffoldingServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        $this->publishes([
            __DIR__.'/metronic' => public_path('metronic'),
            __DIR__.'/templates' => public_path('scaffolding/templates'),
        ], 'scaffolding');
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app['scaffolding.scaffold'] = $this->app->share(function($app)
        {
            return new Scaffold();
        });

        $this->commands('scaffolding.scaffold');
	}

}
