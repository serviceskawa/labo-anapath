@extends('layouts.app2')

@section('title', 'Details examen')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css"
    integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Inclure les fichiers CSS de Dropzone.js via CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/dropzone.min.css" />
<style>
.simple-button {
    background-color: transparent;
    border: none;
    color: #dc3545;
    /* Couleur du texte pour les boutons de suppression */
    cursor: pointer;
}

.simple-link-button {
    background-color: transparent;
    border: none;
    color: #0d6efd;
    /* Couleur du texte pour les boutons de lien */
    cursor: pointer;
}
</style>
@endsection

@section('content')
<div class="">

    @include('layouts.alerts')


    {{-- @include('examens.details.create') --}}

    {{-- Bloc pour modifier les demandes d'examan --}}
    <div class="card my-3">
        @if ($test_order->status == 1)
        <a href="{{ route('report.show', empty($test_order->report->id) ? '' : $test_order->report->id) }}"
            class="btn btn-success w-full">CONSULTEZ LE
            COMPTE RENDU</a>
        @endif

        @include('examens.details.edit')
        <div class="card-header">
            <div class="col-12">
                <div class="page-title-box">
                    @if ($test_order->status != 1)
                    <div class="page-title-right mt-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#standard-modal">Ajouter un nouveau patient</button>
                    </div>
                    @endif

                    Demande d'examen : <strong>{{ $test_order->code }}</strong>
                </div>

            </div>
        </div>

        @php
        $report_search = App\Models\Report::where('test_order_id', $test_order->id)->first();
        @endphp
        {{-- Fusion de read et updaye --}}
        <form action="{{ route('test_order.update', $test_order->id) }}" method="post" autocomplete="off"
            enctype="multipart/form-data">
            <div class="card-body">

                @csrf
                <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Type d'examen<span
                                style="color:red;">*</span></label>

                        @if ($report_search)
                        @if ($report_search->status == 0)
                        <select class="form-select select2" data-toggle="select2" required id="type_examen"
                            name="type_examen_id">
                            <option>Sélectionner le type d'examen</option>
                            @forelse ($types_orders as $type)
                            <option {{ $test_order->type_order_id == $type->id ? 'selected' : '' }}
                                value="{{ $type->id }}">{{ $type->title }}</option>

                            @empty
                            Ajouter un Type d'examen
                            @endforelse
                        </select>
                        @else
                        @foreach ($types_orders as $type)
                        @if ($test_order->type_order_id == $type->id)
                        <input type="text" name="type_examen" class="form-control" readonly value="{{ $type->title }}">
                        <input type="text" name="type_examen_id" class="form-control" readonly value="{{ $type->id }}"
                            hidden>
                        @endif
                        @endforeach

                        @endif
                        @else
                        <select class="form-select select2" data-toggle="select2" required id="type_examen"
                            name="type_examen_id">
                            <option>Sélectionner le type d'examen</option>
                            @forelse ($types_orders as $type)
                            <option {{ $test_order->type_order_id == $type->id ? 'selected' : '' }}
                                value="{{ $type->id }}">{{ $type->title }}</option>

                            @empty
                            Ajouter un Type d'examen
                            @endforelse
                        </select>
                        @endif

                    </div>
                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Contrat<span
                                style="color:red;">*</span></label>
                        @forelse ($contrats as $contrat)

                        @if ($contrat->status == 'ACTIF' && $contrat->is_close == 0)
                        @php
                        $a = App\Models\TestOrder::where('contrat_id', $contrat->id)->get();
                        @endphp

                        @if ($a->count() < $contrat->nbr_tests || $contrat->nbr_tests == -1)
                            <option>Tvfff</option>
                            <option value="{{ $contrat->id }}"
                                {{ $test_order->contrat_id == $contrat->id ? 'selected' : '' }}>{{ $contrat->name }}
                            </option>
                            @endif
                            @endif
                            @empty
                            Ajouter un contrat
                            @endforelse

                            <!-- @if ($report_search)
                        @if ($report_search->status == 0)
                        <select class="form-select select2" data-toggle="select2" required name="contrat_id"
                            id="contrat_id">
                            <option>Sélectionner le contrat</option>
                            @forelse ($contrats as $contrat)
                            @if ($contrat->status == 'ACTIF' && $contrat->invoice_unique == 1 && $contrat->is_close ==
                            0)
                            @php
                            $a = App\Models\TestOrder::where('contrat_id', $contrat->id)->get();
                            @endphp

                            @if ($a->count() < $contrat->nbr_tests || $contrat->nbr_tests == -1)
                                <option value="{{ $contrat->id }}"
                                    {{ $test_order->contrat_id == $contrat->id ? 'selected' : '' }}>
                                    {{ $contrat->name }}
                                </option>
                                @endif
                                @endif
                                @empty
                                Ajouter un contrat
                                @endforelse
                        </select>
                        @else
                        @foreach ($contrats as $contrat)
                        @if ($test_order->contrat_id == $contrat->id)
                        <input type="text" id="contrat_id" class="form-control" readonly value="{{ $contrat->name }}">
                        <input type="text" name="contrat_id" id="contrat_id" class="form-control" readonly
                            value="{{ $contrat->id }}" hidden>
                        @endif
                        @endforeach
                        @endif
                        @else
                        <select class="form-select select2" data-toggle="select2" required name="contrat_id"
                            id="contrat_id">
                            <option>Sélectionner le contrat</option>
                            @forelse ($contrats as $contrat)

                            @if ($contrat->status == 'ACTIF' && $contrat->invoice_unique == 1 && $contrat->is_close ==
                            0)
                            @php
                            $a = App\Models\TestOrder::where('contrat_id', $contrat->id)->get();
                            @endphp

                            @if ($a->count() < $contrat->nbr_tests || $contrat->nbr_tests == -1)
                                <option value="{{ $contrat->id }}"
                                    {{ $test_order->contrat_id == $contrat->id ? 'selected' : '' }}>
                                    {{ $contrat->name }}
                                </option>
                                @endif
                                @endif
                                @empty
                                Ajouter un contrat
                                @endforelse
                        </select>
                        @endif -->
                    </div>
                </div>
                <div class="col-md-12 my-3">

                    <div class="examenReferenceInputExterne mt-3" style="display: none !important">
                        <label for="exampleFormControlInput1" class="form-label">Examen de Référence<span
                                style="color:red;">*</span></label>
                        <input type="text" name="examen_reference_input" class="form-control" required
                            id="examen_reference_input" placeholder="Saisir l'examen de reference"
                            value="{{ $test_order->test_affiliate ?? old('test_affiliate') }}">
                    </div>

                    <div class="examenReferenceInputInterne mt-3" style="display: none !important">
                        <label for="exampleFormControlInput1" class="form-label">Examen de Référence<span
                                style="color:red;">*</span></label>

                        <select class="form-select select2" data-toggle="select2" required id="examen_reference_select"
                            name="examen_reference_select">
                            <option value="" disabled selected>Sélectionner l'examen de Référence</option>
                            @forelse ($test_orders as $TestOrder)
                            <option value="{{ $TestOrder->id }}"
                                {{ $TestOrder->code == $test_order->test_affiliate ? 'selected' : '' }}>
                                {{ $TestOrder->code }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="row mb-3">

                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Patient <span
                                style="color:red;">*</span></label>

                        @if ($report_search)
                        @if ($report_search->status == 0)
                        <select class="form-select select2" data-toggle="select2" name="patient_id" id="patient_id"
                            required>
                            <option>Sélectionner le nom du patient</option>
                            @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}"
                                {{ $test_order->patient_id == $patient->id ? 'selected' : '' }}>
                                {{ $patient->code }} - {{ $patient->firstname }} {{ $patient->lastname }}
                            </option>
                            @endforeach
                        </select>
                        @else
                        @foreach ($patients as $patient)
                        @if ($test_order->patient_id == $patient->id)
                        <input type="text" name="patient_id" id="patient_id" class="form-control" readonly
                            value="{{ $patient->code }} - {{ $patient->firstname }} {{ $patient->lastname }}">
                        <input type="text" name="patient_id" id="patient_id" class="form-control" readonly
                            value="{{ $patient->id }}" hidden>
                        @endif
                        @endforeach
                        @endif
                        @else
                        <select class="form-select select2" data-toggle="select2" name="patient_id" id="patient_id"
                            required>
                            <option>Sélectionner le nom du patient</option>
                            @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}"
                                {{ $test_order->patient_id == $patient->id ? 'selected' : '' }}>
                                {{ $patient->code }} - {{ $patient->firstname }} {{ $patient->lastname }}
                            </option>
                            @endforeach
                        </select>
                        @endif


                    </div>

                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Médecin traitant<span
                                style="color:red;">*</span></label>


                        @if ($report_search)
                        @if ($report_search->status == 0)
                        <select class="form-select select2" data-toggle="select2" name="doctor_id" id="doctor_id"
                            required>
                            <option>Sélectionner le médecin traitant</option>
                            @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->name }}"
                                {{ $test_order->doctor_id == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                            @endforeach
                        </select>
                        @else
                        @foreach ($doctors as $doctor)
                        @if ($test_order->doctor_id == $doctor->id)
                        {{-- <input type="text" name="doctor_id" id="doctor_id" class="form-control"
                                                value="{{ $doctor->name }}"> --}}

                        <select class="form-select select2" data-toggle="select2" name="doctor_id" id="doctor_id"
                            required>
                            <option>Sélectionner le médecin traitant</option>
                            @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->name }}"
                                {{ $test_order->doctor_id == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                            @endforeach
                        </select>
                        @endif
                        @endforeach

                        @endif
                        @else
                        <select class="form-select select2" data-toggle="select2" name="doctor_id" id="doctor_id"
                            required>
                            <option>Sélectionner le médecin traitant</option>
                            @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->name }}"
                                {{ $test_order->doctor_id == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                            @endforeach
                        </select>
                        @endif
                    </div>

                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Hôpital de provenance<span
                                style="color:red;">*</span></label>


                        @if ($report_search)
                        @if ($report_search->status == 0)
                        <select class="form-select select2" data-toggle="select2" name="hospital_id" id="hospital_id"
                            required>
                            <option>Sélectionner le centre hospitalier de provenance</option>
                            @foreach ($hopitals as $hopital)
                            <option value="{{ $hopital->name }}"
                                {{ $test_order->hospital_id == $hopital->id ? 'selected' : '' }}>
                                {{ $hopital->name }}
                            </option>
                            @endforeach
                        </select>
                        @else
                        @foreach ($hopitals as $hopital)
                        @if ($test_order->hospital_id == $hopital->id)
                        <input type="text" name="hospital_id" id="hospital_id" class="form-control" readonly
                            value="{{ $hopital->name }}">
                        @endif
                        @endforeach

                        @endif
                        @else
                        <select class="form-select select2" data-toggle="select2" name="hospital_id" id="hospital_id"
                            required>
                            <option>Sélectionner le centre hospitalier de provenance</option>
                            @foreach ($hopitals as $hopital)
                            <option value="{{ $hopital->name }}"
                                {{ $test_order->hospital_id == $hopital->id ? 'selected' : '' }}>
                                {{ $hopital->name }}
                            </option>
                            @endforeach
                        </select>
                        @endif

                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleFormControlInput1" class="form-label">Référence hôpital</label>
                            @if ($report_search)
                            <input type="text" {{ $report_search->status == 1 ? 'readonly' : '' }} class="form-control"
                                name="reference_hopital" value="{{ $test_order->reference_hopital }}">
                            @else
                            <input type="text" class="form-control" name="reference_hopital"
                                value="{{ $test_order->reference_hopital }}">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Date prélèvement<span style="color:red;">*</span></label>
                        @if ($report_search)
                        <input type="date" {{ $report_search->status == 1 ? 'readonly' : '' }} class="form-control"
                            name="prelevement_date" id="prelevement_date" data-date-format="dd/mm/yyyy"
                            value="{{ $test_order->prelevement_date }}" required>
                        @else
                        <input type="date" class="form-control" name="prelevement_date" id="prelevement_date"
                            data-date-format="dd/mm/yyyy" value="{{ $test_order->prelevement_date }}" required>
                        @endif

                        <label class="form-label mt-3">Affecter à</label>

                        @if (!empty($test_order->attribuate_doctor_id))
                        @if ($report_search)
                        @if ($report_search->status == 0)
                        <select name="attribuate_doctor_id" id="" class="form-control">
                            <option value="">Selectionnez un docteur</option>
                            @foreach (getUsersByRole('docteur') as $item)
                            <option value="{{ $item->id }}"
                                {{ $test_order->attribuate_doctor_id == $item->id ? 'selected' : '' }}>
                                {{ $item->lastname }} {{ $item->firstname }}
                            </option>
                            @endforeach
                        </select>
                        @else
                        @foreach (getUsersByRole('docteur') as $item)
                        @if ($test_order->attribuate_doctor_id == $item->id)
                        <input type="text" id="" class="form-control" readonly
                            value="{{ $item->lastname }} {{ $item->firstname }}">

                        <input type="text" name="attribuate_doctor_id" id="" class="form-control" readonly
                            value="{{ $item->id }}" hidden>
                        @endif
                        @endforeach

                        @endif
                        @else
                        <select name="attribuate_doctor_id" id="" class="form-control">
                            <option value="">Selectionnez un docteur</option>
                            @foreach (getUsersByRole('docteur') as $item)
                            <option value="{{ $item->id }}"
                                {{ $test_order->attribuate_doctor_id == $item->id ? 'selected' : '' }}>
                                {{ $item->lastname }} {{ $item->firstname }}
                            </option>
                            @endforeach
                        </select>
                        @endif
                        @else
                        <select name="attribuate_doctor_id" id="" class="form-control">
                            <option value="">Selectionnez un docteur</option>
                            @foreach (getUsersByRole('docteur') as $item)
                            <option value="{{ $item->id }}"
                                {{ $test_order->attribuate_doctor_id == $item->id ? 'selected' : '' }}>
                                {{ $item->lastname }} {{ $item->firstname }}
                            </option>
                            @endforeach
                        </select>
                        @endif


                        <div class="mt-3">
                            {{-- <input type="checkbox" class="form-check-input" name="is_urgent" id=""> --}}
                            <label class="form-label">Option d'envoie des résultats</label><br>
                            <select class="form-select select2" data-toggle="select2" name="option" id="option"
                                required>
                                <option value="">Sélectionner une option d'envoie</option>
                                <option {{ $test_order->option ? '' : 'selected' }} value="0">Appel</option>
                                <option {{ $test_order->option ? 'selected' : '' }} value="1">SMS</option>

                            </select>
                        </div>

                        <label class="form-label mt-3">Cas urgent</label> <br>
                        @if ($report_search)
                        <input type="checkbox" id="switch3" {{ $report_search->status == 1 ? 'readonly' : '' }}
                            class="form-control" name="is_urgent" {{ $test_order->is_urgent != 0 ? 'checked' : '' }}
                            data-switch="success" />
                        @else
                        <input type="checkbox" id="switch3" class="form-control" name="is_urgent"
                            {{ $test_order->is_urgent != 0 ? 'checked' : '' }} data-switch="success" />
                        @endif
                        <label for="switch3" data-on-label="Urgent" data-off-label="Normal"></label>

                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Pièce jointe</label>
                            <input type="file" name="examen_file" id="example-fileinput" class="form-control dropify"
                                data-default-file="{{ $test_order ? Storage::url($test_order->examen_file) : '' }}">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn w-100 btn-warning">Mettre à jour</button>
                </div>
        </form>
    </div>



    {{-- Debut du code pour la GALLERIE --}}
    <div class="card my-3">
        <div class="card mb-md-0 mb-3">
            <div class="card-header">
                Gallerie des images
            </div>
            <h5 class="card-title mb-0"></h5>

            <div class="card-body">
                <form action="{{ route('test_order.createimagegallerie', $test_order->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <label for="formFileMultiple" class="form-label">Ajouter l'image à la gallerie</label>
                    <div class="row d-flex align-items-center">
                        <div class="col-md-11">
                            <input class="form-control" type="file" name="files_name[]" id="formFileMultiple" multiple>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-success">Ajouter</button>
                        </div>
                    </div>
                </form>

                @if (isset($test_order->files_name))
                <div class="col-md-6 mt-2">
                    <div class="mb-3">
                        <div>
                            <?php $filenames = json_decode($test_order->files_name); ?>
                            @forelse ($filenames as $index => $filename)
                            <div class="row">
                                <div class="col">
                                    Image{{ $loop->iteration }}&nbsp;
                                    <a href="{{ asset('storage/' . $filename) }}" download>
                                        <u style="font-size: 15px;">Voir</u>
                                    </a>
                                    {{-- <img src="{{ asset('storage/examen_images' . $filename) }}" width="50" /> --}}

                                    <form class="d-inline-block delete-form"
                                        action="{{ route('test_order.deleteimagegallerie', ['index' => $index, 'test_order' => $test_order->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="simple-button" type="submit"
                                            data-confirm="Êtes-vous sûr de vouloir supprimer cette image?"
                                            class="btn btn-sm btn-danger"><u>Supprimer</u></button>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <!-- Aucun fichier trouvé -->
                            @endforelse
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    {{-- Fin du bloc qui permet d'afficher la zone d'insertion des images de la galerie --}}


    {{-- Debut du bloc pour faire les l'ajout des demandes --}}
    <div class="card mb-md-0 mb-3">
        <div class="card-header">
            Liste des examens demandés
        </div>
        <h5 class="card-title mb-0"></h5>

        <div class="card-body">

            <!-- Ajouter un examen | si le statut de la demande est 1 alors on peut plus ajouter de nouveau examen dans la demande-->

            @if ($test_order->invoice)
            @if ($test_order->invoice->paid != 1)
            <form method="POST" id="addDetailForm" autocomplete="off">
                @csrf
                <div class="row d-flex align-items-end">
                    <div class="col-md-4 col-12">
                        <input type="hidden" name="test_order_id" id="test_order_id" value="{{ $test_order->id }}"
                            class="form-control">

                        <div class="mb-3">
                            <label for="example-select" class="form-label">Examen</label>
                            <select class="form-select select2" data-toggle="select2" id="test_id" name="test_id"
                                required onchange="getTest()">
                                <option>Sélectionner l'examen</option>
                                @foreach ($tests as $test)
                                <option data-category_test_id="{{ $test->category_test_id }}" value="{{ $test->id }}">
                                    {{ $test->name }}
                                </option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">

                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Prix</label>
                            <input type="text" name="price" id="price" class="form-control" required readonly>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Remise</label>
                            <input type="text" name="remise" id="remise" class="form-control" required readonly>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="mb-3">
                            <label for="example-select" class="form-label">Total</label>

                            <input type="text" name="total" id="total" class="form-control" required readonly>
                        </div>
                    </div>

                    <div class="col-md-2 col-12">
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary" id="add_detail">Ajouter</button>
                        </div>
                    </div>
                </div>
            </form>
            @endif
            @else
            <form method="POST" id="addDetailForm" autocomplete="off">
                @csrf
                <div class="row d-flex align-items-end">
                    <div class="col-md-4 col-12">
                        <input type="hidden" name="test_order_id" id="test_order_id" value="{{ $test_order->id }}"
                            class="form-control">

                        <div class="mb-3">
                            <label for="example-select" class="form-label">Examen</label>
                            <select class="form-select select2" data-toggle="select2" id="test_id" name="test_id"
                                required onchange="getTest()">
                                <option>Sélectionner l'examen</option>
                                @foreach ($tests as $test)
                                <option data-category_test_id="{{ $test->category_test_id }}" value="{{ $test->id }}">
                                    {{ $test->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">

                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Prix</label>
                            <input type="text" name="price" id="price" class="form-control" required readonly>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Remise</label>
                            <input type="text" name="remise" id="remise" class="form-control" required readonly>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="mb-3">
                            <label for="example-select" class="form-label">Total</label>

                            <input type="text" name="total" id="total" class="form-control" required readonly>
                        </div>
                    </div>

                    <div class="col-md-2 col-12">
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary" id="add_detail">Ajouter</button>
                        </div>
                    </div>
                </div>
            </form>
            @endif

            <div id="cardCollpase1" class="show collapse pt-3">

                <table id="datatable1" class="detail-list-table table-striped dt-responsive nowrap w-100 table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Examen</th>
                            <th>Prix</th>
                            <th>Remise</th>
                            <th>Montant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <td colspan="1" class="text-right">
                                <strong>Total:</strong>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>

                            <td id="val">
                                <input type="number" id="estimated_ammount" class="estimated_ammount" value="0"
                                    readonly>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="row mx-3 mt-2">
                    @if ($test_order->invoice)
                    @if ($test_order->invoice->paid != 1)
                    <a type="submit" href="{{ route('test_order.updatestatus', $test_order->id) }}" id="finalisationBtn"
                        class="btn btn-info disabled w-full">ENREGISTRER</a>
                    @else
                    <a href="{{ route('report.show', empty($test_order->report->id) ? '' : $test_order->report->id) }}"
                        class="btn btn-success w-full">CONSULTEZ LE
                        COMPTE RENDU</a>
                    @endif
                    @else
                    <a type="submit" href="{{ route('test_order.updatestatus', $test_order->id) }}" id="finalisationBtn"
                        class="btn btn-info disabled w-full">ENREGISTRER</a>
                    @endif

                </div>
            </div>

        </div>
    </div>


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
                                <label for="simpleinput" class="form-label">Nom <span
                                        style="color:red;">*</span></label>
                                <input type="text" name="firstname" class="form-control" required>
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Prénom<span
                                        style="color:red;">*</span></label>
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
    </div>

</div>
@php
$code_testOrder = $test_order->code;
$code_testInvoice = $test_order->invoice;
@endphp
@endsection

@push('extra-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
    integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.js"></script>
<script>
var test_order = @json($test_order);
var test_order_code = @json($code_testOrder);
var invoiceTest = @json($code_testInvoice);
var ROUTESTOREDETAILTESTORDER = "{{ route('details_test_order.store') }}";

var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

var baseUrl = "{{ url('/') }}"
var TOKENDETAILDELETE = "{{ csrf_token() }}"
var ROUTEUPDATEORDER = "{{ route('test_order.updateorder') }}"
var TOKENUPDATEORDER = "{{ csrf_token() }}"
var ROUTESTOREDETAILTESTORDER = "{{ route('details_test_order.store') }}"
var TOKENSTOREDETAILTESTORDER = "{{ csrf_token() }}"
var ROUTEGETREMISE = "{{ route('examens.getTestAndRemise') }}"
var TOKENGETREMISE = "{{ csrf_token() }}"
var ROUTESTOREPATIENT = "{{ route('patients.storePatient') }}"
var TOKENSTOREPATIENT = "{{ csrf_token() }}"
var ROUTESTOREHOSPITAL = "{{ route('hopitals.storeHospital') }}"
var TOKENSTOREHOSPITAL = "{{ csrf_token() }}"
var ROUTESTOREDOCTOR = "{{ route('doctors.storeDoctor') }}"
var TOKENSTOREDOCTOR = "{{ csrf_token() }}"
var ROUTEFILEUPLOAD = "{{ route('images.upload') }}"
var TOKENGETFILEUPDATE = $('meta[name="csrf-token"]').attr('content')
</script>
<script src="{{ asset('viewjs/test/order/detail.js') }}"></script>
<!-- Inclure les fichiers JavaScript de Dropzone.js via CDN -->


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const deleteButtons = document.querySelectorAll("[data-confirm]");
    deleteButtons.forEach(button => {
        button.addEventListener("click", function(event) {
            event.preventDefault();
            const confirmMessage = this.getAttribute("data-confirm");
            Swal.fire({
                title: 'Confirmation',
                text: confirmMessage,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer!',
                cancelButtonText: 'Annuler' // Ici, nous changeons le texte du bouton "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.closest("form").submit();
                }
            });
        });
    });
});
</script>
@endpush