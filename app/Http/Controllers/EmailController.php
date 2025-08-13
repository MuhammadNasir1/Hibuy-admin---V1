<?php

namespace App\Http\Controllers;

use App\Mail\welcomeemail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendMail()
    {
        $toEmail = "arhamwaheed.info@gmail.com";
        $subject = "Mail Testing";
        $message = "Lorem Ipsum Text Here ....";


        $request = Mail::to($toEmail)->send(new welcomeemail($message, $subject));

        dd($request);
    }
}
