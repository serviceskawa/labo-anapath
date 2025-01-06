@extends('layouts.app2')

@section('title', 'Contrat')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3 mb-1">
                <a href="{{ route('contrats.index') }}" type="button" class="btn btn-primary">Retour à la liste des
                    contrats</a>
            </div>
            <h4 class="page-title">Contrat : [{{ $contrat->name }}]</h4>
        </div>
    </div>
</div>


<div class="">
    @include('layouts.alerts')


    <div class="card mb-md-0 mb-3 mt-4">
        <div class="card-body">

            <h5 class="card-title mb-0">Ajouter un examen pris en compte</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">
                <form action="{{ route('contrat_details.store_test') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                        <div class="mb-3">
                            <label for="example-select" class="form-label">Examens<span
                                    style="color:red;">*</span></label>
                            <select class="form-control select2" data-toggle="select2" id="example-select"
                                name="test_id" required>
                                <option value="">Sélectionner l'examen</option>
                                @foreach ($tests as $test)
                                <option value="{{ $test->id }}">{{ $test->name }} ({{
                                    $test->price }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <div class="mb-3">
                            <label for="simpleinput" class="form-label">Prix<span style="color:red;">*</span></label>
                            <input type="number" name="price" id="price" class="form-control" readonly required>
                        </div> --}}

                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Réduction<span
                                    style="color:red;">*</span></label>
                            <input type="number" name="amount_remise" id="amount_remise" class="form-control" required>
                        </div>

                        {{-- <div class="mb-3">
                            <label for="simpleinput" class="form-label">Montant après remise<span
                                    style="color:red;">*</span></label>
                            <input type="number" name="amount_after_remise" id="amount_after_remise"
                                class="form-control" readonly required>
                        </div> --}}

                        <input type="hidden" name="contrat_id" value="{{ $contrat->id }}" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter un nouveau examen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>
@endsection


@push('extra-js')

<script>
    var baseUrl = "{{ url('/') }}";
</script>
<script src="{{asset('viewjs/contrat/indexContrat.js')}}"></script>
<script src="{{asset('viewjs/contrat/indexdetail.js')}}"></script>
@endpush