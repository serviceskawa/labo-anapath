<div class="modal fade" id="bs-example-modal-lg-ouverture" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Ouverture de la caisse de vente : {{
                    now()->format('d/m/Y')
                    }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('daily.store') }}" method="post">
                    @csrf
                    <div class="row mx-auto">
                        {{-- <div class="col-md-12">
                            <button class="btn btn-success">
                                <h1>CAISSE DE VENTE : {{ $cashboxs->current_balance }} FCFA</h1>
                            </button>
                        </div> --}}

                        <div class="col-md-12 py-4">
                            <p class="text-center">
                                Veuillez entrer le montant du fond de caisse.
                            </p>
                        </div>

                        <div class="col-md-12 d-flex">

                            <input type="text" name="statut" value="1" hidden>
                            <input type="text" name="typecaisse" value="2" hidden>
                            <button class="btn btn-success">
                                <span style="font-size: 20px;">Espèces</span>
                            </button>
                            <input type="number" placeholder="0.0 Francs CFA" name="solde_ouverture" value=""
                                class="form-control">

                        </div>
                    </div>
                    <div class="modal-footer mt-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>