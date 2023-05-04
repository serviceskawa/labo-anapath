@extends('layouts.app2')

@section('title', 'Factures')

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
        <div class="card-body">
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                    aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            <h5 class="card-title mb-0">Liste des Factures </h5>

            <div id="cardCollpase1" class="collapse pt-3 show">

                <table id="datatable1" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Demande</th>
                            <th>Patient</th>
                            <th>Total</th>
                            <th>Remise</th>
                            <th>Statut</th>
                            <th>Actions</th>

                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($invoices as $key =>$item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->date }}</td>
                            <td>{{$item->test_order_id ? getTestOrderData($item->test_order_id)->code :'' }}</td>
                            <td>{{$item->test_order_id?
                                getTestOrderData($item->test_order_id)->patient->firstname.'
                                '.getTestOrderData($item->test_order_id)->patient->lastname :''
                                }}
                            </td>
                            <td>{{ $item->subtotal }}</td>
                            <td>{{ $item->total }}</td>
                            <td><span class="bg-{{$item->paid != 1 ? 'danger' : 'success' }} badge
                                float-end">{{$item->paid == 1 ? "Payé" : "En
                                    attente"}}
                                </span></td>
                            <td>
                                <a type="button" href="{{route('invoice.show',$item->id)}}" class="btn btn-warning"><i
                                        class="mdi mdi-eye"></i> </a>
                                {{-- <button type="button" onclick="normalize({{$item->id}})" title="Normaliser la facture" class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </button> --}}
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
