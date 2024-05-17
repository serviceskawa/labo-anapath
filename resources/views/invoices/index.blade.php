@extends('layouts.app2')

@section('title', 'Factures')

@section('css')

    <!-- Inclure Bootstrap CSS -->
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Inclure Bootstrap JS et les dépendances Popper.js et jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
@endsection

@section('content')

<div class="">
    <div class="page-title-box">
        <div class="page-title-right mr-3">
            <a href="{{ route('invoice.create') }}"><button type="button" class="btn btn-primary">Ajouter une
                    nouvelle facture</button></a>
        </div>
        <h4 class="page-title">Factures</h4>
    </div>

    <!----MODAL---->
    @include('layouts.alerts')

    <div class="card mb-md-0 mb-3">
        <div class="card-body" id="factures">
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                    aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>

            <a href="javascript:void(0);" class="btn btn-success mb-4">Total encaissé : {{ $totalToday }}</a>

            <div class="row d-flex">
                <div class="col-lg-3 p-1 ml-3 alert alert-success rounded-pill"
                    style="margin-right: 5px; text-align:center;">
                    <a href="#factures" style="text-decoration : none; color:inherit;">Liste des factures</a>
                </div>

                <div class="col-lg-3 p-1 ml-3 alert alert-light rounded-pill"
                    style="margin-right: 5px; text-align:center;">
                    <a href="#rapports" style="text-decoration : none; color:inherit;">Rapports</a>
                </div>
            </div>

            <div id="cardCollpase1" class="collapse pt-3 show">

                <div class="row mb-3">

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Rechercher</label>
                            <input type="text" placeholder="Par code d'examen" name="contenu" id="contenu" class="form-control">
                        </div>
                    </div> <!-- end col -->

                    <div class="col-lg-3">

                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Status</label>
                            <select name="cas_status" id="cas_status" class="form-control">
                                <option value="2">Tous</option>
                                <option value="0">En attente</option>
                                <option value="1">Payé</option>
                            </select>
                        </div>

                    </div> <!-- end col -->

                    <div class="col-lg-3">

                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Type de facture</label>
                            <select name="status_invoice" id="status_invoice" class="form-control">
                                <option value="">Tous</option>
                                <option value="0">Facture de vente</option>
                                <option value="1">Facture d'avoir</option>
                            </select>
                        </div>

                    </div> <!-- end col -->

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Date Début</label>
                            <input type="date" name="dateBegin" id="dateBegin" class="form-control">
                        </div>
                    </div> <!-- end col -->

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Date fin</label>
                            <input type="date" name="dateEnd" id="dateEnd" class="form-control">
                        </div>
                    </div> <!-- end col -->
                </div>



                <div class="row mb-3">
                    <div class="col-lg-2 p-1 alert alert-light rounded-pill"
                        style="margin-right: 5px; text-align:center;">
                        <strong> Factures de vente : {{$vente}} </strong>
                    </div>
                    <div class="col-lg-2 p-1 ml-3 alert alert-danger rounded-pill"
                        style="margin-right: 5px; text-align:center;">
                        <strong> Factures d'avoir : {{$avoir}} </strong>
                    </div>
                </div>



                <table id="datatable1" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            {{-- <th>#</th> --}}
                            <th>Date</th>
                            <th>Demande</th>
                            <th>Patient</th>
                            <th>Total</th>
                            <th>Code normalisé</th>
                            {{-- <th>Contrat</th> --}}
                            <th>Type de paiement</th>
                            <th>Statut</th>
                            <th>Actions</th>

                        </tr>
                    </thead>

                </table>

            </div>
        </div>


    </div> <!-- end card-->

      {{-- Deuxieme DataTables Histologie et Biopsie --}}
      <div class="card mb-md-0 mt-5">
        <div class="card-body">

            <div class="row mb-3 d-flex">
                <div class="col-lg-3 p-1 ml-3 alert alert-light rounded-pill"
                    style="margin-right: 5px; text-align:center;">
                    <a href="#factures" style="text-decoration : none; color:inherit;">Liste des factures</a>
                </div>

                <div class="col-lg-3 p-1 ml-3 alert alert-success rounded-pill"
                    style="margin-right: 5px; text-align:center;">
                    <a href="#rapports" style="text-decoration : none; color:inherit;">Rapports</a>
                </div>

               <form action="" id="rapports-datatables">
                    <div class="row mb-3 d-flex">
                        <div class="col-lg-3">
                            <label for="example-fileinput" class="form-label">Année</label>
                            <select name="year" id="year" class="form-control">
                                <option value="">Tous</option>
                               @foreach ($list_years as $list_year)
                                <option value="{{ $list_year->year }}" {{ $list_year->year == $year ? 'selected':''}}>{{  $list_year->year }}</option>
                               @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label for="example-fileinput" class="form-label">Mois</label>
                                <select name="month" id="month" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="01" {{ $month == '01' ? 'selected':''}}>Janvier</option>
                                    <option value="02" {{ $month == '02' ? 'selected':''}}>Février</option>
                                    <option value="03" {{ $month == '03' ? 'selected':''}}>Mars</option>
                                    <option value="04" {{ $month == '04' ? 'selected':''}}>Avril</option>
                                    <option value="05" {{ $month == '05' ? 'selected':''}}>Mai</option>
                                    <option value="06" {{ $month == '06' ? 'selected':''}}>Juin</option>
                                    <option value="07" {{ $month == '07' ? 'selected':''}}>Juillet</option>
                                    <option value="08" {{ $month == '08' ? 'selected':''}}>Août</option>
                                    <option value="09" {{ $month == '09' ? 'selected':''}}>Septembre</option>
                                    <option value="10" {{ $month == '10' ? 'selected':''}}>Octobre</option>
                                    <option value="11" {{ $month == '11' ? 'selected':''}}>Novembre</option>
                                    <option value="12" {{ $month == '12' ? 'selected':''}}>Décembre</option>
                                </select>
                        </div>

                        <div class="col-lg-3" style="margin-top : 25px;">
                            <button type="submit" class="btn btn-success">Filtrer</button>
                        </div>
                    </div>
               </form>
            </div>

            <div class="row" id="rapports">
                <div class="table-responsive">
                    <table class="table table-centered w-100 dt-responsive nowrap"
                        id="">
                        <thead class="table-light">
                            <tr>
                                <th>Période</th>
                                <th>Facture de ventes</th>
                                <th>Facture d'avoirs</th>
                                <th>Chiffre d'affaires</th>
                                <th>Encaissements</th>
                            </tr>
                        </thead>
                        <tfoot>
                            @foreach ($sales as $sale)
                                <tr>
                                    <td>{{ $month }} - {{ $year }}</td>
                                    <td>
                                        Total :
                                        @if ($sale->total_sales == null)
                                            0
                                        @else
                                            {{ $sale->total_sales }}
                                        @endif
                                        <br>
                                         @foreach ($paymensalesByContracts as $paymensalesByContract)
                                            {{ DB::table('contrats')->where('id', $paymensalesByContract->contrat_id)->value('name')}} : {{ $paymensalesByContract->total_contracts }} <br>
                                         @endforeach
                                    </td>
                                   <td>
                                        @foreach ( $credits as $credit)
                                            Total :
                                            @if ($credit->total_credits == null)
                                                0
                                            @else
                                                {{ $credit->total_credits }}
                                            @endif
                                            <br>
                                        @endforeach
                                   </td>

                                    <td>
                                        Total : {{  $sale->total_sales - $credit->total_credits }} <br>
                                    </td>

                                    <td>
                                        @foreach ($payments as $payment)
                                            Total :
                                            @if ($payment->total_payments == null)
                                                0
                                            @else
                                                {{ $payment->total_payments }}
                                            @endif<br>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tfoot>
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
        var ROUTEGETDATATABLE = "{{ route('invoice.getInvoiceIndexforDatatable') }}"
    </script>

    <script src="{{asset('viewjs/invoice/index.js')}}"></script>
@endpush
