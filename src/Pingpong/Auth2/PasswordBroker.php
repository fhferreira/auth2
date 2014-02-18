<?php namespace Pingpong\Auth2;

use Illuminate\Foundation\Application;
use Illuminate\Auth\Reminders\PasswordBroker as BasePasswordBroker;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Auth\Reminders\DatabaseReminderRepository;

class PasswordBroker extends BasePasswordBroker
{
    /**
     * Application object. 
     *
     * @var \Illuminate\Foundation\Application 
     */
	protected $app;

    /**
     * Current provider name. 
     *
     * @var string
     */
	protected $name;
	
	function __construct(Application $app, $name)
	{
		$this->app = $app;
		$this->name = $name;
        $this->createProvider();
		parent::__construct(
            $this->app["auth2.$this->name.reminder.repository"],
            $this->app["auth.$this->name.provider"],
            $this->app['mailer'],
            $this->getEmailTemplate()
        );
	}

    /**
     * Create provider object. 
     *
     * @return void
     */
	protected function createProvider()
	{
		$this->createReminderRepository();
		$this->createEloquentProvider();
		$this->createCryptProvider();
		$this->createReminderBroker();
	}

    /**
     * Create reminder broker object. 
     *
     * @return void
     */
	public function createReminderBroker()
	{
		$this->app["auth2.$this->name.reminder"] = $this->app->share(function($app)
        {
            return new BasePasswordBroker(
                $app["auth2.$this->name.reminder.repository"], 
                $app["auth2.$this->name.auth.provider"], 
                $app['mailer'], 
                $this->getEmailTemplate() // email template for the reminder
            );
        });
	}

    /**
     * Create reminder repository object. 
     *
     * @return void
     */
    protected function createReminderRepository()
    {
        $this->app["auth2.$this->name.reminder.repository"] = $this->app->share(function($app)
        {
            $connection   = $app['db']->connection();
            $table        = $this->getReminderTable();
            $key          = $app['config']['app.key'];

            return new DatabaseReminderRepository($connection, $table, $key);
        });
    }

    /**
     * Create eloquent model object. 
     *
     * @return void
     */
    protected function createEloquentProvider()
    {
        $this->app["auth.$this->name.provider"] = $this->app->share(function($app)
        {
            return new EloquentUserProvider(
                $app["auth2.$this->name.crypt"], 
                $this->getReminderEloquent()
            );
        });
    }

    /**
     * Create crypt provider object. 
     *
     * @return void
     */
    protected function createCryptProvider()
    {
    	$this->app["auth2.$this->name.crypt"] = $this->app->share(function($app)
        {
            return new BcryptHasher;
        });
    }

    /**
     * Get reminder config for current provider. 
     *
     * @return string
     */
    protected function getReminderProvider()
    {
    	return $this->app['config']->get("auth2::auth2.$this->name");
    }

    /**
     * Get email template for current provider. 
     *
     * @return string
     */
	protected function getEmailTemplate()
	{
		return $this->app['config']->get("auth2::auth2.$this->name.view");
	}

    /**
     * Get reminder eloquent name. 
     *
     * @return string
     */
    protected function getReminderEloquent()
    {
        $reminderConf = $this->getReminderProvider();
        return isset($reminderConf['model']) ? $reminderConf['model'] : 'User';
    }

    /**
     * Get reminder table. 
     *
     * @return string
     */
	protected function getReminderTable()
	{
		return $this->app['config']->get("auth.reminder.table");
	}
	
}