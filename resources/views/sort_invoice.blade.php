@foreach($invoices as $invoice)
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Invoice #  @if ($invoice->type==='sent')
                       {{$invoice->invoice_number }} 
                @else
                      {{$invoice->id}}
                @endif  
            </h5>
                <p class="card-text">Date: {{ $invoice->date }}</p>
                <p class="card-text">Status: {{ $invoice->status }}</p>
             
                <a href="{{ route('detail_invoice', ['type'=>$invoice->type,'id' => $invoice->id]) }}" class="btn btn-primary">View Details</a>
            </div>
        </div>
    </div>
@endforeach
