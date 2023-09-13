<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter un nouveau contrat</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('contrats.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Nom du contrat<span
                                style="color:red;">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Type<span style="color:red;">*</span></label>
                        <select class="form-select" id="example-select" name="type" required>
                            <option value="">Sélectionner le type de contrat</option>
                            <option value="ORDINAIRE">Ordinaire</option>
                            <option value="ASSURANCE">Assurance</option>
                            <option value="CAMPAGNE">Campagne</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="simpleinput" class="form-label">Nombre d'examens<span
                                    style="color:red;">*</span></label>
                            <input type="number" min="-1" name="nbr_examen" value="-1" class="form-control" id="" required>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label class="form-label mt-3">Facturation groupée</label> <br>
                                <input type="checkbox" id="switch3" class="form-control"
                                    name="invoice_unique" data-switch="success" />
                            <label for="switch3" data-on-label="oui" data-off-label="non"></label>
                        </div>
                    </div>

                    <div class="mb-3" id="show-client" style="display: none">
                        <label for="example-select" class="form-label">Client</label>
                        <select class="form-select" id="client_id" name="client_id">
                            <option value="">Sélectionner le client du contrat</option>
                            @foreach ($clients as $client)
                                <option value="{{$client->id}}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Description<span style="color:red;">*</span></label>
                        <textarea type="text" name="description" class="form-control"
                            placeholder="Brève description du contrat" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter un nouveau contrat</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
