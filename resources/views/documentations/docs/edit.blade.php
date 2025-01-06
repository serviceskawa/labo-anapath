<div class="modal fade" id="bs-example-modal-lg-edit-{{ $doc->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Modifier le document</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('doc.update') }}" method="POST" autocomplete="on"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">

                        <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                        <input type="hidden" name="doc_id" value="{{$doc->id}}">
                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Nom<span style="color:red;">*</span></label>
                                <input type="text" name="title" value="{{ old('title') ? old('title') : $doc->title }}"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="example-fileinput" class="form-label">Fichier<span
                                        style="color:red;">*</span></label>
                                <input type="file" name="attachment" accept=".pdf" id="example-fileinput" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="float-end mt-3">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
