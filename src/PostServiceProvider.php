<?php

namespace GiapHiep\Post;

use Illuminate\Support\ServiceProvider;

class PostServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $package_name = "giaphiep";

    	//migrations
    	$this->loadMigrationsFrom(__DIR__.'/database/migrations/');
    	
    	$this->publishes([
	        __DIR__.'/database/migrations/' => database_path('migrations')
	    ], 'migrations');


    	// $this->publishes([
	    //     __DIR__.'/database/seeds' => database_path('seeds')
	    // ], 'seeds');

    	//view
    	
    	$this->loadViewsFrom(__DIR__.'/resources/views', $package_name);

	    $this->publishes([
	        __DIR__.'/resources/views' => resource_path('views/vendor/'. $package_name),
	    ]);

    	//assets
     //    $this->publishes([
     //    	__DIR__.'/public/assets' => public_path('vendor/assets'),
    	// ], 'public');

        
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}