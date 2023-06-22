<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Signaler un problème</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('probleme.report.store') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-body">

                <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Code de la demande<span style="color:red;">*</span></label>
                    <input type="text" name="test_order_code" class="form-control" placeholder="XX-XXXX" required>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Catégorie de problème<span style="color:red;">*</span></label>
                    <select name="errorCategory_id" id="errorCategory_id" class="form-control">
                        <option value="">Toutes les catégories</option>
                        @foreach ($problemCategories as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Description<span style="color:red;">*</span></label>
                    <textarea name="description" id="" rows="6" class="form-control"></textarea>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Signaler un problème</button>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
