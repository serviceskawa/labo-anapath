<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modifier un hôpital</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('hopitals.update') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-body">
            
                <input type="hidden"  name="id2" id="id2" class="form-control">


               
                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Libellé</label>
                    <input type="text" name="name2" id="name2" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Telephone</label>
                    <input type="text" name="telephone2" id="telephone2" class="form-control" required>
                </div>

                
                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Email</label>
                    <input type="email" name="email2" id="email2" class="form-control">
                </div>

                
                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Adresse</label>
                    <input type="text" name="adresse2" id="adresse2" class="form-control">
                </div>

                
                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Commision</label>
                    <input type="text" name="commission2" id="commission2" class="form-control" required>
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