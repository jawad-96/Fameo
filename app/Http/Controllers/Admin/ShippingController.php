<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Shipping;
use Illuminate\Http\Request;
use Session, Image, File, Hashids, DataTables;


class ShippingController extends Controller
{
    public $resource = 'admin/shippings';
    public $uploadPath = 'uploads/shippings';

    public function __construct()
   {
        $this->middleware('permission:view shippings', ['only' => ['index']]);        
        $this->middleware('permission:add shippings', ['only' => ['create','store']]);        
        $this->middleware('permission:edit shippings', ['only' => ['edit','update']]);        
        $this->middleware('permission:delete shippings', ['only' => ['destroy']]);    
        
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

            $shippings = Shipping::query();                
        
            return Datatables::of($shippings)
                ->addColumn('image', function ($shipping) {
                    return '<img width="30" src="'.checkImage('shippings/thumbs/'. $shipping->image).'" />';
                })
                ->addColumn('action', function ($shipping) {
                    $action = '';
                    if(Auth::user()->can('edit shippings'))
                        $action .= '<a href="shippings/'. Hashids::encode($shipping->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Shipping"><i class="fa fa-lg fa-edit"></i></a>';
                    if(Auth::user()->can('delete shippings'))
                        $action .= '<a href="shippings/'.Hashids::encode($shipping->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Shipping"><i class="fa fa-lg fa-trash"></i></a>';

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
        
        $shipping = Shipping::create($requestData);
        
        //save image
        if($request->hasFile('image')){
            $image = $request->file('image'); // file
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $fileName = $shipping->id.'-'.str_random(10).'.'.$extension; // renameing image
            
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($this->uploadPath.'/thumbs/'.$fileName);

            $image->move($this->uploadPath, $fileName); // uploading file to given path
            
            //update image record
            $shipping_image['image'] = $fileName;
            $shipping->update($shipping_image);
        }
        
        Session::flash('success', 'Shipping added!');        

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
        
        $shipping = Shipping::findOrFail($id);       

        return view($this->resource.'/edit', compact('shipping'));
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
            'charges' => 'required|numeric',            
        ]);
        
        $requestData = $request->all();                   
        
        $shipping = Shipping::findOrFail($id);
              
        
        //save image
        if($request->hasFile('image')){
            $image = $request->file('image'); // file
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $fileName = $shipping->id.'-'.str_random(10).'.'.$extension; // renameing image
            
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($this->uploadPath.'/thumbs/'.$fileName);

            $image->move($this->uploadPath, $fileName); // uploading file to given path
            
            /*unlink old image*/            
            File::delete($this->uploadPath.'/'.$shipping->image);
            File::delete($this->uploadPath.'/thumbs/'.$shipping->image);
            
            //update image record
            $requestData['image'] = $fileName;
        }                        
        
        $shipping->update($requestData);  

        Session::flash('success', 'Shipping updated!');

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
        
        $shipping = Shipping::find($id);
        
        if($shipping){
            
            /*unlink old image*/            
            File::delete($this->uploadPath.'/'.$shipping->image);
            File::delete($this->uploadPath.'/thumbs/'.$shipping->image);

            $shipping->delete();
            $response['message'] = 'Shipping deleted!';
            $status = $this->successStatus;  
        }else{
            $response['message'] = 'Shipping not exist against this id!';
            $status = $this->errorStatus;  
        }
        
        return response()->json(['result'=>$response], $status);

    }

}
