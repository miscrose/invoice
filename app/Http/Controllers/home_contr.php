<?php

namespace App\Http\Controllers;

use App\Models\client;
use App\Models\devis;
use App\Models\devis_recu;
use App\Models\invoice;
use App\Models\received_invoice;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use ArielMejiaDev\LarapexCharts\LarapexChart;
class home_contr extends Controller
{
  public function home(Request $request)
    {
        $user = Auth::user();
      

        if ($user->usertype === 'user') {
            
            return view('home', compact( 'user'));
                          
            
        } else {
            $idclientinvoice = DB::table('invoices')->pluck('client_id');
            $receivedInvoiceClientId = DB::table('received_invoices')->pluck('client_id');
            $idclientdevis = DB::table('devis')->pluck('client_id');
            $idclientdevisrecus = DB::table('devis_recus')->pluck('client_id');

            $allClientIds = $idclientinvoice->merge($receivedInvoiceClientId)
            ->merge($idclientdevis)
            ->merge($idclientdevisrecus)
            ->unique();
            $clients = Client::whereIn('id', $allClientIds)->get();

            return view('home', compact( 'clients', 'user'));
        }

       
    }



    public function sort_invoice(Request $request)
    {
        $user = Auth::user();
        $invoices = [];
    
    
        
            $client = client::where('user_id', $user->id)->wherenull('state')->first();
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
                    $invoice = invoice::select('id', 'date','created_at','payment_date', 'due_date','status',DB::raw("null as invoice_number"), DB::raw("'received' as type"))
                    ->where('client_id', $client->id);
                
                    $received_invoice = received_invoice::select('id', 'date','created_at','payment_date', 'due_date','status','invoice_number', DB::raw("'sent' as type"))
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
        $devis = [];
    
    
        
            $client = client::where('user_id', $user->id)->wherenull('state')->first();
            $filter = $request->input('filter');    
        
            if ($client) {
                if ($filter === 'received') {
                    $devis = devis::select('*', DB::raw("'received' as type"))
                                       ->where('client_id', $client->id)
                                       ->orderBy('created_at', 'desc')
                                       ->get();
                                       
                } 
                elseif($filter === 'sent') 
                {
                    $devis = devis_recu::select('*', DB::raw("'sent' as type"))
                                       ->where('client_id', $client->id)                                     
                                       ->orderBy('created_at', 'desc')
                                       ->get();
                }
                else{
                    $l_devis = devis::select('id', 'date','created_at',DB::raw("null as devis_number"), DB::raw("'received' as type"))
                    ->where('client_id', $client->id);
                
                    $received_devis = devis_recu::select('id', 'date','created_at','devis_number', DB::raw("'sent' as type"))
                    ->where('client_id', $client->id);
                  

                    $devis=$l_devis->union($received_devis) 
                                ->orderBy('created_at', 'desc')
                                ->get();

                }

            


        } 
    
        return view('sort_devis',compact('devis')); 
               
    }


    public function dashboard()
    {
      
    
}



    function test(){
        return view('404');
    }
}
