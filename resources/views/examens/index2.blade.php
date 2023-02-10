@extends('layouts.app2')

@section('title', 'Examens2')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                {{-- <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#standard-modal">Nouveau</button>
            </div>
            <h4 class="page-title">Gérer les demandes d'examen</h4>
        </div>

        <!----MODAL---->

        {{-- @include('examens.create') --}}

                {{-- @include('doctors.edit') --}}

            </div>
        </div>

        <div class="">

            <div class="page-title-box">
                <div class="page-title-right mr-3">
                    <a href="{{ route('test_order.create') }}"><button type="button" class="btn btn-primary">Ajouter une
                            nouvelle demande d'examen</button></a>
                </div>
                <h4 class="page-title">Demandes d'examen</h4>
            </div>

            <!----MODAL---->
            @include('layouts.alerts')

            <div class="card mb-md-0 mb-3">
                <div class="card-body">
                    <div class="card-widgets">
                        <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                        <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                            aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                        <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                    </div>
                    <h5 class="card-title mb-0">Liste des demandes d'examen </h5>

                    <div id="cardCollpase1" class="show collapse pt-3">

                        <form method="post" id="filter_form">
                            @csrf
                            <div class="row mb-3">

                                {{-- <div class="col-lg-3">

                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Date</label>
                                <input type="text" id="reportrange" class="form-control">
                            </div>


                        </div> <!-- end col --> --}}

                                <div class="col-lg-3">

                                    <div class="mb-3">
                                        <label for="example-fileinput" class="form-label">Contrat</label>
                                        <select name="contrat_id" id="contrat_id" class="form-control">
                                            <option value="">Tous les contrats</option>
                                            @forelse ($contrats as $contrat)
                                                <option value="{{ $contrat->id }}">{{ $contrat->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>

                                </div> <!-- end col -->

                                <div class="col-lg-3">

                                    <div class="mb-3">
                                        <label for="example-fileinput" class="form-label">Status</label>
                                        <select name="status" id="exams_status" class="form-control">
                                            <option value="">Tous</option>
                                            <option value="1">Valider</option>
                                            <option value="0">En attente</option>
                                        </select>
                                    </div>

                                </div> <!-- end col -->

                                <div class="col-lg-3">

                                    <div class="mb-3">
                                        <label for="example-fileinput" class="form-label">Type d'examen</label>
                                        <select name="type_examen" id="type_examen" class="form-control">
                                            <option value="">Tous</option>
                                            @forelse ($types_orders as $type)
                                                <option value="{{ $type->id }}">{{ $type->title }}</option>
                                            @empty
                                                Ajouter un Type d'examen
                                            @endforelse
                                        </select>
                                    </div>

                                </div> <!-- end col -->
                                <div class="col-lg-3">

                                    <div class="mb-3">
                                        <label for="example-fileinput" class="form-label">Urgent</label>
                                        <select name="cas_status" id="cas_status" class="form-control">
                                            <option value="">Tous</option>
                                            <option value="1">Urgent</option>
                                        </select>
                                    </div>

                                </div> <!-- end col -->
                                <div class="col-lg-3">

                                    <div class="mb-3">
                                        <label for="example-fileinput" class="form-label">Docteur</label>
                                        <select name="" id="doctor_signataire" class="form-control">
                                            <option value="">Tous</option>
                                            @foreach (getUsersByRole('docteur') as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->lastname }} {{ $item->firstname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div> <!-- end col -->

                            </div>
                        </form>

                        <table id="datatable1" class="dt-responsive nowrap w-100 table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Code</th>
                                    <th>Patient</th>
                                    <th>Examens</th>
                                    <th>Contrat</th>
                                    {{-- <th>Pièce jointe</th> --}}
                                    {{-- <th>Examens demandés</th> --}}
                                    <th>Montant</th>
                                    <th>Compte rendu</th>
                                    {{-- <th>Type examen</th> --}}
                                    <th>Affecter à</th>
                                    <th>Urgent</th>
                                    <th>Actions</th>

                                </tr>
                            </thead>

                        </table>

                    </div>
                </div>
            </div> <!-- end card-->

        </div>
    @endsection

    @push('extra-js')
        <script>
            // SUPPRESSION
            function deleteModal(id) {

                Swal.fire({
                    title: "La supression d'un examen entraine la suppression du Rapport. Voulez-vous continuez?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Oui ",
                    cancelButtonText: "Non !",
                }).then(function(result) {
                    if (result.value) {
                        window.location.href = "{{ url('test_order/delete') }}" + "/" + id;
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

                var table = $('#datatable1').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('test_order.getTestOrdersforDatatable') }}",
                        data: function(d) {
                            d.attribuate_doctor_id = $('#doctor_signataire').val()
                            d.cas_status = $('#cas_status').val()
                            d.contrat_id = $('#contrat_id').val()
                            d.exams_status = $('#exams_status').val()
                            d.type_examen = $('#type_examen').val()
                        }
                    },
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        }, {
                            data: 'code',
                            name: 'code'
                        }, {
                            data: 'patient',
                            name: 'patient'
                        },
                        {
                            data: 'details',
                            name: 'examens'
                        },
                        {
                            data: 'contrat',
                            name: 'contrat'
                        },
                        {
                            data: 'total',
                            name: 'total'
                        },
                        {
                            data: 'rendu',
                            name: 'rendu'
                        },
                        {
                            data: 'dropdown',
                            name: 'dropdown'
                        },
                        {
                            data: 'urgence',
                            name: 'urgence',
                            visible: false,
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    order: [
                        [0, 'desc']
                    ],

                });

                // Recherche dans la colonne  contrat
                $("#contrat_id").on("change", function() {
                    table.draw();
                    // table
                    //     .columns(4)
                    //     .search(this.value)
                    //     .draw();
                });

                // Recherche dans la colonne  compte rendu
                $("#exams_status").on("change", function() {
                    table.draw();
                    // table
                    //     .columns(8)
                    //     .search(this.value)
                    //     .draw();
                });

                // Recherche dans la colonne  type d'examen
                $("#type_examen").on("change", function() {
                    table.draw();
                    // table
                    //     .columns(9)
                    //     .search(this.value)
                    //     .draw();
                });

                // Recherche selon les cas
                $("#cas_status").on("change", function() {
                    table.draw();
                    // table
                    //     .columns(10)
                    //     .search(this.value)
                    //     .draw();
                });

                $.fn.dataTable.ext.search.push(
                    function(settings, searchData, index, rowData, counter) {
                        var row = table.row(index).node();
                        // var filterValue = $(row).data('mytag');
                        var e = document.getElementById("cas_status");
                        var filter = e.options[e.selectedIndex].value;

                        if (filterValue == filter) {
                            return true;
                        } else if (filter == "") {
                            return true;
                        } else {
                            return false;
                        }

                    }
                );
                // Recherche selon les docteurs signataires
                $("#doctor_signataire").on("change", function() {
                    // alert(this.value)
                    table.draw();
                });
            });


            //EDITION
            function edit(id) {
                var e_id = id;

                // Populate Data in Edit Modal Form
                $.ajax({
                    type: "GET",
                    url: "{{ url('getdoctor') }}" + '/' + e_id,
                    success: function(data) {

                        $('#id2').val(data.id);
                        $('#name').val(data.name);
                        $('#telephone').val(data.telephone);
                        $('#email').val(data.email);
                        $('#role').val(data.role);
                        $('#commission').val(data.commission);



                        console.log(data);
                        $('#editModal').modal('show');
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
                $.ajax({
                    type: "GET",
                    url: "{{ url('gettest') }}" + '/' + test_id,
                    success: function(data) {


                        $('#price').val(data.price);

                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }

            function updateAttribuate(doctor_id, order_id) {

                $.ajax({
                    type: "GET",
                    url: "{{ url('attribuateDoctor') }}" + '/' + doctor_id + '/' + order_id,
                    success: function(data) {
                        console.log(data)
                        if (data) {
                            toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
                        }
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }
        </script>
    @endpush
