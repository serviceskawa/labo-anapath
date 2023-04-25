@extends('layouts.app2')

@section('title', 'Factures')

@section('content')

    <div class="">

        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <a href="{{ route('invoice.index') }}"><button type="button" class="btn btn-primary">Retour à la listes des
                        factures</button></a>
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
                                <th>Période</th>
                                <th>Total facturé</th>
                                <th>Chiffre d'affaire</th>
                                <th>Avoir</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach (['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'] as $key => $mois)
                                <tr>
                                    <td>{{ $mois }} {{Carbon\Carbon::now()->formatLocalized('%G')}}</td>
                                    <td>{{ App\Models\Invoice::whereMonth('updated_at', $key+1)->sum('total'); }} F CFA</td>
                                    <td>{{ App\Models\Invoice::whereMonth('updated_at', $key+1)->where('paid','=',1)->sum('total'); }} F CFA</td>
                                    <td>{{ App\Models\Invoice::whereMonth('updated_at', $key+1)->where('paid','=',0)->sum('total'); }} F CFA</td>
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
    <script></script>
@endpush
