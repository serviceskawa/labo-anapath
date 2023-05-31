@extends('layouts.app2')

@section('title', 'Rôles')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3">
                    <a type="btn" href="{{ route('user.role-create') }} " class="btn btn-primary">Ajouter un nouveau rôle</a>
                </div>
                <h4 class="page-title">Roles</h4>
            </div>
        </div>
    </div>

    <div class="">

        @include('layouts.alerts')

        <div class="card mb-md-0 mb-3">
            <div class="card-body">
                <div class="card-widgets">
                    <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                    <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                        aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                    <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                </div>
                <h5 class="card-title mb-0">Liste des rôles</h5>

                <div id="cardCollpase1" class="collapse pt-3 show">
                    <div class="table-responsive">
                        <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Slug</th>
                                    <th>Créé par</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->slug }}</td>
                                        <td>{{ $role->user? $role->user->fullname():'' }}</td>
                                        <td>
                                            <a href="{{ route('user.role-show', $role->slug) }}"
                                                class="btn btn-primary"><i class="mdi mdi-eye"></i></a>
                                            <a href="{{ route('user.role-delete', $role->id) }}"
                                                class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">Aucun rôle trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('extra-js')
    <script>
        var baseUrl = "{{url('/')}}"
    </script>
    <script src="{{asset('viewjs/user/role.js')}}"></script>
@endpush

