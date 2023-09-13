@extends('layouts.app2')

@section('title', 'Caisse')

@section('content')
<div class="row">
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
        {{-- @include('expenses.create',['expenses' => $expenses,'expenses_categorie' =>
        $expenses_categorie,'cash_ticket' =>
        $cash_ticket]) --}}
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

                        @if ($item->status==1)
                        <tr>
                            <td>
                                <form action="{{route('daily.update',$item->id)}}" method="POST" class="d-flex">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="status" value="0" hidden>
                                    <button class="btn btn-danger" type="submit">Fermer la caisse</button>
                                </form>
                            </td>
                        </tr>
                        @else

                        @endif

                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end card-->
</div>
@endsection


@push('extra-js')
<script>
    var baseUrl = "{{url('/')}}"
</script>
<script src="{{asset('viewjs/patient/index.js')}}"></script>
@endpush
