@extends('layouts.app2')

@section('title', 'Vue-article | Articles')
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

                <div class="row d-flex align-items-center justify-content-lg-between">

                    <div class="col-5">
                        <label for="example-select" class="form-label">Article<span style="color:red;">*</span></label>
                        <select class="form-select" id="langue" name="article_id" required>
                            <option value="">Sélectionner l'article</option>
                            @forelse ($articles as $article)
                            <option value="{{$article->id}}">{{$article->article_name}}</option>
                            @empty
                            <option value="">Aucun article existant</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="col-5">
                        <label for="simpleinput" class="form-label">Quantité<span style="color:red;">*</span></label>
                        <input type="number" name="quantite_changed" placeholder="XX" class="form-control" required>
                    </div>

                    <div class="col-2">
                        <label class="form-label">&nbsp;</label>
                        <input type="hidden" name="ma_variable" value="">
                        <div class="">
                        <button type="submit" class="btn btn-primary" data-ma-variable="augmenter"
                            onclick="updateHiddenField(this)">Entrer</button>
                        <button type="submit" class="btn btn-danger" data-ma-variable="diminuer"
                            onclick="updateHiddenField(this)">sortir</button>
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

                </div>
                <h4 class="page-title">Historique des stocks</h4>
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
                            @if($movement->article)
                                 <tr>
                                    <td>{{ $movement->article->article_name }}</td>
                                    <td>{{ $movement->movement_type == 'augmenter' ? 'Entrer' : ($movement->movement_type == 'diminuer' ? 'Sortir':'Stock initial') }}</td>
                                    <td>{{ $movement->date_mouvement }}</td>
                                    <td>{{ $movement->quantite_changed }}</td>
                                    <td>{{ $movement->user->firstname.' '.$movement->user->lastname }}</td>
                                    {{-- <td>{{ $movement->description }}</td> --}}
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>





{{-- <div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">

            </div>
            <h4 class="page-title">Historiques complet des stocks</h4>
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
            <h5 class="card-title mb-0">Dépenses</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
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
                            @if($movement->description==null)
                            <td>Pas de description</td>
                            @else
                            <td>{{ $movement->description }}</td>
                            @endif
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> --}}



@endsection

@push('extra-js')
<script>
    var baseUrl = "{{url('/')}}"
</script>
<script src="{{asset('viewjs/patient/index.js')}}"></script>
<script>
    function updateHiddenField(button) {
    var maVariable = button.getAttribute('data-ma-variable');
    document.getElementById('monFormulaire').querySelector('input[name="ma_variable"]').value = maVariable;
    document.getElementById('monFormulaire').submit();
}
</script>

@endpush
