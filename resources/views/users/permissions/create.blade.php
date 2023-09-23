@extends('layouts.app2')

@section('title', 'Utilisateurs')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Permissions</h4>
            </div>
        </div>
    </div>
    <div class="">
        @include('layouts.alerts')

        <div class="card my-2">

            <div class="card-body">

                <form action="{{ route('user.permission-store') }}" method="post" autocomplete="off">
                    @csrf

                    <h5 class="card-title mb-0">Ajouter une nouvelle permission</h5>
                    <div id="cardCollpase1" class="collapse pt-3 show">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Nom</label>
                                <input type="text" class="form-control" name="titre">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Ressource</label>
                                <select name="ressource" class="form-select select2" data-toggle="select2">
                                    @forelse ($ressources as $ressource)
                                        <option value="{{ $ressource->id }}"> {{ $ressource->titre }} </option>
                                    @empty
                                    @endforelse

                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Opération</label>
                                <select name="operation" class="form-select select2" data-toggle="select2">
                                    @forelse ($operations as $operation)
                                        <option value="{{ $operation->id }}"> {{ $operation->operation }} </option>

                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>

                    </div>

            </div>

            <div class="modal-footer">
                <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Ajouter une nouvelle permission</button>
            </div>


            </form>
        </div>

        <div class="card mb-md-0 mb-3">
            <div class="card-body">
                <div class="card-widgets">
                    <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                    <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                        aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                    <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                </div>
                <h5 class="card-title mb-0">Liste des permissions</h5>

                <div id="cardCollpase1" class="collapse pt-3 show">


                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Slug</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>


                        <tbody>

                            @foreach ($permissions as $item)
                                <tr>
                                    <td>{{ $item->titre }} </td>
                                    <td>{{ $item->slug }} </td>
                                    <td>{{ $item->created_at }} </td>
                                    <td> <a type="button" href="#" class="btn btn-primary"><i
                                                class="mdi mdi-eye"></i> </a>
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
