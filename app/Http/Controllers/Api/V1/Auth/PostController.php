<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PermissionPost;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use stdClass;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $order = $request->order ?: 'asc';
        $field = $request->field ?: 'created_at';
        $search = $request->search ?: '';
        $per_page = intval($request->perPage) ?: 5;
        if ($search != '') {
            $posts = Post::Select('id', 'tittle', 'author_id', 'author_name', 'visibility', 'updated_at', 'created_at')->where('tittle', 'like', '%' . $search . '%')->orWhere('author_name', 'like', '%' . $search . '%')->orderBy($field, $order)->paginate($per_page);
        } else {
            $posts = Post::Select('id', 'tittle', 'author_id', 'author_name', 'visibility', 'updated_at', 'created_at')->orderBy($field, $order)->paginate($per_page);
        }
        foreach ($posts as $post) {
            $post_permissions = $post->permissionpost()->get();

            if ($post->visibility == 1) {
                $post->visibility = 'Draft';
            }
            if ($post->visibility == 2) {
                $post->visibility = 'Public';
            }
            if ($post->visibility == 3) {
                $post->visibility = 'Private';
            }
            $object_permissions = new stdClass();
            foreach ($post_permissions as $permissions) {
                if ($permissions->permission == 'restricted') {
                    $object_permissions->restricted = true;
                }
                if ($permissions->permission == 'allowComments') {
                    $object_permissions->allowComments = true;
                }
                if ($permissions->permission == 'pinned') {
                    $object_permissions->pinned = true;
                }
            }
            $post->permissions = $object_permissions;
        }

        return response()->json([
            'pagination' => [
                'total' => $posts->total(),
                'currentPage' => $posts->currentPage(),
                'perPage' => $posts->perPage(),
                'last_page' => $posts->lastPage(),
                'from' => $posts->firstItem(),
                'to' => $posts->lastItem(),
            ],
            'posts' => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {

        $category_default = Category::select('id')->where('default', '=', 1)->first();
        $slug = $request->slug != "null" ? $request->slug : null;
        $tittle = $request->tittle != "null" ? $request->tittle : null;
        $content = $request->content != "null" ? $request->content : null;
        $image = $request->image != "null" ? $request->image : null;
        $authUser = User::find($request->authUser);
        $author_name = $authUser->name;
        $author_id = $authUser->id;
        if ($tittle  == null || $content == null) :
            return response()->json(['error' => 'Tittle and Content Cannot be null']);
        endif;

        if ($slug == null) :
            $slug = trim(str_replace(" ", "-", strtolower($tittle)));
        else :
            if ($slug != null) :
                $slug = trim(str_replace(" ", "-", strtolower($slug)));

            endif;
        endif;
        $query_slug = Post::where('slug', '=', $slug)->first();
        if ($query_slug != null) :
            return response()->json(['error' => 'Slug must be unique']);
        endif;


        $allowComments = $request->allowComments;
        $restricted = $request->restricted;
        $pinned = $request->pinned;
        $tittle = $request->tittle;
        if ($request->categories != null) :
            $categories = explode(',', $request->categories);
        else :
            $categories = [$category_default->id];
        endif;

        $image_path = $image;
        $post = Post::create([
            'slug' => $slug,
            'tittle' => $tittle,
            'content' => $content,
            'image' => $image_path,
            'visibility' => $request->visibility,
            'author_name' => $author_name,
            'author_id' => $author_id
        ]);

        if ($post) {
            $post->categories()->sync($categories);
            $post_categories = $post->categories()->get();
            $array_categories = [];
            foreach ($post_categories as $category) {
                array_push($array_categories, $category->id);
            }
            $post->categories = $array_categories;
            if ($allowComments != "false") {
                $permission = PermissionPost::find(2);
                $post->permissionpost()->attach($permission);
            }
            if ($restricted != "false") {
                $permission = PermissionPost::find(3);
                $post->permissionpost()->attach($permission);
            }
            if ($pinned != "false") {
                $permission = PermissionPost::find(1);
                $post->permissionpost()->attach($permission);
            }

            $post_permissions = $post->permissionpost()->get();
            $array_permissions = [];
            foreach ($post_permissions as $permissions) {
                $permission_name = [$permissions->permission => true];
                array_push($array_permissions, $permission_name);
            }
            $post->permissions = $array_permissions;
        }


        return response()->json(['success' => 'Post Successfully Created', 'post' => $post]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //

        $id_post = intval($id);
        if (!is_int($id_post)) :
            return response()->json(['error' => 'The id must be an integer']);
        endif;
        $post = Post::find($id_post);

        $post_categories = $post->categories()->get();
        $array_categories = [];
        foreach ($post_categories as $category) {
            array_push($array_categories, $category->id);
        }
        $post->categories = $array_categories;

        $post_permissions = $post->permissionpost()->get();
        $object_permissions = new stdClass();
        foreach ($post_permissions as $permissions) {
            if ($permissions->permission == 'restricted') {
                $object_permissions->restricted = true;
            }
            if ($permissions->permission == 'allowComments') {
                $object_permissions->allowComments = true;
            }
            if ($permissions->permission == 'pinned') {
                $object_permissions->pinned = true;
            }
        }

        $post->permissions = $object_permissions;

        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
        $this->validate($request, [
            'slug' => 'required|alpha_dash|unique:posts,slug,' . $post->id,
            'tittle' => 'required|string',
            'content' => 'string'
        ]);

        $category_default = Category::select('id')->where('default', '=', 1)->first();
        $slug = $request->slug != "null" ? $request->slug : null;
        $tittle = $request->tittle != "null" ? $request->tittle : null;
        $content = $request->content != "null" ? $request->content : null;
        $image_path = $request->image != "null" ? $request->image : $post->image;
        if ($tittle  == null || $content == null) :
            return response()->json(['error' => 'Tittle and Content Cannot be null']);
        endif;

        if ($slug == null) :
            $slug = trim(str_replace(" ", "-", strtolower($tittle)));
        else :
            if ($slug != null) :
                $slug = trim(str_replace(" ", "-", strtolower($slug)));

            endif;
        endif;


        $allowComments = $request->allowComments;
        $restricted = $request->restricted;
        $pinned = $request->pinned;
        $array_permissions = [];

        if ($allowComments != false) {
            $permission = PermissionPost::select('id')->where('id', '=', 2)->first();
            array_push($array_permissions, $permission);
        }
        if ($restricted != false) {
            $permission = PermissionPost::select('id')->where('id', '=', 3)->first();
            array_push($array_permissions, $permission);
        }
        if ($pinned != false) {
            $permission = PermissionPost::select('id')->where('id', '=', 1)->first();
            array_push($array_permissions, $permission);
        }
        $array_permissions_id = [];
        foreach ($array_permissions as $permission_id) {
            array_push($array_permissions_id, $permission_id->id);
        }

        $tittle = $request->tittle;
        if ($request->categories != null) :
            $categories = $request->categories;
        else :
            $categories = [$category_default->id];
        endif;


        $post->slug = $slug;
        $post->tittle = $tittle;
        $post->content = $content;
        $post->visibility = $request->visibility;
        $post->image = $image_path;
        $post->update();



        if ($post->update()) {
            $post->categories()->sync($categories);
            $post->permissionpost()->sync($array_permissions_id);
        }

        return response()->json([
            'success' => 'Post Updated Succesfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

        $post = Post::find($id);
        if ($post->delete()) {

            return response()->json(['success' => 'Post Successfully Delete']);
        } else {

            return response()->json(['errors' => 'Failed to delete post']);
        }
    }


    public function homeFeatured(Request $request)
    {

        $setting = DB::table('general_settings')->select('pinnedOrder')->first();
        $posts = Post::select('id', 'slug', 'tittle', 'visibility', 'image')->where('visibility', '=', 2)->orderBy('updated_at', $setting->pinnedOrder)->limit(4)->get();

        $array_posts = [];
        $array_ids = [];
        foreach ($posts as $post) {
            $permissions = $post->permissionpost()->get();
            $show = false;
            foreach ($permissions as $permission) {
                if ($permission->permission == 'pinned') {
                    $show = true;
                }
                if ($permission->permission == 'restricted') {
                    $show = false;
                }
            }
            if ($show == true) {
                array_push($array_posts, $post);
                array_push($array_ids, $post->id);
            }
        }
        if (count($array_posts) < 4) {

            $i = count($array_posts);
            for ($i; $i < 4; $i++) {
                $fill_posts = Post::select('id', 'slug', 'tittle', 'visibility', 'image')->where('visibility', '=', 2)->whereNotIn('id', $array_ids)->orderBy('updated_at', $setting->pinnedOrder)->limit(4 - $i)->get();

                foreach ($fill_posts as $fill_post) {
                    $show = true;
                    $permissions_fillPost = $fill_post->permissionpost()->get();
                    foreach ($permissions_fillPost as $permission) {
                        if ($permission->permission == 'restricted') {
                            $show = false;
                        }
                    }
                    if ($show == true) {
                        array_push($array_posts, $fill_post);
                        $i = count($array_posts);
                        array_push($array_ids, $fill_post->id);
                    }
                }
            }
        }
        $month = date('m');
        $year = date('Y');
        $views_total = DB::table('public_views')->where('page', 'home')->whereYear('updated_at', $year)->whereMonth('updated_at', $month)->first();
        if ($views_total) {
            DB::table('public_views')->where('id', '=', $views_total->id)->update([
                'views' => $views_total->views + 1,
                'updated_at' => new \DateTime(),
            ]);
        } else {
            DB::table('public_views')->insert([
                'id' => Uuid::uuid4(),
                'page' => 'home',
                'views' => 1,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),

            ]);
        }

        return response()->json($array_posts);
    }

    public function postsToBlog(Request $request)
    {
        $setting = DB::table('general_settings')->select('pinnedOrder')->first();
        $posts = [];
        if ($request->category == 0 || $request->category == null) {
            $posts = Post::select('id', 'slug', 'tittle', 'content', 'visibility', 'image', 'updated_at')->where('visibility', '=', 2)->orderBy('updated_at', $setting->pinnedOrder)->get();
        } else {
            $category_id = $request->category;

            $array_filter = Post::select('id', 'slug', 'tittle', 'content', 'visibility', 'image', 'updated_at')->where('visibility', '=', 2)->orderBy('updated_at', $setting->pinnedOrder)->with('categories')->get();
            foreach ($array_filter as $post) {
                foreach ($post->categories as $category) {
                    if ($category->id == $category_id) {
                        array_push($posts, $post);
                    }
                }
            }
        }



        $array_posts = [];
        $array_ids = [];
        foreach ($posts as $post) {
            $permissions = $post->permissionpost()->get();
            $categories = $post->categories()->get();
            $post->categories = $categories;
            $show = true;
            foreach ($permissions as $permission) {
                if ($permission->permission == 'restricted') {
                    $show = false;
                }
            }
            if ($show == true) {
                array_push($array_posts, $post);
                array_push($array_ids, $post->id);
            }
        }

        $total_categories = Category::select('id', 'name')->get();



        $month = date('m');
        $year = date('Y');
        $views_total = DB::table('public_views')->where('page', 'blog')->whereYear('updated_at', $year)->whereMonth('updated_at', $month)->first();


        if ($views_total) {
            DB::table('public_views')->where('id', '=', $views_total->id)->update([
                'views' => $views_total->views + 1,
                'updated_at' => new \DateTime(),
            ]);
        } else {
            DB::table('public_views')->insert([
                'id' => Uuid::uuid4(),
                'page' => 'blog',
                'views' => 1,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),

            ]);
        }


        return response()->json(['posts' => $array_posts, 'categories' => $total_categories]);
    }

    public function postVisits(Request $request)
    {


        $post = $request;
        $month = date('m');
        $year = date('Y');
        $views_total = DB::table('public_views')->where('page', 'blog')->where('post_id', $post['id'])->whereYear('updated_at', $year)->whereMonth('updated_at', $month)->first();
        if ($views_total) {
            DB::table('public_views')->where('id', '=', $views_total->id)->update([
                'views' => $views_total->views + 1,
                'updated_at' => new \DateTime(),
            ]);
        } else {
            DB::table('public_views')->insert([
                'id' => Uuid::uuid4(),
                'page' => 'blog',
                'views' => 1,
                'post_id' => $post['id'],
                'post_tittle' => $post['tittle'],
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),

            ]);
        }

        return response()->json($request);
    }
}
