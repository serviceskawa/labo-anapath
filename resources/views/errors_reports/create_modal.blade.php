<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Signaler un problème</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>

        <form action="{{route('probleme.report.store')}} " method="post" autocomplete="off" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
            <div class="mb-3">
                <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <label for="exampleFormControlInput1" class="form-label mt-3">Objet</label>
                        <input type="text" name="subject" placeholder="Entrer une description" id="" class="form-control">
            </div>
            <div class="mb-3">
                <div class="form-group">
                <label for="simpleinput" class="form-label">Description</label>
                {{-- <textarea name="description" id="editor"  rows="10"></textarea> --}}
                <textarea name="description" id="" rows="6" class="form-control" placeholder="Veillez fournir plus de détails que possible pour nous permettre de mieux vous aider" required></textarea>
                </div>
            </div>
            </div>

            <div class="modal-footer">
                <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Créer un ticket</button>
            </div>


            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
