<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	if(!Schema::hasTable('posts')) {

    		Schema::create('posts', function (Blueprint $table) {
	            $table->increments('id');
	            $table->string('title')->index();
	            $table->string('thumbnail')->nullable();
	            $table->mediumText('description')->nullable();
	            $table->longText('content')->nullable();
	            $table->string('slug')->unique()->nullable();
	            $table->integer('user_id')->unsigned()->nullable();
	            $table->tinyInteger('status')->default(1)->comment('1: public, 0: private');
	            $table->tinyInteger('is_featured')->default(1)->comment('1: featured, 0: not featured');
	            $table->timestamps();
	        });
	        
    	}
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
