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
use Yajra\DataTables\Facades\DataTables;
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

        return view('giaphiep::post.edit', ['categories' => $categories, 'post' => $post]);
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

            if (empty($data['thumbnail'])) {
                unset($data['thumbnail']);
            }

            $result = Post::updatePost($post, $data);

            DB::commit();

            return response()->json([
                'error' => false,
                'data' => $result
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

        $posts = Post::orderBy('id', 'desc');

        return Datatables::of($posts)	
        	// ->addColumn('thumbnail', function ($post) {
        	// 	return '<img width="100" height="100" src="'. $post->thumbnail . '">';
        	// })
        	->addColumn('author', function ($post) {
        		return $post->user->name;
        	})
        	->addColumn('featured', function ($post) {

        		if ($post->is_featured) {
        			return 'Yes';
        		}
        		return 'No';
        	})
        	->addColumn('status', function ($post) {

        		if ($post->status) {
        			return 'Publish';
        		}
        		return 'Draft';
        	})
        	->addColumn('created', function ($post) {
        		return $post->created_at->diffForHumans();
        	})

            ->addColumn('action', function ($post) {

                return '<a href="#" class="btn btn-outline btn-circle btn-xs blue">
                            <i class="fa fa-eye" aria-hidden="true"></i> Detail
                        </a>
                        <a href="posts/'. $post->id .'/edit" class="btn btn-outline btn-circle green btn-xs purple">
                            <i class="fa fa-edit"></i> Edit 
                        </a>

                          <a href="javascript:;" type="button" data-id="'. $post->id .'"  class="btn btn-outline btn-circle dark btn-xs red btn-delete">
                            <i class="fa fa-trash-o"></i> Delete 
                          </a>';
            })
            ->make(true);
    }
}
