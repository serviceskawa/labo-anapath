@extends('layouts.app2')

@section('title', 'Créer une consultation')

@section('content')
<div class="">

    @include('layouts.alerts')

    <div class="card my-3">
        <div class="card-header">
            <div class="col-12">
                <div class="page-title-box">
                    {{-- <div class="page-title-right mt-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#standard-modal">Ajouter un nouveau patient</button>
                    </div> --}}
                    Créer une consultation
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
                        <label for="exampleFormControlInput1" class="form-label">Médecin traitant<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="doctor_id" id="doctor_id"
                            required>
                            <option>Sélectionner le médecin traitant</option>
                            @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="exampleFormControlInput1" class="form-label">Type de consultation<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="type_id" id="type_id" required>
                            <option>Sélectionner le type de consultation</option>
                            @foreach ($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="control-label form-label">Status</label>
                        <select class="form-select" name="status" id="priority" required="">
                            <option value="pending">Pending </option>
                            <option value="approved">Approuvé </option>
                            <option value="cancel">Rejetter</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Heure <span style="color:red;">*</span></label>
                        <input type="datetime-local" class="form-control" name="date" value="">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Frais <span style="color:red;">*</span></label>
                        <input type="number" class="form-control" name="fees" value="">
                    </div>
                </div>

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

                </div>
        </div>


        <div class="modal-footer">
            <button type="submit" class="btn w-100 btn-warning">Enregistrer</button>
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

@endpush