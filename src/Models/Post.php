<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use GiapHiep\Admin\Models\User;

use GiapHiep\Post\Models\PostCategory;
use GiapHiep\Post\Models\PostTag;
use GiapHiep\Post\Models\Tag;

class Post extends Model
{
    protected $fillable = ['title', 'thumbnail', 'description', 'content', 'slug', 'user_id', 'status', 'is_featured'];

    
    public function user() {
    	return $this->belongsTo('GiapHiep\Admin\Models\User');
    }

    /**
     * function save a new post
     * 
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function storePost($data) {

    	$data['slug'] = str_slug($data['title']). '-' .time();

        $post = Post::create($data);

        // category
        if (!empty($data['categories'])) {

            foreach ($data['categories'] as $key => $category) {
                PostCategory::create([
                    'post_id' => $post->id,
                    'category_id' => $category
                ]);
            }
        }

        //tags
        if (!empty($data['tags'])) {

            foreach ($data['tags'] as $key => $tag) {
               
               $flag = Tag::where('slug', str_slug($tag))->first();

               if (empty($flag)) {

                    $flag = Tag::create([
                        'name' => $tag,
                        'slug' => str_slug($tag)
                    ]);
               } 

               PostTag::create([
                        'post_id' => $post->id,
                        'tag_id' => $flag->id,
                    ]);
            }
        }

        return $post;
    }
}
