@extends('layouts.app2')

@section('title', 'Prestation')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#standard-modal">Ajouter une nouvelle prestation</button>
                </div>
                <h4 class="page-title">Prestations</h4>
            </div>

            <!----MODAL---->

            @include('prestation.create')

            @include('prestation.edit')

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
                <h5 class="card-title mb-0">Liste des prestations</h5>

                <div id="cardCollpase1" class="show collapse pt-3">

                    <table id="datatable1" class="table-striped dt-responsive nowrap w-100 table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom de la prestation</th>
                                <th>Catégorie</th>
                                <th>Prix</th>
                                <th>Actions</th>

                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($prestations as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category->name }}</td>
                                    <td>{{ number_format($item->price, 0, '.', '') }}</td>
                                    <td>
                                        <button type="button" onclick="edit({{ $item->id }})"
                                            class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </button>
                                        <button type="button" onclick="deleteModal({{ $item->id }})"
                                            class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div> <!-- end card-->

    </div>
@endsection

@push('extra-js')
    <script>
        var baseUrl = "{{url('/')}}"
    </script>
    <script src="{{asset('viewjs/prestation/index.js')}}"></script>
@endpush
