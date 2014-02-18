<?php namespace Pingpong\Auth2\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Auth\AuthManager
 * @see \Illuminate\Auth\Guard
 */
class Auth2 extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'auth2'; }

}