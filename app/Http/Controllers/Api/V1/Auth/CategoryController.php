<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::whereNull('category_id')
            ->with('childrenCategories')
            ->get();
        return response()->json($categories);
    }

    public function listCategories(Request $request)
    {
        $order = $request->order ?: 'desc';
        $field = $request->field ?: 'default';
        $search = $request->search ?: '';
        $per_page = intval($request->perPage) ?: 5;
        if ($search != '') {
            $categories = Category::Select('id', 'slug', 'name', 'default', 'category_id', 'created_at')->where('name', 'like', '%' . $search . '%')->orWhere('slug', 'like', '%' . $search . '%')->orderBy($field, $order)->paginate($per_page);
        } else {
            $categories = Category::Select('id', 'slug', 'name', 'default', 'category_id', 'created_at')->orderBy($field, $order)->paginate($per_page);
        }

        return response()->json([
            'pagination' => [
                'total' => $categories->total(),
                'currentPage' => $categories->currentPage(),
                'perPage' => $categories->perPage(),
                'last_page' => $categories->lastPage(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem(),
            ],
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function parents(Request $request)
    {
        //
        $categories = Category::where('id', '<>', 1)->get();

        return response()->json($categories);
    }


    public function create(Request $request)
    {
        //
        $slug = $request->slug != "null" ? $request->slug : null;
        $name = $request->name != "null" ? $request->name : null;
        $description = $request->description != "null" ? $request->description : null;
        $parent_id = $request->parent != "false" ? $request->parent : null;
        if ($name  == null) :
            return response()->json(['error' => 'Name Cannot be null']);
        endif;
        if ($slug == null) :
            $slug = str_replace(" ", "-", strtolower($name));
        else :
            if ($slug != null) :
                $slug = str_replace(" ", "-", strtolower($slug));

            endif;
        endif;
        $query_slug = Category::where('slug', '=', $slug)->first();
        if ($query_slug != null) :
            return response()->json(['error' => 'Slug must be unique']);
        endif;
        $category = Category::create([
            'slug' => $slug,
            'name' => $name,
            'description' => $description,
            'category_id' => $parent_id,
        ]);
        return response()->json(['success' => 'Category Successfully Created', 'category' => $category]);
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setDefault(Request $request, Category $category)
    {
        //
        $categories = Category::all();
        foreach($categories as $category_reset){
            $category_reset->default = 0;
            $category_reset->update();
        }
        $category->default = 1;
        $category->update();
        if ($category->update()) {
            return response()->json([
                'success' => 'Modified default category', 'default' => $category->default
            ]);
        }else{
            return response()->json([
                'errors' => 'Failed to modify the default category',
            ]);
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
        //

        $id_category = intval($id);
        if (!is_int($id_category)) :
            return response()->json(['error' => 'The id must be an integer']);
        endif;
        $category = Category::find($id_category);


        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
        $this->validate($request, [
            'slug' => 'required|alpha_dash|max:80|unique:categories,slug,' . $category->id,
            'name' => 'required|string|max:60',
            'description' => 'string|nullable',
        ]);

        $slug = $request->slug != "null" ? $request->slug : null;
        $name = $request->name != "null" ? $request->name : null;
        $description = $request->description != "null" ? $request->description : null;
        $category_id = $request->parent != false  ? $request->parent : null;
        $category->slug = $slug;
        $category->name = $name;
        $category->description = $description;
        $category->category_id = $category_id;
        $category->update();
        if ($category->update()) {
            return response()->json([
                'success' => 'Category Updated Succesfully',
            ]);
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
        //

        $category = Category::find($id);
        if ($category->default == 1) {
            return response()->json(['errors' => $category->name . ' is the default category.']);
        } else {
            $categories = Category::where('category_id', '=', $category->id)->get();
            if (count($categories) > 0) {
                foreach ($categories as $category_child) {
                    $category_child->category_id = NULL;
                    $category_child->update();
                }
                if ($category->delete()) {

                    return response()->json(['success' => 'Category Successfully Delete.']);
                } else {
                    return response()->json(['errors' => 'Failed to delete category.']);
                }
            } else {
                if ($category->delete()) {

                    return response()->json(['success' => 'Category Successfully Delete.']);
                } else {
                    return response()->json(['errors' => 'Failed to delete category.']);
                }
            }
        }
    }
}
