@extends('layouts.app2')

@section('title', 'Examen')

@section('css')

<style>
    input[data-switch]+label {
        width: 90px !important;
    }

    input[data-switch]:checked+label:after {
        left: 62px !important;
    }
</style>
@endsection



@section('content')

<div class="page-title-box">
    <div class="page-title-right mr-3">
        @if(isset($cashbox) && $cashbox->statut == 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Caisse fermée - </strong> Veuillez ouvrir la caisse avant de procéder à
            l'encaissement.
        </div>
        @endif
    </div>
    <h4 class="page-title">Demandes d'examen</h4>
</div>

<div class="">

    @include('layouts.alerts')

    {{-- @include('patients.create') --}}

    <div class="card my-3">
        <div class="card-header">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right mt-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#standard-modal">Ajouter un nouveau patient</button>
                    </div>
                    Ajouter une nouvelle demande d'examen
                </div>

            </div>

        </div>
        <div class="card-body">

            <form action="{{ route('test_order.store') }}" method="post" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Type d'examen<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" required id="type_examen"
                            name="type_examen">
                            <option>Sélectionner le type d'examen</option>
                            @forelse ($types_orders->reverse() as $type)
                            @if($type->id != 1)
                            <option value="{{ $type->id }}">{{ $type->title }}</option>
                            @endif
                            @empty
                            Ajouter un Type d'examen
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-6">
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

                    <div class="col-md-12">

                        <div class="examenReferenceSelect" style="display: none !important">
                            <label for="exampleFormControlInput1" class="form-label">Examen de Référence<span
                                    style="color:red;">*</span></label>
                            <select class="form-select select2" data-toggle="select2" name="examen_reference_select"
                                id="examen_reference_select">
                                <option value="">Sélectionner dans la liste</option>

                            </select>
                        </div>
                        <div class="examenReferenceInput mt-3" style="display: none !important">
                            <label for="exampleFormControlInput1" class="form-label">Examen de Référence<span
                                    style="color:red;">*</span></label>
                            <input type="text" name="examen_reference_input" class="form-control"
                                placeholder="Saisir l'examen de reference">
                        </div>

                    </div>
                </div>

                <div class="row mb-3">

                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Patient<span
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
                            <option value="{{ $doctor->name }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Hôpital de provenance<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="hospital_id" id="hospital_id"
                            required>
                            <option>Sélectionner le centre hospitalier de provenance</option>
                            @foreach ($hopitals as $hopital)
                            <option value="{{ $hopital->name }}">{{ $hopital->name }}</option>
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
                        <input type="date" class="form-control" name="prelevement_date" id="prelevement_date"
                            data-date-format="dd/mm/yyyy" required>
                    </div>
                    <div class="col-md-6">
                        <div class="">
                            <label for="example-fileinput" class="form-label">Pièce jointe</label>
                            <input type="file" name="examen_file" id="example-fileinput" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        {{-- <input type="checkbox" class="form-check-input" name="is_urgent" id=""> --}}
                        <label class="form-label">Cas urgent</label><br>
                        <input type="checkbox" id="switch3" class="form-control" name="is_urgent"
                            data-switch="success" />
                        <label for="switch3" data-on-label="Urgent" data-off-label="Normal"></label>
                    </div>

                    <div class="col-md-6">
                        {{-- <input type="checkbox" class="form-check-input" name="is_urgent" id=""> --}}
                        <label class="form-label">Option d'envoie des résultats</label><br>
                        <select class="form-select select2" data-toggle="select2" name="option" id="option" required>
                            <option value="">Sélectionner une option d'envoie</option>
                            <option value="0">Appel</option>
                            <option value="1">SMS</option>

                        </select>
                    </div>
                </div>

        </div>

        <div class="modal-footer">
            <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Ajouter une nouvelle demande d'examen</button>
        </div>

        </form>
    </div>
</div>

{{-- Modal --}}
{{-- <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter un nouveau patient</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form method="POST" id="createPatientForm" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Code</label>
                        <input type="text" name="code" id="code"
                            value="<?php echo substr(md5(rand(0, 1000000)), 0, 10); ?>" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Nom <span style="color:red;">*</span></label>
                        <input type="text" name="firstname" id="firstname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Prénom<span style="color:red;">*</span></label>
                        <input type="text" name="lastname" id="lastname" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Genre<span style="color:red;">*</span></label>
                        <select class="form-select" id="genre" name="genre" required>
                            <option value="">Sélectionner le genre</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="example-date" class="form-label">Date de naissance</label>
                        <input class="form-control" id="example-date" type="date" name="birthday">
                    </div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Age<span style="color:red;">*</span></label>
                        <input type="number" name="age" id="age" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Mois ou Années<span
                                style="color:red;">*</span></label>
                        <select class="form-select" id="year_or_month" name="year_or_month" required>
                            <option value="">Sélectionner entre mois ou ans</option>
                            <option value="0">Mois</option>
                            <option value="1">Ans</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Profession</label>
                        <input type="text" name="profession" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Contact 1<span style="color:red;">*</span></label>
                        <input type="tel" name="telephone1" id="telephone1" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Contact 2</label>
                        <input type="tel" name="telephone2" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Adresse<span style="color:red;">*</span></label>
                        <textarea type="text" name="adresse" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Langue parlée<span
                                style="color:red;">*</span></label>
                        <select class="form-select" id="langue" name="langue" required>
                            <option value="">Sélectionner une langue</option>
                            <option value="français">Français</option>
                            <option value="fon">Fon</option>
                            <option value="anglais">Anglais</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter un nouveau patient</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --> --}}


<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog" style="max-width: 100%; padding-left: 300px; margin-left:50px;">
        <div class="modal-content" style="width: 75%;;">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter un nouveau patient</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('patients.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Code</label>
                        <input type="text" name="code" value="<?php echo substr(md5(rand(0, 1000000)), 0, 10); ?>"
                            class="form-control" readonly>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label for="simpleinput" class="form-label">Nom <span style="color:red;">*</span></label>
                            <input type="text" name="firstname" class="form-control" required>
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label for="simpleinput" class="form-label">Prénom<span style="color:red;">*</span></label>
                            <input type="text" name="lastname" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label for="example-select" class="form-label">Genre<span
                                    style="color:red;">*</span></label>
                            <select class="form-select" id="example-select" name="genre" required>
                                <option value="">Sélectionner le genre</option>
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label for="example-select" class="form-label">Langue parlée<span
                                    style="color:red;">*</span></label>
                            <select class="form-select" id="langue" name="langue" required>
                                <option value="">Sélectionner une langue</option>
                                <option value="français">Français</option>
                                <option value="fon">Fon</option>
                                <option value="anglais">Anglais</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-4">
                            <label for="example-date" class="form-label">Date de naissance</label>
                            <input class="form-control" id="example-date" type="date" name="birthday">
                        </div>

                        <div class="mb-3 col-lg-4">
                            <label for="simpleinput" class="form-label">Age<span style="color:red;">*</span></label>
                            <input type="number" name="age" class="form-control" required>
                        </div>
                        <div class="mb-3 col-lg-4">
                            <label for="example-select" class="form-label">Mois ou Années<span
                                    style="color:red;">*</span></label>
                            <select class="form-select" id="year_or_month" name="year_or_month" required>
                                <option value="">Sélectionner entre mois ou ans</option>
                                <option value="0">Mois</option>
                                <option value="1">Ans</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-4">
                            <label for="simpleinput" class="form-label">Contact 1<span
                                    style="color:red;">*</span></label>
                            <input type="tel" name="telephone1" class="form-control" required>
                        </div>

                        <div class="mb-3 col-lg-4">
                            <label for="simpleinput" class="form-label">Contact 2</label>
                            <input type="tel" name="telephone2" class="form-control">
                        </div>

                        <div class="mb-3 col-lg-4">
                            <label for="simpleinput" class="form-label">Profession</label>
                            <input type="text" name="profession" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Adresse<span style="color:red;">*</span></label>
                        <textarea type="text" name="adresse" class="form-control" required></textarea>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter un nouveau patient</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


</div>
@endsection



@push('extra-js')
<script>
    var baseUrl = "{{ url('/') }}"
        var ROUTESTOREPATIENT = "{{ route('patients.storePatient') }}"
        var TOKENSTOREPATIENT = "{{ csrf_token() }}"
        var ROUTESTOREHOSPITAL = "{{ route('hopitals.storeHospital') }}"
        var TOKENSTOREHOSPITAL = "{{ csrf_token() }}"
        var ROUTESTOREDOCTOR = "{{ route('doctors.storeDoctor') }}"
        var TOKENSTOREDOCTOR = "{{ csrf_token() }}"
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
</script>
<script src="{{ asset('viewjs/test/order/create.js') }}"></script>
@endpush