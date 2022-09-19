@extends('layouts.app2')

@section('content')
    <div class="">

        @include('layouts.alerts')

        <div class="card my-3">
            <div class="card-header">
                Creer une nouvel utilisateur
            </div>
            <div class="card-body">

                <form action="{{ route('user.store') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Nom</label>
                                <input type="text" class="form-control" name="firstname" required>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Prenom</label>
                                <input type="text" class="form-control" name="lastname" required>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="example-select" class="form-label">Roles<span style="color:red;">*</span></label>
                            <select class="form-select select2" data-toggle="select2" required name="roles[]" multiple>
                                <option>Sélectionner les roles</option>
                                @forelse ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @empty
                                    Ajouter un role
                                @endforelse
                            </select>
                        </div>

                    </div>

            </div>

            <div class="modal-footer">
                <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Creer</button>
            </div>


            </form>
        </div>


    </div>

    </div>
@endsection


@push('extra-js')
@endpush
