<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modifier les informations du contrat</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('contrats.update') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Nom du contrat<span
                                style="color:red;">*</span></label>
                        <input type="text" name="name" id="name2" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Type<span style="color:red;">*</span></label>

                        <select class="form-select" id="type2" name="type" required>
                            <option value="">Sélectionner le type de contrat</option>
                            <option value="ORDINAIRE">Ordinaire</option>
                            <option value="ASSURANCE">Assurance</option>
                            <option value="CAMPAGNE">Campagne</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Statut<span style="color:red;">*</span></label>

                        <select class="form-select" id="status2" name="status" required>
                            <option value="">Sélectionner le statut</option>
                            <option value="ACTIF">Actif</option>
                            <option value="INACTIF">Inactif</option>

                        </select>
                    </div>

                    <input type="hidden" id="id2" name="id">
                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Nombre d'examens<span
                                    style="color:red;">*</span></label>
                            <input type="number" min="-1" name="nbr_examen" value="-1" class="form-control" id="" required>
                            <small>-1 pour un nombre illimité</small>
                        </div>

                    <div class="row">
                        <div class="mb-3 col-12 col-lg-4">
                            <label class="form-label mt-3">Facturation groupée</label> <br>
                                <input type="checkbox" id="switch4" class="form-control"
                                    name="invoice_unique" data-switch="success" />
                            <label for="switch3" data-on-label="oui" data-off-label="non"></label>
                        </div>
                        <div class="mt-3 col-12 col-lg-8" id="show-client1" style="display: none">
                            <label for="example-select" class="form-label">Client</label>
                            <select class="form-select" id="client_id1" name="client_id">
                                <option value="">Sélectionner le client du contrat</option>
                                @foreach ($clients as $client)
                                    <option value="{{$client->id}}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Description<span style="color:red;">*</span></label>
                        <textarea type="text" name="description" id="description2" class="form-control"
                            required></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
