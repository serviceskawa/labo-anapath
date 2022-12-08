@extends('layouts.app2')

@section('title', 'Créer un examen')

@section('content')
<div class="">


    @include('layouts.alerts')
    <div class="card my-3">
        <div class="card-header">
            Ajouter une nouvelle demande d'examen
        </div>
        <div class="card-body">

            <form action="{{ route('test_order.store') }}" method="post" autocomplete="off">
                @csrf
                <div class="row mb-3">
                    <div class="alert alert-warning">
                        <strong>Attention! </strong><small>Si un élément ne s'affiche pas dans la liste déroulante,
                            ajoutez-le à la section correspondante et rechargez la page avant de procéder.</small>
                    </div>

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Patient<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="patient_id" required>
                            <option>Sélectionner le nom du patient</option>
                            @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->code }} - {{ $patient->firstname }}
                                {{ $patient->lastname }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Médecin traitant<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="doctor_id" required>
                            <option>Sélectionner le médecin traitant</option>
                            @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>


                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Hôpital de provenance<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="hospital_id" required>
                            <option>Sélectionner le centre hospitalier de provenance</option>
                            @foreach ($hopitals as $hopital)
                            <option value="{{ $hopital->id }}">{{ $hopital->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleFormControlInput1" class="form-label">Référence hôpital</label>
                            <input type="text" class="form-control" name="reference_hopital"
                                placeholder="Le numéro de référence qui se trouve sur le bon d'examen du patient.">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Date prélèvement<span style="color:red;">*</span></label>
                        <input type="text" class="form-control datepicker" name="prelevement_date" id="prelevement_date"
                            data-date-format="dd/mm/yyyy" required>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Pièce jointe</label>
                            <input type="file" id="example-fileinput" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Contrat<span
                            style="color:red;">*</span></label>
                    <select class="form-select select2" data-toggle="select2" required name="contrat_id">
                        <option>Sélectionner le contrat</option>
                        @forelse ($contrats as $contrat)
                        <option value="{{ $contrat->id }}">{{ $contrat->name }}</option>
                        @empty
                        Ajouter un contrat
                        @endforelse
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="checkbox" class="form-check-input" name="is_urgent" id="">
                    <label class="form-label">Cas urgent</label>
                </div>

        </div>


        <div class="modal-footer">
            <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Ajouter une nouvelle demande d'examen</button>
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





        //EDITION
        function edit(id) {
            var e_id = id;

            // Populate Data in Edit Modal Form
            $.ajax({
                type: "GET",
                url: "{{ url('getcontratdetails') }}" + '/' + e_id,
                success: function(data) {

                    $('#category_test_id2').val(data.category_test_id).change();
                    $('#pourcentage2').val(data.pourcentage);
                    $('#contrat_id2').val(data.contrat_id);
                    $('#contrat_details_id2').val(data.id);



                    console.log(data);
                    $('#editModal').modal('show');
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }
</script>
@endpush