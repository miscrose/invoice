
@extends('layout')

@section('contenu')
    
<table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">id</th>
        <th scope="col">name</th>
        <th scope="col">email</th>
        <th scope="col">created at</th>
        <th scope="col">validation</th>
      </tr>
    </thead>
    <tbody>
      
        @foreach ($users as $item)
            
        
        <tr>
            <th scope="row">{{ $item->id }}</th>
            <td>{{ $item->name }}</td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->created_at }}</td>
            <td class="col-3">
                <form action="{{route('validation_acc',['user'=> $item->id])}}" method="POST" style="display: inline-block;">
                    @csrf
                  
                    <button type="submit" class="btn btn-success btn-sm mx-2">validation</button>
                </form>
              
        
                
                <form action="{{route('delete_account',['user'=> $item->id])}}" method="POST" style="display: inline-block;">
                    @csrf
                  
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        
      @endforeach




      
    </tbody>
  </table>


@endsection