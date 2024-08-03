<?php

namespace App\Http\Controllers;

use App\Http\Requests\sign_up_request;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class sign_up_contr extends Controller
{
    function sign_up( ){

        return view('sign_up');
      
          }

      
    function do_sign_up(sign_up_request $request){
        $validatedData =$request->validated();
         User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password']),
    ]);
  
    return to_route('login')->with('success', 'User registered successfully');
      
    }



}
