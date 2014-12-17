<?php namespace Canaan5\Power;

use Illuminate\Support\ServiceProvider;
use Canaan5\Power\Commands\MigrationGeneratorCommand;
use Canaan5\Power\Commands\ModelsGeneratorCommand;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Auth\Guard;

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

		\Auth::extend('power', function() {

			return new Guard(
				new PowerUserProvider(
					new BcryptHasher,
					\Config::get('auth.model', 'User')
				),
				\App::make('session.store')
			);
		});

		// $this->exceptionMessages();
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
		$this->commands('power.models');

		$this->app['power.migration'] = $this->app->share(function($app) {
			return new MigrationGeneratorCommand;
		});

		$this->app['power.models'] = $this->app->share(function($app) {
			return new ModelsGeneratorCommand;
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

	public function exceptionMessages()
	{
		\App::error(function($exception, $code)
		{
		    switch ($code)
		    {
		        case 403:
		            return \Response::view('errors.403', array(), 403);

		        case 404:
		            return \Response::view('errors.404', array(), 404);

		        case 500:
		        	// return Exception::getMessage();
		            return \Response::view('power::errors.500', ['message' => ''], 500);

		        default:
		            return \Response::view('errors.default', array(), $code);
		    }
		});
	}

}
