<?php

namespace GiapHiep\Post\Models;

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

    /**
     * update post
     * 
     * @param  [type] $post [description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function updatePost($post, $data) {

    	$post->update($data);


        //categories
        if (!empty($data['categories'])) {

                //delete all first
                PostCategory::where('post_id', $post->id)->delete();

                foreach ($data['categories'] as $key => $category) {
                  PostCategory::create([
                            'post_id' => $post->id,
                            'category_id' =>$category
                        ]);
                }
        } else {

          //delete all
          PostCategory::where('post_id', $post->id)->delete();
        }

         //tags 
        if (!empty($data['tags'])) {

            //delete all first
            PostTag::where('post_id', $post->id)->delete();

            foreach ($data['tags'] as $key => $value) {

              $flag1 = Tag::where('slug', str_slug($value))->first();

              if ($flag1 == null) {
                  //them vao bang tag
                  $t = new Tag;
                  $t->name = $value;
                  $t->slug = str_slug($value);
                  
                  if ($t->save()) {
                      PostTag::create([
                          'post_id' => $post->id,
                          'tag_id' =>$t->id
                      ]);
                  }
              } else {
                  PostTag::create([
                          'post_id' => $post->id,
                          'tag_id' =>$flag1->id
                      ]);
              }
            }

        } else {
          PostTag::where('post_id', $post->id)->delete();
        }

        return $post;


    }
}
