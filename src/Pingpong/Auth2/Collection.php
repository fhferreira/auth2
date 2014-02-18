<?php namespace Pingpong\Auth2;

use Illuminate\Foundation\Application;

class Collection
{
    /**
     * Application object. 
     *
     * @var \Illuminate\Foundation\Application 
     */
	protected $app;

    /**
     * Config object. 
     *
     * @var \Illuminate\Config\Repository 
     */
	protected $config;

	/**
	 * Array item collection.
	 *
	 * @var array
	 */
	protected $items = array();
	
	public function __construct(Application $app)
	{
		$this->app = $app;
		$this->config = $this->app['config'];
	}

	/**
	 * Get all items collection.
	 *
	 * @return array
	 */
	public function all()
	{
		return $this->scan()->items;
	}

	/**
	 * Scan items collection from config.
	 *
	 * @return self
	 */
	protected function scan()
	{
		$providers = $this->config->get('auth2::auth2');
		foreach ($providers as $key => $value) {
			$this->put($key, $value);
		}
		return $this;
	}

	/**
	 * Put new item collection.
	 *
	 * @return self
	 */
	public function put($key, $value)
	{
		$this->items[$key] = $value;
		return $this;
	}

	/**
	 * Get single item collection.
	 *
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		if($this->has($key))
		{
			return $this->items[$key];
		}
		return $default;
	}

	/**
	 * Is items exists?.
	 *
	 * @return boolean
	 */
	public function has($key)
	{
		return isset($this->items[$key]);
	}
}