@extends('layouts.app2')

@section('content')
    <div class="">

        @include('layouts.alerts')

        <div class="card my-3">
            <div class="card-header">
                Modifier l'utilisateur {{ $user->name }}
            </div>
            <div class="card-body">

                <form action="{{ route('user.update') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Nom</label>
                                <input type="text" class="form-control" name="firstname" value="{{ $user->firstname }}">
                                <input type="hidden" class="form-control" name="id" value="{{ $user->id }}">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Lastname</label>
                                <input type="text" class="form-control" name="lastname" value="{{ $user->lastname }}">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="example-select" class="form-label">Type<span style="color:red;">*</span></label>
                            <select class="form-select select2" data-toggle="select2" required name="roles[]" id=""
                                multiple>
                                <option>Sélectionner les roles</option>
                                @forelse ($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->hasRole($role->slug) ? 'selected' : '' }}>
                                        {{ $role->name }}</option>

                                @empty
                                    Ajouter un role
                                @endforelse
                            </select>
                        </div>

                    </div>

            </div>

            <div class="modal-footer">
                <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-warning">Update</button>
            </div>


            </form>
        </div>


    </div>

    </div>
@endsection


@push('extra-js')
    <script>
        var s2 = $("#selectRoles").select2({
            placeholder: "Choose event type",
            tags: true
        });

        var vals = <?= isset($user->roles) ? json_encode($user->roles) : '' ?>

        console.log(vals)

        vals.forEach(function(e) {
            if (!s2.find('option:contains(' + e + ')').length)
                s2.append($('<option value=' + e.id + '>').text(e.name));
        });

        array.forEach(element => {

        });
        s2.val(vals).trigger("change");
    </script>
@endpush
