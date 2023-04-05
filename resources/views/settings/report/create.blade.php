<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter un nouveau titre</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('report.report-store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Titre<span style="color:red;">*</span></label>
                        <input type="text" name="title" style="text-transform: uppercase;" class="form-control"
                            required>
                    </div>
                    <label class="form-label mt-3">Par défaut</label> <br>
                    <input type="checkbox" id="status" name="status" class="form-control" data-switch="success">
                    <label for="status" data-on-label="oui" data-off-label="non"></label>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter un nouveau titre</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
