
<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter une demande d'examen</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('test_order.store') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">

                <div class="mb-3">
                    <label for="example-select" class="form-label">Contrat</label>
                    <select class="form-select" id="example-select" name="contrat_id" required>
                        <option>...</option>
                        @foreach ($contrats as $contrat)
                        <option value="{{ $contrat->id }}">{{ $contrat->name }}</option>
                        @endforeach


                    </select>
                </div>


                <div class="mb-3">
                    <label for="example-select" class="form-label">Patient</label>
                    <select class="form-select" id="example-select" name="patient_id" required>
                        <option>...</option>
                        @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                        @endforeach


                    </select>
                </div>


                <div class="mb-3">
                    <label for="example-select" class="form-label">Hopital</label>
                    <select class="form-select" id="example-select" name="hospital_id" required>
                        <option>...</option>
                        @foreach ($hopitals as $hopital)
                        <option value="{{ $hopital->id }}">{{ $hopital->name }}</option>
                        @endforeach


                    </select>
                </div>


                <div class="mb-3">
                    <label for="example-select" class="form-label">Médecin</label>
                    <select class="form-select" id="example-select" name="doctor_id" required>
                        <option>...</option>
                        @foreach ($médecins as $médecin)
                        <option value="{{ $médecin->id }}">{{ $médecin->name }}</option>
                        @endforeach


                    </select>
                </div>


                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Reférence hopital</label>
                    <input type="text" name="reference_hopital" class="form-control" >
                </div>


                <div class="mb-3">
                    <label for="example-select" class="form-label">Test</label>
                    <select class="form-select" id="test_id" name="test_id" required onchange="getTest()" >
                        <option>...</option>
                        @foreach ($tests as $test)
                        <option value="{{ $test->id }}">{{ $test->name }}</option>
                        @endforeach


                    </select>
                </div>


                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Prix</label>
                    <input type="text" name="prix" id="price" class="form-control" readonly required>
                </div>


                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Remise en montant</label>
                    <input type="text" name="remise" value="0" class="form-control" required>
                </div>






            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
