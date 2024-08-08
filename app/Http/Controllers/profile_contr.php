<?php

namespace App\Http\Controllers;

use App\Http\Requests\sign_up_request;
use App\Http\Requests\update_request;
use App\Models\client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class profile_contr extends Controller
{


    function setting(){

        return view('setting');


    }






    function update(update_request $request){
        $user_id=Auth::user()->id;
        $user= User::find($user_id);
        $user->name=$request->input('name');
        $user->email=$request->input('email');

        if(!empty($request->input('password'))){

            $user->password= Hash::make($request->input('password'))  ;
        }
        $user->save();

        return to_route('setting');
       
        
    }



    
    function information(Request $request){

        $id=Auth::user()->id;
        $client = Client::where('user_id', $id)->whereNull('state')->first();
        if ($client) {
            return view('client_information',compact('client'));

                     }
         else
            {return view('client_information');}
        
       
            
        

    }


    function add_update_information(Request $request){
        $id=Auth::user()->id;
        $client = Client::where('user_id', $id)->whereNull('state')->first();
        if ($client) {
            $client->state = 'deleted';
            $client->save();
        
           }

          /*
        $client = client::where('user_id', $id)->first();
     
        if ($client) {
          
            $client->name = $request->name;
            $client->address = $request->address;
            $client->tel = $request->tel;
            $client->user_id =$id;
            $client->save();
        
            return to_route('information')->with('success', 'Client mis à jour avec succès.');
        } else {
           
            Client::create([
                'name' => $request->name,
                'address' => $request->address,
                'tel' => $request->tel,
                'user_id' => $id,
            ]);
        
            return to_route('information')->with('success', 'Client ajouté avec succès.');
        }
        */

        Client::create([
            'name' => $request->name,
            'address' => $request->address,
            'tel' => $request->tel,
            'user_id' => $id,
        ]);
    
        return to_route('information')->with('success', 'Client ajouté avec succès.');

    }

}
