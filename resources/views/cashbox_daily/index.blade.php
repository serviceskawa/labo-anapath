@extends('layouts.app2')

@section('title', 'Ouverture et fermeture de la caisse')

@section('content')

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
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Date d'ouverture</th>
                            <th>Solde d'ouverture</th>
                            <th>Date de fermeture</th>
                            <th>Solde de fermeture</th>
                            <th>Utilisateur</th>
                            <th>Ecart</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($cashboxDailys as $item)
                        <tr class="{{ $item->total_ecart < 0 ? 'table-danger' : 'table-white' }}">
                            <td>
                                @include('cashbox_daily.details',['item' => $item])
                                {{-- <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#full-width-modal-show-{{ $item->id }}" class="btn btn-primary"><i
                                        class="mdi mdi-eye"></i> </button> --}}
                                <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-show-{{ $item->id }}"
                                    class="btn btn-primary"><i class="mdi mdi-eye"></i> </button>
                            </td>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->opening_balance }}</td>
                            @if ($item->status==1)
                            <td>Non disponible</td>
                            @elseif(($item->status==0))
                            <td>{{ $item->updated_at }}</td>
                            @endif
                            <td>{{ $item->close_balance }}</td>
                            <td>{{ $item->user->firstname }} {{ $item->user->lastname }}</td>
                            <td>{{ $item->total_ecart }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Fin du code --}}
@endsection


@push('extra-js')
<script>
    var baseUrl = "{{url('/')}}"
</script>
<script src="{{asset('viewjs/patient/index.js')}}"></script>
@endpush