<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Store;
use Illuminate\Http\Request;
use Session, Alert, Image, File, Hashids, DataTables;

class StoreController extends Controller
{
    
    public $resource = 'admin/stores';
    public $uploadPath = 'uploads/stores';

    public function __construct()
   {
        $this->middleware('permission:view stores', ['only' => ['index']]);        
        $this->middleware('permission:add stores', ['only' => ['create','store']]);        
        $this->middleware('permission:edit stores', ['only' => ['edit','update']]);        
        $this->middleware('permission:delete stores', ['only' => ['destroy']]); 
        
        $this->uploadPath = public_path($this->uploadPath);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) 
    {      

        if($request->ajax()){
            $stores = Store::orderBy('id','desc');                
            
            return DataTables::of($stores)
                ->addColumn('image', function ($store) {
                    return '<img width="30" src="'.checkImage('stores/thumbs/'. $store->image).'" />';
                })
                ->addColumn('action', function ($store) {
                    $action = '';
                    if(Auth::user()->can('edit stores'))
                        $action .= '<a href="stores/'. Hashids::encode($store->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Store"><i class="fa fa-lg fa-edit"></i></a>';
                    if(Auth::user()->can('delete stores'))
                        $action .= '<a href="stores/'. Hashids::encode($store->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Store"><i class="fa fa-lg fa-trash"></i></a>';

                    return $action;
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->rawColumns(['image', 'action'])
                ->make(true);
         }
            
        return view($this->resource.'/index');
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {               
        return view($this->resource.'/create');                
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {            
        $this->validate($request, [             
            'name' => 'required|max:255',   
            'address' => 'required'
        ]);   
        
       $requestData = $request->all();         
        
        $store = Store::create($requestData);
        
        //save logo image
        if($request->hasFile('image')){
            $image = $request->file('image'); // file
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $fileName = $store->id.'-'.str_random(10).'.'.$extension; // renameing image
            
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($this->uploadPath.'/thumbs/'.$fileName);

            $image->move($this->uploadPath, $fileName); // uploading file to given path
            
            //update image record
            $store_image['image'] = $fileName;
            $store->update($store_image);
        }
        
        Session::flash('success', 'Store added!');        

        return redirect($this->resource);  
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        
        $id = decodeId($id);
        $store = Store::findOrFail($id);       

        return view($this->resource.'/edit', compact('store'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {        
        $id = decodeId($id);
        
        $this->validate($request, [
            'name' => 'required|max:255',            
            'address' => 'required',                                              
        ]);
        
        $requestData = $request->all();                   
        
        $store = Store::findOrFail($id);
        $store->update($requestData);        
        
        //save store image
        if($request->hasFile('image')){
            $image = $request->file('image'); // file
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $fileName = $store->id.'-'.str_random(10).'.'.$extension; // renameing image
            
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($this->uploadPath.'/thumbs/'.$fileName);

            $image->move($this->uploadPath, $fileName); // uploading file to given path
            
            //remove old image
            File::delete($this->uploadPath .'/'. $store->image);
            File::delete($this->uploadPath .'/thumbs/'. $store->image);
            
            //update image record
            $store_image['image'] = $fileName;
            $store->update($store_image);
        }                        
        
        Session::flash('success', 'Store updated!');

        return redirect($this->resource);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {   
        $id = decodeId($id);
        
        $store = Store::find($id);
        
        if($store){
            $store->delete();
            $response['message'] = 'Store deleted!';
            $status = $this->successStatus;  
        }else{
            $response['message'] = 'Store not exist against this id!';
            $status = $this->errorStatus;  
        }
        
        return response()->json(['result'=>$response], $status);

    }

}
