@extends('layouts.app2')

@section('title', 'Ouverture et fermeture de la caisse')

@section('content')

{{-- <div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <form action="{{ route('daily.store') }}" method="post" class="d-flex">
                    @csrf
                    <input type="text" name="solde" value="{{ $cashboxs->current_balance
                    }}" hidden>
                    <input type="text" name="status" value="1" hidden>
                    <input type="text" name="typecaisse" value="1" hidden>
                    <button class="btn btn-primary" type="submit">Ouvrir la caisse(solde : {{ $cashboxs->current_balance
                        }})</button>
                </form>
            </div>
            <h4 class="page-title">Operation sur la caisse</h4>
        </div>
    </div>
</div> --}}

{{-- <div class="">
    @include('layouts.alerts')
    <div class="card mb-md-0 mb-3">
        <div class="card-body">
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                    aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            <h5 class="card-title mb-0">Dépenses</h5>

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
                        @foreach ($cashboxDailys as $item)
                        <tr>
                            <td>{{ $item->cashbox->type }}</td>
                            <td>{{ $item->opening_balance }}</td>
                            <td>{{ $item->close_balance }}</td>
                            <td>{{ $item->created_at }}</td>
                            @if ($item->status==1)
                            <td>Non disponible</td>
                            @elseif(($item->status==0))
                            <td>{{ $item->updated_at }}</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div> --}}


{{-- Debut Ouverture et fermeture de la caisse --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                @if ($cashboxtest->statut==0)

                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                    data-bs-target="#bs-example-modal-lg-ouverture">Ouvrir la caisse</button>
                @include('cashbox_daily.ouverture')

                @elseif($cashboxtest->statut==1)

                <a href="{{ route('daily.fermeture',$cashboxtest->id) }}" class="btn btn-danger">Fermer la caisse</a>
                @endif
            </div>
            <h4 class="page-title">Ouverture et fermeture (Caisse de vente)</h4>
        </div>
    </div>
</div>

<div class="">
    <div class="card mb-md-0 mb-3">
        <div class="card-body">
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                    aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            <h5 class="card-title mb-0">Historiques</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Solde d'ouverture</th>
                            <th>Solde de fermeture</th>
                            <th>Date d'ouverture</th>
                            <th>Date de fermeture</th>
                            <th>Utilisateur</th>
                            <th>Ecart</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($cashboxDailys as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->opening_balance }}</td>
                            <td>{{ $item->close_balance }}</td>
                            <td>{{ $item->created_at }}</td>
                            @if ($item->status==1)
                            <td>Non disponible</td>
                            @elseif(($item->status==0))
                            <td>{{ $item->updated_at }}</td>
                            @endif
                            <td>{{ $item->user->firstname }} {{ $item->user->lastname }}</td>
                            <td>{{ $item->total_ecart }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end card-->
</div>
{{-- Fin du code --}}
@endsection


@push('extra-js')
<script>
    var baseUrl = "{{url('/')}}"
</script>
<script src="{{asset('viewjs/patient/index.js')}}"></script>
@endpush