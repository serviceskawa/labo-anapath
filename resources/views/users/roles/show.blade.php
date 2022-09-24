@extends('layouts.app2')

@section('content')
    <div class="">

        @include('layouts.alerts')
        <div class="card my-3">
            <div class="card-header">
                Mettre à jour le role {{ $role->name }}
            </div>
            <div class="card-body">

                <form action="{{ route('user.role-update') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="row mb-3">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Titre du role</label>
                                <input type="text" class="form-control" value="{{ $role->name }}" name="titre">
                                <input type="hidden" name="id" class="form-control" value="{{ $role->id }}"
                                    tabindex="-1" />
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
                                                    <div class="d-flex">
                                                        @forelse ($ressource->permissions as $permission)
                                                            <div class="form-check me-3 me-lg-5">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="{{ $permission->operation->operation }}[]"
                                                                    value="{{ $ressource->id }}"
                                                                    {{ getPermission($role->id, $permission->operation->operation, $permission->id) ? 'checked' : '' }} />
                                                                <label class="form-check-label" for="userManagementRead">
                                                                    {{ $permission->operation->operation }}
                                                                </label>
                                                            </div>
                                                        @empty
                                                        @endforelse


                                                    </div>
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
                <button type="submit" class="btn btn-warning">Update</button>
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
