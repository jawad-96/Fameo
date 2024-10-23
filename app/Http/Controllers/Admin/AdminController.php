<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\ShoppingCart;
use App\User;
use Illuminate\Support\Facades\Auth;

use App\Admin;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Session,Hashids,DataTables,DB;

class AdminController extends Controller
{

   public function __construct()
   {
        $this->middleware('permission:view admins', ['only' => ['index']]);
        $this->middleware('permission:add admins', ['only' => ['create','store']]);
        $this->middleware('permission:edit admins', ['only' => ['edit','update']]);
        $this->middleware('permission:delete admins', ['only' => ['destroy']]);
    }

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        if($request->ajax()){

            $admins = Admin::get();

            return DataTables::of($admins)
                ->addColumn('action', function ($admin) {
                $action = '';
                if(Auth::user()->can('edit admins'))
                    $action .= '<a href="admins/'. Hashids::encode($admin->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Admin"><i class="fa fa-lg fa-edit"></i></a>';

                return $action;
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->rawColumns(['action'])
                ->make(true);

        }

        return view('admin.admins.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::pluck('name','name');
        return view('admin.admins.create',compact('roles'));
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
            'role' => 'required',
            'email' => 'required|email|max:255|unique:admins',
            'password' => 'required|min:6|confirmed'
        ]);

       $requestData = $request->all();
       $requestData['password'] = bcrypt($requestData['password']);

       $admin = Admin::create($requestData);
       $admin->assignRole($requestData['role']);


        Session::flash('success', 'Admin added!');

        return redirect('admin/admins');
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

        $admin = Admin::findOrFail($id);
        $roles = Role::pluck('name','name');

        return view('admin.admins.edit', compact('admin','roles'));
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
            'role' => 'required',
        ]);

        $requestData = $request->all();

        $admin = Admin::findOrFail($id);

        if($admin){
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            $admin->assignRole($requestData['role']);
        }



        $admin->update($requestData);

        Session::flash('success', 'Admin updated!');

        return redirect('admin/admins');
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
        $id = decodeId($id)[0];

        $admin = Admin::find($id);

        if($admin){
            $admin->delete();
            $response['message'] = 'Admin deleted!';
            $status = $this->successStatus;
        }else{
            $response['message'] = 'Admin not exist against this id!';
            $status = $this->errorStatus;
        }

        return response()->json(['result'=>$response], $status);

    }


    /**
     * Company Login.
     *
     * @param \Illuminate\Http\Request $company_id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function companyLogin($company_id)
    {
        $id = Hashids::decode($company_id)[0];

        Auth::guard('company')->loginUsingId($id);

        return redirect('company/dashboard');
    }

    public function updateStatusPayment($type,$id)
    {
        if(isset($id) and !empty($id)){
            $id = Hashids::decode($id)[0];
            User::where(['type' => $type,'id' => $id])->update([
                'is_latest' => 0
            ]);
        }else{
            User::where('type',$type)->update([
                'is_latest' => 0
            ]);

        }
        return back();
    }

    public function updateStatusOrder(Request $request, $id)
    {
        $id = Hashids::decode($id)[0];
        // Transaction::where('id',$id)->update([
        //    'is_latest' => 0
        // ]);

        $tran = Transaction::find($id);
        if ($tran) {
            $tran->update(['is_latest' => 0]);

            ShoppingCart::where('id', $tran->cart_id)->update([
                'delivery_status' => "dispatched"
             ]);
        }
        //return back();

        $queryString = '?a=1';
        if ($request->filled('user_id')) {
            $queryString .= '&user_id='.$request->user_id;
        }
        if ($request->filled('from_date')) {
            $queryString .= '&from_date='.$request->from_date;
        }
        if ($request->filled('to_date')) {
            $queryString .= '&to_date='.$request->to_date;
        }
        if ($request->filled('start')) {
            $queryString .= '&start='.$request->start;
        }
        if ($request->filled('length')) {
            $queryString .= '&length='.$request->length;
        }
        
        Session::flash('success', 'Status successfully updated!');
        return redirect('admin/orders' . $queryString);

        
    }

    public function updateDeliveryStatus($id)
    {
        $id = Hashids::decode($id)[0];
        ShoppingCart::where('id',$id)->update([
           'delivery_status' => "dispatched"
        ]);
        return back();
    }

}
