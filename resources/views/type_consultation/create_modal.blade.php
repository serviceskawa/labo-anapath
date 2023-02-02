<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter une nouvelle categorie</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('type_consultation.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="row">
                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Nom de la categorie de consultation <span
                                    style="color:red;">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required>
                            <input type="hidden" name="id" id="id" class="form-control">
                        </div>
                    </div>
                    <div class="row my-3">
                        <label for="simpleinput" class="form-label">Cocher les fichiers joints<span
                                style="color:red;">*</span></label>
                        <div class="">
                            @forelse ($files as $item)
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input"
                                        name="type_files[{{ $item->id }}]" id="customCheck3">
                                    <label class="form-check-label" for="customCheck3">{{ $item->title }}</label>
                                </div>
                            @empty
                            @endforelse

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary">Ajouter une nouvelle
                        categorie</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
