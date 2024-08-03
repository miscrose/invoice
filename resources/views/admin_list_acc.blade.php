@extends('template')

@section('contenu')





<div class="container d-flex justify-content-center">
    <form class="d-flex w-auto mx-auto" method="POST" action="{{route('search_users_email')}}">
        @csrf
      <input class="form-control me-2" type="search" placeholder="Search email" aria-label="Search " name="email">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
  </div>

<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">id</th>
            <th scope="col">name</th>
            <th scope="col">email</th>
            <th scope="col">validation</th>
            <th scope="col">client information</th>
            <th scope="col"></th>
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

                <form class="toggleForm" action="{{ route('validation_change', ['user'=> $item->id]) }}" method="POST">
                    @csrf
                    <div class="toggleButton toggle-button" id="{{$item->id}}">
                        <div class="toggle-circle" ></div>
                    </div>
                </form>
            </td>

            <td>
                <form action="{{ route('admin_client',['id'=> $item->id])  }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-info btn-sm mx-4">client info</button>
                </form>
                


            </td>
            <td class="col-3">
                <form action="{{ route('account_update', ['user'=> $item->id]) }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-info btn-sm mx-4">Update</button>
                </form>
                
                <form action="{{ route('delete_account', ['user'=> $item->id]) }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
        
    </tbody>
</table>




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
