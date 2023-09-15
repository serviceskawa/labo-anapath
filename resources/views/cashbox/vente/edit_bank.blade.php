<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Enregistrer un dépôt bancaire</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{route('cashbox.vente.store.bank')}}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Nom de la banque<span style="color:red;">*</span></label>
                            <select class="form-select" name="bank_id" required id="bank_id">
                                <option value="">Sélectionner une banque</option>
                                @foreach ( $banks as $bank )
                                <option value="{{$bank->id}}">{{ $bank->name }}</option>

                                @endforeach

                            </select>
                        </div>
                        <div class="mb-3 ">
                            <label for="simpleinput" class="form-label">Montant<span style="color:red;">*</span></label>
                        <input type="number" name="amount" required id="amount" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="example-date" class="form-label">Date</label>
                            <input class="form-control" id="date" value="{{Carbon\Carbon::now()}}" type="date" name="date">
                        </div>

                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Attachement<span style="color:red;">*</span></label>
                            <input type="file" required id="example-fileinput" placeholder="Scan du reçu de dépôt" class="form-control" name="bank_file">
                        </div>

                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Commentaire</label>
                            <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" onclick="alert('Confirmez-vous l\'enregistrement de ce dépôt')"  class="btn btn-primary">Enregistrer un dépôt bancaire</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
