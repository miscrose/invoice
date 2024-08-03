<?php

namespace App\Http\Controllers;

use App\Http\Requests\admin_update_request;
use App\Http\Requests\update_request;
use App\Models\client;
use App\Models\companyinfo;
use App\Models\devis;
use App\Models\devis_recu;
use App\Models\invoice;
use App\Models\received_invoice;
use App\Models\User;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class admin_contr extends Controller
{
    function admin_list_acc(){
        if (Auth::check() && Auth::user()->usertype === 'admin') {

            $users=User::whereNotIn('usertype',['admin'])->get(); 
        
            return view('admin_list_acc',compact('users'));
        }

      
        return to_route('home')->with('error', 'Accès non autorisé');

        
       
    }


    function account_update(User $user){
        return view('admin_update',compact('user'));

    }


    function do_update(admin_update_request $request,User $user){
 
        $user->name=$request->input('name');
        $user->email=$request->input('email');

        if(!empty($request->input('password'))){

            $user->password= Hash::make($request->input('password'))  ;
        }
        $user->save();
        return to_route('admin_list_acc');


    }


    function delete_account(User $user){
        $user->delete();

        return to_route('admin_list_acc');

    }


    function validation_list(){
       
        if (Auth::check() && Auth::user()->usertype === 'admin') {

            $users=User::whereNotIn('usertype',['admin'])->where('uservalid','nv')->get(); 
        
            return view('validation_list',compact('users'));
        }

      
        return to_route('home')->with('error', 'Accès non autorisé');

    }


    function validation_acc(user $user){
        if (Auth::check() && Auth::user()->usertype === 'admin') {

         $user->uservalid='v';
         $user->save();
         return to_route('validation_list');
         
        }

      
        return to_route('home')->with('error', 'Accès non autorisé');

        
    }


    public function validation_Change(Request $request, User $user)
    {
        // Logique pour changer la validation
        $user->uservalid = $user->uservalid === 'v' ? 'nv' : 'v';
        $user->save();
    
        // Réponse JSON avec la nouvelle validation
        return response()->json(['validation' => $user->uservalid]);
    }
    

    function search_users_email(Request $request){
        $email = $request->input('email');
        $users = User::where('email', 'LIKE', "%$email%")->whereNotIn('usertype',['admin'])->get();
        return view('admin_list_acc', compact('users'));
    }


    function client_information(Request $request,$id){
      
        $client = Client::where('user_id', $id)->latest()->first();
        
        if ($client) {
            return view('admin_client',compact('id','client'));

                     }
         else
            {return view('admin_client',compact('id'));}
        
    }



    function add_update_client(Request $request,$id){
       
 /*      $client = Client::where('user_id', $id)->first();

        if ($client) {
          
            $client->name = $request->name;
            $client->address = $request->address;
            $client->tel = $request->tel;
            $client->user_id = $request->id;
            $client->save();
        
            return to_route('admin_list_acc')->with('success', 'Client mis à jour avec succès.');
        } else {
           
           
        
            return to_route('admin_list_acc')->with('success', 'Client ajouté avec succès.');
        }*/
        Client::create([
            'name' => $request->name,
            'address' => $request->address,
            'tel' => $request->tel,
            'user_id' => $request->id,
        ]);
        return to_route('admin_list_acc')->with('success', 'Client ajouté avec succès.');
    }


    function company_info(){
        if (Auth::check() && Auth::user()->usertype === 'admin') {
            $companyInfo=companyinfo::all();
            return view('company_info_form',compact('companyInfo'));   
        }

      
        return to_route('home')->with('error', 'Accès non autorisé');

    }


    function company_info_save(Request $request){

        $companyInfo = new companyinfo();
        $companyInfo->name = $request->name;
        $companyInfo->address = $request->address;
        $companyInfo->city = $request->city;     
        $companyInfo->tel = $request->tel;
        $companyInfo->email = $request->email;
      
        $companyInfo->save();

        return redirect()->back()->with('success', 'Company information saved successfully.');
    
    }




    function list_client_invoice(Request $request,$id){
       return view('list_client_invoice',compact('id'));

    }


    public function sort_client_invoice(Request $request)
    {
        
        $invoices = [];
      
    
            $id=$request->input('id');
        
            $filter = $request->input('filter');    
            
   
                if ($filter === 'sent') {
                    $invoices = invoice::select('*', DB::raw("'received' as type"))
                                       ->where('client_id', $id)
                                       ->orderBy('created_at', 'desc')
                                       ->get();
                                       
                } 
                elseif($filter === 'received') 
                {
                    $invoices = received_invoice::select('*', DB::raw("'sent' as type"))
                                       ->where('client_id', $id)                                     
                                       ->orderBy('created_at', 'desc')
                                       ->get();
                }
                else{
                    $invoice = invoice::select('id', 'date','created_at', 'due_date','status',DB::raw("null as invoice_number"), DB::raw("'received' as type"))
                    ->where('client_id', $id);
                
                    $received_invoice = received_invoice::select('id', 'date','created_at', 'due_date','status','invoice_number', DB::raw("'sent' as type"))
                    ->where('client_id', $id);
                  

                    $invoices=$invoice->union($received_invoice) 
                                ->orderBy('created_at', 'desc')
                                ->get();

                

            }


        
        return view('sort_invoice',compact('invoices')); 
               
    }
    

    
public function search_client_name(Request $request)
{
    $query = $request->input('query');
    $clients = Client::where('name', 'like', "%{$query}%")->get();
    if ($clients->isEmpty()) {
        return response()->json(['message' => 'No clients found.']);
    }
    return response()->json(['clients' => $clients]);
}


function list_client_devis(Request $request,$id){
    return view('list_client_devis',compact('id'));

 }


 public function sort_client_devis(Request $request)
    {
        
        $devis = [];
      
    
            $id=$request->input('id');
        
            $filter = $request->input('filter');    
            
   
                if ($filter === 'sent') {
                    $devis = devis::select('*', DB::raw("'received' as type"))
                                       ->where('client_id', $id)
                                       ->orderBy('created_at', 'desc')
                                       ->get();
                                       
                } 
                elseif($filter === 'received') 
                {
                    $devis = devis_recu::select('*', DB::raw("'sent' as type"))
                                       ->where('client_id', $id)                                     
                                       ->orderBy('created_at', 'desc')
                                       ->get();
                }
                else{
                    $devis = devis::select('id', 'date','created_at',DB::raw("null as devis_number"), DB::raw("'received' as type"))
                    ->where('client_id', $id);
                
                    $received_devis = devis_recu::select('id', 'date','created_at', 'devis_number', DB::raw("'sent' as type"))
                    ->where('client_id', $id);
                  

                    $devis=$devis->union($received_devis) 
                                ->orderBy('created_at', 'desc')
                                ->get();

                

            }


        
        return view('sort_devis',compact('devis')); 
               
    }
    






}

    

