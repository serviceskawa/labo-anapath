@extends('layouts.app2')

@section('title', 'Fermeture de la caisse')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            {{-- <div class="page-title-right mr-3">
                <a href="#" class="btn btn-danger">Fermer la caisse</a>
            </div> --}}
            <h4 class="page-title">Opération de fermeture de la caisse</h4>
        </div>
    </div>
</div>

<div class="">
    @include('layouts.alerts')
    <div class="card mb-md-0 mb-3">
        {{-- <div class="card-body">
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                    aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            <h5 class="card-title mb-0">Fermeture de la caisse</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">

                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Type de caisse</th>
                            <th>Solde d'ouverture</th>
                            <th>Solde de fermeture</th>
                            <th>Date d'ouverture</th>
                            <th>Date de fermeture</th>
                        </tr>
                    </thead>


                    <tbody>

                    </tbody>
                </table>
            </div>
        </div> --}}


        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="header-title mb-3">Point sur les depenses</h4>

                        <form action="{{ route('daily.update') }}" method="POST" autocomplete="on">
                            @csrf
                            @method('PUT')
                            <div id="progressbarwizard">

                                <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
                                    <li class="nav-item">
                                        <a href="#account-2" data-bs-toggle="tab" data-toggle="tab"
                                            class="nav-link rounded-0 pt-2 pb-2">
                                            <i class="mdi mdi-account-circle me-1"></i>
                                            <span class="d-none d-sm-inline">Comptage</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#profile-tab-2" data-bs-toggle="tab" data-toggle="tab"
                                            class="nav-link rounded-0 pt-2 pb-2">
                                            <i class="mdi mdi-face-profile me-1"></i>
                                            <span class="d-none d-sm-inline">Mise en coffre</span>
                                        </a>
                                    </li>
                                    {{-- <li class="nav-item">
                                        <a href="#finish-2" data-bs-toggle="tab" data-toggle="tab"
                                            class="nav-link rounded-0 pt-2 pb-2">
                                            <i class="mdi mdi-checkbox-marked-circle-outline me-1"></i>
                                            <span class="d-none d-sm-inline">Cloture de caisse</span>
                                        </a>
                                    </li> --}}
                                </ul>

                                <div class="tab-content b-0 mb-0">

                                    <div id="bar" class="progress mb-3" style="height: 7px;">
                                        <div
                                            class="bar progress-bar progress-bar-striped progress-bar-animated bg-success">
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="account-2">
                                        <div class="row">
                                            <div class="col-12">
                                                <input type="text" name="status" value="0" hidden>

                                                {{-- <table class="table table-bordered table-centered mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Mode de paiement</th>
                                                            <th>Nombre</th>
                                                            <th>Montant calculé</th>
                                                            <th>Montant compté</th>
                                                            <th>Ecart</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Espèces</td>
                                                            <td>0</td>
                                                            <td><input type="number" class="form-control"
                                                                    id="cash_calculated" name="cash_calculated"
                                                                    value="{{$especes}}" readonly></td>
                                                            <td><input type="number" class="form-control"
                                                                    id="cash_confirmation" name="cash_confirmation"
                                                                    value=""></td>
                                                            <td><input type="number" class="form-control"
                                                                    id="cash_ecart" name="cash_ecart" value="" readonly>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Mobile Money</td>
                                                            <td>0</td>
                                                            <td><input type="number" class="form-control"
                                                                    id="mobile_money_calculated"
                                                                    name="mobile_money_calculated"
                                                                    value="{{$mobilemoney}}" readonly></td>
                                                            <td><input type="number" class="form-control"
                                                                    id="mobile_money_confirmation"
                                                                    name="mobile_money_confirmation" value=""></td>
                                                            <td><input type="number" class="form-control"
                                                                    id="mobile_money_ecart" name="mobile_money_ecart"
                                                                    value="" readonly></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Chèques</td>
                                                            <td>0</td>
                                                            <td><input type="number" class="form-control"
                                                                    id="cheque_calculated" name="cheque_calculated"
                                                                    value="{{$cheques}}" readonly></td>
                                                            <td><input type="number" class="form-control"
                                                                    id="cheque_confirmation" name="cheque_confirmation"
                                                                    value=""></td>
                                                            <td><input type="number" class="form-control"
                                                                    id="cheque_ecart" name="cheque_ecart" value=""
                                                                    readonly></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Virement</td>
                                                            <td>0</td>
                                                            <td><input type="number" class="form-control"
                                                                    id="virement_calculated" name="virement_calculated"
                                                                    value="{{$virement}}" readonly></td>
                                                            <td><input type="number" class="form-control"
                                                                    id="virement_confirmation"
                                                                    name="virement_confirmation" value=""></td>
                                                            <td><input type="number" class="form-control"
                                                                    id="virement_ecart" name="virement_ecart" value=""
                                                                    readonly></td>
                                                        </tr>

                                                    </tbody>
                                                </table> --}}

                                                <div class="row mb-3">
                                                    <div class="col-md-2">
                                                        <label class="form-label" style="font-weight: 900;"
                                                            for="userName1">Mode de
                                                            paiement</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label class="form-label" style="font-weight: 900;"
                                                            for="userName1">Nombre</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label" style="font-weight: 900;"
                                                            for="userName1">Montant
                                                            calculé</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label" style="font-weight: 900;"
                                                            for="userName1">Montant compté</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label" style="font-weight: 900;"
                                                            for="userName1">Ecart</label>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-2">
                                                        <label class="form-label" for="userName1">MOBILE MONEY</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="number" class="form-control"
                                                            id="mobile_money_count" name="mobile_money_count"
                                                            value="{{$mobilemoneycount}}" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control"
                                                            id="mobile_money_calculated" name="mobile_money_calculated"
                                                            value="{{$mobilemoneysum}}" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control"
                                                            id="mobile_money_confirmation"
                                                            name="mobile_money_confirmation" value="">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control"
                                                            id="mobile_money_ecart" name="mobile_money_ecart" value=""
                                                            readonly>
                                                    </div>

                                                </div>


                                                <div class="row mb-3">
                                                    <div class="col-md-2">
                                                        <label class="form-label" for="userName1">CHEQUES</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="number" class="form-control" id="cheque_count"
                                                            name="cheque_count" value="{{$chequescount}}" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control" id="cheque_calculated"
                                                            name="cheque_calculated" value="{{$chequessum}}" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control"
                                                            id="cheque_confirmation" name="cheque_confirmation"
                                                            value="">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control" id="cheque_ecart"
                                                            name="cheque_ecart" value="" readonly>
                                                    </div>
                                                </div>


                                                <div class="row mb-3">
                                                    <div class="col-md-2">
                                                        <label class="form-label" for="userName1">VIREMENT</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="number" class="form-control" id="virement_count"
                                                            name="virement_count" value="{{$virementcount}}" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control"
                                                            id="virement_calculated" name="virement_calculated"
                                                            value="{{$virementsum}}" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control"
                                                            id="virement_confirmation" name="virement_confirmation"
                                                            value="">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control" id="virement_ecart"
                                                            name="virement_ecart" value="" readonly>
                                                    </div>
                                                </div>


                                                <div class="row mb-3">
                                                    <div class="col-md-2">
                                                        <label class="form-label" for="userName1">ESPECES</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="number" class="form-control" id="cash_count"
                                                            name="cash_count" value="{{$especescount}}" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control" id="cash_calculated"
                                                            name="cash_calculated" value="{{$especessum}}" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control" id="cash_confirmation"
                                                            name="cash_confirmation" value="">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control" id="cash_ecart"
                                                            name="cash_ecart" value="" readonly>
                                                    </div>
                                                </div>









                                                {{-- Especes --}}
                                                {{-- <div class="row mb-3">

                                                    <div class="col-md-4">
                                                        <label class="form-label" for="userName1">Total espèces</label>
                                                        <input type="number" class="form-control" id="cash_calculated"
                                                            name="cash_calculated" value="{{$especessum}}" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label" for="userName1">Total espèces
                                                            confirmer</label>
                                                        <input type="number" class="form-control" id="cash_confirmation"
                                                            name="cash_confirmation" value="">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label" for="userName1">Difference</label>
                                                        <input type="number" class="form-control" id="cash_ecart"
                                                            name="cash_ecart" value="" readonly>
                                                    </div>
                                                </div> --}}

                                                {{-- Mobile Money --}}
                                                {{-- <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label" for="userName1">Total Mobile
                                                            Money</label>
                                                        <input type="number" class="form-control"
                                                            id="mobile_money_calculated" name="mobile_money_calculated"
                                                            value="{{$mobilemoneysum}}" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label" for="userName1">Total Mobile Money
                                                            confirmer</label>
                                                        <input type="number" class="form-control"
                                                            id="mobile_money_confirmation"
                                                            name="mobile_money_confirmation" value="">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label" for="userName1">Difference</label>
                                                        <input type="number" class="form-control"
                                                            id="mobile_money_ecart" name="mobile_money_ecart" value=""
                                                            readonly>
                                                    </div>
                                                </div> --}}

                                                {{-- Cheques --}}
                                                {{-- <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label" for="userName1">Total cheques</label>
                                                        <input type="number" class="form-control" id="cheque_calculated"
                                                            name="cheque_calculated" value="{{$chequessum}}" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label" for="userName1">Total cheques
                                                            confirmer</label>
                                                        <input type="number" class="form-control"
                                                            id="cheque_confirmation" name="cheque_confirmation"
                                                            value="">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label" for="userName1">Difference</label>
                                                        <input type="number" class="form-control" id="cheque_ecart"
                                                            name="cheque_ecart" value="" readonly>
                                                    </div>
                                                </div> --}}

                                                {{-- Virement --}}
                                                {{-- <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label" for="userName1">Total virement</label>
                                                        <input type="number" class="form-control"
                                                            id="virement_calculated" name="virement_calculated"
                                                            value="{{$virementsum}}" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label" for="userName1">Total virement
                                                            confirmer</label>
                                                        <input type="number" class="form-control"
                                                            id="virement_confirmation" name="virement_confirmation"
                                                            value="">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label" for="userName1">Difference</label>
                                                        <input type="number" class="form-control" id="virement_ecart"
                                                            name="virement_ecart" value="" readonly>
                                                    </div>
                                                </div> --}}

                                                {{-- Total / point --}}
                                                {{-- <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label" for="userName1">Total calculer</label>
                                                        <input type="number" class="form-control" id="total_calculated"
                                                            name="total_calculated" value="" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label" for="userName1">Total
                                                            confirmer</label>
                                                        <input type="number" class="form-control"
                                                            id="total_confirmation" name="total_confirmation" value=""
                                                            readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label" for="userName1">Total
                                                            difference</label>
                                                        <input type="number" class="form-control" id="total_ecart"
                                                            name="total_ecart" value="" readonly>
                                                    </div>
                                                </div> --}}

                                            </div> <!-- end col -->
                                        </div> <!-- end row -->
                                    </div>








                                    {{-- Panel 2 pour le point general --}}
                                    <div class="tab-pane" id="profile-tab-2">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row mb-3">
                                                    <div class="col-md-2">
                                                        <label class="form-label" style="font-weight: 900;"
                                                            for="userName1">Mode de
                                                            paiement</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label" style="font-weight: 900;"
                                                            for="userName1">Fond initial</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label" style="font-weight: 900;"
                                                            for="userName1">Vente</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label" style="font-weight: 900;"
                                                            for="userName1">Solde</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label" style="font-weight: 900;"
                                                            for="userName1">Comptage</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label" style="font-weight: 900;"
                                                            for="userName1">Ecart</label>
                                                    </div>
                                                </div>


                                                <div class="row mb-3">
                                                    <div class="col-md-2">
                                                        <label class="form-label" for="userName1">ESPECES</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control" id="open_cash"
                                                            name="open_cash" value="{{$open_cash->opening_balance}}"
                                                            readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control" id="cash_calculated"
                                                            name="cash_calculated" value="{{$especessum}}" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control"
                                                            id="total_solde_especes" name="total_solde_especes" value=""
                                                            readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control"
                                                            id="cash_confirmation_point" name="cash_confirmation_point"
                                                            value="" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control" id="cash_ecart_point"
                                                            name="cash_ecart_point" value="" readonly>
                                                    </div>
                                                </div>


                                                <div class="row mb-3">
                                                    <div class="col-md-2">
                                                        <label class="form-label" for="userName1">MOBILE MONEY</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        -
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control"
                                                            id="mobile_money_calculated" name="mobile_money_calculated"
                                                            value="{{$mobilemoneysum}}" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        -
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control"
                                                            id="mobile_money_confirmation_point"
                                                            name="mobile_money_confirmation_point" value="" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control"
                                                            id="mobile_money_ecart_point"
                                                            name="mobile_money_ecart_point" value="" readonly>
                                                    </div>
                                                </div>


                                                <div class="row mb-3">
                                                    <div class="col-md-2">
                                                        <label class="form-label" for="userName1">CHEQUES</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        -
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control" id="cheque_calculated"
                                                            name="cheque_calculated" value="{{$chequessum}}" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        -
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control"
                                                            id="cheque_confirmation_point"
                                                            name="cheque_confirmation_point" value="" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control"
                                                            id="cheque_ecart_point" name="cheque_ecart_point" value=""
                                                            readonly>
                                                    </div>
                                                </div>


                                                <div class="row mb-3">
                                                    <div class="col-md-2">
                                                        <label class="form-label" for="userName1">VIREMENT</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        -
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control"
                                                            id="virement_calculated" name="virement_calculated"
                                                            value="{{$virementsum}}" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        -
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control"
                                                            id="virement_confirmation_point"
                                                            name="virement_confirmation_point" value="" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control"
                                                            id="virement_ecart_point" name="virement_ecart_point"
                                                            value="" readonly>
                                                    </div>
                                                </div>


                                                {{-- Total --}}
                                                <div class="row mb-3">
                                                    <div class="col-md-2">
                                                        <label class="form-label" for="userName1">TOTAL</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control" id="open_cash"
                                                            name="open_cash" value="{{$open_cash->opening_balance}}"
                                                            readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control" id="total_calculated"
                                                            name="total_calculated" value="" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control total-solde"
                                                            id="total_solde" name="total_solde" value="" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number"
                                                            class="form-control total-confirmation-point"
                                                            id="total_confirmation_point"
                                                            name="total_confirmation_point" value="" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control total-ecart-point"
                                                            id="total_ecart_point" name="total_ecart_point" value=""
                                                            readonly>
                                                    </div>
                                                </div>



                                            </div> <!-- end col -->
                                            <div class="col-12">
                                                <div class="col-md-4">
                                                    Montant de clôture
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control close-balance"
                                                        id="close_balance" name="close_balance" value="" readonly>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <button type="submit" class="btn btn-danger float-end">
                                                    Confirmer et fermer la caisse
                                                </button>
                                            </div>
                                        </div>
                                    </div>



                                    {{-- <div class="tab-pane" id="finish-2">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="text-center">
                                                    <h2 class="mt-0"><i class="mdi mdi-check-all"></i></h2>
                                                    <h3 class="mt-0">Thank you !</h3>

                                                    <p class="w-75 mb-2 mx-auto">Quisque nec turpis at urna dictum
                                                        luctus. Suspendisse convallis dignissim eros at volutpat. In
                                                        egestas mattis dui. Aliquam
                                                        mattis dictum aliquet.</p>

                                                    <div class="mb-3">
                                                        <div class="form-check d-inline-block">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="customCheck3">
                                                            <label class="form-check-label" for="customCheck3">I agree
                                                                with the Terms and Conditions</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->
                                    </div> --}}

                                    {{-- <ul class="list-inline mb-0 wizard">
                                        <li class="previous list-inline-item">
                                            <a href="#" class="btn btn-info">Previous</a>
                                        </li>
                                        <li class="next list-inline-item float-end">
                                            <a href="#" class="btn btn-info">Next</a>
                                        </li>
                                    </ul> --}}

                                </div> <!-- tab-content -->
                            </div> <!-- end #progressbarwizard-->
                        </form>

                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
    </div> <!-- end card-->
</div>


@endsection


@push('extra-js')









{{-- total panel 2 ecart --}}
<script>
    // Sélectionnez les champs d'entrée
    let cashEcartInput = document.getElementById('cash_ecart');
    let mobileMoneyEcartInput = document.getElementById('mobile_money_ecart');
    let chequeEcartInput = document.getElementById('cheque_ecart');
    let virementEcartInput = document.getElementById('virement_ecart');
    
    // Sélectionnez les champs de point correspondants
    let cashEcartPointInput = document.getElementById('cash_ecart_point');
    let mobileMoneyEcartPointInput = document.getElementById('mobile_money_ecart_point');
    let chequeEcartPointInput = document.getElementById('cheque_ecart_point');
    let virementEcartPointInput = document.getElementById('virement_ecart_point');
    let totalEcartPointInput = document.getElementById('total_ecart_point');

    // close balance 



    // Ajoutez des écouteurs d'événements pour détecter les changements dans les champs d'écart
    cashEcartInput.addEventListener('input', updateEcartPoint);
    mobileMoneyEcartInput.addEventListener('input', updateEcartPoint);
    chequeEcartInput.addEventListener('input', updateEcartPoint);
    virementEcartInput.addEventListener('input', updateEcartPoint);
    

    // Fonction pour mettre à jour les champs de point en fonction des valeurs d'écart
    function updateEcartPoint() {
        let cashEcartValue = parseFloat(cashEcartInput.value) || 0;
        let mobileMoneyEcartValue = parseFloat(mobileMoneyEcartInput.value) || 0;
        let chequeEcartValue = parseFloat(chequeEcartInput.value) || 0;
        let virementEcartValue = parseFloat(virementEcartInput.value) || 0;

        // Calculez les valeurs de point en fonction des valeurs d'écart
        let totalEcartPoint = cashEcartValue + mobileMoneyEcartValue + chequeEcartValue + virementEcartValue;
    
        // Mettez à jour les champs de point avec les valeurs calculées
        cashEcartPointInput.value = cashEcartValue;
        mobileMoneyEcartPointInput.value = mobileMoneyEcartValue;
        chequeEcartPointInput.value = chequeEcartValue;
        virementEcartPointInput.value = virementEcartValue;
        
        // Mettez à jour le champ de point total avec la somme totale des valeurs de point
        let totalPoint = cashEcartValue + mobileMoneyEcartValue + chequeEcartValue + virementEcartValue;
        totalEcartPointInput.value = totalPoint;

        // balanceCloseInput.value = totalSoldeInput.value - totalPoint.value;
    }
    
    // Appelez la fonction initiale pour calculer les valeurs au chargement de la page
    updateEcartPoint();
</script>



{{-- total solde --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Récupérez les éléments par leurs identifiants
        let openCashField = document.getElementById('open_cash');
        let totalCalculatedField = document.getElementById('total_calculated');
        let totalSoldeField = document.getElementById('total_solde');
        let totalSoldeEspeceField = document.getElementById('total_solde_especes');

        // Assurez-vous que les champs existent avant de manipuler leurs valeurs
        if (openCashField && totalCalculatedField && totalSoldeField && totalSoldeEspeceField) {
            // Récupérez les valeurs des champs en tant que nombres
            let openCashValue = parseFloat(openCashField.value) || 0;
            let totalCalculatedValue = parseFloat(totalCalculatedField.value) || 0;

            // Calculez la somme
            let sum = openCashValue + totalCalculatedValue;

            // Mettez la somme dans le champ total_solde
            // Assurez-vous que le résultat est arrondi à 2 décimales

            totalSoldeField.value = sum.toFixed(2);
            totalSoldeEspeceField =  sum.toFixed(2);
            let monInputElement = document.getElementById('total_solde_especes');
            monInputElement.value = sum.toFixed(2);
        }
    });
</script>
{{-- Comptage panel 2 --}}
<script>
    // Sélectionnez les champs d'entrée
    let cashConfirmationInput = document.getElementById('cash_confirmation');
    let mobileMoneyConfirmationInput = document.getElementById('mobile_money_confirmation');
    let chequeConfirmationInput = document.getElementById('cheque_confirmation');
    let virementConfirmationInput = document.getElementById('virement_confirmation');
    
    // Sélectionnez les champs de point correspondants
    let cashConfirmationPointInput = document.getElementById('cash_confirmation_point');
    let mobileMoneyConfirmationPointInput = document.getElementById('mobile_money_confirmation_point');
    let chequeConfirmationPointInput = document.getElementById('cheque_confirmation_point');
    let virementConfirmationPointInput = document.getElementById('virement_confirmation_point');
    let totalConfirmationPointInput = document.getElementById('total_confirmation_point');


    // Ajoutez des écouteurs d'événements pour détecter les changements dans les champs de confirmation
    cashConfirmationInput.addEventListener('input', updateConfirmationPoint);
    mobileMoneyConfirmationInput.addEventListener('input', updateConfirmationPoint);
    chequeConfirmationInput.addEventListener('input', updateConfirmationPoint);
    virementConfirmationInput.addEventListener('input', updateConfirmationPoint);
    
    // Fonction pour mettre à jour les champs de point en fonction des valeurs de confirmation
    function updateConfirmationPoint() {
    let cashConfirmationValue = parseFloat(cashConfirmationInput.value) || 0;
    let mobileMoneyConfirmationValue = parseFloat(mobileMoneyConfirmationInput.value) || 0;
    let chequeConfirmationValue = parseFloat(chequeConfirmationInput.value) || 0;
    let virementConfirmationValue = parseFloat(virementConfirmationInput.value) || 0;
    // let virementConfirmationValue = parseFloat(totalConfirmationPointInput.value) || 0;


    // Calculez les valeurs de point en fonction des valeurs de confirmation
    let totalConfirmationPoint = cashConfirmationValue + mobileMoneyConfirmationValue + chequeConfirmationValue +
    virementConfirmationValue;
    
    // Mettez à jour les champs de point avec les valeurs calculées
    cashConfirmationPointInput.value = cashConfirmationValue;
    mobileMoneyConfirmationPointInput.value = mobileMoneyConfirmationValue;
    chequeConfirmationPointInput.value = chequeConfirmationValue;
    virementConfirmationPointInput.value = virementConfirmationValue;
    
    // Mettez à jour le champ de point total avec la somme totale des valeurs de point
    let totalPoint = cashConfirmationValue + mobileMoneyConfirmationValue + chequeConfirmationValue +
    virementConfirmationValue;
    totalConfirmationPointInput.value = totalPoint;
    }
    
    // Appelez la fonction initiale pour calculer les valeurs au chargement de la page
    updateConfirmationPoint();
</script>
{{-- calcule totale --}}
<script>
    // Fonction pour calculer la somme des valeurs et mettre à jour les champs
    function calculateTotals() {
        // Récupérez les valeurs de chaque champ
        let cashCalculated = parseFloat(document.querySelector("#cash_calculated").value) || 0;
        let mobileMoneyCalculated = parseFloat(document.querySelector("#mobile_money_calculated").value) || 0;
        let chequeCalculated = parseFloat(document.querySelector("#cheque_calculated").value) || 0;
        let virementCalculated = parseFloat(document.querySelector("#virement_calculated").value) || 0;

        let cashConfirmation = parseFloat(document.querySelector("#cash_confirmation").value) || 0;
        let mobileMoneyConfirmation = parseFloat(document.querySelector("#mobile_money_confirmation").value) || 0;
        let chequeConfirmation = parseFloat(document.querySelector("#cheque_confirmation").value) || 0;
        let virementConfirmation = parseFloat(document.querySelector("#virement_confirmation").value) || 0;

        let cashEcart = parseFloat(document.querySelector("#cash_ecart").value) || 0;
        let mobileMoneyEcart = parseFloat(document.querySelector("#mobile_money_ecart").value) || 0;
        let chequeEcart = parseFloat(document.querySelector("#cheque_ecart").value) || 0;
        let virementEcart = parseFloat(document.querySelector("#virement_ecart").value) || 0;

        // Calculez les sommes
        let totalCalculated = cashCalculated + mobileMoneyCalculated + chequeCalculated + virementCalculated;
        let totalConfirmation = cashConfirmation + mobileMoneyConfirmation + chequeConfirmation + virementConfirmation;
        // const totalEcart = cashEcart + mobileMoneyEcart + chequeEcart + virementEcart;
        let totalEcart = totalCalculated - totalConfirmation;

        // let s = totalCalculated - totalEcart;
        // Mettez à jour les champs de total
        document.querySelector("#total_calculated").value = totalCalculated;
        // document.querySelector("#total_confirmation").value = totalConfirmation;
        // document.querySelector("#total_ecart").value = totalEcart;
        let op = parseFloat($('#open_cash').val());
        // parseFloat(document.querySelector("#close_balance").value) = totalCalculated + op - totalEcart;
        // parseFloat(document.querySelector("#close_balance").value) + totalCalculated + op - totalEcart;
        let cal = totalCalculated + op;
        let t =  cal - totalEcart;
        document.querySelector("#close_balance").value = t;
        // console.log();
    }

    // Ajoutez un écouteur d'événement pour les champs qui changent
    let fieldsToUpdate = [
        "cash_calculated", "mobile_money_calculated", "cheque_calculated", "virement_calculated",
        "cash_confirmation", "mobile_money_confirmation", "cheque_confirmation", "virement_confirmation",
        "cash_ecart", "mobile_money_ecart", "cheque_ecart", "virement_ecart"
    ];

    fieldsToUpdate.forEach((fieldName) => {
        let fieldInput = document.querySelector(`#${fieldName}`);
        fieldInput.addEventListener("input", calculateTotals);
    });

    // Appelez la fonction de calcul au chargement de la page
    calculateTotals();
</script>

{{-- Ecart Essaie 2 --}}
<script>
    // Fonction pour calculer et mettre à jour la différence (écart) entre les champs calculés et confirmés
    function updateEcart(calculatedInputName, confirmationInputName, ecartInputName) {
        // Sélectionnez les éléments input
        let calculatedInput = document.querySelector(`input[name="${calculatedInputName}"]`);
        let confirmationInput = document.querySelector(`input[name="${confirmationInputName}"]`);
        let ecartInput = document.querySelector(`input[name="${ecartInputName}"]`);

        // Ajoutez un gestionnaire d'événements pour détecter les modifications dans confirmationInput
        confirmationInput.addEventListener('input', () => {
            // Obtenez les valeurs des champs calculés et confirmés
            let calculatedValue = parseFloat(calculatedInput.value) || 0;
            let confirmationValue = parseFloat(confirmationInput.value) || 0;

            // Calculez la différence
            let difference = calculatedValue - confirmationValue;

            // Mettez le résultat dans le champ ecartInput
            ecartInput.value = difference;
            // Mettez à jour les champs de point correspondants
            updatePoint(ecartInputName, ecartInputName + "_point");

            // Mettez à jour le champ de point total
            updateTotalEcart();
        });
    }

    // Fonction pour mettre à jour les champs de point en fonction des valeurs des champs d'écart
    function updatePoint(ecartInputName, pointInputName) {
        // Sélectionnez les éléments input d'écart et de point
        let ecartInput = document.querySelector(`input[name="${ecartInputName}"]`);
        let pointInput = document.querySelector(`input[name="${pointInputName}"]`);

        // Obtenez la valeur du champ d'écart
        let ecartValue = parseFloat(ecartInput.value) || 0;

        // Mettez à jour le champ de point avec la valeur de l'écart
        pointInput.value = ecartValue;
    }

    // Fonction pour calculer la somme totale des champs de point (écarts)
    function updateTotalEcart() {
        // Sélectionnez les éléments input des champs de point
        let cashEcartPointInput = document.querySelector(`input[name="cash_ecart_point"]`);
        let mobileMoneyEcartPointInput = document.querySelector(`input[name="mobile_money_ecart_point"]`);
        let chequeEcartPointInput = document.querySelector(`input[name="cheque_ecart_point"]`);
        let virementEcartPointInput = document.querySelector(`input[name="virement_ecart_point"]`);

        // Calculez la somme totale des champs de point
        let totalEcart = parseFloat(cashEcartPointInput.value || 0) +
            parseFloat(mobileMoneyEcartPointInput.value || 0) +
            parseFloat(chequeEcartPointInput.value || 0) +
            parseFloat(virementEcartPointInput.value || 0);

        // Sélectionnez le champ de point total et mettez à jour sa valeur
        let totalEcartInput = document.querySelector(`input[name="total_ecart_point"]`);
        totalEcartInput.value = totalEcart;
    }

    // Appelez la fonction pour chaque paire de champs d'écart et de point
    updateEcart('cash_calculated', 'cash_confirmation', 'cash_ecart');
    updateEcart('mobile_money_calculated', 'mobile_money_confirmation', 'mobile_money_ecart');
    updateEcart('cheque_calculated', 'cheque_confirmation', 'cheque_ecart');
    updateEcart('virement_calculated', 'virement_confirmation', 'virement_ecart');
</script>






<script>
    var baseUrl = "{{url('/')}}"
</script>
<script src="{{asset('viewjs/patient/index.js')}}"></script>
@endpush