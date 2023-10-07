@extends('layouts.app2')

@section('title', 'Problèmes signalés | Examens')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#standard-modal">Ajouter une nouvelle catégorie</button>
            </div>
            <h4 class="page-title">Problèmes signalés</h4>
        </div>

        <!----MODAL---->



    </div>
</div>


<div class="">


    @include('layouts.alerts')


    <div class="card mb-md-0 mb-3">
        <div class="card-body">
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false" aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            <h5 class="card-title mb-0">Liste des catégories d'examens</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type de signal</th>
                            <th>Code examen</th>
                            <th>Comentaire</th>
                            <th>Envoyé par</th>

                            {{-- //Lorsque l'utilisateur n'a pas le role nécessaire. --}}
                            @if (getOnlineUser()->can('view-process-ticket'))
                                <th>Actions</th>
                            @endif
                            
                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($signals as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->type_signal == "erreurSaving"? 'Erreur d\'enregistrement':'Annulation de facture' }}</td>
                            <td>{{ getTestOrderData($item->test_order_id)->code }}</td>
                            <td>{{ $item->commentaire }}</td>
                            <td>{{ $item->user->lastname }} {{ $item->user->firstname }}</td>
                            {{-- //Lorsque l'utilisateur n'a pas le role nécessaire. --}}
                            @if (getOnlineUser()->can('view-process-ticket'))
                                <td>
                                    <button type="button" class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </button>
                                </td>
                            @endif

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
<script>
    var baseUrl = "{{url('/')}}"
</script>
<script src="{{asset('viewjs/test/category.js')}}"></script>

@endpush
