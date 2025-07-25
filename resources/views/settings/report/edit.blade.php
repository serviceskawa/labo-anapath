<div id="editModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modifier le titre</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('report.report-update') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-body">

                <input type="hidden"  name="id" id="id2" class="form-control" required>


                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Titre</label>
                    <input type="text" name="title" style="text-transform: uppercase;" id="title2" class="form-control" required>
                </div>
                <label class="form-label mt-3">Par défaut</label> <br>
                <input type="checkbox" id="status2" name="status" class="form-control" data-switch="success" >
                <label for="status2" data-on-label="oui" data-off-label="non"></label>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
