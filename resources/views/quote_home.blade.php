@extends('layout')
@section('contenu')
    

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>My Quotes</h2>
    <select id="filter-select" class="form-select form-select-sm" style="width: auto;">
        <option value="all" >all</option>
        <option value="received"  >Reçues</option>
        <option value="sent">Envoyées</option>
    </select>
</div>
<div id="invoice-container" class="row">

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
    });
    function fetchInvoices(filter) {
    $.ajax({
        url: '/sort_devis',
        type: 'GET',
        data: { filter: filter },
        success: function(response) {
            $('#invoice-container').html(response);
        },
        error: function(xhr, status, error) {
            console.error('La requête a échoué.', error);
        }
    });}




</script>

@endsection
