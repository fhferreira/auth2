<?php namespace Pingpong\Auth2;

class Password2 {
	
    /**
     * Application object. 
     *
     * @var \Illuminate\Foundation\Application 
     */
	protected $app;
	
    /**
     * Provider array. 
     *
     * @var array
     */
	protected $providers = array();
	
	public function __construct($app) {
		$this->app = $app;
		$this->setup();
	}

    /**
     * Setup password broker. 
     *
     * @return void
     */
	protected function setup()
	{
		$providers = $this->app['auth2.collection']->all();
		foreach($providers as $key => $config) {
			$this->providers[$key] = new PasswordBroker($this->app, $key);
		}
	}
	
    /**
     * Magic call. 
     *
     * @var $name
     * @var $arguments
     * @return mixed
     */
	public function __call($name, $arguments) {
		if(array_key_exists($name, $this->providers)) {
			return $this->providers[$name];
		}
	}
	
}
