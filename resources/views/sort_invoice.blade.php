<div class="table-responsive scrollbar">
    <table class="table table-hover table-striped overflow-hidden">
        <thead>
            <tr>
                <th scope="col">Invoice Number</th>
                <th scope="col">Date</th>
                <th scope="col">Status</th>
                <th class="text-end" scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr class="align-middle">
                    <td class="text-nowrap">
                        @if ($invoice->type === 'sent')
                            {{ $invoice->invoice_number }}
                        @else
                            {{ $invoice->id }}
                        @endif
                    </td>
                    <td class="text-nowrap">{{ $invoice->date }}</td>
                   
                    <td class="text-nowrap" data-id="{{ $invoice->type}}-{{$invoice->id }}">
                        
                         @if ($invoice->status === 'paid')
                              paid ({{$invoice->payment_date}})
                        @else
                            @if ((Auth::user()->usertype === 'admin' && $invoice->type === 'received') ||
                            (Auth::user()->usertype !== 'admin' && $invoice->type === 'sent'))
                                      
                                {{ $invoice->status }}
                                <button 
                                    class="btn btn-success btn-sm ms-2 btn-mark-paid" 
                                    data-id="{{ $invoice->id }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#paymentModal"
                                >
                                    Mark as Paid 
                                        </button>
                                     @else
                                    {{ $invoice->status }}
                                    @endif
                                    
                                    
                                    
                       
                       
                       
                       
                    @endif  
                        </td>

                    <td class="text-end">
                        <form action="{{ route('detail_invoice', ['type' => $invoice->type, 'id' => $invoice->id]) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">View Details</button>
                        </form>
                           </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Enter Payment Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm" >
                    @csrf
                    
                    <div class="mb-3">
                        <label for="payment_date" class="form-label">Payment Date</label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
