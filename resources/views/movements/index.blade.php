@extends('layouts.app2')

@section('title', 'Vue-article | Articles')
@section('css')

@endsection

@section('content')




<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#bs-example-modal-lg-create">Ajouter ou diminuer le stock</button>
            </div>
            <h4 class="page-title">Historique de stock</h4>
        </div>
        @include('movements.create',['articles' => $articles])
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
            <h5 class="card-title mb-0">Vos historiques de stock</h5>

            <div class="table-responsive mt-4">
                <table class="table table-bordered table-centered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Article</th>
                            <th>Action</th>
                            <th>Date</th>
                            <th>Quantité</th>
                            <th>Fais par</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movements as $movement)
                        <tr>
                            <td>{{ $movement->article->article_name }}</td>
                            <td>{{ $movement->movement_type }}</td>
                            <td>{{ $movement->date_mouvement }}</td>
                            <td>{{ $movement->quantite_changed }}</td>
                            <td>{{ $movement->user->firstname.' '.$movement->user->lastname }}</td>
                            <td>{{ $movement->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> <!-- end table-responsive-->
        </div>
    </div> <!-- end card-->


</div>















@endsection

@push('extra-js')


@endpush