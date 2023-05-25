@extends('layouts.app2')

@section('title', 'Details examen')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css"
        integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    <div class="">

        @include('layouts.alerts')


        {{-- @include('examens.details.create') --}}

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

                            @if ($test_order->invoice)
                                @if ($test_order->invoice->paid != 1)
                                    <select class="form-select select2" data-toggle="select2" required id="type_examen"
                                        name="type_examen">
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
                                            <input type="text" name="type_examen" class="form-control" readonly
                                                value="{{ $type->title }}">
                                        @endif
                                    @endforeach

                                @endif
                            @else
                                <select class="form-select select2" data-toggle="select2" required id="type_examen"
                                    name="type_examen">
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

                            @if ($test_order->invoice)
                                @if ($test_order->invoice->paid != 1)
                                    <select class="form-select select2" data-toggle="select2" required name="contrat_id"
                                        id="contrat_id">
                                        <option>Sélectionner le contrat</option>
                                        @forelse ($contrats as $contrat)
                                            <option value="{{ $contrat->id }}"
                                                {{ $test_order->contrat_id == $contrat->id ? 'selected' : '' }}>
                                                {{ $contrat->name }}</option>
                                        @empty
                                            Ajouter un contrat
                                        @endforelse
                                    </select>
                                @else
                                    @foreach ($contrats as $contrat)
                                        @if ($test_order->contrat_id == $contrat->id)
                                            <input type="text" name="contrat_id" id="contrat_id" class="form-control"
                                                readonly value="{{ $contrat->name }}">
                                        @endif
                                    @endforeach

                                @endif
                            @else
                                <select class="form-select select2" data-toggle="select2" required name="contrat_id"
                                    id="contrat_id">
                                    <option>Sélectionner le contrat</option>
                                    @forelse ($contrats as $contrat)
                                        <option value="{{ $contrat->id }}"
                                            {{ $test_order->contrat_id == $contrat->id ? 'selected' : '' }}>
                                            {{ $contrat->name }}</option>
                                    @empty
                                        Ajouter un contrat
                                    @endforelse
                                </select>
                            @endif


                        </div>
                    </div>
                    <div class="col-md-12 my-3">

                        <div class="examenReferenceSelect" style="display: none !important">
                            <label for="exampleFormControlInput1" class="form-label">Examen de Référence<span
                                    style="color:red;">*</span></label>
                            <select class="form-select select2" data-toggle="select2" name="examen_reference_select"
                                id="examen_reference_select">
                                <option value="">Sélectionner dans la liste</option>
                            </select>
                        </div>
                        <div class="examenReferenceInput" style="display: none !important">
                            <label for="exampleFormControlInput1" class="form-label">Examen de Référence<span
                                    style="color:red;">*</span></label>
                            <input type="text" name="examen_reference_input" class="form-control"
                                placeholder="Saisir l'examen de reference" value="{{ $test_order->test_affiliate }}">
                        </div>

                    </div>
                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label for="exampleFormControlInput1" class="form-label">Patient <span
                                    style="color:red;">*</span></label>

                            @if ($test_order->invoice)
                                @if ($test_order->invoice->paid != 1)
                                    <select class="form-select select2" data-toggle="select2" name="patient_id"
                                        id="patient_id" required>
                                        <option>Sélectionner le nom du patient</option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}"
                                                {{ $test_order->patient_id == $patient->id ? 'selected' : '' }}>
                                                {{ $patient->code }} - {{ $patient->firstname }}
                                                {{ $patient->lastname }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    @foreach ($patients as $patient)
                                        @if ($test_order->patient_id == $patient->id)
                                            <input type="text" name="patient_id" id="patient_id" class="form-control"
                                                readonly
                                                value="{{ $patient->code }} - {{ $patient->firstname }}
                                    {{ $patient->lastname }}">
                                        @endif
                                    @endforeach

                                @endif
                            @else
                                <select class="form-select select2" data-toggle="select2" name="patient_id"
                                    id="patient_id" required>
                                    <option>Sélectionner le nom du patient</option>
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}"
                                            {{ $test_order->patient_id == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->code }} - {{ $patient->firstname }}
                                            {{ $patient->lastname }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif


                        </div>

                        <div class="col-md-6">
                            <label for="exampleFormControlInput1" class="form-label">Médecin traitant<span
                                    style="color:red;">*</span></label>


                            @if ($test_order->invoice)
                                @if ($test_order->invoice->paid != 1)
                                    <select class="form-select select2" data-toggle="select2" name="doctor_id"
                                        id="doctor_id" required>
                                        <option>Sélectionner le médecin traitant</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->name }}"
                                                {{ $test_order->doctor_id == $doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    @foreach ($doctors as $doctor)
                                        @if ($test_order->doctor_id == $doctor->id)
                                            <input type="text" name="doctor_id" id="doctor_id" class="form-control"
                                                readonly value="{{ $doctor->name }}">
                                        @endif
                                    @endforeach

                                @endif
                            @else
                                <select class="form-select select2" data-toggle="select2" name="doctor_id"
                                    id="doctor_id" required>
                                    <option>Sélectionner le médecin traitant</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->name }}"
                                            {{ $test_order->doctor_id == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="exampleFormControlInput1" class="form-label">Hôpital de provenance<span
                                    style="color:red;">*</span></label>


                            @if ($test_order->invoice)
                                @if ($test_order->invoice->paid != 1)
                                    <select class="form-select select2" data-toggle="select2" name="hospital_id"
                                        id="hospital_id" required>
                                        <option>Sélectionner le centre hospitalier de provenance</option>
                                        @foreach ($hopitals as $hopital)
                                            <option value="{{ $hopital->name }}"
                                                {{ $test_order->hospital_id == $hopital->id ? 'selected' : '' }}>
                                                {{ $hopital->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    @foreach ($hopitals as $hopital)
                                        @if ($test_order->hospital_id == $hopital->id)
                                            <input type="text" name="hospital_id" id="hospital_id"
                                                class="form-control" readonly value="{{ $hopital->name }}">
                                        @endif
                                    @endforeach

                                @endif
                            @else
                                <select class="form-select select2" data-toggle="select2" name="hospital_id"
                                    id="hospital_id" required>
                                    <option>Sélectionner le centre hospitalier de provenance</option>
                                    @foreach ($hopitals as $hopital)
                                        <option value="{{ $hopital->name }}"
                                            {{ $test_order->hospital_id == $hopital->id ? 'selected' : '' }}>
                                            {{ $hopital->name }}</option>
                                    @endforeach
                                </select>
                            @endif

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Référence hôpital</label>
                                @if ($test_order->invoice)
                                    <input type="text" {{ $test_order->invoice->paid == 1 ? 'readonly' : '' }}
                                        class="form-control" name="reference_hopital"
                                        value="{{ $test_order->reference_hopital }}">
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
                            @if ($test_order->invoice)
                                <input type="date" {{ $test_order->invoice->paid == 1 ? 'readonly' : '' }}
                                    class="form-control" name="prelevement_date" id="prelevement_date"
                                    data-date-format="dd/mm/yyyy" value="{{ $test_order->prelevement_date }}" required>
                            @else
                                <input type="date" class="form-control" name="prelevement_date" id="prelevement_date"
                                    data-date-format="dd/mm/yyyy" value="{{ $test_order->prelevement_date }}" required>
                            @endif

                            <label class="form-label mt-3">Affecter à</label>

                            @if ($test_order->invoice)
                                @if ($test_order->invoice->paid != 1)
                                    <select name="attribuate_doctor_id" id="" class="form-control">
                                        <option value="">Selectionnez un docteur</option>
                                        @foreach (getUsersByRole('docteur') as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $test_order->attribuate_doctor_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->lastname }} {{ $item->firstname }} </option>
                                        @endforeach
                                    </select>
                                @else
                                    @foreach (getUsersByRole('docteur') as $item)
                                        @if ($test_order->attribuate_doctor_id == $item->id)
                                            <input type="text" name="attribuate_doctor_id" id=""
                                                class="form-control" readonly
                                                value="{{ $item->lastname }} {{ $item->firstname }}">
                                        @endif
                                    @endforeach

                                @endif
                            @else
                                <select name="attribuate_doctor_id" id="" class="form-control">
                                    <option value="">Selectionnez un docteur</option>
                                    @foreach (getUsersByRole('docteur') as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $test_order->attribuate_doctor_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->lastname }} {{ $item->firstname }} </option>
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
                            @if ($test_order->invoice)
                                <input type="checkbox" id="switch3"
                                    {{ $test_order->invoice->paid == 1 ? 'readonly' : '' }} class="form-control"
                                    name="is_urgent" {{ $test_order->is_urgent != 0 ? 'checked' : '' }}
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
                                @if ($test_order->invoice)
                                    <input type="file" name="examen_file"
                                        {{ $test_order->invoice->paid == 1 ? 'readonly' : '' }} id="example-fileinput"
                                        class="form-control dropify"
                                        data-default-file="{{ $test_order ? Storage::url($test_order->examen_file) : '' }}">
                                @else
                                    <input type="file" name="examen_file" id="example-fileinput"
                                        class="form-control dropify"
                                        data-default-file="{{ $test_order ? Storage::url($test_order->examen_file) : '' }}">
                                @endif
                            </div>
                        </div>
                    </div>



                </div>

                <div class="modal-footer">
                    @if ($test_order->invoice)
                        @if ($test_order->invoice->paid != 1)
                            <button type="submit" class="btn w-100 btn-warning">Mettre à jour</button>
                        @endif
                    @else
                        <button type="submit" class="btn w-100 btn-warning">Mettre à jour</button>
                    @endif
                </div>

            </form>
        </div>

        <div class="card mb-md-0 mb-3">
            <div class="card-header">
                Liste des examens demandés
            </div>
            <h5 class="card-title mb-0"></h5>

            <div class="card-body">

                <!-- Ajouter un examen | si le statut de la demande est 1 alors on peut plus ajouter de nouveau examen dans la demande-->
                @if ($test_order->status != 1)
                    <form method="POST" id="addDetailForm" autocomplete="off">
                        @csrf
                        <div class="row d-flex align-items-end">
                            <div class="col-md-4 col-12">
                                <input type="hidden" name="test_order_id" id="test_order_id"
                                    value="{{ $test_order->id }}" class="form-control">

                                <div class="mb-3">
                                    <label for="example-select" class="form-label">Examen</label>
                                    <select class="form-select select2" data-toggle="select2" id="test_id"
                                        name="test_id" required onchange="getTest()">
                                        <option>Sélectionner l'examen</option>
                                        @foreach ($tests as $test)
                                            <option data-category_test_id="{{ $test->category_test_id }}"
                                                value="{{ $test->id }}">{{ $test->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">

                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Prix</label>
                                    <input type="text" name="price" id="price" class="form-control" required
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Remise</label>
                                    <input type="text" name="remise" id="remise" class="form-control" required
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="mb-3">
                                    <label for="example-select" class="form-label">Total</label>

                                    <input type="text" name="total" id="total" class="form-control" required
                                        readonly>
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
                                {{-- @if ($test_order->invoice)
                                    @if ($test_order->invoice->paid != 1)
                                        <th>Actions</th>
                                    @endif
                                @else
                                    <th>Actions</th>
                                @endif --}}

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
                                    <input type="number" id="estimated_ammount" class="estimated_ammount"
                                        value="0" readonly>
                                </td>
                                <td></td>
                                {{-- @if ($test_order->invoice)
                                    @if ($test_order->invoice->paid != 1)
                                        <td></td>
                                    @endif
                                @else
                                    <td></td>
                                @endif --}}

                            </tr>
                        </tfoot>
                    </table>

                    <div class="row mx-3 mt-2">
                        @if ($test_order->status != 1)
                            <a type="submit" href="{{ route('test_order.updatestatus', $test_order->id) }}"
                                id="finalisationBtn" class="btn btn-info disabled w-full">ENREGISTRER</a>
                        @endif
                        @if ($test_order->status == 1)
                            <a href="{{ route('report.show', empty($test_order->report->id) ? '' : $test_order->report->id) }}"
                                class="btn btn-success w-full">CONSULTEZ LE
                                COMPTE RENDU</a>
                        @endif
                    </div>
                </div>

            </div>
        </div> <!-- end card-->

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
                                <input type="text" name="code" id="code" value="<?php echo substr(md5(rand(0, 1000000)), 0, 10); ?>"
                                    class="form-control" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Nom <span style="color:red;">*</span></label>
                                <input type="text" name="firstname" id="firstname" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Prénom<span
                                        style="color:red;">*</span></label>
                                <input type="text" name="lastname" id="lastname" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="example-select" class="form-label">Genre<span
                                        style="color:red;">*</span></label>
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
                                <label for="example-select" class="form-label">Mois ou Années<span
                                        style="color:red;">*</span></label>
                                <select class="form-select" id="example-select" name="year_or_month" required>
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
                                <label for="simpleinput" class="form-label">Contact 1<span
                                        style="color:red;">*</span></label>
                                <input type="tel" name="telephone1" id="telephone1" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Contact 2</label>
                                <input type="tel" name="telephone2" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Adresse<span
                                        style="color:red;">*</span></label>
                                <textarea type="text" name="adresse" class="form-control" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="example-select" class="form-label">Langue parlée<span style="color:red;">*</span></label>
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
                                <input type="text" name="code" value="<?php echo substr(md5(rand(0, 1000000)), 0, 10); ?>" class="form-control"
                                    readonly>
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
                                    <label for="simpleinput" class="form-label">Age<span
                                            style="color:red;">*</span></label>
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
                                <label for="simpleinput" class="form-label">Adresse<span
                                        style="color:red;">*</span></label>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var test_order = {!! json_encode($test_order) !!}
        var invoiceTest = {!! json_encode($test_order->invoice) !!}
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
    </script>
    <script src="{{ asset('viewjs/test/order/detail.js') }}"></script>
@endpush
