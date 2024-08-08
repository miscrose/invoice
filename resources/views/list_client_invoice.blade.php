@extends('layout')

@section('contenu')
    


<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Invoices</h2>
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
       
        fetchInvoices('all');

        document.getElementById('filter-select').addEventListener('change', function() {
            var filter = this.value;
            fetchInvoices(filter);
        });

   

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
                url: "{{ route('paye_change_admin') }}", // Adjust the route as necessary
                method: 'POST',
                data: formData,
                success: function(response) {   
                    var paymentDate = response.paymentDate;  
                    $('#paymentModal').modal('hide');
                    $('.modal-backdrop').remove(); 
                    var updatedRow = ` <td class="text-nowrap">
                                     paid (${paymentDate})
                              </td>`;

                        $(`td[data-id="received-${invoiceId}"]`).replaceWith(updatedRow);
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
        url: '/sort_client_invoice',
        type: 'GET',
        data: { filter: filter, id: {{$id}} },
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