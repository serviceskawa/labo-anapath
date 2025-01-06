<!-- Large modal -->
<div class="modal fade" id="bs-example-modal-lg-edit-{{$item->id}}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Modifier la catégorie de dépense</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('expense.update',$item->id) }}" method="POST" autocomplete="on">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">

                        <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Nom</article><span
                                        style="color:red;">*</span></label>
                                <input type="text" name="name" value="{{  old('name') ? old('name') : $item->name}}"
                                    class="form-control" required>
                            </div>
                        </div>


                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Description</article><span
                                        style="color:red;">*</span></label>
                                <textarea type="text" name="description"
                                    class="form-control">{{ old('description') ? old('description') : $item->description}}</textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->