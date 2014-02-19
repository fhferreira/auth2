##Laravel 4 Multi Auth With Password Reminder


## Installation

Open your composer.json file, and add the new required package.

  ```
  "pingpong/auth2": "dev-master" 
  ```

Next, open a terminal and run.

  ```
  composer update 
  ```
  
After the composer updated. Add new service provider in app/config/app.php.

  ```
  'Pingpong\Auth2\Auth2ServiceProvider'
  ```
Open your terminal and run:
  
  ```
  php artisan config:publish pingpong/auth2
  ```
  
Finish.

## Configuration File

  ```php
  
  <?php
  
    return array(
    
    	// example 
    	'admin'	=>	array(
    		'driver' 	=> 'eloquent',
    		'model' 	=> 'Admin',
    		'table' 	=> 'admins',
    		'view' 		=> 'emails.auth.reminder'
    	),		
    
    );

  ```
## Example

#### Auth

  
  ```php
  
  Auth2::admin()->attempt($credentials, $remember);
  
  if(Auth2::admin()->check())
  {
    // code
  }
  
  Auth2::admin()->get(); //it's same with Auth::user() , for get credentials
  
  Auth2::admin()->logout();
  
  Auth2::admin()->loginWithId('admin', 1, TRUE);
  
  ```
  
#### Password Reminder
  
  Password Remind
  
  ```php
  
    /**
	 * Handle a POST request to remind a user of their password.
	 *
	 * @return Response
	 */
	public function postRemind()
	{
		$response = Password2::admin()->remind(Input::only('email'), function($message)
		{
		    $message->subject('Password Reminder');
		});

		switch ($response)
		{
			case 'reminders.user':
				return Redirect::back()->with('error', Lang::get($response));

			case 'reminders.sent':
				return Redirect::back()->with('status', Lang::get($response));
		}
	}
  ```
  
  Password Reset

  ```php
  
    $credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$response = Password2::admin()->reset($credentials, function($user, $password)
		{
			$user->password = Hash::make($password);

			$user->save();
		});

		switch ($response)
		{
			case 'reminders.password':
			case 'reminders.token':
			case 'reminders.user':
				return Redirect::back()->with('error', Lang::get($response));

			case 'reminders.reset':
				return Redirect::to('admin');
		}
  ```
  
