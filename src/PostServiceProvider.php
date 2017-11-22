<?php

namespace GiapHiep\Post;

use Illuminate\Support\ServiceProvider;
use Config;
use GiapHiep\Admin\Commands\PostInstall;

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
	    ], 'post_migrations');

    	//view
    	
    	$this->loadViewsFrom(__DIR__.'/resources/views', $package_name);

	    $this->publishes([
	        __DIR__.'/resources/views' => resource_path('views/vendor/'. $package_name),
	    ], 'post_views');


	    //config
	    $this->publishes([
            __DIR__ . '/config/lfm.php' => base_path('config/lfm.php'),
        ], 'lfm_post_config');
        
        $this->app->register(RouteServiceProvider::class);

        //commands
        if ($this->app->runningInConsole()) {
	        $this->commands([
	            PostInstall::class,
	        ]);
    	}
        
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //middleware
        $this->app['router']->aliasMiddleware('optimizeImages', \Spatie\LaravelImageOptimizer\Middlewares\OptimizeImages::class);
    }
}