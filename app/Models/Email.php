<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Mail;
use Log;

class Email extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email_name', 'email_key', 'email_subject', 'email_from', 'email_body', 'email_body_variable'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email_key',
    ];



    public static function sendEmail($params = array()){

        $user_name      = (!empty($params['user_name']))    ?    $params['user_name']       :   false;
        $email_title    = (!empty($params['email_title']))  ?    $params['email_title']     :   false;
        $email_subject  = (!empty($params['email_subject']))?    $params['email_subject']   :   false;
        $email_from     = (!empty($params['email_from']))   ?    $params['email_from']      :   false;
        $email_to       = (!empty($params['email_to']))     ?    $params['email_to']        :   false;
        $final_content  = (!empty($params['final_content']))?    $params['final_content']   :   false;
        $id             = (!empty($params['id']))           ?    $params['id']              :   false;
        $url            = (!empty($params['url']))          ?    $params['url']             :   false;

        if($email_title)
        {
            $email_data     = Email::where('email_key', $email_title)->first();
            $email_body     = $email_data->email_body;
            $email_subject  = $email_data->email_subject;
            $email_from     = $email_data->email_from;

            $final_content  = str_replace('{name}', $user_name, $email_body);
            $final_content  = str_replace('{url}', $url, $final_content);
        }
        $final_content  = str_replace('{name}', $user_name, $final_content);
        $final_content  = str_replace('{url}', $url, $final_content);

        $result['final_content'] = $final_content;
        // laravel server email
        // try {
        //     Mail::send('mail.mail_body',$result, function ($message) use ($email_from, $email_to, $email_subject) {

        //         $message->from($email_from, "AQS");
        //         $message->to($email_to, $email_to)->subject($email_subject);
        //     });

        //     Log::info('Email sent');
        // }catch (Exception $e) {
        //     Log::error('Email error: ' . $e->getMessage());
        //     echo $e->getMessage();
        // }

        // simple email

        $headers =  'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: AQS <'.$email_from.'>' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        mail($email_to, $email_subject, $final_content, $headers);
    }

}
