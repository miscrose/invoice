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
use Illuminate\Support\Facades\Session;



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
       
        $user->uservalid = $user->uservalid === 'v' ? 'nv' : 'v';
        $user->save();
    
       
        return response()->json(['validation' => $user->uservalid]);
    }
    

    function search_users_email(Request $request){
        $email = $request->input('email');
        $users = User::where('email', 'LIKE', "%$email%")->whereNotIn('usertype',['admin'])->get();
        return view('admin_list_acc', compact('users'));
    }


    function client_information(Request $request,$id){
      
        $client = Client::where('user_id', $id)
        ->whereNull('state') 
        ->first();

        $unassignedClients = client::whereNull('state') ->whereNull('user_id')->get();
        if ($client) {
            return view('admin_client',compact('id','client','unassignedClients'));

                     }
         else
            {   
                
                return view('admin_client',compact('id','unassignedClients'));}
        
    }



    function add_update_client(Request $request,$id){

   $client = Client::where('user_id', $id)->whereNull('state')->first();
   if ($client) {
    $client->state = 'deleted';
    $client->save();

   }


 /*  
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
                    $invoice = invoice::select('id', 'date','created_at','payment_date', 'due_date','status',DB::raw("null as invoice_number"), DB::raw("'received' as type"))
                    ->where('client_id', $id);
                
                    $received_invoice = received_invoice::select('id', 'date','created_at','payment_date', 'due_date','status','invoice_number', DB::raw("'sent' as type"))
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

    $idclientinvoice = DB::table('invoices')->pluck('client_id');
    $receivedInvoiceClientId = DB::table('received_invoices')->pluck('client_id');
    $idclientdevis = DB::table('devis')->pluck('client_id');
    $idclientdevisrecus = DB::table('devis_recus')->pluck('client_id');

    $allClientIds = $idclientinvoice->merge($receivedInvoiceClientId)
    ->merge($idclientdevis)
    ->merge($idclientdevisrecus)
    ->unique();


    $clients = Client::whereIn('id', $allClientIds)->where('name', 'like', "%{$query}%")->get();
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
    

                                                              

 public function addClientAjax(Request $request)
    {
        $client = Client::create([
            'name' => $request->name,
            'address' => $request->address,
            'tel' => $request->tel,
            'user_id' => null,
        ]);
       
        return response()->json([
            'id' => $client->id,
            'name' => $client->name,
            'address' =>$client->address,
        ]);
    }

    function link_user_client(Request $request,$id){
       $id_client= $request->unassigned_client_id;
       if(empty($id_client)){   return redirect()->back(); }
       else{
            $previousClient = Client::where('user_id', $id)->whereNull('state')->first();
                if ($previousClient) {
                    $previousClient->state = 'deleted';
                    $previousClient->save();

                }

            $client = Client::find($id_client);
            $client->user_id=$id;
            $client->save();
            return redirect()->back();
       }    
   
    }


}



