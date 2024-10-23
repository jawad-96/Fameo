<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Http\Request;
use Session,Image,File,Hashids,DataTables,DB,Auth;



class PermissionController extends Controller
{
    
    public function __construct()
   {
        $this->middleware('permission:view permissions', ['only' => ['index']]);        
        $this->middleware('permission:add permissions', ['only' => ['create','store']]);        
        $this->middleware('permission:edit permissions', ['only' => ['edit','update']]);        
        $this->middleware('permission:delete permissions', ['only' => ['destroy']]);        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
        if($request->ajax()){
            $permissions = Permission::query();                
        
            return DataTables::of($permissions)
                ->addColumn('action', function ($permission) {
                    $action = '';
                    if(Auth::user()->can('edit permissions'))
                        $action .= '<a href="permissions/'. Hashids::encode($permission->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Permission"><i class="fa fa-lg fa-edit"></i> </a>';
                    if(Auth::user()->can('delete permissions'))
                        $action .= '<a href="permissions/'.Hashids::encode($permission->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Permission"><i class="fa fa-lg fa-trash"></i></a>';

                    return $action;
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->make(true);
        }
        return view('admin.permissions.index');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        
       return view('admin.permissions.create'); 
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|unique:permissions,name',
        ]);
        
        $requestData = $request->all();
        $requestData['guard_name'] = 'admin';
        
        Permission::create($requestData);
        
        Session::flash('success', 'Permission added!');   
        
        return redirect('admin/permissions');
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
        
        $id = decodeId($id);
        
        $permission = Permission::findOrFail($id);
        
        return view('admin.permissions.edit',compact('permission')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        
        $id = Hashids::decode($id)[0];
        
        $this->validate($request, [ 
            'name' => 'required|unique:permissions,name,'.$id,
        ]);
        
        $permission = Permission::findOrFail($id);
        
        $permission->update($request->all());
        
        Session::flash('success', 'Permission updated!');   
        
        return redirect('admin/permissions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $id = decodeId($id);
        
        $permission = Permission::find($id);
        
        if($permission){
            $permission->delete();
            $response['message'] = 'Permission deleted!';
            $status = $this->successStatus;  
        }else{
            $response['message'] = 'Permission not exist against this id!';
            $status = $this->errorStatus;  
        }
        
        return response()->json(['result'=>$response], $status);

    }
           
    
}
