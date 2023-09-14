@extends('layouts.app2')

@section('title', 'Examens')

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
            <h5 class="card-title mb-0">Liste des demandes d'examen</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">

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
                                    <option value="{{$contrat->name}}">{{$contrat->name}}</option>
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
                                    <option value="Valider">Valider</option>
                                    <option value="En attente">En attente</option>
                                    <option value="Livrer">Livrer</option>
                                    <option value="Non livrer">Non livrer"</option>
                                </select>
                            </div>


                        </div> <!-- end col -->

                        <div class="col-lg-3">

                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Type d'examen</label>
                                <select name="type_examen" id="type_examen" class="form-control">
                                    <option value="">Tous</option>
                                    @forelse ($types_orders as $type)
                                    <option value="{{ $type->title }}">{{ $type->title }}</option>
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

                    </div>
                </form>

                <table id="datatable1" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Actions</th>
                            <th>Date</th>
                            <th>Code</th>
                            <th>Patient</th>
                            <th>Contrat</th>
                            <th>Pièce jointe</th>
                            <th>Examens demandés</th>
                            <th>Montant</th>
                            <th>Compte rendu</th>
                            <th>Type examen</th>

                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($examens as $item)
                        <tr data-mytag="{{ $item->is_urgent ? $item->is_urgent : "" }}">
                            <td>{{ $item->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                            <td>{{ $item->code }} </td>
                            <td>{{ $item->patient->firstname }} {{ $item->patient->lastname }}</td>
                            <td>{{ $item->contrat->name }}</td>
                            <td>
                                @if (!empty($item->examen_file))
                                <a href="{{Storage::url($item->examen_file)}}" target="_blank" rel="noopener noreferrer"
                                    type="button" class="btn btn-secondary">
                                    <i class="mdi mdi-cloud-download"></i>
                                </a>
                                @else
                                Aucun fichier
                                @endif

                            </td>
                            <td>
                                @forelse ( $item->details as $detail)
                                {{ $detail->test_name }} <br>
                                @empty
                                Aucune donnée
                                @endforelse

                            </td>
                            <td>{{ $item->total }}</td>
                            <td>
                                <span class="badge bg-primary rounded-pill">
                                    {{ $item->getReport($item->id) == 1 ? "Valider" : "En attente"}}

                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary rounded-pill">
                                    {{ $item->type->title}}

                                </span>
                            </td>
                            <td>
                                <a type="button" href="{{ route('details_test_order.index', $item->id) }}"
                                    class="btn btn-primary" title="Voir les détails"><i class="mdi mdi-eye"></i> </a>
                                <a type="button" href="{{ route('test_order.edit', $item->id) }}"
                                    class="btn btn-primary" title="Mettre à jour l'examen"><i
                                        class="mdi mdi-lead-pencil"></i>
                                </a>
                                <a type="button"
                                    href="{{!empty($item->getReportId($item->id)) ? route('report.show', $item->report->id) : "" }}"
                                    class="btn btn-warning" title="Compte rendu"><i class="uil-file-medical"></i> </a>
                                <a href="{{!empty($item->invoice->id) ? route('invoice.show',$item->invoice->id):
                                    route('invoice.storeFromOrder',$item->id)}}" class="btn btn-success"
                                    title="Facture"><i class="mdi mdi-printer"></i></a>
                                @if ($item->status != 1)
                                <button type="button" onclick="deleteModal({{ $item->id }})" class="btn btn-danger"><i
                                        class="mdi mdi-trash-can-outline" title="Supprimer"></i>
                                </button>
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
            "createdRow": function( row, data, dataIndex ) {
            
                var urgent = $(row).data('mytag');
                    if ( urgent == 1 ) {        
                    $(row).addClass('table-danger');  
                }
            },
            "order": [
                [0, "desc"]
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "searchable": true
                }
            ],
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
  
        // Recherche dans la colonne  contrat
        $("#contrat_id").on("change", function() {
            table
            .columns(4)
            .search(this.value)
            .draw();
        });
        
        // Recherche dans la colonne  compte rendu
        $("#exams_status").on("change", function() {
            table
            .columns(8)
            .search(this.value)
            .draw();
        });
        
        // Recherche dans la colonne  type d'examen
        $("#type_examen").on("change", function() {
            table
            .columns(9)
            .search(this.value)
            .draw();
        });
        
        // Recherche selon les cas
        $("#cas_status").on("change", function() {
            table.draw();
        });
        $.fn.dataTable.ext.search.push(
            function( settings, searchData, index, rowData, counter ) 
            {
                var row = table.row(index).node();
                var filterValue = $(row).data('mytag');
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
        // var table = $("#datatable1").DataTable({
        // processing: true,
        // ajax: "{{ route('test_order.get_test_order') }}",
        // columns: [
        //     { data: 'id', name: '#' },
        //     { data: 'prelevement_date', name: 'Date' },
        //     { data: 'code', name: 'Code' },
        //     { data: 'code', name: 'Patient' },
        //     { data: 'code', name: 'Examens demandés' },
        //     { data: 'code', name: 'Montant' },
        //     { data: 'code', name: 'Compte rendu' },
        //     { data: null},
        // ],
        // columnDefs: [{
        //         "targets": -1,
        //         "render": function(data, type, row) {
        //             if (row["status"] != 1) {
        //                 return (
        //                     '<button type="button" id="deleteBtn" class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>'
        //                 );
        //             }
        //             return "";
        //         }

        //     }],
        // language: {
        //     url: "//cdn.datatables.net/plug-ins/1.12.1/i18n/fr-FR.json",
        // },

        // });
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
    
    // $(function() {



    //     var start = moment().subtract(29, 'days');
    //     var end = moment();

    //     function cb(start, end) {
    //         $('#reportrange').val(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
    //     }

    //     $('#reportrange').daterangepicker({
    //         startDate: start,
    //         endDate: end,
    //         locale: {
    //             format: 'DD/MM/YYYY'
    //         },
    //         ranges: {
    //         'Today': [moment(), moment()],
    //         'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    //         'Last 7 Days': [moment().subtract(6, "days"), moment()],
    //         'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    //         'This Month': [moment().startOf('month'), moment().endOf('month')],
    //         'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    //         }
    //     }, cb);

    //     cb(start, end);
    //     $("#dropdown1").on("change", function() {
    //         table
    //         .columns(0)
    //         .search(this.value)
    //         .draw();
    //     });
    // });

    // $('#filter_form').change(function(){
    //     var date = $('#reportrange').val();
    //     var contratId = $('#contrat_id').val();
    //     var status = $('#exams_status').val();
    //     var cas = $('#cas_status').val();
    //     alert(cas)

    //     $.ajax({
    //         type: "GET",
    //         data:{date:date,contrat_id:contratId,exams_status:status,cas_status:cas},
    //         url: "{{ route('test_order.get_test_order') }}",
    //         success: function(data) {

    //             console.log(data);
    //             var data = data;
    //             @php
    //             $examens = "a"
    //             @endphp
                
    //         },
    //         error: function(data) {
    //             console.log('Error:', data);
    //         }
    //     });
    // });
</script>
@endpush