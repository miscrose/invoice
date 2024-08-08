<?php

namespace App\Http\Controllers;

use App\Models\client;
use App\Models\companyinfo;
use App\Models\invoice;
use App\Models\invoice_item;
use App\Models\received_invoice;
use App\Models\received_invoice_item;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Dompdf\Adapter\PDFLib;
use Illuminate\Support\Facades\DB;

class invoice_contr extends Controller
{
    function invoice_form(){
       /*
        $latestClientsData=Client::select('user_id', DB::raw('MAX(created_at) as latest_created_at'))
                    ->groupby('user_id')->get();
    
        $userIds = $latestClientsData->pluck('user_id');
        $latestCreatedAts = $latestClientsData->pluck('latest_created_at');

        $client = Client::whereIn('user_id', $userIds)
                       ->whereIn('created_at', $latestCreatedAts)
                       ->get();
*/

        $client=client::wherenull('state')->get();

        $companyinfo=companyinfo::all();
        return view('invoice_form',compact('client','companyinfo'));
    }




    function save_invoice_admin(Request $request){
        $request->validate([
            'date' => 'required|date',
            'due_date' => 'required|date',
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|string',
            'company_id' => 'required|exists:companyinfos,id',
        ]);
        $quantities = $request->input('quantities');
        $unit_prices = $request->input('unit_prices');
        $tvas = $request->input('tvas');
        $descriptions = $request->input('descriptions');

        if(isset($descriptions)&& isset($quantities )&& isset( $unit_prices )&&isset($tvas )){

            $invoice = new invoice();
            $invoice->date = $request->date;
            $invoice->due_date = $request->due_date;
            $invoice->client_id = $request->client_id;
            $invoice->status = $request->status;
            $invoice->companyinfo_id = $request->company_id;
            $invoice->payment_date = $request->payment_date;
            $invoice->save();

            foreach ($descriptions as $key => $description) {
                $item = new invoice_item();
                $item->description = $description;
                $item->quantity = $quantities[$key];
                $item->unit_price = $unit_prices[$key];
                $item->tva = $tvas[$key];
              
                $item->invoice_id = $invoice->id;
                $item->save();
            }
            return redirect()->back()->with('success', 'Invoice created successfully');


        }else return redirect()->back()->with('error', 'ereur');
     
    }


    function invoice_form_client(){
      
        $companyinfo=companyinfo::all();
        return view('invoice_form_client',compact('companyinfo'));
    }



    public function saveInvoiceClient(Request $request){
      
        $request->validate([
            'date' => 'required|date',
            'due_date' => 'required|date',
            'invoice_number'=>'required',
            'status' => 'required|string',
            'company_id' => 'required|exists:companyinfos,id',
        ]);
        $descriptions = $request->input('descriptions');
        $quantities = $request->input('quantities');
        $unit_prices = $request->input('unit_prices');
        $tvas = $request->input('tvas');

        if(isset($descriptions)&& isset($quantities )&& isset( $unit_prices )&&isset($tvas )){

                        $user_id = auth()->user()->id;
                        $client = client::where('user_id', $user_id)->wherenull('state')->first();
                        if (!$client) {
                            return redirect()->back()->with('error', 'Client not found.');
                        }
                        $invoice = new received_invoice();
                        $invoice->date = $request->date;

                        $invoice->due_date = $request->due_date;
                        $invoice->companyinfo_id = $request->company_id;
                        $invoice->client_id = $client->id; 
                        $invoice->status = $request->status;
                        $invoice->invoice_number = $request->invoice_number;
                    
                        $invoice->save();

                        

                        foreach ($descriptions as $key => $description) {
                            $item = new received_invoice_item();
                            $item->description = $description;
                            $item->quantity = $quantities[$key];
                            $item->unit_price = $unit_prices[$key];
                            $item->tva = $tvas[$key];
                        
                            $item->received_invoice_id = $invoice->id;
                            $item->save();
                        }   

                        return redirect()->back()->with('success', 'Invoice created successfully.');}

                else return redirect()->back()->with('error', 'ereur');
}



function detail_invoice(Request $request,$type,$id)
{   
    
    if($type=='sent'){
        $invoice=received_invoice::findOrfail($id);
        $invoice_item = received_invoice_item::where('received_invoice_id', $invoice->id)->get();
    }
    else
    {

        $invoice=invoice::findOrfail($id);
        $invoice_item = invoice_item::where('invoice_id', $invoice->id)->get();
    }


   

    $client = client::where('id', $invoice->client_id)->first();
    $company = companyinfo::where('id', $invoice->companyinfo_id)->first();
  

    $totals = [];
    foreach ($invoice_item as $item) {
        $total = $item->quantity * $item->unit_price;
        $totals[] = $total;
    }

    $tva_array = [];
    foreach ($invoice_item as $item) {
        $tva = $item->tva * $item->quantity * $item->unit_price / 100;
        $tva_array[] = $tva;
    }

    $total_tva = array_sum($tva_array);
    $total_net = array_sum($totals);
    $ttc = $total_net - $total_tva;

    // Génération du PDF
    $pdf =FacadePdf::loadView('detail_invoice', [
        'invoice' => $invoice,
        'client' => $client,
        'invoice_item' => $invoice_item,
        'company' => $company,
        'totals' => $totals,
        'tva_array' => $tva_array,
        'total_net' => $total_net,
        'total_tva' => $total_tva,
        'ttc' => $ttc,
        'type'=>$type
    ]);

    // Retourne le PDF en tant que téléchargement
    return $pdf->stream('invoice_' . $invoice->id . '.pdf');
}



function paye_change_admin(Request $request){
    $invoice = Invoice::find($request->id);
    if ($invoice) { $invoice->payment_date = $request->date;
        $invoice->status = 'paid'; 
        $invoice->save();
        return response()->json([
            
            'paymentDate' => $invoice->payment_date 
        ]);
    } else {
      
        return response()->json(['error' => 'Invoice not found'], 404);
    }
}


function paye_change_client(Request $request){
    $invoice = received_invoice::find($request->id);
    if ($invoice) { $invoice->payment_date = $request->date;
        $invoice->status = 'paid'; 
        $invoice->save();
        return response()->json([
            
            'paymentDate' => $invoice->payment_date 
        ]);
    } else {
      
        return response()->json(['error' => 'Invoice not found'], 404);
    }
}
}
