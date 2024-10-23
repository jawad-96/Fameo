<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\Redirect;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Newsletter;
use App\Models\Newsletter_status;
use DataTables, Hashids, Session, Auth;
use App\Models\Newsletter_subscriber;

class NewslettersController extends Controller
{

    public $resource = 'admin/newsletters';

    public function __construct()
   {
        $this->middleware('permission:view newsletters', ['only' => ['index']]);        
        $this->middleware('permission:add newsletters', ['only' => ['create','store']]);        
        $this->middleware('permission:edit newsletters', ['only' => ['edit','update']]);        
        $this->middleware('permission:delete newsletters', ['only' => ['destroy']]);        
        $this->middleware('permission:view newsletter subscriber', ['only' => ['subscribers']]);        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){

            $newsletters = Newsletter::query();
                return DataTables::of($newsletters)
                ->addColumn('direction', function ($newsletter) {
                    if($newsletter->direction == 'left')
                        return 'Left';
                    elseif($newsletter->direction == 'right')
                        return 'Right';
                })
                ->addColumn('action', function ($row) {
                    $action = '';
                    if(Auth::user()->can('edit newsletters'))
                        $action .= '<a href="newsletters/'.Hashids::encode($row->id).'/edit" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                    if(Auth::user()->can('delete newsletters'))
                        $action .= '<a href="newsletters/'.Hashids::encode($row->id).'" class="text-danger btn-delete btn btn-xs btn-danger"><i class="glyphicon glyphicon-remove"></i>Delete</a>';

                    

                    if($row->status == 'pending'){
                    $new_action = '<a href="javascript:void(0)" data-id="'.Hashids::encode($row->id).'"  data-status="start" class="btn btn-xs btn-success change_status test-'.Hashids::encode($row->id).'">Start</a>
                    <a href="javascript:void(0)" data-id="'.Hashids::encode($row->id).'"  data-status="duplicate" class="btn btn-xs btn-primary change_status">Duplicate</a>';

                    $action = $new_action .  $action;
                } elseif($row->status == 'stop'){
                    $new_action = '<a href="javascript:void(0)" data-id="'.Hashids::encode($row->id).'"  data-status="start" class="btn btn-xs btn-success change_status">Start</a>
                    <a href="javascript:void(0)" data-id="'.Hashids::encode($row->id).'"  data-status="duplicate" class="btn btn-xs btn-primary change_status">Duplicate</a>';

                    $action = $new_action .  $action;
                }
                elseif($row->status == 'sending'){
                    $action = '<a href="javascript:void(0)" data-id="'.Hashids::encode($row->id).'"  data-status="stop" class="btn btn-xs btn-danger change_status">Stop</a>
                    <a href="javascript:void(0)" data-id="'.Hashids::encode($row->id).'"  data-status="duplicate" class="btn btn-xs btn-primary change_status">Duplicate</a>
                    <div id="send_status"></div>';

                } elseif($row->status == 'completed'){
                    $new_action = '<a href="javascript:void(0)" data-id="'.Hashids::encode($row->id).'"  data-status="'.$row->status.'" class="btn btn-xs btn-warning change_status">Completed</a>
                    <a href="javascript:void(0)" data-id="'.Hashids::encode($row->id).'"  data-status="duplicate" class="btn btn-xs btn-primary change_status">Duplicate</a>';

                    $action = $new_action .  $action;
                }

                return $action;
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->rawColumns(['action'])
                    ->make(true);

        }
        return view('admin.newsletters.index');
    }

    /**
     * get subscribers
     *
     *
     */
    public function subscribers(Request $request){

        if($request->ajax()){

            $subscribers = Newsletter_subscriber::query();

            return DataTables::of($subscribers)
                ->addColumn('direction', function ($subscriber) {
                    if($subscriber->direction == 'left')
                        return 'Left';
                    elseif($subscriber->direction == 'right')
                        return 'Right';
                })
                ->addColumn('action', function ($user) {
                    if($user->is_subscribed == 1)
                        return '<input type="checkbox" checked="checked" class="click" id="'.Hashids::encode($user->id).'">';
                     else
                        return '<input type="checkbox" class="click" id="'.Hashids::encode($user->id).'">';
                    })
                ->editColumn('id', 'ID: {{$id}}')
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.newsletters.subscribers');
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
    public function user_subscribed(Request $request)
    {
        $this->validate($request, [
            'subscriber_email'   => 'email|required|unique:newsletter_subscribers,email'
        ]);

        $input = $request->all();

        $input['email'] = $request->subscriber_email;
        $newsletter = Newsletter_subscriber::create($input);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'from'   => 'required|email',
            'subject'=> 'required',
            'content'=> 'required'
        ]);
        $input = $request->all();
        $input['status'] = 'pending';

        $newsletter = Newsletter::create($input);

        if($request->invisible == 1){

            // Session::flash('newsletter_action', Hashids::encode($newsletter->id));
            Session::flash('success', 'Newsletter created!');
            return redirect($this->resource);
        }
        Session::flash('success', 'Newsletter created!');
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
        $newsletter = Newsletter::findOrFail($id);

        return view($this->resource.'/edit', compact('newsletter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = decodeId($id);
        $this->validate($request, [
            'from'   => 'required|email',
            'subject'=> 'required',
            'content'=> 'required'
        ]);

        $newsletter = Newsletter::findOrFail($id);
        $input = $request->all();
        $input['status'] = 'pending';

        $newsletter->update($input);

        if($request->invisible == 1){

            // Session::flash('newsletter_action', Hashids::encode($newsletter->id));
            Session::flash('success', 'Newsletter updated!');
            return redirect($this->resource);
        }
        Session::flash('success', 'Newsletter updated!');
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
        $id = decodeId($id);
        $newsletter = Newsletter::find($id);

        if($newsletter){
            $newsletter->delete();
            $response['message'] = 'Newsletter deleted!';
            $status = $this->successStatus;
        }else{
            $response['message'] = 'Newsletter not exist against this id!';
            $status = $this->errorStatus;
        }
        return response()->json(['result'=>$response], $status);
    }

    public function is_subscribed(Request $request){

        //$id = Hashids::decode($request->id)[0];
        Newsletter_subscriber::whereId(Hashids::decode($request->id)[0])->update(["is_subscribed" => $request->is_subscribed ]);
        return 'true';
    }

    /**
     * newsletters listing
     *
     *
     */
    public function newsletters_listing(){

        return view('admin.newsletters.newsletters_listing');
    }

    /**
     * get newsletters
     *
     *
     */
    public function get_newsletters(){

        $newsletters = Newsletter::get();
        return Datatables::of($newsletters)
            ->addColumn('action', function($row) {
                if($row->status == 'pending'){
                    return
                    '<a href="javascript:void(0)" data-id="'.Hashids::encode($row->id).'"  data-status="start" class="btn btn-xs btn-success change_status test-'.Hashids::encode($row->id).'">Start</a>
                    <a href="javascript:void(0)" data-id="'.Hashids::encode($row->id).'"  data-status="duplicate" class="btn btn-xs btn-primary change_status">Duplicate</a>
                    <a href="newsletters/'.Hashids::encode($row->id).'/edit" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                    <a href="javascript:void(0)" class="btn btn-xs btn-danger btn-delete" id="'.Hashids::encode($row->id).'"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
                } elseif($row->status == 'stop'){
                    return
                    '<a href="javascript:void(0)" data-id="'.Hashids::encode($row->id).'"  data-status="start" class="btn btn-xs btn-success change_status">Start</a>
                    <a href="javascript:void(0)" data-id="'.Hashids::encode($row->id).'"  data-status="duplicate" class="btn btn-xs btn-primary change_status">Duplicate</a>
                    <a href="newsletters/'.Hashids::encode($row->id).'/edit" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                }
                elseif($row->status == 'sending'){
                    return
                    '<a href="javascript:void(0)" data-id="'.Hashids::encode($row->id).'"  data-status="stop" class="btn btn-xs btn-danger change_status">Stop</a>
                    <a href="javascript:void(0)" data-id="'.Hashids::encode($row->id).'"  data-status="duplicate" class="btn btn-xs btn-primary change_status">Duplicate</a>
                    <div id="send_status"></div>';

                } elseif($row->status == 'completed'){
                    return
                    '<a href="javascript:void(0)" data-id="'.Hashids::encode($row->id).'"  data-status="'.$row->status.'" class="btn btn-xs btn-warning change_status">Completed</a>
                    <a href="javascript:void(0)" data-id="'.Hashids::encode($row->id).'"  data-status="duplicate" class="btn btn-xs btn-primary change_status">Duplicate</a>
                    <a href="javascript:void(0)" class="btn btn-xs btn-danger btn-delete" id="'.Hashids::encode($row->id).'"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
                }
            })
            ->make(true);
    }

    public function newsletter_action(Request $request){

        if($request->status == 'start'){

            $id = Hashids::decode($request->id)[0];
            $newsletter = Newsletter::findOrFail($id);

            Newsletter::whereId(Hashids::decode($request->id)[0])->update(["status" => 'sending', 'start_date' => date("Y-m-d h:i:s") ]);

            $offset = $request->offset;
            $limit  = $request->limit;

            $total = Newsletter_subscriber::where('is_subscribed', 1)->count();
            $user_array = Newsletter_subscriber::where('is_subscribed', 1)->offset($offset)->limit($limit)->get();

            /*add data to newsletter_status*/
            $user_array->map(function ($single_user) use ($newsletter){
                $email = $single_user->email;
                $subscriber_id = $single_user->id;

                if($email!=''){
                    $data = [
                        'newsletter_id' => $newsletter->id,
                        'status' => 'pending',
                        'subscriber_id' => $subscriber_id,
                        'send_time' => date("Y-m-d h:i:s")
                    ];
                    Newsletter_status::create($data);

                    /*send email on each ajax request*/
                    $headers =  'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'From: Your name <info@address.com>' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                    mail($email, $newsletter->subject, $newsletter->content, $headers);
                }
            });
            /*update status to completed of newsletter and newsletter_status*/
            if(($total-$offset) <= 1){
                Newsletter::whereId(Hashids::decode($request->id)[0])->update(["status" => 'completed' ]);
                Newsletter_status::where('newsletter_id', $newsletter->id)->update(["status" => 'completed' ]);
            }
            return $arr = array('status' => 'true', 'total' => $total, 'offset' => $offset );
        }

        if($request->status == 'stop'){
            Newsletter::whereId(Hashids::decode($request->id)[0])->update(["status" => 'stop' ]);
        }

        if($request->status == 'duplicate'){
            $data = Newsletter::findOrFail(Hashids::decode($request->id)[0]);

            $data = [
                    'subject'   => 'copy - ' . $data->subject,
                    'from'      => $data->from,
                    'content'   => $data->content,
                    'status'    => 'pending'
                    //'status' => date("Y-m-d h:i:s")
                ];
            Newsletter::create($data);
        }

        return 'true';
    }

    public function unsubscribe_newsletter(Request $request){
        $id = $request->get('id');
        $id = decodeId($id);

        Newsletter_subscriber::whereId($id)->update(["is_subscribed" => 0 ]);
        Session::flash('success', 'Newsletter Unsubscribed Successfully!');
        return redirect(url('/'));
    }
}
