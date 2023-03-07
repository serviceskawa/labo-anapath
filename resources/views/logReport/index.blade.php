@extends('layouts.app2')

@section('title', 'Historique')

@section('css')

    <link href="{{ asset('adminassets/css/fullcalendar/main.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('adminassets/js/fullcalendar/main.js') }}"></script>
    
    {{-- <script>
        var calendarEl = document.getElementById('calendar');
        document.addEventListener('DOMContentLoaded', function() {
            var SITEURL = "{{ url('/') }}";
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'fr',
                initialView: 'dayGridMonth',
                slotDuration: "00:15:00",
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                buttonText: {
                    today: "Aujourd'hui",
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'day',
                    list: 'Liste'
                },
                eventClick: function(info) {
                    console.log(info.event.id)

                    var id = info.event.id;

                    $.ajax({
                        type: "GET",
                        url: "{{ url('appointements/getAppointementsById') }}" + '/' + id,
                        success: function(data) {

                            $('#appointement_id2').val(data.id);
                            $('#patient_id2').val(data.patient_id).change();
                            $('#doctor_id2').val(data.user_id).change();
                            $('#time2').val(data.date);
                            $('#message2').val(data.message);
                            $('#priority2').val(data.priority).change();

                            console.log(data);
                            $('#event-modal-edit').modal('show');
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                },
                events: "{{ route('appointement.getAppointements') }}",
            });
            calendar.render();

            $('#form-event').on('submit', function(e) {
                e.preventDefault();
                let doctorId = $('#doctor_id').val();
                let patientId = $('#patient_id').val();
                let priority = $('#priority').val();
                let message = $('#message').val();
                let time = $('#time').val();
                // console.log(time);
                $.ajax({
                    url: "{{ route('appointement.store') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        doctorId: doctorId,
                        patientId: patientId,
                        priority: priority,
                        message: message,
                        time: time
                    },
                    success: function(data) {
                        calendar.refetchEvents();
                        $('#form-event').trigger("reset");
                        $('#event-modal').modal('hide');
                        toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
                        console.log(data);
                    },
                    error: function(data) {
                        console.log(data);
                    },

                });

            });
            $('#form-event-edit').on('submit', function(e) {
                e.preventDefault();
                let doctorId = $('#doctor_id2').val();
                let patientId = $('#patient_id2').val();
                let priority = $('#priority2').val();
                let message = $('#message2').val();
                let time = $('#time2').val();
                let id = $('#appointement_id2').val();
                console.log(time);
                $.ajax({
                    url: "{{ url('appointements/update') }}" + '/' + id,
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        doctor_id: doctorId,
                        patient_id: patientId,
                        priority: priority,
                        message: message,
                        date: time
                    },
                    success: function(data) {
                        calendar.refetchEvents();
                        $('#form-event-edit').trigger("reset");
                        $('#event-modal-edit').modal('hide');
                        toastr.success("Donnée mis à jour avec succès", 'Mis à jour réussi');
                        console.log(data);
                    },
                    error: function(data) {
                        console.log(data);
                    },

                });

            });
            $('#deleteAppointement').on('click', function(e) {
                e.preventDefault();

                let id = $('#appointement_id2').val();

                $.ajax({
                    url: "{{ url('appointements/delete') }}" + '/' + id,
                    type: "get",
                    success: function(data) {
                        calendar.refetchEvents();
                        $('#event-modal-edit').modal('hide');
                        toastr.success("Donnée supprimé avec succès", 'Supprimé réussi');
                        console.log(data);
                    },
                    error: function(data) {
                        console.log(data);
                    },

                });

            });
        });
    </script>

    <style>
        #calendar {
            /* max-width: 1100px; */
            margin: 25px;
        }
    </style> --}}

@endsection
@section('content')

    @include('logReport.show')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3">
                    <a href="{{ route('report.index') }}" type="button" class="btn btn-primary">Retour à la liste des comptes rendu</a>
                </div>
                <h4 class="page-title">Historiqques</h4>
            </div>

        </div>
    </div>
    <!-- end page title -->
    <div class="">
        @include('layouts.alerts')

        <div class="card mb-md-0 mb-3">

            <div class="card-body">

                <div class="card-widgets">
                    <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                    <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                        aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                    <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                </div>
                <h5 class="card-title mb-0">Liste des historiques</h5>

                <div id="cardCollpase1" class="show collapse pt-3">

                    <table id="datatable1" class="table-striped dt-responsive nowrap w-100 table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Traitement effectué</th>
                                <th>Code compte rendu</th>
                                <th>Date</th>
                                <th>Effectué par</th>
                                <th>Actions</th>

                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->operation }}
                                    </td>
                                    <td>{{ $log->report !=null ? $log->report->code :'Toute la liste'}}</td>
                                    <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y') }}</td>
                                    <td>{{ $log->user->lastname }} {{ $log->user->firstname }}</td>
                                    <td>
                                        <button type="button" onclick="edit({{ $log->id }})"
                                            class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </button>
                                        {{-- <button type="button" onclick="deleteModal({{ $log->id }})"
                                            class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button> --}}
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
    <script type="text/javascript">
        // SUPPRESSION
        // function deleteModal(id) {

        //     Swal.fire({
        //         title: "Voulez-vous supprimer l'élément ?",
        //         icon: "warning",
        //         showCancelButton: true,
        //         confirmButtonText: "Oui ",
        //         cancelButtonText: "Non !",
        //     }).then(function(result) {
        //         if (result.value) {
        //             window.location.href = "{{ url('prestations_order/delete') }}" + "/" + id;
        //             Swal.fire(
        //                 "Suppression !",
        //                 "En cours de traitement ...",
        //                 "success"
        //             )
        //         }
        //     });
        // }


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
                url: "{{ url('log/show') }}" + '/' + e_id,
                success: function(data) {

                    $('#id').val(data.id);
                    $('#operation').val(data.operation);
                    $('#report').val(data.report_id);
                    getuser(data.user_id);
                    console.log(data);
                    $('#editModal').modal('show');
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }

        function getuser(id) {
            var e_id = id;

            // Populate Data in Edit Modal Form
            $.ajax({
                type: "GET",
                url: "{{ url('log/user') }}" + '/' + e_id,
                success: function(data) {
                    console.log(data);
                  $('#by').val(data.lastname +" "+ data.firstname);
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }

        // function getTest() {
        //     var prestation_id = $('#prestation_id').val();

        //     $.ajax({
        //         type: "POST",
        //         url: "{{ route('prestations_order.getPrestationOrder') }}",
        //         data: {
        //             "_token": "{{ csrf_token() }}",
        //             prestationId: prestation_id,
        //         },
        //         success: function(data) {
        //             console.log(data.total);
        //             $('#total').val(data.total);
        //         },
        //         error: function(data) {
        //             console.log('Error:', data);
        //         }
        //     });

        // }
    </script>
@endpush
