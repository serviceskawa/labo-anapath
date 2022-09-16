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
                                        @forelse ($permissions as $permission)
                                            <tr>
                                                <td class="text-nowrap fw-bolder">{{ $permission->titre }} </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="userManagementRead{{ $permission->id }}" name="view[]"
                                                                value="{{ $permission->id }}"
                                                                {{ getPermission($role->id, 'view', $permission->id) ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="userManagementRead"> View
                                                            </label>
                                                        </div>
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="userManagementWrite{{ $permission->id }}"
                                                                name="create[]" value="{{ $permission->id }}"
                                                                {{ getPermission($role->id, 'create', $permission->id) ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="userManagementWrite">
                                                                Create
                                                            </label>
                                                        </div>
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="userManagementCreate{{ $permission->id }}"
                                                                name="edit[]" value="{{ $permission->id }}"
                                                                {{ getPermission($role->id, 'edit', $permission->id) ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="userManagementCreate">
                                                                Edit
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="userManagementDelete{{ $permission->id }}"
                                                                name="delete[]" value="{{ $permission->id }}"
                                                                {{ getPermission($role->id, 'delete', $permission->id) ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="userManagementCreate">
                                                                Delete
                                                            </label>
                                                        </div>
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
