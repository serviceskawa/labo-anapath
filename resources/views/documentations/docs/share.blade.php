<div class="modal fade" id="shareModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Partager ce document</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('doc.file.share') }}" method="POST" autocomplete="on"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div style="text-align:right;"><span style="color:red;">*</span>Champs obligatoires</div>
                        <input type="hidden" name="doc_id" id="doc_id">
                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="" class="form-label">Rôle</label>
                                <select required name="role_id" class="form-select" id="role_id">
                                    <option>Selectionner le rôle</option>
                                    @foreach (getAllRoles() as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="float-end mt-3">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Partager</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
