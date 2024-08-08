@extends('layout')

@section('contenu')



   
 <div class="container-fluid">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">client information    </div>
                <form action="{{route('add_update_information')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder=""value="{{ isset($client) ? $client->name : '' }}" required>
                    </div>
                   
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ isset($client) ? $client->address : '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="tel" class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" id="tel" name="tel" value="{{ isset($client) ? $client->tel : '' }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ isset($client) ?'update' : 'add' }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

 

@endsection

