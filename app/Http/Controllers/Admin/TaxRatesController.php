<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\TaxRate;
use Illuminate\Http\Request;
use Session, Image, File, Hashids, DataTables;


class TaxRatesController extends Controller
{
    
    public $resource = 'admin/tax-rates';

    public function __construct()
   {
        $this->middleware('permission:view tax rates', ['only' => ['index']]);        
        $this->middleware('permission:add tax rates', ['only' => ['create','store']]);        
        $this->middleware('permission:edit tax rates', ['only' => ['edit','update']]);        
        $this->middleware('permission:delete tax rates', ['only' => ['destroy']]);        
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) 
    {      

        if($request->ajax()){
            $tax_rates = TaxRate::query();                
        
            return DataTables::of($tax_rates)
                ->addColumn('action', function ($tax_rate) {
                    $action = '';
                    if(Auth::user()->can('edit tax rates'))
                        $action .= '<a href="tax-rates/'. Hashids::encode($tax_rate->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Tax Rate"><i class="fa fa-lg fa-edit"></i></a>';
                    if(Auth::user()->can('delete tax rates'))
                        $action .= '<a href="tax-rates/'.Hashids::encode($tax_rate->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Tax Rate"><i class="fa fa-lg fa-trash"></i></a>';

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
            'name' => 'required|max:100',  
            'code' => 'required|max:50',            
            'rate' => 'required|numeric',
        ]);   
        
       $requestData = $request->all();         
       $requestData['company_id'] = Auth::id();         
        
        TaxRate::create($requestData);
        
        Session::flash('success', 'Tax rate added!');        

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
        
        $tax_rate = TaxRate::findOrFail($id);       

        return view($this->resource.'/edit', compact('tax_rate'));
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
            'name' => 'required|max:100',  
            'code' => 'required|max:50',            
            'rate' => 'required|numeric',
        ]);
        
        $requestData = $request->all();                   
        
        $tax_rate = TaxRate::findOrFail($id);
        $tax_rate->update($requestData);                               
        
        Session::flash('success', 'Tax rate updated!');

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
        
        $tax_rate = TaxRate::find($id);
        
        if($tax_rate){
            $tax_rate->delete();
            $response['message'] = 'Tax rate deleted!';
            $status = $this->successStatus;  
        }else{
            $response['message'] = 'Tax rate not exist against this id!';
            $status = $this->errorStatus;  
        }
        
        return response()->json(['result'=>$response], $status);

    }

}
