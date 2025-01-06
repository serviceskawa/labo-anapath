@extends('layouts.app2')

@section('title', 'Caisse de vente')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                @if ($cashboxs->statut == 0)
                <a type="button" class="btn btn-secondary mb-4" data-bs-toggle="modal"
                    data-bs-target="#standard-modal">Enregistrer un dépôt de banque</a>
                @endif
                <a href="{{ route('daily.index') }}" class="btn btn-primary mb-4">Ouverture/Fermeture</a>
            </div>
            <h4 class="page-title">Caisse de vente
                @if ($cashboxs->statut == 0)
                <span style="color: red;">[Fermée]</span>
                @else
                <span style="color: green;">[Ouvert]</span>
                @endif
            </h4>
        </div>

        {{-- @include('suppliers.category.create') --}}

        @include('cashbox.vente.edit')
        @include('cashbox.vente.edit_bank')

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

            <a href="javascript:void(0);" class="btn btn-success mb-4">Solde actuel : {{ $totalToday }}</a>
            {{-- <a href="javascript:void(0);" class="btn btn-success mb-4">Entrer : {{ $entree }}</a>

            <a href="javascript:void(0);" class="btn btn-danger mb-4">Sortie : {{ $sortie }}</a>
            <a href="javascript:void(0);" class="btn mb-4"> du {{ now()->format('d/m/y')
                }}</a> --}}

            <h5 class="card-title mb-2">Historique des opérations </h5>
            <div id="cardCollpase1" class="collapse pt-3 show">

                <table id="datatable1" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            {{-- <th>Date</th> --}}
                            <th>Montant</th>
                            <th>Facture</th>
                            <th>Type de payement</th>
                            <th>Date</th>
                            <th>Utilisateur</th>
                        </tr>
                    </thead>



                    <tbody>

                        @foreach ($cashadds as $key => $item)
                        <tr class="{{ $item->invoice ? '' : 'table-danger' }}">
                            <td>{{ ++$key }}</td>
                            {{-- <td>{{ date('d-m-Y (H:i)', strtotime($item->date )) }}</td> --}}
                            <td>{{ $item->amount }}</td>
                            <td>

                                @if ($item->invoice)
                                {{ $item->invoice->order ? $item->invoice->order->code : $item->invoice->code }}
                                <br>
                                <small class="text-muted">Du
                                    {{ date('d-m-y (H:i)', strtotime($item->invoice->created_at)) }}</small>
                                @else
                                -
                                @endif

                            </td>

                            <td>
                                @if ($item->invoice)
                                {{ $item->invoice->payment == 'MOBILEMONEY'
                                ? 'MOBILE MONEY'
                                : ($item->invoice->payment == 'CARTEBANCAIRE'
                                ? 'CARTE BANQUAIRE'
                                : $item->invoice->payment) }}
                                @else
                                -
                                @endif
                            </td>

                            <td>{{ $item->created_at->format('d-m-Y (H:i)') }}</td>

                            <td>
                               
                                {{ optional($item->user)->firstname ?? 'NoData' }}
{{ optional($item->user)->lastname ?? 'NoData' }}

                            </td>
                            {{-- <td>
                                <button type="button" onclick="editVente({{$item->id}})" class="btn btn-primary"><i
                                        class="mdi mdi-lead-pencil"></i> </button>
                                <button type="button" onclick="deleteModalVente({{$item->id}})"
                                    class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                            </td> --}}
                        </tr>
                        @endforeach




                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end card-->

</div>



{{-- Debut Ouverture et fermeture de la caisse --}}

{{-- Fin du code --}}

@endsection


@push('extra-js')
<script>
    var baseUrl = "{{ url('/') }}"
</script>
<script src="{{ asset('viewjs/bank/cashbox.js') }}"></script>
@endpush