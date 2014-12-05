<?php namespace MrAudioGuy\Commons;

use Illuminate\Support\ServiceProvider;

class CommonsServiceProvider extends ServiceProvider {

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
		$this->package('mr-audio-guy/commons');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->booting(function()
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('Arr', 'MrAudioGuy\Commons\Facades\Arr');
			$loader->alias('Error', 'MrAudioGuy\Commons\Facades\Error');
		});
		$this->app['arr'] = $this->app->share(function($app)
		{
			return new Arr();
		});

		$this->app['error'] = $this->app->share(function($app)
		{
			return new Error();
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('arr','error');
	}

}
