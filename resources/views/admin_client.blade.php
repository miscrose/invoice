@extends('template')

@section('contenu')



<div class="container-fluid">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">client {{$id}} information    </div>
                <form action="{{ route('add_update_client', ['id' => $id]) }}" method="POST">
                    @csrf
                   
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom</label>
                        <input type="text" value="{{ isset($client) ? $client->name : '' }}" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Adresse</label>
                        <input type="text" value="{{ isset($client) ? $client->address : '' }}" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="tel" class="form-label">Téléphone</label>
                        <input type="tel"  value="{{ isset($client) ? $client->tel : '' }}" class="form-control" id="tel" name="tel" required>
                    </div>
                  
                    <button type="submit" class="btn btn-primary">{{ isset($client) ?'update' : 'add' }}</button>
                </form>
            </div>
        </div>
    </div>
</div>


 

@endsection

