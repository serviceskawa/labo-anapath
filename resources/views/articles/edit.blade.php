<!-- Large modal -->
<div class="modal fade" id="bs-example-modal-lg-edit-{{$item->id}}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Modifier l'article</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('article.update',$item->id) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">

                        <div style="text-align:right;"><span style="color:red;">*</span>Champs obligatoires</div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Nom de l'article</article><span
                                        style="color:red;">*</span></label>
                                <input type="text" name="article_name"
                                    value="{{  old('article_name') ? old('article_name') : $item->article_name}}"
                                    class="form-control" required>
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Quantité en stock<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="quantity_in_stock"
                                    value="{{  old('quantity_in_stock') ? old('quantity_in_stock') : $item->quantity_in_stock}}"
                                    class="form-control" required readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="example-select" class="form-label">Unité de mesure<span
                                        style="color:red;">*</span></label>
                                <select class="form-select" id="langue" name="unit_of_measurement" required>
                                    <option value="">Sélectionner l'unité de mesure de la quantité</option>
                                    @foreach ($units as $unit)
                                    <option value="{{$unit->id}}" {{$unit->id==$item->unit_measurement_id ? 'selected' :
                                        ''}}>{{ $unit->name}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="mb-3 col-lg-6">
                                <label for="example-date" class="form-label">Date d'expiration</label>
                                <input class="form-control" id="example-date" type="date"
                                    value="{{  old('expiration_date') ? old('expiration_date') : $item->expiration_date}}"
                                    name="expiration_date">
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Numéro du lot</label>
                                <input type="number" name="lot_number"
                                    value="{{  old('lot_number') ? old('lot_number') : $item->lot_number}}"
                                    class="form-control">
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Minumum<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="minimum"
                                    value="{{  old('minimum') ? old('minimum') : $item->minimum}}" class="form-control"
                                    required>
                            </div>
                        </div>



                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Prix<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="prix" value="{{  old('prix') ? old('prix') : $item->prix}}"
                                    class="form-control" required>
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Description</label>
                                <textarea type="text" name="description" class="form-control" cols="12"
                                    rows="3">{{ old('description') ? old('description') : $item->description}}</textarea>
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