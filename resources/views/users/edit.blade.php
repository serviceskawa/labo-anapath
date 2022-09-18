@extends('layouts.app2')

@section('content')
    <div class="">

        @include('layouts.alerts')

        <div class="card my-3">
            <div class="card-header">
                Modifier l'utilisateur {{ $user->name }}
            </div>
            <div class="card-body">

                <form action="{{ route('user.store') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Nom</label>
                                <input type="text" class="form-control" name="name" value="{{ $user->name }}">
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
                                    @foreach ($user->roles as $my)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
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
