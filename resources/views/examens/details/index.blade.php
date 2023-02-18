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
                                <input type="file" name="examen_file" id="example-fileinput"
                                    class="form-control dropify"
                                    data-default-file="{{ $test_order ? Storage::url($test_order->examen_file) : '' }}">
                            </div>
                        </div>
                    </div>

                    

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn w-100 btn-warning">Mettre à jour</button>
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
        <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
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
        $('.dropify').dropify();
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var test_order = {!! json_encode($test_order) !!}
            // console.log(test_order)

            var dtDetailTable = $('.detail-list-table')

            var dt_basic = $('#datatable1').DataTable({
                ajax: {
                    url: '/test_order/detailstest/' + test_order.id,
                    dataSrc: ''
                },
                // deferRender: true,
                columns: [
                    // columns according to JSON
                    {
                        data: 'id'
                    },
                    {
                        data: 'test_name'
                    },
                    {
                        data: 'price'
                    },
                    {
                        data: 'discount'
                    },
                    {
                        data: 'total'
                    },
                    {
                        data: null
                    }
                ],
                columnDefs: [{
                    "targets": -1,
                    "render": function(data, type, row) {
                        if (row["status"] != 1) {
                            return (
                                '<button type="button" id="deleteBtn" class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>'
                            );
                        }
                        return "";
                    }

                }],

                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Remove the formatting to get integer data for summation
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i ===
                            'number' ? i : 0;
                    };

                    // Total over all pages
                    total = api
                        .column(4)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    // 
                    discount = api
                        .column(3)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    subTotal = api
                        .column(2)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer
                    $(api.column(4).footer()).html(total);
                    $(api.column(3).footer()).html(discount);
                    $(api.column(2).footer()).html(subTotal);

                    sendTotal(test_order.id, total, discount, subTotal);

                    // if ($(api.column(4).footer()).html(total)) {
                    //     // console.log('footer');
                    // }

                    var numRows = api.rows().count();
                    if (numRows > 0) {
                        var element = document.getElementById('finalisationBtn');

                        element.classList.remove("disabled");
                    }
                },

            });

            // setInterval(function() {
            //     dt_basic.ajax.reload();
            // }, 3000);

            // 
            $('.detail-list-table tbody').on('click', '#deleteBtn', function() {
                var data = dt_basic.row($(this).parents('tr')).data();
                var id = $(this).data('id');
                console.log(data)
                Swal.fire({
                    title: "Voulez-vous supprimer l'élément ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Oui ",
                    cancelButtonText: "Non !",
                }).then(function(result) {
                    if (result.value) {
                        // window.location.href = "{{ url('contrats_details/delete') }}" + "/" + id;
                        $.ajax({
                            url: "/test_order/detailsdelete",
                            type: "post",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                id: data.id,
                            },
                            success: function(response) {

                                console.log(response);
                                Swal.fire(
                                    "Suppression !",
                                    "En cours de traitement ...",
                                    "success"
                                )
                                dt_basic.ajax.reload()
                                // window.location.reload();
                            },
                            error: function(response) {
                                console.log(response);
                                dt_basic.ajax.reload()

                                // Command: toastr["error"]("Error")
                            },
                        });

                    }
                });
            });

            function sendTotal(test_order_id, total, discount, subTotal) {
                console.log(test_order_id, total, discount, subTotal);

                $.ajax({
                    url: "{{ route('test_order.updateorder') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        test_order_id: test_order_id,
                        discount: discount,
                        subTotal: subTotal,
                        total: total

                    },
                    success: function(response) {
                        console.log(response)

                    },
                    error: function(response) {
                        console.log(response)
                    },
                });
            };

        });

        $('#addDetailForm').on('submit', function(e) {
            e.preventDefault();
            let test_order_id = $('#test_order_id').val();
            let test_id = $('#test_id').val();
            let price = $('#price').val();
            let remise = $('#remise').val();
            let total = $('#total').val();

            $.ajax({
                url: "{{ route('details_test_order.store') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    test_order_id: test_order_id,
                    test_id: test_id,
                    price: price,
                    discount: remise,
                    total: total

                },
                success: function(response) {
                    $('#addDetailForm').trigger("reset")

                    if (response) {
                        toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
                    }
                    $('#datatable1').DataTable().ajax.reload();
                    // $('#addDetailForm').trigger("reset")
                    // updateSubTotal();
                },
                error: function(response) {
                    console.log(response)
                },
            });

        });

        function getTest() {
            var test_id = $('#test_id').val();

            // Importation des paramètres de getRemise
            var contrat_id = $('#contrat_id').val();
            let element = document.getElementById("test_id");
            let category_test_id = element.options[element.selectedIndex].getAttribute("data-category_test_id");

            $.ajax({
                type: "POST",
                url: "{{ route('examens.getTestAndRemise') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    testId: test_id,
                    contratId: contrat_id,
                    categoryTestId: category_test_id
                },
                success: function(data) {

                    $('#price').val(data.data.price);

                    var discount = $('#price').val() * data.detail / 100;
                    $('#remise').val(discount);

                    var total = $('#price').val() - discount;
                    $('#total').val(total);

                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });

        }
    </script>

    {{-- Fusion --}}
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

        $(document).ready(function() {

            $('#doctor_id').select2({
                placeholder: 'Select Category',
                theme: 'bootstrap4',
                tags: true,
            }).on('select2:close', function() {
                var element = $(this);
                var new_category = $.trim(element.val());

                if (new_category != '') {
                    $.ajax({
                        url: "{{ route('doctors.storeDoctor') }}",
                        method: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            name: new_category
                        },
                        success: function(data) {

                            if (data.status === "created") {
                                toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');

                                element.append('<option value="' + data.id + '">' + data.name +
                                    '</option>').val(new_category);
                            }
                        }
                    })
                }

            });

            // Create hopital
            $('#hospital_id').select2({
                placeholder: 'Choisissez un hopital',
                theme: 'bootstrap4',
                tags: true,
            }).on('select2:close', function() {
                var element = $(this);
                var new_category = $.trim(element.val());

                if (new_category != '') {
                    $.ajax({
                        url: "{{ route('hopitals.storeHospital') }}",
                        method: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            name: new_category
                        },
                        success: function(data) {

                            if (data.status === "created") {
                                toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');

                                element.append('<option value="' + data.id + '">' + data.name +
                                    '</option>').val(new_category);
                            }
                        }
                    })
                }

            });

            $('#createPatientForm').on('submit', function(e) {
                e.preventDefault();
                let code = $('#code').val();
                let lastname = $('#lastname').val();
                let firstname = $('#firstname').val();
                let age = $('#age').val();
                let telephone1 = $('#telephone1').val();
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
                        telephone1: telephone1,
                        genre: genre
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
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $('#type_examen').on('change', function(e) {
                var typeExamenOption = $('#type_examen option:selected').text();
                // alert(typeExamenOption);
                if (typeExamenOption == "Immuno Externe") {
                    $(".examenReferenceSelect").hide();
                    $(".examenReferenceInput").show();

                } else if (typeExamenOption == "Immuno Interne") {
                    $(".examenReferenceSelect").hide();
                    $(".examenReferenceInput").show();

                } else {
                    $(".examenReferenceInput").hide();
                    $(".examenReferenceSelect").hide();
                }
            });


        });

        window.addEventListener("load", (event) => {
            var typeExamenOption = $('#type_examen option:selected').text();
            // alert(typeExamenOption);
            if (typeExamenOption == "Immuno Externe") {
                $(".examenReferenceSelect").hide();
                $(".examenReferenceInput").show();

            } else if (typeExamenOption == "Immuno Interne") {
                $(".examenReferenceSelect").hide();
                $(".examenReferenceInput").show();

            } else {
                $(".examenReferenceInput").hide();
                $(".examenReferenceSelect").hide();
            }

        });
    </script>
@endpush
