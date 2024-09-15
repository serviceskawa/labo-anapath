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
                <div class="card-header justify-content-between align-items-center d-flex">
                    <div>
                        Contrat : {{ $contrat->name }}
                    </div>

                    <div>
                        @if($contrat->status == "ACTIF" && $contrat->is_close == 0)
                        <div class="rounded"
                            style="background-color: red; border-radius : 10px solid red; padding : 4px;">
                            <a style="text-decoration: none; color :white; font-size : 12px;"
                                onclick="closeModal({{ $contrat->id }})" title="Clôturer le contrat">Clôturer</a>
                        </div>
                        @elseif($contrat->status == "INACTIF" && $contrat->is_close == 0)
                        <div class="rounded"
                            style="background-color: #0ACF97; border-radius : 10px solid #0ACF97; padding : 4px;">

                            <a onclick="activeContratModal({{ $contrat->id }})"
                                style="text-decoration: none; color :white; font-size : 12px;" class=""
                                title="Activer le contrat" target="_blank">Activer</a>
                        </div>
                        @endif
                    </div>
                </div>

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
                                    "Illimité" : $contrat->orders->count()."/".$contrat->nbr_tests }}</p>
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

                <div class="card-header justify-content-between align-items-center d-flex">
                    <div>
                        Facture
                    </div>

                    <div>
                        <div class="rounded"
                            style="background-color: #0ACF97; border-radius : 10px solid #0ACF97; padding : 4px;">
                            <a href="{{ route('invoice.show', $inf_invoice->id) }}"
                                style="text-decoration: none; color :white; font-size : 12px;" title="Voir la facture"
                                target="_blank"> Voir plus </a>
                        </div>
                    </div>
                </div>


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











    {{-- <div class="card mb-md-0 mt-4 mb-3">
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
    <span class="d-inline" data-bs-toggle="popover" data-bs-content="Veuillez ajouter un detail avant de sauvegarder">

        <a type="button" href="{{ route('contrat_details.update-status', $contrat->id) }}"
            class=" mt-3 btn btn-success w-100 @if (count($details) == 0) disabled @endif ">Sauvegarder</a>
    </span>
    @endif
</div>
</div>
</div> --}}











<div class="card mb-md-0 mt-4 mb-3">
    <div class="card-body">

        <h4 class="card-title mb-0">Ajouter des examens</h4>

        <div id="cardCollpase1" class="collapse pt-3 show">
            <form method="POST" id="addDetailForm" autocomplete="off">
                @csrf
                <div class="row d-flex align-items-end">
                    <div class="col-md-4 col-12">
                        <input type="hidden" name="contrat_id" id="contrat_id" value="{{ $contrat->id }}"
                            class="form-control">

                        <div class="mb-3">
                            <label for="example-select" class="form-label">Examen</label>
                            <select class="form-select select2" data-toggle="select2" id="test_id" name="test_id"
                                required onchange="getTest()">
                                <option>Sélectionner l'examen</option>
                                @foreach ($tests as $test)
                                <option value="{{ $test->id }}">{{
                                        $test->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">

                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Prix</label>
                            <input type="text" name="price" id="price" class="form-control" required readonly>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Remise</label>
                            <input type="text" name="remise" id="remise" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="mb-3">
                            <label for="example-select" class="form-label">Total</label>

                            <input type="text" name="total" id="total" class="form-control" required readonly>
                        </div>
                    </div>
                    @if ($contrat->is_close == 0)
                    <div class="col-md-2 col-12">
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary" id="add_detail">Ajouter</button>
                        </div>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>




<div class="card mb-md-0 mb-4 mt-4">
    <div class="card-body">

        <div class="page-title-box">
            <h4 class="page-title">Examens prises en compte</h4>
        </div>

        <div id="cardCollpase1" class="collapse show">

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
                            <a href="{{ route('contrats.edit_examen_reduction',$item->id) }}" class="btn btn-primary"><i
                                    class="mdi mdi-lead-pencil"></i> </a>
                            <a type="button" onclick="deleteModal({{ $item->id }})" class="btn btn-danger"><i
                                    class="mdi mdi-trash-can-outline"></i> </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($contrat->is_close == 0)
            <span class="d-inline" data-bs-toggle="popover"
                data-bs-content="Veuillez ajouter un detail avant de sauvegarder">
                <a type="button" href="{{ route('contrat_details.update-status', $contrat->id) }}"
                    class=" mt-3 btn btn-success w-100 @if (count($detail_tests) == 0) disabled @endif ">Sauvegarder</a>
            </span>
            @endif
        </div>
    </div>
</div>
</div>
@endsection


@push('extra-js')

{{-- <script>
    var ROUTEGETREMISE = "{{ route('examens.getTestAndRemise') }}"
var TOKENGETREMISE = "{{ csrf_token() }}"

function getTest() {
var test_id = $("#test_id").val();
console.log(test_id);
// Importation des paramètres de getRemise
var contrat_id = $("#contrat_id").val();

let element = document.getElementById("test_id");
let category_test_id = element.options[element.selectedIndex].getAttribute(
"data-category_test_id"
);

$.ajax({
type: "POST",
url: ROUTEGETREMISE,
data: {
_token: TOKENGETREMISE,
testId: test_id,
contratId: contrat_id,
categoryTestId: category_test_id,
},
success: function (data) {
console.log(data, data.detail);

$("#price").val(data.data.price);

$("#remise").val(discount);

var total = $("#price").val() - discount;
$("#total").val(total);
},
error: function (data) {
console.log("Error:", data);
},
});
}
</script> --}}


<script>
var ROUTEGETREMISE = "{{ route('examens.getTestAndRemise') }}";
var TOKENGETREMISE = "{{ csrf_token() }}";

function getTest() {
    var test_id = $("#test_id").val();
    console.log(test_id);
    // Importation des paramètres de getRemise
    var contrat_id = $("#contrat_id").val();

    let element = document.getElementById("test_id");
    let category_test_id = element.options[element.selectedIndex].getAttribute("data-category_test_id");

    $.ajax({
        type: "POST",
        url: ROUTEGETREMISE,
        data: {
            _token: TOKENGETREMISE,
            testId: test_id,
            contratId: contrat_id,
            categoryTestId: category_test_id,
        },
        success: function(data) {
            console.log(data, data.detail);

            let price = parseFloat(data.data.price) || 0;
            let discount = parseFloat(data.data.discount) || 0;

            $("#price").val(price.toFixed(2));
            $("#remise").val(discount.toFixed(2));

            updateTotal();
        },
        error: function(data) {
            console.log("Error:", data);
        },
    });
}

function updateTotal() {
    let price = parseFloat($("#price").val()) || 0;
    let remise = parseFloat($("#remise").val()) || 0;
    let total = price - remise;
    $("#total").val(total.toFixed(2));
}

$(document).ready(function() {
    $("#test_id").change(getTest);
    $("#remise").on('input', updateTotal);
});



// Enregistrement des donnees recuperees depuis le front vers la base de données
var ROUTESTOREDETAILTESTORDER = "{{ route('contrat_details.store_test') }}"
var TOKENSTOREDETAILTESTORDER = "{{ csrf_token() }}"

$("#addDetailForm").on("submit", function(e) {
    e.preventDefault();
    let contrat_id = $("#contrat_id").val();
    let test_id = $("#test_id").val();
    let price = $("#price").val();
    let remise = $("#remise").val();
    let total = $("#total").val();

    $.ajax({
        url: ROUTESTOREDETAILTESTORDER,
        type: "POST",
        data: {
            _token: TOKENSTOREDETAILTESTORDER,
            contrat_id: contrat_id,
            test_id: test_id,
            price: price,
            remise: remise,
            total: total,
        },
        success: function(response) {
            $("#addDetailForm").trigger("reset");

            if (response) {
                toastr.success("Donnée ajoutée avec succès", "Ajout réussi");
            }

            location.reload();
        },
        error: function(response) {
            console.log(response);
        },
    });
});
</script>


<script>
var baseUrl = "{{ url('/') }}";
</script>
<script src="{{asset('viewjs/contrat/indexContrat.js')}}"></script>
<script src="{{asset('viewjs/contrat/indexdetail.js')}}"></script>
@endpush