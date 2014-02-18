<?php namespace Pingpong\Auth2;

use Illuminate\Support\ServiceProvider;

class Auth2ServiceProvider extends ServiceProvider {
	
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
		$this->package('pingpong/auth2');
	}
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->registerProviders();
		$this->registerAuthManager();
		$this->registerFacades();
	}

	/**
	 * Register the Auth Manager.
	 *
	 * @return void
	 */
	protected function registerAuthManager()
	{
		$this->app->bindShared('auth2', function($app) {
			$app['auth2.loaded'] = true;
			
			return new Auth2($app);
		});
	}

	/**
	 * Register all service provider.
	 *
	 * @return void
	 */
	protected function registerProviders()
	{
		$this->app['auth2.collection'] = $this->app->share(function($app)
		{
			return new Collection($app);
		});		
		$this->app['password2'] = $this->app->share(function($app)
		{
			return new Password2($app);
		});
	}

	/**
	 * Register all facades.
	 *
	 * @return void
	 */
	protected function registerFacades()
	{
		$this->app->booting(function()
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('Auth2', 'Pingpong\Auth2\Facades\Auth2');
			$loader->alias('Password2', 'Pingpong\Auth2\Facades\Password2');
		});
	}

}
