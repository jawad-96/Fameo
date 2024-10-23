<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Courier;
use Illuminate\Http\Request;
use Session, Image, File, Hashids, DataTables;


class CourierController extends Controller
{

    public $resource = 'admin/couriers';

    public function __construct()
    {
        $this->middleware('permission:view couriers', ['only' => ['index']]);        
        $this->middleware('permission:add couriers', ['only' => ['create','store']]);        
        $this->middleware('permission:edit couriers', ['only' => ['edit','update']]);        
        $this->middleware('permission:delete couriers', ['only' => ['destroy']]);        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {             

        if($request->ajax()){

            $couriers = Courier::query();                
        
            return Datatables::of($couriers)                
                ->addColumn('action', function ($courier) {
                    $action = '';
                    if(Auth::user()->can('edit couriers'))
                        $action .= '<a href="couriers/'. Hashids::encode($courier->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Courier"><i class="fa fa-lg fa-edit"></i></a>';
                    if(Auth::user()->can('delete couriers'))
                        $action .= '<a href="couriers/'.Hashids::encode($courier->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Courier"><i class="fa fa-lg fa-trash"></i></a>';

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
            'charges' => 'required|numeric',            
        ]);   
        
       $requestData = $request->all();              
        
        Courier::create($requestData);
        
        Session::flash('success', 'Courier added!');        

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
        
        $courier = Courier::findOrFail($id);       

        return view($this->resource.'/edit', compact('courier'));
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
            'charges' => 'required|numeric',            
        ]);
        
        $requestData = $request->all();                   
        
        $courier = Courier::findOrFail($id);  
        
        $courier->update($requestData);  

        Session::flash('success', 'Courier updated!');

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
        
        $courier = Courier::find($id);
        
        if($courier){
            
            $courier->delete();
            $response['message'] = 'Courier deleted!';
            $status = $this->successStatus;  
        }else{
            $response['message'] = 'Courier not exist against this id!';
            $status = $this->errorStatus;  
        }
        
        return response()->json(['result'=>$response], $status);

    }

}
