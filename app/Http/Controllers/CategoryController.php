<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Category;
use App\CourseCategory;
use DB;
use App\CategoryBrand;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:category');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $category = Category::all();
//        foreach ($category as $ct){
//            $ct->link = 'category_'.$ct->id;
//            $ct->save();
//        }
//        die('done');
        return view('admin.category.category', compact('category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $parent = DB::table('category')->lists('title', 'id');
        $brands = DB::table('brands')->lists('name', 'id');
        return view('admin.category.createcategory', compact('parent', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $add = new Category();
        $add->title = $request->title;
        $add->title_ar = $request->title_ar;
        $link = str_replace(" ", "-", $add->title);
        $add->link = str_replace("/", "-", $link);

        $add->status = $request->status;
        $add->parent = $request->parent;
        $add->meta_keywords = $request->meta_keywords;
        $add->meta_description = $request->meta_description;
        $add->save();

        $add->link = 'category_' . $add->id;
        $add->save();
        return redirect('category');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $category = Category::find($id);
        $brands = DB::table('brands')->lists('name', 'id');
        $catbrands = DB::table('category_brand')->where('category_id', $category->id)->lists('brand_id');
        $parent = DB::table('category')->lists('title', 'id');
        return view('admin.category.editcategory', compact('category', 'parent', 'brands', 'catbrands'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $add = Category::find($id);
        $add->title = $request->title;
        $add->title_ar = $request->title_ar;
        $link = str_replace(" ", "-", $request->link);
        $add->link = str_replace("/", "-", $link);
        $add->link = 'category_' . $add->id;
        $add->status = $request->status;
        $add->parent = $request->parent;
        $add->meta_keywords = $request->meta_keywords;
        $add->meta_description = $request->meta_description;
        $add->save();
        CategoryBrand::where('category_id', $add->id)->delete();
        if ($request->brands) {
            foreach ($request->brands as $x) {
                if ($x != '') {
                    $y = new CategoryBrand();
                    $y->category_id = $add->id;
                    $y->brand_id = $x;
                    $y->save();
                }

            }
        }
        return redirect('category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($ids)
    {
        //
        $ids = explode(',', $ids);
        if ($ids[0] == 'on') {
            unset($ids[0]);
        }
        foreach ($ids as $id) {
            $c = Category::findOrFail($id);
            $c->delete();
        }
    }
}
