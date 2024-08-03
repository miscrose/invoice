<?php

namespace App\Http\Controllers;

use App\Http\Requests\login_request;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class login_contr extends Controller
{
    function login(){
        return view('login');
    }

    function do_login(login_request $request){

        $validatedData=$request->validated();
        if(Auth::attempt([
            'email' => $validatedData['email'],
            'password' => $validatedData['password']])){

                $request->session()->regenerate();
                return redirect()->intended('home');
        }
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }



    function do_logout(Request $request){
        auth::logout();
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 
        return redirect('login');


    }






}
