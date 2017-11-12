<?php

namespace GiapHiep\Post\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GiapHiep\Post\Requests\PostRequest;
use GiapHiep\Post\Requests\EditPostRequest;

use GiapHiep\Post\Models\Post;
use GiapHiep\Post\Models\Category;
use GiapHiep\Post\Models\PostTag;
use GiapHiep\Post\Models\PostCategory;
use GiapHiep\Post\Models\Tag;

use Validator;
use DB;
use Log;
use Datatables;
use Auth;
use Exception;

class PostController extends Controller
{

    public function __construct() {

      $this->middleware('admin.auth');

      //image-optimizer
      // $this->middleware('optimizeImages')->only(['store', 'update']);
        

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('giaphiep::post.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::get();


        return view('giaphiep::post.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $data = $request->all();

        DB::beginTransaction();

        try {

            $post = Post::storePost($data);

            DB::commit();

            return response()->json([
                'error' => false,
                'data' => $post
            ]);

        } catch(Exception $e) {

            Log::info('Can not create post');

            DB::rollback();
            response()->json([
                    'error' => true,
                    'message' => 'Internal Server Error'
                ], 500);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::get();
        $post = Post::find($id);

        return view('posts.edit', ['categories' => $categories, 'post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditPostRequest $request, $id)
    {
        $data = $request->all();

        $post = Post::where('id', $id)->first();


        DB::beginTransaction();

        try {

        	if ($data['old_title'] != $data['title']) {

	            $data['slug'] = str_slug($data['title']). '-' . time();

	        }

            if (empty($data['image'])) {
                unset($data['image']);
            }
            if (empty($data['image_icon'])) {
                unset($data['image_icon']);
            }


	        



            $post->update($data);

            //categories
            if (!empty($data['categories'])) {

                    //delete all first
                    PostCategory::where('post_id', $id)->delete();

                    foreach ($data['categories'] as $key => $category) {
                      PostCategory::create([
                                'post_id' => $id,
                                'category_id' =>$category
                            ]);
                    }
            } else {
              //delete all
              PostCategory::where('post_id', $id)->delete();
            }

             //tags 
            if (!empty($data['tags'])) {

                //delete all first
                PostTag::where('post_id', $id)->delete();

                foreach ($data['tags'] as $key => $value) {

                  $flag1 = Tag::where('slug', str_slug($value))->first();

                  if ($flag1 == null) {
                      //them vao bang tag
                      $t = new Tag;
                      $t->name = $value;
                      $t->slug = str_slug($value);
                      
                      if ($t->save()) {
                          PostTag::create([
                              'post_id' => $id,
                              'tag_id' =>$t->id
                          ]);
                      }
                  } else {
                      PostTag::create([
                              'post_id' => $id,
                              'tag_id' =>$flag1->id
                          ]);
                  }
                }

            } else {
              PostTag::where('post_id', $id)->delete();
            }
            

            DB::commit();

            return response()->json([
                'error' => false,
                'data' => $post
            ]);

        } catch(Exception $e) {
            Log::info($e->getMessage());
            DB::rollback();
            response()->json([
                    'error' => true,
                    'message' => 'Internal Server Error'
                ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {


            PostTag::where('post_id',$id)->delete();

            Post::where('id', $id)->delete();


            DB::commit();

            return response()->json([
                    'error' => false,
                    'message' => 'Delete success!'
                ], 200);

        } catch(Exception $e) {
            Log::info('Can not delete post has id = ' . $id);
            DB::rollback();
            response()->json([
                    'error' => true,
                    'message' => 'Internal Server Error'
                ], 500);
        }
    }

    public function getList() {


        $posts = Post::orderBy('id', 'desc')->get();

        if (count($posts)) {
            foreach ($posts as $key => $post) {
                $post->thumbnail = '<img width="100" height="100" src="' . asset('') . $post->image . '">';
                $post->author = $post->user->name;
                // $post->category = Category::find($post->category_id)->name;
            }
        }

        return Datatables::of($posts)
            ->addColumn('action', function ($post) {

                return '<a href="#" class="btn btn-outline btn-circle btn-xs blue">
                            <i class="fa fa-eye" aria-hidden="true"></i> Chi tiết
                        </a>
                        <a href="posts/'. $post->id .'/edit" class="btn btn-outline btn-circle green btn-xs purple">
                            <i class="fa fa-edit"></i> Sửa 
                        </a>

                          <a href="javascript:;" type="button" onclick="alertDel('. $post->id .')" class="btn btn-outline btn-circle dark btn-xs red">
                            <i class="fa fa-trash-o"></i> Xóa 
                          </a>';
            })
            ->make(true);
    }
}
