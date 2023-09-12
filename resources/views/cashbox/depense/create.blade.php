<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog" style="max-width: 100%; padding-left: 300px; margin-left:50px;">
        <div class="modal-content" style="width: 70%;;">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Approvisionner la caisse</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{route('cashbox.depense.store')}}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    {{-- <input type="hidden" name="id" id="id2" class="form-control"> --}}

                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label for="simpleinput" class="form-label">Nom de la banque</label>
                            <select class="form-select" name="bank_id" id="bank_id">
                                <option value="">Sélectionner une banque</option>
                                @foreach ( $banks as $bank )
                                <option value="{{$bank->id}}">{{ $bank->name }}</option>

                                @endforeach

                            </select>
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label for="simpleinput" class="form-label">Numéro de chèque</label>
                            <input type="text" name="cheque_number" id="cheque_number" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Montant</label>
                        <input type="number" name="amount" id="amount" class="form-control">
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label for="example-date" class="form-label">Date</label>
                            <input class="form-control" id="date" type="date" name="date">
                        </div>

                        <div class="mb-3 col-lg-6">
                            <label for="simpleinput" class="form-label">Attachement</label>
                            <input type="file" id="example-fileinput" class="form-control" name="depense_file">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Description</label>
                        <textarea type="text" name="description" id="description" class="form-control"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Approvisionner la caisse</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
