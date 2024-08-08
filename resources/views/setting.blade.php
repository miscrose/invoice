@extends('layout')

@section('contenu')
    <div class="container-fluid">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Account Settings    </div>

                    <div class="card-body">
                        <form method="POST" action="{{route('setting.update')}}">
                            @csrf
                          
                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom</label>
                                <input id="name" type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" required>
                            </div>

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input id="password" type="password" class="form-control" name="password">
                            </div>

                        

                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>





@endsection
