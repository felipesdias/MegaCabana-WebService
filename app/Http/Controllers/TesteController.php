<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\ConfirmaEmail;

class TesteController extends Controller
{
    public function teste(Request $request) {
      //  Mail::to("felipe.s.dias@outlook.com")
       //     ->send(new ConfirmaEmail("felipe.s.dias@outlook.com", "fdiusfdsfsd"));

    Mail::send('emails.teste', ['email' => "felipe.s.dias@outlook.com", 'verificationCode' => "felipe.s.dias@outlook.com"], function ($m) use ($request) {
         $m->to("felipe.s.dias@outlook.com", 'test')->subject('Confirmação de email');
     });

        return true;
    }
}
