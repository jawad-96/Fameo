<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

use DataTables, Session, Hashids, Auth;

class PageController extends Controller
{
    public $resource = 'admin/pages';

    public function __construct()
   {
        $this->middleware('permission:view pages', ['only' => ['index']]);        
        $this->middleware('permission:add pages', ['only' => ['create','store']]);        
        $this->middleware('permission:edit pages', ['only' => ['edit','update']]);        
        $this->middleware('permission:delete pages', ['only' => ['destroy']]);        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $pages = Page::query();                
        
            return DataTables::of($pages)
                ->addColumn('action', function ($page) {
                    $action = '';
                    if(Auth::user()->can('edit pages'))
                        $action .= '<a href="pages/'. Hashids::encode($page->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Page"><i class="fa fa-lg fa-edit"></i></a>';
                    if(Auth::user()->can('delete pages'))
                        $action .= '<a href="pages/'.Hashids::encode($page->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Page" ><i class="fa fa-lg fa-trash"></i></a>';

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
    public function create()
    {
        return view($this->resource.'/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'            => 'required|unique:pages',
            'meta_title'       => 'required',
            'meta_keywords'    => 'required',
            'meta_description' => 'required',
            'content'          => 'required'

        ]);

        $input = $request->all();
        $input['slug'] = Str::slug($request->title, '-');
        $page = Page::create($input);

        Session::flash('success', 'Page added!');  
        return redirect($this->resource);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decodeId($id);
        $page = Page::findOrFail($id);    
        return view($this->resource.'/edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $id = decodeId($id);
        $this->validate($request, [
            'title'            => 'required|unique:pages,title,'.$id,
            'meta_title'       => 'required',
            'meta_keywords'    => 'required',
            'meta_description' => 'required',
            'content'          => 'required'
        ]);

        $input = $request->all();
        $input['slug'] = Str::slug($request->title, '-');

        $page = Page::findOrFail($id);
        $page->update($input);                             
        
        Session::flash('success', 'Page updated!');
        return redirect('admin/pages');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = Hashids::decode($id)[0];
        
        $page = Page::find($id);
        
        if($page){
            $page->delete();
            $response['message'] = 'Page deleted!';
            $status = $this->successStatus;  
        }else{
            $response['message'] = 'Page not exist against this id!';
            $status = $this->errorStatus;  
        }
        
        return response()->json(['result'=>$response], $status);
    }
}
