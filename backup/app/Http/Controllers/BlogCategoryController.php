<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\BlogCategory;

class BlogCategoryController extends Controller
{


public function __construct()
    {
        $this->middleware('permission:blogcategory');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $blogcategory = BlogCategory::orderBy('id','desc')->get();
        return view('admin.blogcategory.blogcategory',compact('blogcategory'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.blogcategory.createblogcategory');
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
        $blogcategory = new BlogCategory();
        $blogcategory->title = $request->title;
        $link = str_replace(" ","-",$blogcategory->title);
        $blogcategory->link = str_replace("/","-",$link);
        $blogcategory->date = $request->date;
        $blogcategory->status = $request->status;
        $blogcategory->meta_keywords = $request->meta_keywords;
        $blogcategory->meta_description = $request->meta_description;
        $blogcategory->save();
        return redirect('blogcategory');
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
        $blogcategory = BlogCategory::find($id);
        return view('admin.blogcategory.editblogcategory',compact('blogcategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $blogcategory = BlogCategory::find($id);
        $blogcategory->title = $request->title;
        $link = str_replace(" ","-",$request->link);
        $blogcategory->link = str_replace("/","-",$link);
        $blogcategory->date = $request->date;
        $blogcategory->status = $request->status;
        $blogcategory->meta_keywords = $request->meta_keywords;
        $blogcategory->meta_description = $request->meta_description;
        $blogcategory->save();
        return redirect('blogcategory');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
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
            $m = BlogCategory::findOrFail($id);
            $m->delete();
        }
    }
}
