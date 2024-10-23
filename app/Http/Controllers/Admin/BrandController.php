<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Brand;
use Illuminate\Http\Request;
use Session, Image, File, Hashids, DataTables;


class BrandController extends Controller
{

    public $resource = 'admin/brands';
    public $uploadPath = 'uploads/brands';

    public function __construct()
   {
        $this->middleware('permission:view brands', ['only' => ['index']]);        
        $this->middleware('permission:add brands', ['only' => ['create','store']]);        
        $this->middleware('permission:edit brands', ['only' => ['edit','update']]);        
        $this->middleware('permission:delete brands', ['only' => ['destroy']]); 
        
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

            $brands = Brand::query();                
        
            return Datatables::of($brands)
                ->addColumn('image', function ($brand) {
                    return '<img width="30" src="'.checkImage('brands/thumbs/'. $brand->image).'" />';
                })
                ->addColumn('action', function ($brand) {
                    $action = '';
                    if(Auth::user()->can('edit brands'))
                        $action .= '<a href="brands/'. Hashids::encode($brand->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Brand"><i class="fa fa-lg fa-edit"></i></a>';
                    if(Auth::user()->can('delete brands'))
                        $action .= '<a href="brands/'.Hashids::encode($brand->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Brand"><i class="fa fa-lg fa-trash"></i></a>';

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
            'name' => 'required|max:255'                      
        ]);   
        
       $requestData = $request->all();         
       $requestData['company_id'] = Auth::id();         
        
        $brand = Brand::create($requestData);
        
        //save image
        if($request->hasFile('image')){
            $image = $request->file('image'); // file
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $fileName = $brand->id.'-'.str_random(10).'.'.$extension; // renameing image
            
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($this->uploadPath.'/thumbs/'.$fileName);

            $image->move($this->uploadPath, $fileName); // uploading file to given path
            
            //update image record
            $brand_image['image'] = $fileName;
            $brand->update($brand_image);
        }
        
        Session::flash('success', 'Brand added!');        

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
        
        $brand = Brand::findOrFail($id);       

        return view($this->resource.'/edit', compact('brand'));
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
        
        $brand = Brand::findOrFail($id);
              
        
        //save image
        if($request->hasFile('image')){
            $image = $request->file('image'); // file
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $fileName = $brand->id.'-'.str_random(10).'.'.$extension; // renameing image
            
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($this->uploadPath.'/thumbs/'.$fileName);

            $image->move($this->uploadPath, $fileName); // uploading file to given path
            
            /*unlink old image*/            
            File::delete($this->uploadPath.'/'.$brand->image);
            File::delete($this->uploadPath.'/thumbs/'.$brand->image);
            
            //update image record
            $requestData['image'] = $fileName;
        }                        
        
        $brand->update($requestData);  

        Session::flash('success', 'Brand updated!');

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
        
        $brand = Brand::find($id);
        
        if($brand){

            /*unlink old image*/            
            File::delete($this->uploadPath.'/'.$brand->image);
            File::delete($this->uploadPath.'/thumbs/'.$brand->image);

            $brand->delete();
            $response['message'] = 'Brand deleted!';
            $status = $this->successStatus;  
        }else{
            $response['message'] = 'Brand not exist against this id!';
            $status = $this->errorStatus;  
        }
        
        return response()->json(['result'=>$response], $status);

    }

}
