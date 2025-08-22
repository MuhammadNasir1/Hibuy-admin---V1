<?php

namespace App\Http\Controllers;

use App\Mail\welcomeemail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendMail($Email, $sub, $body)
    {
        $toEmail = $Email;
        $subject = $sub;
        $message = $body;


        $request = Mail::to($toEmail)->send(new welcomeemail($message, $subject));
    }
}
