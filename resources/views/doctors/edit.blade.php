<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modifier un hôpital</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('doctors.update') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-body">
            
                <input type="hidden"  name="id2" id="id2" class="form-control">



         
                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Nom & Prénom</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Telephone</label>
                    <input type="text" name="telephone" id="telephone" class="form-control" >
                </div>

                
                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>

                
                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Rôle</label>
                    <input type="text" name="role" id="role" class="form-control">
                </div>

                
                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Commision</label>
                    <input type="text" name="commission" id="commission" class="form-control" required>
                </div>


           
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->