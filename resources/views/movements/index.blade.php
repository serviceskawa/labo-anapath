@extends('layouts.app2')

@section('title', 'Stocks')
@section('css')

@endsection

@section('content')


@include('layouts.alerts')



<div class="">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3">
                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#bs-example-modal-lg-create">Augmenter ou diminuer le stock</button> --}}
                </div>
                <h4 class="page-title">Gestion des stocks</h4>
            </div>
            @include('movements.create',['articles' => $articles])
        </div>
    </div>

    <div class="card mb-md-0 mb-3">
        <div class="card-body">

            <h5 class="card-title mb-0">Opérations sur le stock</h5>

                <form action="{{ route('movement.store') }}" method="POST" autocomplete="on" id="monFormulaire">
                    @csrf

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="row d-flex">

                        <div class="mb-3 col-lg-4">
                            <label for="example-select" class="form-label">Article<span
                                    style="color:red;">*</span></label>
                            <select class="form-select" id="langue" name="article_id" required>
                                <option value="">Sélectionner l'article</option>
                                @forelse ($articles as $article)
                                <option value="{{$article->id}}">{{$article->article_name}}</option>
                                @empty
                                <option value="article">Aucun article existant</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="mb-3 col-lg-4">
                            <label for="simpleinput" class="form-label">Quantité<span
                                    style="color:red;">*</span></label>
                            <input type="number" name="quantite_changed" placeholder="XX" class="form-control" required>
                        </div>

                        {{-- <input type="hidden" name="ma_variable" value=""> --}}

                        <div class="mb-3 col-lg-4">
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary" data-ma-variable="augmenter"
                                    onclick="updateHiddenField(this)">Entrer</button>

                                <button type="submit" class="btn btn-danger" data-ma-variable="diminuer"
                                    onclick="updateHiddenField(this)">Sortir</button>
                            </div>
                        </div>

                    </div>

                </form>
        </div>
    </div>
</div>





<div class="">

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3">
                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#bs-example-modal-lg-create">Augmenter ou diminuer le stock</button> --}}
                </div>
                {{-- <h4 class="page-title">Historique des stocks</h4> --}}
            </div>
            @include('movements.create',['articles' => $articles])
        </div>
    </div>

    <div class="card mb-md-0 mb-3">
        {{-- <h5 class="card-title mb-0">Historique</h5> --}}
        <div class="card-body">
            <h5 class="card-title mb-0">Historique des stocks</h5>
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                    aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            <h5 class="card-title mb-0"></h5>

            <div class="table-responsive mt-4">
                <table class="table table-striped table-centered mb-0">
                    <thead class="">
                        <tr>
                            <th>Article</th>
                            <th>Action</th>
                            <th>Date</th>
                            <th>Quantité</th>
                            <th>Utilisateur</th>
                            {{-- <th>Description</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movements as $movement)
                        <tr>
                            <td>{{ $movement->article->article_name }}</td>
                            <td>{{ $movement->movement_type = 'augmenter' ? 'Entrer' : 'Sortir'  }}</td>
                            <td>{{ $movement->date_mouvement }}</td>
                            <td>{{ $movement->quantite_changed }}</td>
                            <td>{{ $movement->user->firstname.' '.$movement->user->lastname }}</td>
                            {{-- <td>{{ $movement->description }}</td> --}}
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

<script>
    function updateHiddenField(button) {
    var maVariable = button.getAttribute('data-ma-variable');
    document.getElementById('monFormulaire').querySelector('input[name="ma_variable"]').value = maVariable;
    document.getElementById('monFormulaire').submit();
}
</script>

@endpush
