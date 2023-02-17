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

        {{-- <div class="card my-3">
            @if ($test_order->status == 1)
                <a href="{{ route('report.show', empty($test_order->report->id) ? '' : $test_order->report->id) }}"
                    class="btn btn-success w-full">CONSULTEZ LE
                    COMPTE RENDU</a>
            @endif

            <div class="card-header">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right mt-0">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#standard-modal">Ajouter un nouveau patient</button>
                        </div>
                        Demande d'examen : <strong>{{ $test_order->code }}</strong>
                    </div>

                </div>
            </div>
            <!-- Fusion de read et updaye -->
            <form action="{{ route('test_order.update', $test_order->id) }}" method="post" autocomplete="off"
                enctype="multipart/form-data">
                <div class="card-body">

                    @csrf
                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="exampleFormControlInput1" class="form-label">Type d'examen<span
                                    style="color:red;">*</span></label>
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
                        </div>
                        <div class="col-md-6">
                            <label for="exampleFormControlInput1" class="form-label">Contrat<span
                                    style="color:red;">*</span></label>
                            <select class="form-select select2" data-toggle="select2" required name="contrat_id">
                                <option>Sélectionner le contrat</option>
                                @forelse ($contrats as $contrat)
                                    <option value="{{ $contrat->id }}"
                                        {{ $test_order->contrat_id == $contrat->id ? 'selected' : '' }}>
                                        {{ $contrat->name }}</option>
                                @empty
                                    Ajouter un contrat
                                @endforelse
                            </select>
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
                            <select class="form-select select2" data-toggle="select2" name="patient_id" id="patient_id"
                                required>
                                <option>Sélectionner le nom du patient</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}"
                                        {{ $test_order->patient_id == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->code }} - {{ $patient->firstname }}
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
                                    <option value="{{ $doctor->name }}"
                                        {{ $test_order->doctor_id == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}</option>
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
                                    <option value="{{ $hopital->name }}"
                                        {{ $test_order->hospital_id == $hopital->id ? 'selected' : '' }}>
                                        {{ $hopital->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Référence hôpital</label>
                                <input type="text" class="form-control" name="reference_hopital"
                                    value="{{ $test_order->reference_hopital }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Date prélèvement<span style="color:red;">*</span></label>
                            <input type="date" class="form-control" name="prelevement_date" id="prelevement_date"
                                data-date-format="dd/mm/yyyy" value="{{ $test_order->prelevement_date }}" required>

                            <label class="form-label mt-3">Docteur signataire</label>
                            <select name="attribuate_doctor_id" id="" class="form-control">
                                <option value="">Choississez un docteur signataire</option>
                                @foreach (getUsersByRole('docteur') as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $test_order->attribuate_doctor_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->lastname }} {{ $item->firstname }} </option>
                                @endforeach
                            </select>
                            <label class="form-label mt-3">Cas urgent</label> <br>
                            <input type="checkbox" id="switch3" class="form-control" name="is_urgent"
                                {{ $test_order->is_urgent != 0 ? 'checked' : '' }} data-switch="success" />
                            <label for="switch3" data-on-label="Urgent" data-off-label="Normal"></label>

                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Pièce jointe</label>
                                <input type="file" name="examen_file" id="example-fileinput" class="form-control dropify" data-default-file="{{ $test_order ? Storage::url($test_order->examen_file) : '' }}">
                            </div>
                        </div>
                    </div>

                    

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn w-100 btn-warning">Mettre à jour</button>
                </div>

            </form>
        </div> --}}

        <div class="card mb-md-0 mb-3">
            

            <div class="card-body">
                @include('prestationsOrder.edit')
                <!-- Ajouter un examen | si le statut de la demande est 1 alors on peut plus ajouter de nouveau examen dans la demande-->
                {{-- @if ($test_order->status != 1) --}}

                <form method="POST" action={{ route('prestations_order.store') }} id="addDetailForm" autocomplete="off">
                    @csrf
                    <div class="row d-flex align-items-end">
                        <div class="col-md-4 col-12">

                            <div class="mb-3">
                                <label for="example-select" class="form-label">Patients</label>
                                <select class="form-select select2" data-toggle="select2" id="" name="patient_id"
                                    required>
                                    <option>Tous les patients</option>
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}"> {{ $patient->firstname }}
                                            {{ $patient->lastname }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">

                            <div class="mb-3">
                                <label for="example-select" class="form-label">Prestations</label>
                                <select class="form-select select2" data-toggle="select2" id="prestation_id"
                                    name="prestation_id" required onchange="getTest()">
                                    <option>Toutes les prestations</option>
                                    @foreach ($prestations as $prestation)
                                        <option data-category_test_id="{{ $prestation->id }}" value="{{ $prestation->id }}">
                                            {{ $prestation->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-12">

                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Prix</label>
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

                <div class="card-widgets">
                    <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                    <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                        aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                    <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                </div>
                <h5 class="card-title mb-0">Liste des demandes</h5>

                <div id="cardCollpase1" class="collapse pt-3 show">


                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient</th>
                                <th>Prestations</th>
                                <th>Prix</th>
                                <th>Status</th>
                                <th>Actions</th>

                            </tr>
                        </thead>


                        <tbody>

                            @foreach ($prestationOrders as $prestationOrder)
                                <tr>
                                    <td>{{ $prestationOrder->id }}</td>
                                    <td>{{ $prestationOrder->patient->firstname }} {{ $prestationOrder->patient->lastname }}</td>
                                    <td>{{ $prestationOrder->prestation->name }}</td>
                                    <td>{{ $prestationOrder->total }}</td>
                                    <td>
                                        <span
                                            class="bg-{{ $prestationOrder->status != 1 ? 'danger' : 'success' }} badge
                                            float-end">{{ $prestationOrder->status != 1 ? 'en cours' : 'payé' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ( $prestationOrder->status != 1)
                                            <button type="button" onclick="edit({{ $prestationOrder->id }})"
                                                class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </button>
                                            <button type="button" onclick="deleteModal({{ $prestationOrder->id }})"
                                                class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach




                        </tbody>
                    </table>

                </div>



            </div>
        </div> <!-- end card-->

    </div>
@endsection


@push('extra-js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('.dropify').dropify();
    </script>

    <script type="text/javascript">
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
                    window.location.href = "{{ url('prestations_order/delete') }}" + "/" + id;
                    Swal.fire(
                        "Suppression !",
                        "En cours de traitement ...",
                        "success"
                    )
                }
            });
        }


        /* DATATABLE */
        $(document).ready(function() {

            $('#datatable1').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "columnDefs": [{
                    "targets": [0],
                    "searchable": false
                }],
                "language": {
                    "lengthMenu": "Afficher _MENU_ enregistrements par page",
                    "zeroRecords": "Aucun enregistrement disponible",
                    "info": "Afficher page _PAGE_ sur _PAGES_",
                    "infoEmpty": "Aucun enregistrement disponible",
                    "infoFiltered": "(filtré à partir de _MAX_ enregistrements au total)",
                    "sSearch": "Rechercher:",
                    "paginate": {
                        "previous": "Précédent",
                        "next": "Suivant"
                    }
                },
            });
        });


        //EDITION
        function edit(id) {
            var e_id = id;

            // Populate Data in Edit Modal Form
            $.ajax({
                type: "GET",
                url: "{{ url('prestations_order/edit') }}" + '/' + e_id,
                success: function(data) {

                    $('#id').val(data.id);
                    $('#patient').val(data.patient_id);
                    $('#prestation_id2').val(data.prestation_id);
                    $('#total2').val(data.total);
                    $('#status').val(data.status);
                    console.log(data);
                    $('#editModal').modal('show');
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }

        function getTest() {
            var prestation_id = $('#prestation_id').val();

            $.ajax({
                type: "POST",
                url: "{{ route('prestations_order.getPrestationOrder') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    prestationId: prestation_id,
                },
                success: function(data) {
                    console.log(data.total);
                    $('#total').val(data.total);
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });

        }
    </script>
@endpush
