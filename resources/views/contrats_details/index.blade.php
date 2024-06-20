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


        @include('contrats_details.create')
        @include('contrats_details.create_test')
        @include('contrats_details.edit')
    </div>
</div>


<div class="">
    @include('layouts.alerts')
    <div class="row">
        <div class="col-md-6">
            <div class="card mt-3">

                <h5 class="card-header justify-content-between d-flex">

                    <div>
                        Contrat : {{ $contrat->name }}
                    </div>

                    <div>
                        @if($contrat->status == "ACTIF")
                        <div class="rounded"
                            style="background-color: red; border-radius : 10px solid red; padding : 8px;">
                            <a style="text-decoration: none; color :white; font-size : 15px;"
                                onclick="closeModal({{ $contrat->id }})" title="Facture">Clôturer</a>
                        </div>
                        @elseif($contrat->status == "INACTIF")
                        <div class="rounded"
                            style="background-color: #0ACF97; border-radius : 10px solid #0ACF97; padding : 8px;">

                            <a onclick="activeContratModal({{ $contrat->id }})"
                                style="text-decoration: none; color :white; font-size : 15px;" class="" title="Facture"
                                target="_blank">Activer</a>
                        </div>
                        @endif
                    </div>



                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="float-left mt-1">
                                <p class="font-13"><strong>Date : </strong> {{ $contrat->created_at->format('d/m/y') }}
                                </p>
                                <p class="font-13"><strong>Type : </strong> {{ $contrat->type }}</p>
                                <p class="font-13"><strong>Status : </strong>
                                    @if ($contrat->is_close == 1)
                                    CLÔTURER, le {{ $contrat->updated_at->format('d/m/y') }}
                                    @elseif($contrat->status == "ACTIF")
                                    ACTIF
                                    @elseif($contrat->status == "INACTIF")
                                    INACTIF
                                    @endif
                                </p>
                                <p class="font-13"><strong>Nombre d'examens : </strong> {{ $contrat->nbr_tests == -1 ?
                                    "Illimité" : $contrat->nbr_tests }}</p>
                                <p class="font-13"><strong>Description : </strong> {{ $contrat->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        @php
        $inf_invoice = App\Models\Invoice::where('contrat_id', $contrat->id)->first();
        @endphp

        <div class="col-md-6">
            <div class="card mt-3">
                @if($contrat->invoice_unique == 1)
                @if ($inf_invoice)
                <h5 class="card-header">
                    <span class="text-start">Facture</span>
                    <a href="{{ route('invoice.show', $inf_invoice->id) }}" style="text-decoration: none;"
                        title="Facture" target="_blank"> <i style="color: #0ACF97;"
                            class="mdi mdi-arrow-right-box fs-6">Voir plus</i></a>
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="float-left mt-1">
                                @if ($inf_invoice && $inf_invoice->code)
                                <p class="font-13"><strong>Code : </strong> {{ $inf_invoice->code }}</p>
                                @endif

                                <p class="font-13"><strong>Client : </strong> {{ $contrat->client->name }}</p>


                                <p class="font-13"><strong>Status : </strong>
                                    @if ($inf_invoice->paid == 1)
                                    <span>Payé, le {{ $inf_invoice->updated_at->format('d/m/y') }}</span>
                                    @else
                                    <span>Non payé</span>
                                    @endif
                                </p>
                                <p class="font-13"><strong>Total : </strong> {{ $inf_invoice->total }}</p>

                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>

    </div>







    <div class="card mb-md-0 mb-3 mt-4">
        <div class="card-body">
            <div class="card-widgets">
                @if ($contrat->is_close == 0)
                <button type="button" class="btn btn-primary float-left" data-bs-toggle="modal"
                    data-bs-target="#modal3">Ajouter un nouveau examen</button>
                @endif
            </div>
            <h5 class="card-title mb-0">Examens prises en compte</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">

                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Nom examen</th>
                            <th>Prix</th>
                            <th>Réduction</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($detail_tests as $item)

                        <tr>
                            <td>{{ $item->test->name }}</td>
                            <td>{{ $item->test->price }}</td>
                            <td>{{ $item->amount_remise }}</td>
                            <td>{{ $item->amount_after_remise }}</td>
                            <td>
                                <button type="button" onclick="edit({{ $item->id }})" class="btn btn-primary"><i
                                        class="mdi mdi-lead-pencil"></i> </button>
                                <button type="button" onclick="deleteModal({{ $item->id }})" class="btn btn-danger"><i
                                        class="mdi mdi-trash-can-outline"></i> </button>
                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>

                @if ($contrat->is_close == 0)
                <span class="d-inline" data-bs-toggle="popover"
                    data-bs-content="Veuillez ajouter un detail avant de sauvegarder">

                    <a type="button" href="{{ route('contrat_details.update-status', $contrat->id) }}"
                        class=" mt-3 btn btn-success w-100 @if (count($details) == 0) disabled @endif ">Sauvegarder</a>
                </span>
                @endif
            </div>
        </div>
    </div>



    <div class="card mb-md-0 mb-3">
        <div class="card-body">
            <div class="card-widgets">
                @if ($contrat->is_close == 0)
                <button type="button" class="btn btn-warning float-left" data-bs-toggle="modal"
                    data-bs-target="#modal2">Ajouter une nouvelle catégorie d'examen</button>
                @endif
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
                        @if ($contrat->is_close == 0)
                        <tr>
                            <td>{{ $item->categorytest() ? $item->categorytest()->name : '' }}</td>
                            <td>{{ $item->pourcentage . ' %' }}</td>
                            <td>
                                <button type="button" onclick="edit({{ $item->id }})" class="btn btn-primary">
                                    <i class="mdi mdi-lead-pencil"></i>
                                </button>
                                <button type="button" onclick="deleteModal({{ $item->id }})" class="btn btn-danger">
                                    <i class="mdi mdi-trash-can-outline"></i>
                                </button>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>

                @if ($contrat->is_close == 0)
                <span class="d-inline" data-bs-toggle="popover"
                    data-bs-content="Veuillez ajouter un detail avant de sauvegarder">

                    <a type="button" href="{{ route('contrat_details.update-status', $contrat->id) }}"
                        class=" mt-3 btn btn-success w-100 @if (count($details) == 0) disabled @endif ">Sauvegarder</a>
                </span>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection


@push('extra-js')

<script>
    var baseUrl = "{{ url('/') }}";
</script>
<script src="{{asset('viewjs/contrat/indexContrat.js')}}"></script>
<script src="{{asset('viewjs/contrat/indexdetail.js')}}"></script>
@endpush