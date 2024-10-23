<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Email;
use Log;
use Session;
use Auth;
use App\Models\WholesellerWallet;
use App\Models\ShoppingCart;
use App\Models\Product;
use Cart;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::stores([1])
                    ->whereHas('store_products', function($q){
                        $q->where('quantity', 0);
                    })
                    ->where('is_active', '1')->pluck('name');

        return view('admin.dashboard', compact('products'));
    }
    
    public function sendEmailView()
    {
        $users = User::where('type', '!=', 'retailer')->orderBy('first_name', 'asc')->get()->pluck('full_name_with_email', 'id');
        return view('admin.send-email', compact('users'));
    }
    public function sendEmail(Request $request)
    {
        if($request->filled('user_ids')) {
            foreach($request->user_ids as $userId) {
                
                $user = User::find($userId);
                if ($user) {
                    
                    $data = [
                        'email_from'    => 'aqsintetrnationalstore@gmail.com',
                        'email_to'      => $user->email,
                        'email_subject' => @$request->subject,
                        'user_name'     => $user->full_name,
                        'final_content' => @$request->body,
                    ];
                    try{
                        Email::sendEmail($data);
                    }
                    catch(Exception $e)
                    {
                        Log::error('Admin Email error: ' . $e->getMessage());
                    }
                }
                
            }
        }
        
        Session::flash('success', 'Email successfully sent!');
        return redirect('admin/send-email');
    }


    public function loginBypass($email)
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            $userId = $user->id;
            Auth::guard('web')->loginUsingId($userId);

            $data = ShoppingCart::where(['user_id' => $userId, 'payment_status' => 'pending'])->first();
            
            $cart = Cart::getContent()->values()->toArray();
            
            if($data){
                $cartData = unserialize($data->cart_details);
                $cart = array_merge($cartData, $cart);
                $this->updateCartInDB($cart, $userId);
            }

            if($cart){
                Cart::session($userId)->add($cart);
                $this->updateCartInDB($cart, $userId);
            }

            return redirect('products');
        }
        
        exit('error');
    }

    public function updateCartInDB($cart, $userId){
        $input['user_id']       = $userId;
        $input['cart_details']  = serialize(Cart::getContent()->values()->toArray());
        $result                 = ShoppingCart::updateOrCreate(['user_id' => $userId, 'payment_status' => 'pending'],
            ['user_id' => $userId, 'cart_details' => serialize($cart)]);
    }


    public function sendStockEmail()
    {
        
        $products = Product::stores([1])
                    ->whereHas('store_products', function($q){
                        $q->where('quantity', 0);
                    })
                    ->where('is_active', '1')->pluck('name');

        $html = '<strong>These products are out of stock</strong><br/><ul>';            
        foreach($products as $product){
            $html .= '<li>'. $product .'</li>';
        }
        $html .= '</ul>';
        
        $users = User::where('is_active', 'yes')->whereIn('type', ['dropshipper', 'wholesaler'])->pluck('email');
        foreach($users as $email) {
            $data = [
                'email_from'    => 'aqsintetrnationalstore@gmail.com',
                'email_to'      => $email,
                'email_subject' => 'These products are out of stock',
                'user_name'     => $email,
                'final_content' => $html,
            ];
        
            try{
                Email::sendEmail($data);
            }
            catch(Exception $e)
            {
                Log::error('Admin Stock Email error: ' . $e->getMessage());
            }
        }
        
        Session::flash('success', 'Email successfully sent');  
        return redirect('admin/dashboard');
    }

    public function adjustOverdueAmount($userId) {
        $userId = decodeId($userId);
        
        $limit = 0;
        $user = User::find($userId);
        if ($user) {
            $limit = (int) $user->max_limit;

            $totalDebit = WholesellerWallet::where('user_id', $userId)->sum('debit');
            $totalCredit = WholesellerWallet::where('user_id', $userId)->sum('credit');
            
            $totalDebit = ($totalDebit)?$totalDebit:0;
            $totalCredit = ($totalCredit)?$totalCredit:0;

            $previousAmount = 0;
            $walletAmount = $totalCredit-$totalDebit;
            $previousAmount = $limit - $walletAmount;
            $previousAmount = (float) number_format($previousAmount, 2, '.', '');
            
            WholesellerWallet::create([
                'debit' => $previousAmount,
                'payment_mode' => 'adjustment',
                'user_id' => $userId,
                'date' => date('Y-m-d')
            ]);

            Session::flash('success', 'Adjustment created');  
        } else {
            Session::flash('success', 'User not found');
        }

        return redirect()->back();
    }
}
