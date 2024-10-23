<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\User;

use Illuminate\Support\Facades\Validator;
use Auth, Session;

class ProfileController extends Controller{
    public $successStatus = 200;
    public $errorStatus = 401;
    public $resource = 'profile';

    public function index()
    {
        $userData       = Auth::user();
        return view($this->resource.'/index', compact('userData'));
    }

    function updateProfile(Request $request){
        $input      = $request->formData;
        parse_str($input, $searcharray);

        $user = User::findOrFail(Auth::id());
        $user->update($searcharray);
        $response['message']            =  'Profile updated successfully';
        $response['status']             =  true;
        return response()->json($response);
    }
}
