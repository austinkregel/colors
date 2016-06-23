<?php 

namespace Kregel\Colors;

use Illuminate\Support\ServiceProvider;

class ColorsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
	
	public function boot()
	{
	    $this->app->bind('color', function()
        {
            return new \Kregel\Colors\Color;
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}
