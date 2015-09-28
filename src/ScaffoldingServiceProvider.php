<?php namespace Scaffolding;

use Illuminate\Support\ServiceProvider;
use Scaffolding\Commands\ScaffoldCommand;

class ScaffoldingServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        $this->publishes([
            __DIR__.'/metronic' => public_path('metronic')
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
            return new ScaffoldCommand();
        });

        $this->commands('scaffolding.scaffold');
	}

}
