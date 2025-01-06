<!-- Large modal -->
<div class="modal fade" id="bs-example-modal-lg-timeoffs-see" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Ajouter un congé</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                    <div class="modal-body">

                        <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Employé<span
                                        style="color:red;">*</span></label>
                                <select class="form-select select2" id="user_id_employee" name="user_id_employee">
                                    <option value="">Selectionner un employé</option>
                                    @forelse (getAllEmployees() as $user)
                                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                    @empty
                                        Ajouter un utilisateur
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div id="cardCollpase1" class="collapse pt-3 show">


                            <table id="datatable8" class="table table-striped dt-responsive nowrap w-100">
                                <tbody>

                                </tbody>
                            </table>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" id="see-all-order" class="btn btn-primary">Voir</button>
                    </div>
            </div>
        </div>
    </div>
</div>
