<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Variant;
use Illuminate\Http\Request;
use Session, Image, File, Hashids, DataTables;

class VariantController extends Controller
{
    public $resource = 'admin/variants';

    public function __construct()
   {
        $this->middleware('permission:view attributes', ['only' => ['index']]);        
        $this->middleware('permission:add attributes', ['only' => ['create','store']]);        
        $this->middleware('permission:edit attributes', ['only' => ['edit','update']]);        
        $this->middleware('permission:delete attributes', ['only' => ['destroy']]);        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) 
    {      

        if($request->ajax()){

            $variants = Variant::query();                
            
            return DataTables::of($variants)
                ->addColumn('action', function ($variant) {
                    $action = '';
                    if(Auth::user()->can('edit attributes'))
                        $action .= '<a href="variants/'. Hashids::encode($variant->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Variant"><i class="fa fa-lg fa-edit"></i></a>';
                    if(Auth::user()->can('delete attributes'))
                        $action .= '<a href="variants/'.Hashids::encode($variant->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Variant"><i class="fa fa-lg fa-trash"></i></a>';

                    return $action;
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->rawColumns(['action'])
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
        ]);   
        
       $requestData = $request->all();         
       $requestData['company_id'] = Auth::id();         
        
        Variant::create($requestData);
        
        Session::flash('success', 'Variant added!');        

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
                        
        $id = Hashids::decode($id)[0];
        
        $variant = Variant::findOrFail($id);       

        return view($this->resource.'/edit', compact('variant'));
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
        $id = Hashids::decode($id)[0];
        
        $this->validate($request, [          
            'name' => 'required|max:255',                           
        ]);
        
        $requestData = $request->all();                   
        
        $variant = Variant::findOrFail($id);
        $variant->update($requestData);                               
        
        Session::flash('success', 'Variant updated!');

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
        $id = Hashids::decode($id)[0];
        
        $variant = Variant::find($id);
        
        if($variant){
            $variant->delete();
            $response['message'] = 'Variant deleted!';
            $status = $this->successStatus;  
        }else{
            $response['message'] = 'Variant not exist against this id!';
            $status = $this->errorStatus;  
        }
        
        return response()->json(['result'=>$response], $status);

    }

}
