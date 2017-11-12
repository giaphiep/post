<?php

Route::prefix('admin')->group(function() {
	
	Route::get('post/list', ['as' => 'posts.list', 'uses' => 'PostController@getList']);

	Route::resource('posts','PostController');
	Route::resource('categories','CategoryController');
	Route::resource('tags','TagController');
});