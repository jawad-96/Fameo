<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Currency;
use Illuminate\Http\Request;
use Session, Image, File, Hashids, DataTables, Auth;

class CurrencyController extends Controller
{
    public $resource = 'admin/currencies';
    
    public function __construct()
   {
        $this->middleware('permission:view currencies', ['only' => ['index']]);        
        $this->middleware('permission:add currencies', ['only' => ['create','store']]);        
        $this->middleware('permission:edit currencies', ['only' => ['edit','update']]);        
        $this->middleware('permission:delete currencies', ['only' => ['destroy']]);        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) 
    {      

        if($request->ajax()){
            $currencies = Currency::query();                
        
            return DataTables::of($currencies)
                ->addColumn('direction', function ($currency) {
                    if($currency->direction == 'left')
                        return 'Left';
                    elseif($currency->direction == 'right')
                        return 'Right';
                })
                ->addColumn('action', function ($currency) {
                    $action = '';
                    if(Auth::user()->can('edit currencies'))
                        $action .= '<a href="currencies/'. Hashids::encode($currency->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Currency"><i class="fa fa-lg fa-edit"></i></a>';
                    if(Auth::user()->can('delete currencies'))
                        $action .= '<a href="currencies/'.Hashids::encode($currency->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Currency" ><i class="fa fa-lg fa-trash"></i></a>';

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
            'code' => 'required',            
            'name' => 'required|max:255',                           
            'symbol' => 'required',
            'direction' => 'required',
        ]);   
        
       $requestData = $request->all();         
        
        Currency::create($requestData);
        
        Session::flash('success', 'Currency added!');        

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
        
        $currency = Currency::findOrFail($id);       

        return view($this->resource.'/edit', compact('currency'));
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
            'code' => 'required',            
            'name' => 'required|max:255',                           
            'symbol' => 'required',    
            'direction' => 'required',
        ]);
        
        $requestData = $request->all();                   
        
        $currency = Currency::findOrFail($id);
        $currency->update($requestData);                               
        
        Session::flash('success', 'Currency updated!');

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
        
        $currency = Currency::find($id);
        
        if($currency){
            $currency->delete();
            $response['message'] = 'Currency deleted!';
            $status = $this->successStatus;  
        }else{
            $response['message'] = 'Currency not exist against this id!';
            $status = $this->errorStatus;  
        }
        
        return response()->json(['result'=>$response], $status);

    }

}
