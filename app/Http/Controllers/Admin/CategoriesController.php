<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Items;
use App\Models\Store;
use App\Models\Category_products;
use Session, Image, File, Hashids, DataTables;

class CategoriesController extends Controller
{
   
    public $resource = 'admin/categories';
    public $uploadPath = 'uploads/categories';

    public function __construct()
   {
        $this->middleware('permission:view categories', ['only' => ['index']]);        
        $this->middleware('permission:add categories', ['only' => ['create','store']]);        
        $this->middleware('permission:edit categories', ['only' => ['edit','update']]);        
        $this->middleware('permission:delete categories', ['only' => ['destroy']]); 
        
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
            $all_categories = Categories::with(['store'])->get();

            $categories = [];
            $this->getCategoriesRecursive($all_categories, $categories);              
            
            return DataTables::of($categories)
                ->addColumn('category_image', function ($category) {
                    return '<img width="30" src="'.checkImage('categories/'. $category['category_image']).'" />';
                })
                ->addColumn('store_id', function ($category) {
                    return @$category['store_name'];
                })
                ->addColumn('products_count', function ($category) {
                    //return @Category_products::where('category_id',$category['id'])->count();
                    return 2;
                })
                ->addColumn('action', function ($category) {
                    $action = '';
                    if(Auth::user()->can('edit categories'))
                        $action .= '<a href="categories/'. Hashids::encode($category['id']).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Category"><i class="fa fa-lg fa-edit"></i></a>';
                    if(Auth::user()->can('delete categories'))
                        $action .= '<a href="categories/'.Hashids::encode($category['id']).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Category""><i class="fa fa-lg fa-trash"></i></a>';

                    return $action;            
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->rawColumns(['category_image', 'products_count', 'action'])
                ->make(true);     
        }
            
        return view($this->resource.'/index');
    }
      
    
    // utility method to build the categories tree
    private function getCategoriesRecursive($all_categories, &$categories, $parent_id = 0, $depth = 0)
    {
        $cats = $all_categories->filter(function ($item) use ($parent_id) {
            return $item->parent_id == $parent_id;
        });

        foreach ($cats as $cat)
        {
                $categories[$cat->id] = array(
                  "id" => $cat->id, 
                  "category_image" => $cat->image,
                  "prefix" => $cat->prefix,
                  "category_name" => str_repeat('-', $depth) .' '. $cat->name, 
                  "store_name" => @$cat->store->name,
                  "created_at" => $cat->created_at, 
                );

            $this->getCategoriesRecursive($all_categories, $categories, $cat->id, $depth + 1);
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {               
        $store_ids = Store::pluck   ('id');
        
        $categories = ['0' => 'Root Category'];       
        
        return view($this->resource.'/create', compact('categories'));                
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
            'store_id' => 'required',
            'prefix' => 'required',
            'name' => 'required',
            'ordering' => 'required|integer|min:0'
        ]);
        
        $requestData = $request->all();              
        
        $category = Categories::create($requestData);
        
        //save category image
        if($request->hasFile('category_image')){
            $image = $request->file('category_image'); // file
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $fileName = $category->id.'-'.str_random(10).'.'.$extension; // renameing image
            
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($this->uploadPath.'/thumbs/'.$fileName);

            $image->move($this->uploadPath, $fileName); // uploading file to given path
            
            //update image record
            $category_image['image'] = $fileName;
            $category->update($category_image);
        }
        
        Session::flash('success', 'Category added!');        
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
        
        $category = Categories::with(['category'])->find($id); 
        
        if($category->category){
            $categories = ['0' => 'Root Category', $category->category->id => $category->category->name];
        }else{
            $categories = ['0' => 'Root Category'];
        }
        
        return view($this->resource.'/edit', compact('category','categories'));
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
            'store_id' => 'required', 
            'prefix' => 'required', 
            'name' => 'required', 
            'ordering' => 'required|integer|min:0'
        ]);
        
        $requestData = $request->all();                   
        
        $category = Categories::findOrFail($id);
        
        //save category image
        if($request->hasFile('category_image')){
            $image = $request->file('category_image'); // file
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $fileName = $category->id.'-'.str_random(10).'.'.$extension; // renameing image
            
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($this->uploadPath.'/thumbs/'.$fileName);

            $image->move($this->uploadPath, $fileName); // uploading file to given path
            
            //remove old image
            File::delete($this->uploadPath .'/'. $category->image);
            File::delete($this->uploadPath .'/thumbs/'. $category->image);
            
            //update image record
            $requestData['image'] = $fileName;
        }       

        $category->update($requestData);        
        
        Session::flash('success', 'Category updated!');

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
        
        $categories = Categories::find($id);
        
        if($categories){
            $categories->delete();
            $response['message'] = 'Categories deleted!';
            $status = $this->successStatus;  
        }else{
            $response['message'] = 'Categories not exist against this id!';
            $status = $this->errorStatus;  
        }
        
        return response()->json(['result'=>$response], $status);

    }
    
    /**
     * getStoreCategories function
     *      
     * @param  int  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function getStoreCategories($store_id, Request $request)
    {      
        $categories = Categories::where('parent_id',0)->where('store_id',$store_id)->get();                                                 
        
        $categories = $categories->all(); 
        
        $status = $this->successStatus;

        return response()->json(['results' => $categories], $status);

    }    
    
}
