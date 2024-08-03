<?php

namespace App\Http\Controllers;

use App\Models\client;
use App\Models\companyinfo;
use App\Models\devis;
use App\Models\devis_items;
use App\Models\devis_recu;
use App\Models\devis_recu_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class devis_contr extends Controller
{
    function devis_form(){
       
        $latestClientsData=client::select('user_id', DB::raw('MAX(created_at) as latest_created_at'))
                    ->groupby('user_id')->get();
    
        $userIds = $latestClientsData->pluck('user_id');
        $latestCreatedAts = $latestClientsData->pluck('latest_created_at');

        $client = Client::whereIn('user_id', $userIds)
                       ->whereIn('created_at', $latestCreatedAts)
                       ->get();

        $companyinfo=companyinfo::all();
        return view('devis_form',compact('client','companyinfo'));
    }




    function save_devis_admin(Request $request){
        $request->validate([
            'date' => 'required|date',
           
            'client_id' => 'required|exists:clients,id',
        
            'company_id' => 'required|exists:companyinfos,id',
        ]);
        $quantities = $request->input('quantities');
        $unit_prices = $request->input('unit_prices');
        $tvas = $request->input('tvas');
        $descriptions = $request->input('descriptions');

        if(isset($descriptions)&& isset($quantities )&& isset( $unit_prices )&&isset($tvas )){

            $devis = new devis();
            $devis->date = $request->date;
         
            $devis->client_id = $request->client_id;
        
            $devis->companyinfo_id = $request->company_id;
    
            $devis->save();

            foreach ($descriptions as $key => $description) {
                $item = new devis_items();
                $item->description = $description;
                $item->quantity = $quantities[$key];
                $item->unit_price = $unit_prices[$key];
                $item->tva = $tvas[$key];
              
                $item->devis_id = $devis->id;
                $item->save();
            }
            return redirect()->back()->with('success', 'created successfully');


        }else return redirect()->back()->with('error', 'ereur');
     
    }



    function devis_form_client(){
   
        $companyinfo=companyinfo::all();
        return view('devis_form_client',compact('companyinfo'));
    }


    
    public function save_devis_client(Request $request){
      
        $request->validate([
            'date' => 'required|date',
           
            'devis_number'=>'required',
           
            'company_id' => 'required|exists:companyinfos,id',
        ]);
        $descriptions = $request->input('descriptions');
        $quantities = $request->input('quantities');
        $unit_prices = $request->input('unit_prices');
        $tvas = $request->input('tvas');

        if(isset($descriptions)&& isset($quantities )&& isset( $unit_prices )&&isset($tvas )){

                        $user_id = auth()->user()->id;
                        $client = client::where('user_id', $user_id)->first();
                        if (!$client) {
                            return redirect()->back()->with('error', 'Client not found.');
                        }
                        $devis = new devis_recu();
                        $devis->date = $request->date;

                       
                        $devis->companyinfo_id = $request->company_id;
                        $devis->client_id = $client->id; 
                      
                        $devis->devis_number = $request->devis_number;
                    
                        $devis->save();

                        

                        foreach ($descriptions as $key => $description) {
                            $item = new devis_recu_item();
                            $item->description = $description;
                            $item->quantity = $quantities[$key];
                            $item->unit_price = $unit_prices[$key];
                            $item->tva = $tvas[$key];
                        
                            $item->devis_recu_id = $devis->id;
                            $item->save();
                        }   

                        return redirect()->back()->with('success', ' created successfully.');}

                else return redirect()->back()->with('error', 'ereur');
}


function detail_devis(Request $request,$type,$id)
{   
    
    if($type=='sent'){
        $devis=devis_recu::findOrfail($id);
        $devis_item = devis_recu_item::where('devis_recu_id', $devis->id)->get();
    }
    else
    {

        $devis=devis::findOrfail($id);
        $devis_item = devis_items::where('devis_id', $devis->id)->get();
    }


   

    $client = client::where('id', $devis->client_id)->first();
    $company = companyinfo::where('id', $devis->companyinfo_id)->first();
  

    $totals = [];
    foreach ($devis_item as $item) {
        $total = $item->quantity * $item->unit_price;
        $totals[] = $total;
    }

    $tva_array = [];
    foreach ($devis_item as $item) {
        $tva = $item->tva * $item->quantity * $item->unit_price / 100;
        $tva_array[] = $tva;
    }

    $total_tva = array_sum($tva_array);
    $total_net = array_sum($totals);
    $ttc = $total_net - $total_tva;

    // Génération du PDF
    $pdf =FacadePdf::loadView('detail_devis', [
        'devis' => $devis,
        'client' => $client,
        'devis_item' => $devis_item,
        'company' => $company,
        'totals' => $totals,
        'tva_array' => $tva_array,
        'total_net' => $total_net,
        'total_tva' => $total_tva,
        'ttc' => $ttc,
        'type'=>$type
    ]);

    // Retourne le PDF en tant que téléchargement
    return $pdf->stream('devis_' . $devis->id . '.pdf');
}



function test(){

    return view ('login');
}



}
