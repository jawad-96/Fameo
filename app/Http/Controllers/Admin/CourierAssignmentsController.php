<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\CouriersAssignment;
use App\Models\CouriersAssignmentDetail;
use App\Models\Email;
use App\Models\Newsletter_subscriber;
use App\Models\ShoppingCart;
use Darryldecode\Cart\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\User;
use Illuminate\Http\Request;
use Session,Hashids,DataTables,DB;


class CourierAssignmentsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view drop_shipper', ['only' => ['index']]);
        $this->middleware('permission:add drop_shipper', ['only' => ['create','store']]);
        $this->middleware('permission:edit drop_shipper', ['only' => ['edit','update']]);
        $this->middleware('permission:delete drop_shipper', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {


        if($request->ajax()){

            //$users = User::where('type','dropshipper')->orderBy('updated_at','desc');
            $courierAssignments =  CouriersAssignment::with('user')->orderBy('updated_at','desc');
            
            return DataTables::of($courierAssignments)

                ->addColumn('name', function ($courierAssignment) {
                    $name = $courierAssignment->user['first_name'] .' '.$courierAssignment->user['last_name'];
                    return $name;
                })

                ->addColumn('Cart No', function ($courierAssignment) {
                    return $courierAssignment->cart_id;
                })

                ->addColumn('is_active', function ($courierAssignment) {
                    $satatus = '<a class="btn btn-xs btn-danger">Pending</a>';
                    if($courierAssignment->status==2)
                        $satatus = '<a class="btn btn-xs btn-success">Completed</a>';
                        if($courierAssignment->status==3)
                        $satatus = '<a class="btn btn-xs btn-danger">Canceled by Admin</a>';
                        if($courierAssignment->status==4)
                        $satatus = '<a class="btn btn-xs btn-danger">Canceled by Customer</a>';

                    return $satatus;
                })
                ->addColumn('action', function ($courierAssignment) {
                    $action = '';
                    if(Auth::user()->can('edit drop_shipper') && $courierAssignment->status == 1)
                        $action .= '<a href="courier-assignment/'. Hashids::encode($courierAssignment->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Courier Assignment"><i class="fa fa-lg fa-edit"></i></a>';
                    if(Auth::user()->can('view drop_shipper'))
                        $action .= '<a href="courier-assignment/'.Hashids::encode($courierAssignment->id).'" class=" " data-toggle="tooltip" title="View Courier Assignment"><i class="fa fa-lg fa-eye"></i></a>';

                    return $action;
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->rawColumns(['is_active','action'])
                ->make(true);

        }

        return view('admin.courier-assignment.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.drop-shipper.create');
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
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $requestData = $request->all();
        $requestData['password'] = bcrypt($requestData['password']);
        $requestData['type'] = 'dropshipper';

        $user = User::create($requestData);

        Session::flash('success', 'Drop shipper added!');

        return redirect('admin/drop-shipper');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request,$id)
    {

        $id = decodeId($id);

        $courierAssignment = CouriersAssignment::with('user')->with('detail')->findOrFail($id);




            $count          = $courierAssignment->detail->count();
            $cartContents   = $courierAssignment->detail;



        $couriers= Courier::all();
        return view('admin.courier-assignment.edit', compact('couriers','courierAssignment','count', 'cartContents'));
    }  public function show(Request $request,$id)
    {

        $id = decodeId($id);

        $courierAssignment = CouriersAssignment::with('user')->with('detail')->findOrFail($id);
        $courierAssignment->view=1;




            $count          = $courierAssignment->detail->count();
            $cartContents   = $courierAssignment->detail;



        $couriers= Courier::all();
        return view('admin.courier-assignment.view', compact('couriers','courierAssignment','count', 'cartContents'));
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */


    public function cancelRequestAdmin($id)
    {

        $courierAssignment =CouriersAssignment:: find($id);
        $courierAssignment->status = 3;
        $courierAssignment->cart_id = 0;
        $courierAssignment->save();


        CouriersAssignmentDetail::where('couriers_assignment_id',$id)->update(['cart_id'=>0]);
        $email = $courierAssignment->user->email;
        //$email ="baadrayltd@gmail.com";
        $data = [
            'email_from'    => 'baadrayltd@gmail.com',
            'email_to'      => $email,
            'email_subject' => 'New Request For Courier Assignmnet',
            'user_name'     => 'User',
            'final_content' => '<p>Dear '.$courierAssignment->user->name.'</p>
                                    <p>Your Order has been canceled By Admin</p>',
        ];
            try{
                Email::sendEmail($data);
            }catch(\Exception $e)
            {

            }


        Session::flash('success', 'Order has been Canceled');
        return redirect('admin/courier-assignment');
    }

    public function resetCourier($id)
    {




        CouriersAssignmentDetail::where('couriers_assignment_id',$id)->update(['couriers_id'=>0,'group_no'=>0]);




        Session::flash('success', 'Courier Assignment Reset Sucessfully');
        return redirect()->back();
    }



    public function update($id, Request $request)
    {
      // dd($request->all());
        $id = decodeId($id);
        $this->validate($request, [
            'couriers_id' => 'required|max:255',
            'product_id' => 'required|max:255',
        ]);




        $requestData = $request->all();
//            dd($requestData);
        $couriers_id= $request->couriers_id;
        $product_id= $request->product_id;

        $group_check= $request->group_check;
        $group_no= $request->group_no;

        $complete_check=1;


        $max = CouriersAssignmentDetail::where('couriers_assignment_id',$id)->get()->sum('group_no');
        $g_no=$max+1;

        foreach ($product_id as $key=> $pid)
        {
            $courier_assignment = CouriersAssignmentDetail::where('couriers_assignment_id',$id)->where('product_id',$pid)->first();



            if($group_no[$key] ==0 && $group_check[$key] == 1)
            {

              $courier_assignment->couriers_id=$couriers_id;
              $courier_assignment->group_no=($g_no);
              $courier_assignment->save();
            

            }


            if($group_no[$key] == 0)
            {
                $complete_check=0;
            }

        }




        if($complete_check == 1)
        {


        $couriers_assignments = CouriersAssignment::with('user')->where('id',$id)->first();
        $couriers_assignments->status=2;

        $couriers_assignments->save();

            $email = $couriers_assignments->user->email;
//            $email ="mobeen7asif@gmail.com";
            $data = [
                'email_from' => 'baadrayltd@gmail.com',
                'email_to' => $email,
                'email_subject' => 'Your Order Has been Approved',
                'user_name' => 'User',
                'url' => url('cart-checkout'),
                'final_content' => '<p>Dear '.$couriers_assignments->user->first_name.'</p>
                                <p>Your Order has been approved by Admin Here is your Order Link <br>'.url('cart-checkout').'</p>',
            ];
            try{
                Email::sendEmail($data);
            }catch(\Exception $e)
            {

            }
            Session::flash('success', 'Courier Assignment has been Updated');
            return redirect('admin/courier-assignment');
        }





        Session::flash('success', 'Courier Assignment has been Completed');
        return redirect()->back();
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


    }

    function GenerateRandomColor()
    {
        $color = '#';
        $colorHexLighter = array("9","A","B","C","D","E","F" );
        for($x=0; $x < 6; $x++):
            $color .= $colorHexLighter[array_rand($colorHexLighter, 1)]  ;
        endfor;
        return substr($color, 0, 7);
    }

}
