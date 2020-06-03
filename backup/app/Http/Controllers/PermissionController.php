<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Permission;
use App\PermissionRole;
use DB;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:users');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $p = Permission::all();
        return view('admin.permissions.permission',compact('p'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
         $ur = DB::table('roles')->lists('display_name','id');
         return view('admin.permissions.createpermission',compact('ur'));
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
        $add = new Permission();
        $add->name = $request->Input('name');
        $add->display_name = $request->Input('display_name');
        $add->description = $request->Input('description');
        $add->save();

        if ($request->Input('roles_id')) {
            foreach ($request->Input('roles_id') as $ur) {
                $t = new PermissionRole();
                $t->permission_id = $add->id;
                $t->role_id = $ur;
                $t->save();
            }
        }
        
        return redirect('permissions');
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
        $permission = Permission::find($id);
        $ur = DB::table('roles')->lists('display_name','id');
        $urt = DB::table('permission_role')->where('permission_id', '=', $permission->id)->lists('role_id');

        return view('admin.permissions.editpermission',compact('permission','ur','urt'));
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
        $update = Permission::find($id);
        $update->name = $request->Input('name');
        $update->display_name = $request->Input('display_name');
        $update->description = $request->Input('description');
        $update->save();

        $tt = DB::table('permission_role')->where('permission_id', '=', $id)->delete();



         if ($request->Input('roles_id')) {
            foreach ($request->Input('roles_id') as $ur) {
                $t = new PermissionRole();
                $t->permission_id = $id;
                $t->role_id = $ur;
                $t->save();
            }
        }
        
        return redirect('permissions');
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
            $per = Permission::findOrFail($id);
            $per->delete();
        }
    }
}
