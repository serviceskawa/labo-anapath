@extends('layouts.app2')

@section('title', 'Rôles')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3">
                    <a type="btn" href="{{ route('user.role-create') }} " class="btn btn-primary">Ajouter un nouveau
                        rôle</a>
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
                <h5 class="card-title mb-0">Liste des roles</h5>

                <div id="cardCollpase1" class="collapse pt-3 show">


                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Slug</th>
                                <th>Crée par</th>
                                <th>Actions</th>
                            </tr>
                        </thead>


                        <tbody>

                            @foreach ($roles as $item)
                                <tr>
                                    <td>{{ $item->name }} </td>
                                    <td>{{ $item->slug }} </td>
                                    <td>{{ $item->user->firstname }} {{ $item->user->lastname }} </td>
                                    <td> <a type="button" href="{{ route('user.role-show', $item->slug) }}"
                                            class="btn btn-primary"><i class="mdi mdi-eye"></i> </a>
                                            <a type="button" href="{{ route('user.role-delete', $item->id) }}"
                                                class="btn btn-danger"> <i class="mdi mdi-trash-can-outline"></i></a>
                                    </td>

                                </tr>
                            @endforeach
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
        // SUPPRESSION
        function deleteModal(id) {

        Swal.fire({
                title: "Voulez-vous supprimer l'élément ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Oui ",
                cancelButtonText: "Non !",
            }).then(function(result) {
                if (result.value) {
                    window.location.href="{{url('roles-delete')}}"+"/"+id;
                    Swal.fire(
                        "Suppression !",
                        "En cours de traitement ...",
                        "success"
                    )
                }
            });
        }                                                                                    
    </script>
@endpush
