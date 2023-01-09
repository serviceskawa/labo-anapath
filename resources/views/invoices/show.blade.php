@extends('layouts.app2')

@section('title', 'Details Facture')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Facture</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <!-- Invoice Logo-->
                <div class="clearfix">
                    <div class="float-start mb-3">
                        <img src="{{ $setting ? Storage::url($setting->logo_blanc) : '' }}" alt="" height="18">
                    </div>
                    <div class="float-end">
                        <h4 class="m-0 d-print-none">Facture</h4>
                    </div>
                </div>

                <!-- Invoice Detail-->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="float-left mt-3">
                            <h4>Informations du Patient</h4>
                            <address>
                                <strong>Nom: </strong> {{$invoice->client_name}}<br>
                                <strong>Addresse: </strong> {{$invoice->client_address}}<br>
                            </address>
                        </div>

                    </div><!-- end col -->
                    <div class="col-sm-4 offset-sm-2">
                        <div class="mt-3 float-sm-end">
                            <p class="font-13"><strong>Date: </strong> {{$invoice->created_at}}</p>
                            <p class="font-13"><strong>Status: </strong>
                                <span class="bg-{{$invoice->paid != 1 ? 'danger' : 'success' }} badge
                                    float-end">{{$invoice->paid == 1 ? "Payé" : "En
                                    attente"}}
                                </span>
                            </p>
                            <p class="font-13"><strong>ID: </strong> <span class="float-end">{{$invoice->id}}</span></p>
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table mt-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Titre de l'examen</th>
                                        <th>Quantité</th>
                                        <th>Prix</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->details as $key => $item)
                                    <tr>
                                        <td>{{$key+1}} </td>
                                        <td>
                                            <b>{{$item->test_name}}</b>
                                        </td>
                                        <td>1</td>
                                        <td>{{$item->price}}</td>
                                        <td class="text-end">{{$item->total}}</td>
                                    </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        </div> <!-- end table-responsive-->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-sm-6">
                        <div class="clearfix pt-3">
                            <h6 class="text-muted">Notes:</h6>
                            <small>
                                Toutes mes transactions
                            </small>
                        </div>
                    </div> <!-- end col -->
                    <div class="col-sm-6">
                        <div class="float-end mt-3 mt-sm-0">
                            <p><b>Sub-total: </b> <span class="float-end">{{$invoice->subtotal}}</span></p>
                            <p><b>Remise: </b> <span class="float-end">{{$invoice->discount}}</span></p>
                            <h3><b>Total: </b>{{$invoice->total}}</h3>
                        </div>
                        <div class="clearfix"></div>
                    </div> <!-- end col -->
                </div>
                <!-- end row-->

                <div class="d-print-none mt-4">
                    <div class="text-end">
                        <a href="{{route('invoice.print',$invoice->id)}} " class="btn btn-primary"><i
                                class="mdi mdi-printer"></i>
                            Imprimer</a>
                        <a href="{{route('invoice.updateStatus',$invoice->id)}} " class="btn btn-success"><i
                                class="mdi mdi-cash"></i>
                            Payé</a>
                    </div>
                </div>
                <!-- end buttons -->

            </div> <!-- end card-body-->
        </div> <!-- end card -->
    </div> <!-- end col-->
</div>
<!-- end row -->
@endsection


@push('extra-js')

@endpush