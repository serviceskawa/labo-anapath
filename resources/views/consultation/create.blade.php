@extends('layouts.app2')

@section('title', 'Créer une consultation')

@section('content')
    <div class="">

        @include('layouts.alerts')
        @include('component.modal_create_patient')
        <div class="card my-3">
            <div class="card-header">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right mt-0">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#standard-modal">Ajouter un nouveau patient</button>
                        </div>
                        Ajouter une nouvelle consultation
                    </div>

                </div>

            </div>
            <div class="card-body">

                <form action="{{ route('consultation.store') }}" method="post" autocomplete="off"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">

                        <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                        <div class="col-md-6">
                            <label for="exampleFormControlInput1" class="form-label">Patient <span
                                    style="color:red;">*</span></label>
                            <select class="form-select select2" data-toggle="select2" name="patient_id" id="patient_id"
                                required>
                                <option>Sélectionner le nom du patient</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->code }} - {{ $patient->firstname }}
                                        {{ $patient->lastname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="exampleFormControlInput1" class="form-label">Docteur<span
                                    style="color:red;">*</span></label>
                            <select class="form-select select2" data-toggle="select2" name="doctor_id" id="doctor_id"
                                required>
                                <option>Sélectionner le Docteur</option>
                                @foreach (getUsersByRole('docteur') as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->lastname }} {{ $item->firstname }} </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row mb-3">
                        {{-- @if ($user->hasRole('docteur'))
                            <div class="col-md-3">
                                <label for="exampleFormControlInput1" class="form-label">Categorie de consultation<span
                                        style="color:red;">*</span></label>
                                <select class="form-select select2" data-toggle="select2" name="type_id" id="type_id">
                                    <option value="">Sélectionner le type de consultation</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif --}}

                        <div class="col-md-3">
                            <label for="exampleFormControlInput1" class="form-label">Prestations<span
                                    style="color:red;">*</span></label>
                            <select class="form-select select2" data-toggle="select2" name="prestation_id"
                                id="prestation_id" required>
                                <option>Sélectionner la prestation</option>
                                @foreach ($prestations as $prestation)
                                    <option value="{{ $prestation->id }}">{{ $prestation->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="control-label form-label">Statut de la consultation</label>
                            <select class="form-select" name="status" id="priority" required disabled>
                                <option value="pending" selected>En attente </option>
                                <option value="approved">Approuvé </option>
                                <option value="cancel">Rejetter</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Frais <span style="color:red;">*</span></label>
                            <input type="number" class="form-control" name="fees" id="fees" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Heure<span style="color:red;">*</span></label>
                            <input type="datetime-local" id="dateConsultation" class="form-control" name="date"
                                value="">
                        </div>

                    </div>
                    {{--
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="control-label form-label">Methode de paiement</label>
                            <select class="form-select" name="payement_mode">
                                <option value="espece">Espèce </option>
                                <option value="carte">Carte </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prochaine Consultation <span style="color:red;">*</span></label>
                            <input type="datetime-local" class="form-control" name="next_appointment" value="">
                        </div>

                    </div> --}}
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn w-100 btn-success">Ajouter une nouvelle consultation</button>
            </div>

            </form>
        </div>
    </div>

    </div>
@endsection

@push('extra-js')
    <script>
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            startDate: '-3d'
        });
        // SUPPRESSION
        function deleteModal(id) {

            Swal.fire({
                title: "Voulez-vous supprimer l'élément ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Oui ",
                cancelButtonText: "Non !",
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ url('contrats_details/delete') }}" + "/" + id;
                    Swal.fire(
                        "Suppression !",
                        "En cours de traitement ...",
                        "success"
                    )
                }
            });
        }
        // Chargement de la date par defaut
        window.addEventListener("load", function() {
            var now = new Date();
            var offset = now.getTimezoneOffset() * 60000;
            var adjustedDate = new Date(now.getTime() - offset);
            var formattedDate = adjustedDate.toISOString().substring(0, 16); // For minute precision
            var datetimeField = document.getElementById("dateConsultation");
            datetimeField.value = formattedDate;
        });
        //     $("#type_id").on("change", function() {
        //         alert('a')
        //         $.ajax({
        //             type: "GET",
        //             url: "{{ url('type_consultations/show') }}" + '/' + e_id,
        //             success: function(data) {
        // console.log(data)
        //             },
        //             error: function(data) {
        //                 // console.log('Error:', data);
        //             }
        //         });
        //     });
    </script>

    {{-- Patient modal submit --}}
    <script>
        $(document).ready(function() {

            $('#createPatientForm').on('submit', function(e) {
                e.preventDefault();
                let code = $('#code').val();
                let lastname = $('#lastname').val();
                let firstname = $('#firstname').val();
                let age = $('#age').val();
                let year_or_month = $('#year_or_month').val();
                let telephone1 = $('#telephone1').val();
                let langue = $('#langue').val();
                let genre = $('#genre').val();
                // alert(firstname);
                $.ajax({
                    url: "{{ route('patients.storePatient') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        code: code,
                        lastname: lastname,
                        firstname: firstname,
                        age: age,
                        year_or_month: year_or_month,
                        telephone1: telephone1,
                        genre: genre,
                        langue: langue
                    },
                    success: function(data) {

                        $('#createPatientForm').trigger("reset")
                        $('#standard-modal').modal('hide');
                        toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
                        $('#patient_id').append('<option value="' + data.id + '">' + data.code +
                                ' - ' + data.firstname + ' ' + data.lastname + '</option>')
                            .trigger('change').val(data.id);

                    },
                    error: function(data) {
                        console.log(data)
                    },
                    // processData: false,
                });

            });
            $('#prestation_id').on('change', function(e) {
                e.preventDefault();
                let prestation_id = $('#prestation_id').val();

                $.ajax({
                    type: "GET",
                    url: "{{ url('prestations/show_by_id') }}" + '/' + prestation_id,
                    success: function(data) {

                        $('#fees').val(data.price);
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });

            });
        });
    </script>
@endpush
