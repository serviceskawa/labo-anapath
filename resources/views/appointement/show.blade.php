@extends('layouts.app2')

@section('title', 'Mettre à jour un Rendez-vous')

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
                    Mettre à jour un rendez-vous
                </div>


            </div>

        </div>
        <div class="card-body">

            <form action="{{ route('appointement.update', $appointement->id) }}" method="post" autocomplete="off"
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
                            <option value="{{ $patient->id }}" {{ $appointement->patient_id == $patient->id ? 'selected'
                                :
                                ''}}>{{ $patient->code }} - {{ $patient->firstname }}
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
                            <option value="{{ $doctor->id }}" {{ $appointement->doctor_id == $doctor->id ? 'selected' :
                                ''}}>{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Heure de rendez-vous<span style="color:red;">*</span></label>
                        <input type="datetime-local" class="form-control" name="date" value="{{$appointement->date}}">
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="control-label form-label">Priorité</label>
                            <select class="form-select" name="priority" id="priority" required="">
                                <option value="normal" {{$appointement->priority == "normal" ? 'selected' : ''}}>Normal
                                </option>
                                <option value="urgent" {{$appointement->priority == "urgent" ? 'selected' : ''}}>Urgent
                                </option>
                                <option value="tres urgent" {{$appointement->priority == "tres urgent" ? 'selected' :
                                    ''}}>Très urgent</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label form-label">Message</label>
                            <textarea name="message" class="form-control" id="" cols="30"
                                rows="10"> {{$appointement->message}} </textarea>
                        </div>
                    </div>
                </div>

        </div>


        <div class="modal-footer">
            <button type="submit" class="btn w-100 btn-warning">Mettre à jour</button>
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


</script>

@endpush