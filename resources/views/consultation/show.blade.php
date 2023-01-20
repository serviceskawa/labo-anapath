@extends('layouts.app2')

@section('title', 'Mettre à jour une consultation')

@section('css')
<link href="{{ asset('/adminassets/css/vendor/quill.core.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/adminassets/css/vendor/quill.snow.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css"
    integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    .ck-editor__editable[role="textbox"] {
        /* editing area */
        min-height: 200px;
    }
</style>
@endsection

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
                    Mettre à jour une consultation
                </div>


            </div>

        </div>
        <div class="card-body">

            <form action="{{ route('consultation.update', $consultation->id) }}" method="post" autocomplete="off"
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
                            <option value="{{ $patient->id }}" {{ $consultation->patient_id == $patient->id ? 'selected'
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
                            <option value="{{ $doctor->id }}" {{ $consultation->doctor_id == $doctor->id ? 'selected'
                                :
                                ''}}>{{ $doctor->name }}</option>
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
                            <option value="{{ $type->id }}" {{ $consultation->type_consultation_id == $type->id ?
                                'selected' : ''}}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="control-label form-label">Status</label>
                        <select class="form-select" name="status" id="priority" required="">
                            <option value="pending" {{$consultation->status == "pending" ? 'selected' : ''}}>Pending
                            </option>
                            <option value="approved" {{$consultation->status == "approved" ? 'selected' : ''}}>Approuvé
                            </option>
                            <option value="cancel" {{$consultation->status == "cancel" ? 'selected' : ''}}>Rejetter
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Heure <span style="color:red;">*</span></label>
                        <input type="datetime-local" class="form-control" name="date" value="{{$consultation->date}}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Frais <span style="color:red;">*</span></label>
                        <input type="number" class="form-control" name="fees" value="{{$consultation->fees}}">
                    </div>
                </div>

                <div class="row mb-3">

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label form-label">Motif</label>
                            <textarea name="motif" class="form-control" id="" cols="30"
                                rows="5"> {{$consultation->motif}} </textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label form-label">Anamnèse</label>
                            <textarea name="anamnese" class="form-control" id="" cols="30"
                                rows="5">{{$consultation->anamnese}}</textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label form-label">Antécédent</label>
                            <textarea name="antecedent" class="form-control" id="" cols="30"
                                rows="5"> {{$consultation->antecedent}} </textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label form-label">Examen physique</label>
                            <textarea name="examen_physique" class="form-control" id="" cols="30"
                                rows="5">{{$consultation->examen_physique}}</textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label form-label">Diagnostic</label>
                            <textarea name="diagnostic" class="form-control" id="" cols="30"
                                rows="5">{{$consultation->diagnostic}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="control-label form-label">Methode de paiement</label>
                        <select class="form-select" name="payement_mode">
                            <option value="espece" {{$consultation->payement_mode == "espece" ? 'selected' : ''}}>Espèce
                            </option>
                            <option value="carte" {{$consultation->payement_mode == "carte" ? 'selected' : ''}}>Carte
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prochaine Consultation <span style="color:red;">*</span></label>
                        <input type="datetime-local" class="form-control" name="next_appointment"
                            value="{{$consultation->next_appointment}}">
                    </div>

                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="control-label form-label">Image</label>
                        <input type="file" class="dropify" name="favicon"
                            data-default-file="{{ $consultation ? Storage::url($consultation->favicon) : '' }}"
                            data-max-file-size="3M" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Comment<span style="color:red;">*</span></label>
                        <textarea name="comment" class="form-control" id="" cols="30" rows="5"></textarea>
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
<script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
    integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('.dropify').dropify();
</script>
<script>
    ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
</script>
<script>
    ClassicEditor
            .create(document.querySelector('#editor2'))
            .catch(error => {
                console.error(error);
            });
</script>
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