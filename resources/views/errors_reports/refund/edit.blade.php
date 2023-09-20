<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Metre à jour un remboursement</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('refund.request.update') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="modal-body">

                <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                <input type="hidden"  name="id" id="id2" class="form-control" required>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Facture<span
                        style="color:red;">*</span></label>
                <select class="form-select select2" name="invoice_id"
                    id="invoice_id2" required>
                    <option>Sélectionner une facture</option>
                    @foreach ($invoices as $invoice)
                        <option value="{{ $invoice->id }}">{{$invoice->code}} ({{ $invoice->order ? $invoice->order->code : $invoice->code }})</option>
                    @endforeach
                </select>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Raison de la demande<span
                        style="color:red;">*</span></label>
                    <select class="form-select select2" name="refund_reason_id"
                        id="refund_reason_id2" required>
                        <option>Sélectionner une raison</option>
                        @foreach ($categories as $categorie)
                            <option value="{{ $categorie->id }}">{{ $categorie->description }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Montant<span style="color:red;">*</span></label>
                    <input type="number" name="montant" id="montant2" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Pièce jointe<span
                            style="color:red;">*</span></label>
                    <input type="file" id="example-fileinput" name="attachement" id="attachment" class="form-control">

                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Description<span style="color:red;">*</span></label>
                    <textarea name="note" id="description2" rows="6" class="form-control" required></textarea>
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
