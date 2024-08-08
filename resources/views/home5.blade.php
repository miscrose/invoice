@extends('template')

@section('contenu')

@if (Auth::user()->usertype=='admin')
<form action="{{route('invoice_form')}}" method="POST"  >
  @csrf
  <button class="btn btn-success col-2 float-end mx-2" type="submit" >add invoice</button>

</form>


<div class="container d-flex justify-content-center">
    <form id="searchForm" class="d-flex w-auto mx-auto">
      <input id="searchInput" class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
    
    </form>
</div>
@else


<form action="{{route('invoice_form_client')}}" method="POST"  >
  @csrf
  <button class="btn btn-success col-2 float-end mx-2" type="submit" >add invoice</button>

</form>

<form action="{{route('devis_form_client')}}" method="POST"  >
    @csrf
    <button class="btn btn-success col-2 mx-2" type="submit" >add quote</button>
  
  </form>

@endif






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
            @foreach($clients as $client)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $client->name }}</h5>
                            <p class="card-text">Address: {{ $client->address }}</p>
                            <p class="card-text">Tel: {{ $client->tel }}</p>
                            <a href="{{route('list_client_invoice',['id'=>$client->id])}}" class="btn btn-primary">View Invoices</a>
                            <a href="{{route('list_client_devis',['id'=>$client->id])}}" class="btn btn-primary">View Quotes</a>
                        </div>
                    </div>
                </div>
            @endforeach
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
                            let resultsHtml = '';
                            response.clients.forEach(client => {
                                resultsHtml += `
                                    <div class="col-md-4">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title">${client.name}</h5>
                                                <p class="card-text">Address: ${client.address}</p>
                                                <p class="card-text">Tel: ${client.tel}</p>
                                                <a href="/list-client-invoice/${client.id}" class="btn btn-primary">View Invoices</a>
                                                 <a href="/list_client_devis/${client.id}" class="btn btn-primary">View Quotes</a>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
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