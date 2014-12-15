<?php namespace Canaan5\Power;

use Illuminate\Support\ServiceProvider;
use Canaan5\Power\Commands\MigrationGeneratorCommand;

class PowerServiceProvider extends ServiceProvider {

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
		$this->package('canaan5/power');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// main power registration
		$this->power();

		$this->app->booting(function() {

			$loader = \Illuminate\Foundation\AliasLoader::getInstance();

			$loader->alias('Power', 'Canaan5\Power\Facades\Power');
		});

		// Registration for Power Command
		$this->commands('power.migration');
		$this->app['power.migration'] = $this->app->share(function($app) {
			return new MigrationGeneratorCommand;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('power');
	}

	public function power()
	{
		$this->app['power'] = $this->app->share(function($app) {

			return new Power($app);
		});
	}

}
