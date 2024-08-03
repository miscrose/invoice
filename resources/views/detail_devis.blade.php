

<h2 style=" text-align: center;
            margin-bottom: 20px;">DEVIS n°
        @if ($type==='sent')
        {{$devis->devis_number}}
        @else
        {{$devis->id}}
        @endif
            
         
        
        </h2>

<div>
    @if ($type==='sent')
      <h2>{{$client->name}}</h1>   
    <p>{{$client->address}}</p> 
    <p>{{$client->tel}}</p> 
@else
<h3>{{$company->name}}</h3>   
<p>{{$company->address}}</p> 
<p>{{$company->city}}</p> 
<p>{{$company->tel}}</p> 
<p>{{$company->email}}</p> 

@endif
</div>

<div style="text-align: right; margin-bottom: 20px;">
    @if ($type==='sent')
    <h1>{{$company->name}}</h1>   
    <p>{{$company->address}}</p> 
    <p>{{$company->city}}</p> 
    <p>{{$company->tel}}</p> 
    <p>{{$company->email}}</p> 
    @else
     <h3>{{$client->name}}</h3>   
    <p>{{$client->address}}</p> 
    <p>{{$client->tel}}</p> 
    @endif


</div>

<table class="devis-items" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
    <thead>
        <tr>
            
            <th style="border: 1px solid #ccc; padding: 10px; text-align: left;">Description</th>
            <th style="border: 1px solid #ccc; padding: 10px; text-align: left;">Qté</th>
            <th style="border: 1px solid #ccc; padding: 10px; text-align: left;">Prix unitaire  HT</th>
            <th style="border: 1px solid #ccc; padding: 10px; text-align: left;">TVA</th>
            <th style="border: 1px solid #ccc; padding: 10px; text-align: left;">Total HT</th>
        
      
        </tr>
    </thead>
    <tbody>
        @foreach ($devis_item as $key=>$item)
            

        <tr>
            <td style="border: 1px solid #ccc; padding: 10px;">{{$item->description}} </td>
            <td style="border: 1px solid #ccc; padding: 10px;">{{$item->quantity}} </td>
            <td style="border: 1px solid #ccc; padding: 10px;">{{$item->unit_price}}</td>
            <td style="border: 1px solid #ccc; padding: 10px;">{{$item->tva}}%  <br><i>({{$tva_array[$key]}})</i> </td>
            <td style="border: 1px solid #ccc; padding: 10px;">{{$totals[$key]}}</td>
        </tr>
        @endforeach
    </tbody>

</table>

<div  style="text-align: right; margin-bottom: 20px;">
    <p>Toltal net HT:{{$total_net}}DH </p>
    <p>Total TVA :{{$total_tva}}DH </p>
    <p>Montant total TTC :{{$ttc}}DH </p>
  
</div>
