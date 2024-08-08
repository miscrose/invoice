@extends('layout')

@section('contenu')





<div class="container d-flex justify-content-center">
    <form class="d-flex w-auto mx-auto" method="POST" action="{{route('search_users_email')}}">
        @csrf
      <input class="form-control me-2" type="search" placeholder="Search email" aria-label="Search " name="email">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
  </div>

<div class="table-responsive scrollbar">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Validation</th>
                <th scope="col">Client Information</th>
                <th class="text-end" scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $item)
            <tr>
                <th scope="row">{{ $item->id }}</th>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>
                    <span class="validation">{{ $item->uservalid }}</span> 
                    <form class="toggleForm" action="{{ route('validation_change', ['user'=> $item->id]) }}" method="POST" style="display: inline-block;">
                        @csrf
                        <div class="toggleButton toggle-button" id="{{$item->id}}">
                            <div class="toggle-circle"></div>
                        </div>
                    </form>
                </td>
                <td>
                    <form action="{{ route('admin_client', ['id'=> $item->id]) }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-info btn-sm">Client Info</button>
                    </form>
                </td>
                <td class="text-end">
                    <div>
                        <form action="{{ route('account_update', ['user'=> $item->id]) }}" method="POST" style="display: inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-info btn-sm">Update</button>
                        </form>
                      <!-- Bouton de Suppression -->
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteConfirmModal" data-action="{{ route('delete_account', ['user' => $item->id]) }}">
                            Delete
                        </button>

                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- Modal de Confirmation -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('delete_account', ['user'=> $item->id]) }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.toggleButton');
        const validations = document.querySelectorAll('.validation');
        const toggleForms = document.querySelectorAll('.toggleForm');

        toggleButtons.forEach((toggleButton, index) => {
            const validation = validations[index].textContent.trim();

            if (validation === 'v') {
                toggleButton.classList.add('on');
            }

            toggleButton.addEventListener('click', function() {
                $.ajax({
                    url: toggleForms[index].action,
                    type: 'POST',
                 //   data: $(toggleForms[index]).serialize(), 
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Réponse du serveur :', response);
                      
                        if (response.validation === 'v') {
                            toggleButton.classList.add('on');
                            validations[index].textContent = 'v'; 
                        } else {
                            toggleButton.classList.remove('on');
                            validations[index].textContent = 'nv'; 
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Erreur AJAX:', error);
                    }
                   
                });
            });
        });
      
    });
</script>

@endsection
