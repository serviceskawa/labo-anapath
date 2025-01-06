<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modifier les informations de la demande</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('prestations_order.update') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <input type="hidden" name="id" id="id" class="form-control">

                    <div class="mb-3">
                        {{-- <label for="simpleinput" class="form-label">Nom du patient <span style="color:red;">*</span></label>
                        <input type="text" name="firstname" id="firstname" class="form-control" required> --}}
                        <label for="example-select" class="form-label">Patients</label>
                        <select class="form-select select2" id="patient" data-toggle="" name="patient" required>
                            <option>Tous les patients</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}"> {{ $patient->firstname }}
                                    {{ $patient->lastname }}</option>
                            @endforeach

                        </select>
                    </div>

                    {{-- <div class="mb-3">
                        <label for="simpleinput" class="form-label">Prénom du patient <span style="color:red;">*</span></label>
                        <input type="text" name="lastname" id="lastname" class="form-control" required>
                    </div> --}}

                    <div class="mb-3">
                        {{-- <label for="simpleinput" class="form-label">Prestation<span style="color:red;">*</span></label>
                        <input type="number" name="namePresttation" id="namePresttation" class="form-control" required> --}}
                        <label for="example-select" class="form-label">Prestations</label>
                        <select class="form-select select2" id="prestation_id2" data-toggle="" name="prestation_id" onchange="getprestation()" required>
                            <option>Toutes les prestations</option>
                            @foreach ($prestations as $prestation)
                                <option value="{{ $prestation->id }}">
                                    {{ $prestation->name }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Prix</label>
                        <input type="text" name="total" id="total2" class="form-control" required readonly>
                    </div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Status<span style="color:red;">*</span></label>
                        <select class="form-select" name="status" id="status2">
                            <option value="">Sélectionner le status</option>
                            <option value="0">En cours</option>
                            <option value="1">Terminé</option>

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@push('extra-js')

   <script>
     var ROUTEGETPRESTATIONORDER = "{{ route('prestations_order.getPrestationOrder') }}"
     var TOKENGETPRESTATIONORDER = "{{ csrf_token() }}"
   </script>
   <script src="{{asset('viewjs/prestation/prestationorderedit.js')}}"></script>
@endpush
