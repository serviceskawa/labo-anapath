@extends('layouts.app2')

@section('title', 'Roles create')

@section('content')
    <div class="">

        @include('layouts.alerts')
        <div class="card my-3">
            <div class="card-header">

            </div>
            <div class="card-body">

                <form action="{{ route('user.role-store') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="row mb-3">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Titre du role</label>
                                <input type="text" class="form-control" name="titre">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-flush-spacing">
                                    <tbody>
                                        <tr>
                                            <td class="text-nowrap fw-bolder">
                                                Tout les droits
                                                <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Attribuer toutes les permissions">
                                                    <i data-feather="info"></i>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="selectAll" />
                                                    <label class="form-check-label" for="selectAll"> Tout </label>
                                                </div>
                                            </td>
                                        </tr>
                                        @forelse ($ressources as $ressource)
                                            <tr>
                                                <td class="text-nowrap fw-bolder">{{ $ressource->titre }} </td>
                                                <td>
                                                    @forelse ($ressource->permissions as $permission)
                                                        <label
                                                            for="">{{ $permission->operation->operation }}</label>
                                                        <input class="form-check-input" type="checkbox"
                                                            name="{{ $permission->operation->operation }}[]" id=""
                                                            value="{{ $ressource->id }}">
                                                    @empty
                                                    @endforelse

                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
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
        // Select All checkbox click
        const selectAll = document.querySelector('#selectAll'),
            checkboxList = document.querySelectorAll('[type="checkbox"]');
        selectAll.addEventListener('change', t => {
            checkboxList.forEach(e => {
                e.checked = t.target.checked;
            });
        });
    </script>
@endpush
