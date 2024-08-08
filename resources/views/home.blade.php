@extends('layout')

@section('search')
@if (Auth::user()->usertype=='admin')

<form id="searchForm" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
    <div class="input-group">
        <input id="searchInput" class="form-control bg-light border-0 small" type="text" placeholder="Search by name..." aria-label="Search" aria-describedby="basic-addon2">
     
    </div>
</form>


@endif
@endsection


@section('contenu')







<div id="inv" >
  <div class="container-fluid mt-5">
    @if($user->usertype === 'user')
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>My Invoices</h2>
                <select id="filter-select" class="form-select form-select-sm" style="width: auto;">
                    <option value="all" >all</option>
                    <option value="received"  >Reçues</option>
                    <option value="sent">Envoyées</option>
                </select>
            </div>
            <div id="invoice-container" class="row">
            
            </div>
    
    </div>  
      
        
    @else
        <h2>Clients</h2>
        <div class="row" id="clientResults">
            <div class="table-responsive scrollbar">
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Address</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                            <tr class="align-middle">
                                <td class="text-nowrap">
                                    <div class="d-flex align-items-center">
                                       
                                        <div class="ms-2">{{ $client->name }}</div>
                                    </div>
                                </td>
                                <td class="text-nowrap">{{ $client->tel }}</td>
                                <td class="text-nowrap">{{ $client->address }}</td>
                                <td class="text-end">
                                    <a href="{{ route('list_client_invoice', ['id' => $client->id]) }}" class="btn btn-primary btn-sm">View Invoices</a>
                                    <a href="{{ route('list_client_devis', ['id' => $client->id]) }}" class="btn btn-primary btn-sm">View Quotes</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
    @endif
</div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
     
        if (document.getElementById('filter-select')) {
            fetchInvoices('all'); 
            document.getElementById('filter-select').addEventListener('change', function() {
                var filter = this.value;
                fetchInvoices(filter);
            });
        }

     
        if (document.getElementById('searchForm')) {
            const searchForm = document.getElementById('searchForm');
            const searchInput = document.getElementById('searchInput');
            const clientResults = document.getElementById('clientResults');

            searchForm.addEventListener('input', function(event) {
                event.preventDefault();  

                const query = searchInput.value;
                $.ajax({
                    url: '{{ route("search_client_name") }}',
                    type: 'POST',
                    data: {
                        query: query,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.clients && response.clients.length > 0) {
                            let resultsHtml =  `
                            <div class="table-responsive scrollbar">
                                <table class="table table-hover table-striped overflow-hidden">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Address</th>
                                            <th class="text-end" scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;
                            response.clients.forEach(client => {
                                resultsHtml += `
                                <tr class="align-middle">
                                    <td class="text-nowrap">${client.name}</td>
                                    <td class="text-nowrap">${client.tel}</td>
                                    <td class="text-nowrap">${client.address}</td>
                                    <td class="text-end">
                                        <a href="/list_client_invoice/${client.id}" class="btn btn-primary btn-sm">View Invoices</a>
                                        <a href="/list_client_devis/${client.id}" class="btn btn-primary btn-sm">View Quotes</a>
                                    </td>
                                </tr>
                            `;
                            });
                            resultsHtml += `
                                    </tbody>
                                </table>
                            </div>
                        `;
                            clientResults.innerHTML = resultsHtml;  
                        } else {
                            clientResults.innerHTML = '<p>No clients found.</p>';  
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Erreur AJAX:', error);
                        clientResults.innerHTML = '<p>There was an error processing your request.</p>';  
                    }
                });
            });
        }

        
        $(document).on('click', '.btn-mark-paid', function() {
       
       var invoiceId = $(this).data('id');


       $('#paymentForm').on('submit', function(event) {
          var     date=$('#payment_date').val()
           event.preventDefault(); // Prevent default form submission
   var formData = {
                 id: invoiceId,
                date: date,

                _token: '{{ csrf_token() }}'
                     };
                     $.ajax({
       url: "{{ route('paye_change_client') }}", // Adjust the route as necessary
       method: 'POST',
       data: formData,
       success: function(response) {   
           var paymentDate = response.paymentDate;  
           $('#paymentModal').modal('hide');
           $('.modal-backdrop').remove(); 
           var updatedRow = ` <td class="text-nowrap">
                            paid (${paymentDate})
                     </td>`;

               $(`td[data-id="sent-${invoiceId}"]`).replaceWith(updatedRow);
       },
       error: function(xhr) {
           console.log(xhr.responseText);
       }
   });      
});

document.querySelector('body').addEventListener('hidden.bs.modal', (event) => {

document.querySelector('body').removeAttribute('style');
});    

});






    });

    function fetchInvoices(filter) {
    $.ajax({
        url: '/sort_invoice',
        type: 'GET',
        data: { filter: filter },
        success: function(response) {
            $('#invoice-container').html(response);
        },
        error: function(xhr, status, error) {
            console.error('La requête a échoué.', error);
        }
    });
}

</script>


@endsection