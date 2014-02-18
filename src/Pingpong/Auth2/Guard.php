<?php namespace Pingpong\Auth2;

use Illuminate\Auth\Guard as BaseGuard;
use Illuminate\Auth\UserProviderInterface;
use Illuminate\Session\Store as SessionStore;

class Guard extends BaseGuard {
	
	protected $name;
	
	/**
	 * Create a new authentication guard.
	 *
	 * @param  \Illuminate\Auth\UserProviderInterface  $provider
	 * @param  \Illuminate\Session\Store  $session
	 * @return void
	 */
	public function __construct(UserProviderInterface $provider, SessionStore $session, $name, Request $request = null) {
		parent::__construct($provider, $session, $request);
		
		$this->name = $name;
	}
	
	/**
	 * Get a unique identifier for the auth session value.
	 *
	 * @return string
	 */
	public function getName() {
		return 'login_' . $this->name . '_' . md5(get_class($this));
	}
	
	/**
	 * Get the name of the cookie used to store the "recaller".
	 *
	 * @return string
	 */
	public function getRecallerName() {
		return 'remember_' . $this->name . '_' . md5(get_class($this));
	}
	
	/**
	 * Get the currently authenticated user.
	 *
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function get() {
		return $this->user();
	}
	
	/**
	 * Log the given user ID into the application.
	 *
	 * @param  string $type
	 * @param  mixed  $id
	 * @param  bool   $remember
	 * @return \Illuminate\Auth\UserInterface
	 */
	public function loginWithId($type, $id, $remember = false) {
		if($this->check()) {
			return Auth::$type()->loginUsingId($id, $remember);
		}
	}
	
}
