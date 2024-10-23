<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Session, Image, File, Hashids, DataTables;


class SuppliersController extends Controller
{
    
    public $resource = 'admin/suppliers';
    public $uploadPath = 'uploads/suppliers';

    public function __construct()
   {
        $this->middleware('permission:view suppliers', ['only' => ['index']]);        
        $this->middleware('permission:add suppliers', ['only' => ['create','store']]);        
        $this->middleware('permission:edit suppliers', ['only' => ['edit','update']]);        
        $this->middleware('permission:delete suppliers', ['only' => ['destroy']]);  
        
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

            $suppliers = Supplier::query();                
        
            return Datatables::of($suppliers)
                ->addColumn('image', function ($supplier) {
                    return '<img width="30" src="'.checkImage('suppliers/thumbs/'. $supplier->image).'" />';
                })
                ->addColumn('action', function ($supplier) {
                    $action = '';
                    if(Auth::user()->can('edit suppliers'))
                        $action .= '<a href="suppliers/'. Hashids::encode($supplier->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Supplier"><i class="fa fa-lg fa-edit"></i></a>';
                    if(Auth::user()->can('delete suppliers'))
                        $action .= '<a href="suppliers/'.Hashids::encode($supplier->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Supplier"><i class="fa fa-lg fa-trash"></i></a>';

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
            'code' => 'required|numeric',            
            'name' => 'required|max:255'                      
        ]);   
        
       $requestData = $request->all();                 
        
        $supplier = Supplier::create($requestData);
        
        //save image
        if($request->hasFile('image')){
            $image = $request->file('image'); // file
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $fileName = $supplier->id.'-'.str_random(10).'.'.$extension; // renameing image
            
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($this->uploadPath.'/thumbs/'.$fileName);

            $image->move($this->uploadPath, $fileName); // uploading file to given path
            
            //update image record
            $supplier_image['image'] = $fileName;
            $supplier->update($supplier_image);
        }
        
        Session::flash('success', 'Supplier added!');        

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
        
        $supplier = Supplier::findOrFail($id);       

        return view($this->resource.'/edit', compact('supplier'));
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
            'code' => 'required|numeric',            
            'name' => 'required|max:255',                           
        ]);
        
        $requestData = $request->all();                   
        
        $supplier = Supplier::findOrFail($id);
              
        
        //save image
        if($request->hasFile('image')){
            $image = $request->file('image'); // file
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $fileName = $supplier->id.'-'.str_random(10).'.'.$extension; // renameing image
            
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($this->uploadPath.'/thumbs/'.$fileName);

            $image->move($this->uploadPath, $fileName); // uploading file to given path
            
            /*unlink old image*/            
            File::delete($this->uploadPath.'/'.$supplier->image);
            File::delete($this->uploadPath.'/thumbs/'.$supplier->image);
            
            //update image record
            $requestData['image'] = $fileName;
        }                        
        
        $supplier->update($requestData);  

        Session::flash('success', 'Supplier updated!');

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
        
        $supplier = Supplier::find($id);
        
        if($supplier){

            /*unlink old image*/            
            File::delete($this->uploadPath.'/'.$supplier->image);
            File::delete($this->uploadPath.'/thumbs/'.$supplier->image);
            
            $supplier->delete();
            $response['message'] = 'Supplier deleted!';
            $status = $this->successStatus;  
        }else{
            $response['message'] = 'Supplier not exist against this id!';
            $status = $this->errorStatus;  
        }
        
        return response()->json(['result'=>$response], $status);

    }

}
