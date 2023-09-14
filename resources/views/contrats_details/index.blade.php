@extends('layouts.app2')

@section('title', 'Contrat')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3 mb-1">
                    <a href="{{ route('contrats.index') }}" type="button" class="btn btn-primary">Retour à la liste des
                        contrats</a>
                </div>
                <h4 class="page-title"></h4>
            </div>

            <!----MODAL---->

            @include('contrats_details.create')

            @include('contrats_details.edit')

        </div>
    </div>


    <div class="">


        @include('layouts.alerts')

        <div class="card mt-3">
            <h5 class="card-header">Contrat : {{ $contrat->name }}</h5>

            <div class="card-body">
                <p><b>Type</b> : {{ $contrat->type }}</p>
                <p><b>Statut</b> : {{ $contrat->status }}</p>
                <p><b>Description</b> : {{ $contrat->description }}</p>
            </div>
        </div>

        <div class="card mb-md-0 mb-3">


            <div class="card-body">
                <div class="card-widgets">
                    <button type="button" class="btn btn-warning float-left" data-bs-toggle="modal"
                        data-bs-target="#modal2">Ajouter une nouvelle catégorie d'examen</button>
                </div>
                <h5 class="card-title mb-0">Catégories d'examen prises en compte</h5>


                <div id="cardCollpase1" class="collapse pt-3 show">


                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Catégorie d'examen</th>
                                <th>Pourcentage</th>
                                <th>Actions</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($details as $item)
                                <tr>
                                    <td>{{ $item->categorytest()?$item->categorytest()->name:'' }}</td>
                                    <td>{{ $item->pourcentage . ' %' }}</td>
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

                    <span class="d-inline" data-bs-toggle="popover"
                        data-bs-content="Veuillez ajouter un detail avant de sauvegarder">

                        <a type="button" href="{{ route('contrat_details.update-status', $contrat->id) }}"
                            class=" mt-3 btn btn-success w-100 @if (count($details) == 0) disabled @endif ">Sauvegarder</a>
                    </span>


                </div>
            </div>
        </div> <!-- end card-->


    </div>
@endsection


@push('extra-js')

    <script>
        var baseUrl = "{{ url('/') }}";
    </script>
    <script src="{{asset('viewjs/contrat/indexContrat.js')}}"></script>
@endpush
