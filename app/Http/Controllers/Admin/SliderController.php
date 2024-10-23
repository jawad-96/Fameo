<?php



namespace App\Http\Controllers\Admin;



use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Image, Hashids,DataTables,Auth,Session, File;

class SliderController extends Controller

{
    public $resource = 'admin/sliders';
    public $uploadPath = 'uploads/sliders';

    public function __construct()
   {
        $this->middleware('permission:view sliders', ['only' => ['index']]);        
        $this->middleware('permission:add sliders', ['only' => ['create','store']]);        
        $this->middleware('permission:edit sliders', ['only' => ['edit','update']]);        
        $this->middleware('permission:delete sliders', ['only' => ['destroy']]); 
        
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
            $sliders = Slider::orderBy('updated_at','desc');             

            return DataTables::of($sliders)
                ->addColumn('image', function ($slider) {
                    return '<img width="60px" src="'. $slider->image_thumb.'" alt="" />';
                })
                ->addColumn('status', function ($slider) {
                    if($slider->status == 1){
                        return '<a href="javascript:void(0)" class="btn btn-xs btn-success" data-toggle="tooltip" title="Active"><i class="fa fa-check"></i> Active</a>';
                    }else{
                        return '<a href="javascript:void(0)" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Inactive"><i class="fa fa-times"></i> Inactive</a>';
                    }               
                })
                ->addColumn('action', function ($slider) {
                    
                    $action = '';
                    if(Auth::user()->can('edit sliders'))
                        $action .= '<a href="sliders/'.Hashids::encode($slider->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Slider"><i class="fa fa-lg fa-edit"></i></a>';
                    if(Auth::user()->can('delete sliders'))
                        $action .= '<a href="sliders/'.Hashids::encode($slider->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Slider"><i class="fa fa-lg fa-trash"></i></a>';

                    return $action;        
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->rawColumns(['status','image','action'])
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
            //'html' => 'required',            
            'ordering' => 'required',        
            'status' => 'required'        
        ]);   


        $requestData = $request->all();  

        $slider = Slider::create($requestData);        

        //save image
        if($request->hasFile('image')){
            $destinationPath = public_path('uploads/sliders'); // upload path
            $image = $request->file('image'); // file
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $fileName = $slider->id.'-'.str_random(10).'.'.$extension; // renameing image

            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/thumbs/'.$fileName);

            $image->move($destinationPath, $fileName); // uploading file to given path

            //update image record
            $slider_image['image'] = $fileName;
            $slider->update($slider_image);
        
        }


        Session::flash('success', 'Slider successfully created!');        

        return redirect($this->resource);  

    }


    /**
     * Show the detail of user.
     *
     * @return \Illuminate\View\View
     */

    public function show($id)
    {        
        exit;
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
        $slider = Slider::find($id);

        return view($this->resource.'/edit', compact('slider'));
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
            //'html' => 'required',        
            'ordering' => 'required',        
            'status' => 'required'        
        ]);            

        
        $slider = Slider::findOrFail($id);

        $requestData = $request->all();

        //save image
        if($request->hasFile('image')){
            $destinationPath = 'uploads/sliders'; // upload path
            $image = $request->file('image'); // file
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $fileName = $slider->id.'-'.str_random(10).'.'.$extension; // renameing image

            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/thumbs/'.$fileName);

            $image->move($destinationPath, $fileName); // uploading file to given path

            //update image record
            $requestData['image'] = $fileName;            
        }
        

        $slider->update($requestData);

        
        Session::flash('success', 'Slider successfully updated!');

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

        $slider = Slider::find($id);

        if($slider){
            /*unlink old image*/            
            File::delete($this->uploadPath.'/'.$slider->image);
            File::delete($this->uploadPath.'/thumbs/'.$slider->image);

            $slider->delete();
            $response['message'] = 'Slider successfully deleted!';
            $status = $this->successStatus;  

        }else{

            $response['message'] = 'Slider not exist against this id!';
            $status = $this->notFoundStatus;  

        }

        return response()->json(['result'=>$response], $status);

    }   


}

