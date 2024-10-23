<?php



namespace App\Http\Controllers\Admin;



use App\Http\Requests;

use App\Http\Controllers\Controller;



use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Session, Alert, DB;

use App\Admin;



class SettingsController extends Controller

{

    
    public function __construct()
   {
        $this->middleware('permission:view settings', ['only' => ['index']]);        
        $this->middleware('permission:edit settings', ['only' => ['edit','update']]);        
    }

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\View\View

     */

    public function index()

    {

        $settings = DB::table('site_settings')->get();

        

        return view('admin.settings.settings', compact('settings'));

    }    



    /**

     * Update the specified resource in storage.

     *

     * @param  int  $id

     * @param \Illuminate\Http\Request $request

     *

     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector

     */

    public function update(Request $request)

    {

        $requestData = $request->all();

        unset($requestData['_token']);

        

        $validateData = [];

        // foreach ($requestData as $key => $value) {

        //     $validateData[$key] = 'required';

        // }

         

        // $this->validate($request, $validateData);

         

        foreach ($requestData as $key => $value) {


            if(!empty($value)){
                $setting = DB::table('site_settings')->where('key',$key)->count();

                if($setting > 0){

                   DB::table('site_settings')->where('key',$key)->update(['value' => $value]); 

                }else{

                    DB::table('site_settings')->insert(['key' => $key,'value' => $value]);

                }        

            }
        }

        

        if($request->hasFile('site_logo')){            

                $destinationPath = 'uploads/settings'; // upload path

                $image = $request->file('site_logo'); // file

                $extension = $image->getClientOriginalExtension(); // getting image extension

                $fileName = 'site_logo.'.$extension; // renameing image



                $image->move($destinationPath, $fileName); // uploading file to given path



                //insert image record            

                DB::table('site_settings')->where('key','site_logo')->update(['value' => $fileName]); 

            }



            Session::flash('success', 'Settings updated!');  



                

        return redirect('admin/settings');

    }

    

    /**

     * Change Password the specified resource in storage.

     *

     * @param \Illuminate\Http\Request $request

     *

     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector

     */

    public function change_password_view()

    {

        $data = Admin::find(Auth::id());

        return view('admin.settings.change-password', compact('data'));

    }  



    public function changePassword(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'current_password' => 'required', 

            'password' => 'required|confirmed'            

        ]);

        

        $requestData = $request->all();

        $validator->after(function($validator) use ($request) {

                       

            if (!Auth::attempt(['password' => $request->current_password])){

                $validator->errors()->add('current_password','Your current password is incorrect, please try again.');            

            }

        });

        

        if ($validator->fails()){

            return redirect('admin/change-password')->withErrors($validator);

        }

        

        $user = Auth::user();

        $user->password = \Hash::make($request->password);

        $user->save();

        

        Session::flash('success', 'Password updated!');  



        return redirect('admin/change-password');

    }

}

