<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Faq;

use DataTables, Session, Hashids, Auth;

class FaqsController extends Controller
{
    public $resource = 'admin/faqs';
   
   public function __construct()
   {
        $this->middleware('permission:view faqs', ['only' => ['index']]);        
        $this->middleware('permission:add faqs', ['only' => ['create','store']]);        
        $this->middleware('permission:edit faqs', ['only' => ['edit','update']]);        
        $this->middleware('permission:delete faqs', ['only' => ['destroy']]);        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $faqs = Faq::orderBy('ordering');                
        
            return DataTables::of($faqs)
                ->addColumn('direction', function ($faq) {
                    if($faq->direction == 'left')
                        return 'Left';
                    elseif($faq->direction == 'right')
                        return 'Right';
                })
                ->addColumn('action', function ($faq) {
                    $action = '';
                    if(Auth::user()->can('edit faqs'))
                        $action .= '<a href="faqs/'. Hashids::encode($faq->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Faq"><i class="fa fa-lg fa-edit"></i></a>';
                    if(Auth::user()->can('delete faqs'))
                        $action .= '<a href="faqs/'.Hashids::encode($faq->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Faq" ><i class="fa fa-lg fa-trash"></i></a>';

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
     * @return \Illuminate\Http\Response
     */
    public function create(){
        
        return view($this->resource.'/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        //
        $this->validate($request, [
            'question'            => 'required',
            'answer'            => 'required'
        ]);
        $input = $request->all();
        $faq = Faq::create($input);
        
        Session::flash('success', 'Faq added!');  
        return redirect($this->resource);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decodeId($id);
        $faq = Faq::findOrFail($id);    
        return view($this->resource.'/edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $id = decodeId($id);
        $this->validate($request, [
            'question'            => 'required',
            'answer'            => 'required'
        ]);
        $input = $request->all();
        $faq = Faq::findOrFail($id);
        $faq->update($input);
              
        Session::flash('success', 'Faq updated!');
        return redirect($this->resource);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = Hashids::decode($id)[0];
        
        $faq = Faq::find($id);
        
        if($faq){
            $faq->delete();
            $response['message'] = 'Faq deleted!';
            $status = $this->successStatus;  
        }else{
            $response['message'] = 'Faq not exist against this id!';
            $status = $this->errorStatus;  
        }
        
        return response()->json(['result'=>$response], $status);
    }
    
}
