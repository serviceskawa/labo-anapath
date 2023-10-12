<div class="modal fade" id="bs-example-modal-lg-create-docs" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Ajouter un nouveau ce fichier</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('doc.file.store') }}" method="POST" autocomplete="on"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div style="text-align:right;"><span style="color:red;">*</span>Champs obligatoires</div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Nom<span style="color:red;">*</span></label>
                                <input type="text" required name="title" class="form-control" required>
                            </div>
                        </div>

                        {{-- <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Type de catégorie</label>
                                <select class="form-select select2" name="categorie_id" data-toggle="select2">
                                    <option>Selectionner la catégorie</option>
                                    @foreach ($select_categorie as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}


                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="" class="form-label">Type de catégorie</label>
                                <select required name="documentation_categorie_id" class="form-select" id="">
                                    <option>Selectionner la catégorie</option>
                                    @foreach ($select_categorie as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="example-fileinput" class="form-label">Fichier<span
                                        style="color:red;">*</span></label>
                                <input required type="file" name="attachment" id="example-fileinput"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter un nouveau fichier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>