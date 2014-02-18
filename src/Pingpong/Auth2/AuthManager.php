<?php namespace Pingpong\Auth2;

use Illuminate\Auth\AuthManager as BaseAuthManager;
use Illuminate\Auth\DatabaseUserProvider;
use Illuminate\Auth\EloquentUserProvider;

class AuthManager extends BaseAuthManager {
	
	/**
	 * Config for current auth.
	 *
	 * @var $config
	 */
	protected $config;

	/**
	 * Name of auth.
	 *
	 * @var $name
	 */
	protected $name;
	
	public function __construct($app, $name, $config) {
		parent::__construct($app);
		
		$this->config = $config;
		$this->name = $name;
	}
	
	/**
	 * Create a new driver instance.
	 *
	 * @param  string  $driver
	 * @return mixed
	 */
	protected function createDriver($driver) {
		$guard = parent::createDriver($driver);
		
		$guard->setCookieJar($this->app['cookie']);
		$guard->setDispatcher($this->app['events']);

		return $guard->setRequest($this->app->refresh('request', $guard, 'setRequest'));
	}
	
	/**
	 * Call a custom driver creator.
	 *
	 * @param  string  $driver
	 * @return mixed
	 */
	protected function callCustomCreator($driver) {
		$custom = parent::callCustomCreator($driver);

		if ($custom instanceof Guard) return $custom;

		return new Guard($custom, $this->app['session.store'], $this->name);
	}	

	/**
	 * Create an instance of the database driver.
	 *
	 * @return \Illuminate\Auth\Guard
	 */
	public function createDatabaseDriver() {
		$provider = $this->createDatabaseProvider();

		return new Guard($provider, $this->app['session.store'], $this->name);
	}
	
	/**
	 * Create an instance of the database user provider.
	 *
	 * @return \Illuminate\Auth\DatabaseUserProvider
	 */
	protected function createDatabaseProvider() {
		$connection = $this->app['db']->connection();
		$table = $this->config['table'];

		return new DatabaseUserProvider($connection, $this->app['hash'], $table);
	}
	
	/**
	 * Create an instance of the Eloquent driver.
	 *
	 * @return \Illuminate\Auth\Guard
	 */
	public function createEloquentDriver() {
		$provider = $this->createEloquentProvider();

		return new Guard($provider, $this->app['session.store'], $this->name);
	}
	
	/**
	 * Create an instance of the Eloquent user provider.
	 *
	 * @return \Illuminate\Auth\EloquentUserProvider
	 */
	protected function createEloquentProvider() {
		$model = $this->config['model'];

		return new EloquentUserProvider($this->app['hash'], $model);
	}
	
	/**
	 * Get the default authentication driver name.
	 *
	 * @return string
	 */
	public function getDefaultDriver() {
		return $this->config['driver'];
	}

	/**
	 * Set the default authentication driver name.
	 *
	 * @param  string  $name
	 * @return void
	 */
	public function setDefaultDriver($name)
	{
		$this->config['driver'] = $name;
	}

}
