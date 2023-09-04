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
                    data-bs-target="#bs-example-modal-lg-create">Ajouter un nouvel article</button>
            </div>
            <h4 class="page-title">Articles</h4>
        </div>
        @include('articles.create')
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
            <h5 class="card-title mb-0">Liste des articles</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <form action="{{ route('article.update',$article) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">

                        <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Nom de l'article</article><span
                                        style="color:red;">*</span></label>
                                <input type="text" name="article_name"
                                    value="{{  old('article_name') ? old('article_name') : $article->article_name}}"
                                    class="form-control" required>
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Description<span
                                        style="color:red;">*</span></label>
                                <textarea type="text" name="description" class="form-control" cols="6" rows="3"
                                    required>{{ old('description') ? old('description') : $article->description}}</textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Quantité en stock<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="quantity_in_stock"
                                    value="{{  old('quantity_in_stock') ? old('quantity_in_stock') : $article->quantity_in_stock}}"
                                    class="form-control" required>
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="example-select" class="form-label">Unité de mesure<span
                                        style="color:red;">*</span></label>
                                <select class="form-select" id="langue" name="unit_of_measurement" required>
                                    <option value="">Sélectionner l'unité de mesure de la quantité</option>
                                    <option value="litre">Litre</option>
                                    <option value="bouteille">Bouteille</option>
                                    <option value="kilogramme">Kilogramme</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label for="example-date" class="form-label">Date d'expiration</label>
                                <input class="form-control" id="example-date"
                                    value="{{  old('expiration_date') ? old('expiration_date') : $article->expiration_date}}"
                                    type="date" name="expiration_date">
                            </div>

                            <div class="mb-3 col-lg-4">
                                <label for="simpleinput" class="form-label">Numéro du lot<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="lot_number"
                                    value="{{  old('lot_number') ? old('lot_number') : $article->lot_number}}"
                                    class="form-control" required>
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label for="simpleinput" class="form-label">Minumum<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="minimum" class="form-control"
                                    value="{{  old('minimum') ? old('minimum') : $article->minimum}}" required>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Mettre a l'article</button>
                    </div>
                </form>


            </div>
        </div>
    </div> <!-- end card-->


</div>















@endsection

@push('extra-js')


@endpush