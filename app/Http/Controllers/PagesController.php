<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Email;
use App, Session;

class PagesController extends Controller{

    function faqs()
    {
        $title      = 'FAQs';
        $faqs = Faq::orderBy('ordering')->get();
        
        return view('pages/faqs',compact('faqs', 'title'));
    }

    function page($slug = '') {
        
        $page = Page::where(['slug' => $slug])->firstOrFail();        

        if($slug == 'contact-us'){
            return view('pages.contact-us', compact('page'));
        }

        return view('pages/pages_view', compact('page'));
    }

    public function contact_us(Request $request)
    {
        $rulesArray = [
            'name'      => 'required',
            'email'     => 'required|email',
            'subject'   => 'required',
            'message'   => 'required'
        ];
        $this->validate($request, $rulesArray);

        $data = $request->all();

        if($data){

            $email_to       = 'aqsinternational@badrayltd.co.uk';
            $email_body     = $data['message'].'<br>'.'<b>Email From: </b>'.$data['email'].'<br>'.'<b>Username: </b>'.$data['name'].'<br>'.'<b>Subject: </b>'.$data['subject']; 

           Email::sendEmail(
                array(
                    'email_subject' => 'Contact Us',
                    'email_to'      => $email_to,
                    'email_from'    => $request->email,
                    'final_content' => '<b>Message: </b>'.$email_body
                )
            );
        }

        Session::flash('success', 'Your message has been sent successfully.');
        return redirect('page/contact-us');
    }

}
