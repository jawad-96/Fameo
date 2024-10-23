<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\UserWishList;
use App\Models\Product;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth, Session;

class UserWishListController extends Controller{
    public $successStatus = 200;
    public $errorStatus = 401;
    public $resource = 'wishlist';

    public function myWishlist()
    {
        return view($this->resource.'/index');
    }

    function getWishlist(Request $request)
    {
        if($request->product_id)
        {
            $whereArr = array('user_id' => auth()->id(),'product_id' => $request->product_id);
            $favoriteData = UserWishList::where($whereArr)->get()->first();
            $favoriteData->delete($whereArr);
        }

        $wishlist_records   = UserWishList::with(['product.product_images'])->whereUserId(Auth::id())->get();

        return view('wishlist.partial_records', compact('wishlist_records'));
    }

    function addToWishlist(Request $request){
        $input      = $request->all();
        $rules['product_id']        = 'required';

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['errors']              =  $validator->errors()->all();
            $response['message']            =  $response['errors'][0];
            $response['status']             =  false;
            return response()->json($response);
        }

        $whereArr = array('user_id' => auth()->id(),'product_id' => $input['product_id']);
        $favoriteData = UserWishList::where($whereArr)->get()->first();

        $favorite = false;
        if($favoriteData){
            $favoriteData->delete($whereArr);
            $favorite = false;
        }else{
            UserWishList::create($whereArr);
            $favorite = true;
        }

        $response['message']            =  'Done';
        $response['status']             =  true;
        $response['favorite']           =  $favorite;
        return response()->json($response);
    }

}
