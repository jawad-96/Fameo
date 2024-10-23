<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Product;
use App\Models\Promotion;
use App\Models\PromotionProduct;
use Illuminate\Http\Request;
use Session, Image, File, Hashids, DataTables;


class PromotionController extends Controller
{
    public $resource = 'admin/promotions';    

    public function __construct()
   {
        $this->middleware('permission:view promotions', ['only' => ['index']]);        
        $this->middleware('permission:add promotions', ['only' => ['create','store']]);        
        $this->middleware('permission:edit promotions', ['only' => ['edit','update']]);        
        $this->middleware('permission:delete promotions', ['only' => ['destroy']]);        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {             
        if($request->ajax()){

            $promotions = Promotion::with('products')->orderBy('start_time','desc');                
        
            return Datatables::of($promotions)
                ->editColumn('start_time', function ($promotion) {
                    return date('d M Y h:i a',strtotime($promotion->start_time));
                })
                ->editColumn('end_time', function ($promotion) {
                    return date('d M Y h:i a',strtotime($promotion->end_time));
                })
                ->addColumn('total_products', function ($promotion) {
                    return @$promotion->products->count();
                })
                ->addColumn('action', function ($promotion) {
                    $action = '';
                    if(Auth::user()->can('edit promotions'))
                        $action .= '<a href="promotions/'. Hashids::encode($promotion->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Promotion"><i class="fa fa-lg fa-edit"></i></a>';
                    if(Auth::user()->can('delete promotions'))
                        $action .= '<a href="promotions/'.Hashids::encode($promotion->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Promotion"><i class="fa fa-lg fa-trash"></i></a>';

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
        $products = Product::whereProductId(0)->pluck('name','id');
        $product_ids = [];
        return view($this->resource.'/create',compact('products','product_ids'));                
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
            'product_ids' => 'required',                      
            'start_time' => 'required',                      
            'end_time' => 'required',                      
        ]);   
        
       $requestData = $request->all();                 
        
        $promotion = Promotion::create($requestData);
        
        if($promotion){
            foreach($request->product_ids as $product_id){
                PromotionProduct::create(['promotion_id'=>$promotion->id,'product_id'=>$product_id]);
            }
        }

        Session::flash('success', 'Promotion added!');        

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
        
        $products = Product::whereProductId(0)->pluck('name','id');

        $promotion = Promotion::with('products')->findOrFail($id);       
        $product_ids = $promotion->products->pluck('id');

        return view($this->resource.'/edit', compact('promotion','products','product_ids'));
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
            'product_ids' => 'required',                      
            'start_time' => 'required',                      
            'end_time' => 'required',                      
        ]);   
        
        $requestData = $request->all();                   
        
        $promotion = Promotion::findOrFail($id);                                  
        $promotion->update($requestData);  

        if($promotion){
            PromotionProduct::wherePromotionId($id)->delete();
            foreach($request->product_ids as $product_id){
                PromotionProduct::create(['promotion_id'=>$promotion->id,'product_id'=>$product_id]);
            }
        }

        Session::flash('success', 'Promotion updated!');

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
        
        $promotion = Promotion::find($id);
        
        if($promotion){
            PromotionProduct::wherePromotionId($id)->delete();
            $promotion->delete();

            $response['message'] = 'Promotion deleted!';
            $status = $this->successStatus;  
        }else{
            $response['message'] = 'Promotion not exist against this id!';
            $status = $this->errorStatus;  
        }
        
        return response()->json(['result'=>$response], $status);

    }

}
