<?php

namespace App\Http\Controllers;

use App\Models\client;
use App\Models\devis;
use App\Models\devis_recu;
use App\Models\invoice;
use App\Models\received_invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class home_contr extends Controller
{
  public function home(Request $request)
    {
        $user = Auth::user();
      

        if ($user->usertype === 'user') {
            
            return view('home', compact( 'user'));
                          
            
        } else {
            $clients = client::all(); 
            return view('home', compact( 'clients', 'user'));
        }

       
    }



    public function sort_invoice(Request $request)
    {
        $user = Auth::user();
        $invoices = [];
    
    
        
            $client = client::where('user_id', $user->id)->first();
            $filter = $request->input('filter');    
            
            if ($client) {
                if ($filter === 'received') {
                    $invoices = invoice::select('*', DB::raw("'received' as type"))
                                       ->where('client_id', $client->id)
                                       ->orderBy('created_at', 'desc')
                                       ->get();
                                       
                } 
                elseif($filter === 'sent') 
                {
                    $invoices = received_invoice::select('*', DB::raw("'sent' as type"))
                                       ->where('client_id', $client->id)                                     
                                       ->orderBy('created_at', 'desc')
                                       ->get();
                }
                else{
                    $invoice = invoice::select('id', 'date','created_at', 'due_date','status',DB::raw("null as invoice_number"), DB::raw("'received' as type"))
                    ->where('client_id', $client->id);
                
                    $received_invoice = received_invoice::select('id', 'date','created_at', 'due_date','status','invoice_number', DB::raw("'sent' as type"))
                    ->where('client_id', $client->id);
                  

                    $invoices=$invoice->union($received_invoice) 
                                ->orderBy('created_at', 'desc')
                                ->get();

                }

            


        } 
    
        return view('sort_invoice',compact('invoices')); 
               
    }




    
    public function sort_devis(Request $request)
    {
        $user = Auth::user();
        $invoices = [];
    
    
        
            $client = client::where('user_id', $user->id)->first();
            $filter = $request->input('filter');    
            
            if ($client) {
                if ($filter === 'received') {
                    $l_devis = devis::select('*', DB::raw("'received' as type"))
                                       ->where('client_id', $client->id)
                                       ->orderBy('created_at', 'desc')
                                       ->get();
                                       
                } 
                elseif($filter === 'sent') 
                {
                    $l_devis = devis_recu::select('*', DB::raw("'sent' as type"))
                                       ->where('client_id', $client->id)                                     
                                       ->orderBy('created_at', 'desc')
                                       ->get();
                }
                else{
                    $l_devis = devis::select('id', 'date','created_at',DB::raw("null as invoice_number"), DB::raw("'received' as type"))
                    ->where('client_id', $client->id);
                
                    $received_devis = devis_recu::select('id', 'date','created_at','invoice_number', DB::raw("'sent' as type"))
                    ->where('client_id', $client->id);
                  

                    $devis=$l_devis->union($received_devis) 
                                ->orderBy('created_at', 'desc')
                                ->get();

                }

            


        } 
    
        return view('sort_devis',compact('devis')); 
               
    }
    



    function test(){
        return view('404');
    }
}
