@extends('layouts.app2')

@section('title', 'Utilisateurs')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3">
                    <a type="btn" href="{{ route('user.create') }} " class="btn btn-primary">Ajouter un nouveau
                        utilisateur</a>
                </div>
                <h4 class="page-title">Utilisateurs</h4>
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
                <h5 class="card-title mb-0">Liste des utilisateurs</h5>

                <div id="cardCollpase1" class="collapse pt-3 show">

                    @if (auth()->check())
                    @endif
                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôles</th>
                                <th>Actions</th>
                            </tr>
                        </thead>


                        <tbody>

                            @foreach ($users as $item)
                                <tr>
                                    <td>{{ $item->firstname }} {{ $item->lastname }}</td>
                                    <td>{{ $item->email }} </td>
                                    <td>
                                        @forelse ($item->roles as $role)
                                            <span class="badge bg-primary">{{ $role->name }} </span>
                                        @empty
                                            Aucun
                                        @endforelse

                                    </td>

                                    <td>
                                        <a type="button" href="{{ route('user.edit', $item->id) }} "
                                            class="btn btn-primary"><i class="mdi mdi-eye"></i> </a>
                                        <a type="button" href="{{ route('user.delete', $item->id) }} "
                                            class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </a>
                                        <a type="button" href="{{ route('user.statusActive', $item->id)}}" class="btn btn-secondary"> {{$item->is_active !=1 ? 'Actif':'Inactif'}} </a>
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
@endpush
