<?php

namespace GiapHiep\Post\Commands;

use Illuminate\Console\Command;
use Artisan;

class PostInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Posts, Tags, Category';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	if ($this->confirm('Do you want to install it?', false)) {
            Artisan::call('vendor:publish',['--tag' => 'post_migrations']);
	    	Artisan::call('vendor:publish',['--tag' => 'post_views']);
	    	Artisan::call('migrate');
	    	Artisan::call('route:clear');
	    	Artisan::call('config:clear');

	    	Artisan::call('vendor:publish',['--tag' => 'lfm_post_config']);
	    	Artisan::call('vendor:publish',['--tag' => 'lfm_public']);
	    	Artisan::call('vendor:publish',['--tag' => 'lfm_view']);
	    	Artisan::call('vendor:publish',['--tag' => 'lfm_handler']);

	    	$this->displayOutput();
        }
    	

    }

    protected function displayOutput()
    {
 		
        $message = [
            "=========================================================",
            "  ________.__                  ___ ___ .__               ",
            " /  _____/|__|____  ______    /   |   \|__| ____ ______  ",
            "/   \  ___|  \__  \ \____ \  /    ~    \  |/ __ \\____ \ ",
            "\    \_\  \  |/ __ \|  |_> > \    Y    /  \  ___/|  |_> >",
            " \______  /__(____  /   __/   \___|_  /|__|\___  >   __/ ",
            "        \/        \/|__|            \/         \/|__|    ",
            "                                                         ",
            "================ INSTALLATION COMPLETE ==================",
        ];
        $this->line($message);
    }
}
