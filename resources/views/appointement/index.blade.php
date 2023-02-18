@extends('layouts.app2')

@section('title', 'Onco | Rendez-vous')
@section('css')

    <link href="{{ asset('adminassets/css/fullcalendar/main.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('adminassets/js/fullcalendar/main.js') }}"></script>
    {{--
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.js"></script> --}}

    <script>
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
    </style>

@endsection
@section('content')

    <div class="">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right mr-3">
                        <button class="btn btn-lg font-16 btn-primary" data-bs-toggle="modal" data-bs-target="#event-modal"><i
                                class="mdi mdi-plus-circle-outline"></i> Ajouter un
                            nouvel rendez-vous</button>
                    </div>
                    <h4 class="page-title">Rendez-vous</h4>
                </div>

            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            {{-- <div class="col-lg-3">
                            <div class="d-grid">
                                <button class="btn btn-lg font-16 btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#event-modal"><i class="mdi mdi-plus-circle-outline"></i> Ajouter un
                                    nouvel rendez-vous</button>
                            </div>


                        </div> <!-- end col--> --}}

                            <div class="col-lg-12">
                                <div class="mt-lg-0 mt-4">
                                    <div id="calendar"></div>
                                </div>
                            </div> <!-- end col -->

                        </div> <!-- end row -->
                    </div> <!-- end card body-->
                </div> <!-- end card -->

                <!-- Add New Event MODAL -->
                <div class="modal fade" id="event-modal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form class="needs-validation" name="event-form" id="form-event" novalidate="">
                                @csrf
                                <div class="modal-header border-bottom-0 py-3 px-4">
                                    <h5 class="modal-title" id="modal-title">Ajouter un nouvel Rendez-vous</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body px-4 pb-4 pt-0">
                                    <div class="row">

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="exampleFormControlInput1" class="form-label">Patient<span
                                                        style="color:red;">*</span></label>
                                                <select class="form-select select2" data-toggle="select2" name="patient_id"
                                                    id="patient_id" required>
                                                    <option>Sélectionner le nom du patient</option>
                                                    @foreach ($patients as $patient)
                                                        <option value="{{ $patient->id }}">{{ $patient->code }} -
                                                            {{ $patient->firstname }}
                                                            {{ $patient->lastname }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">Please select a valid event category</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="exampleFormControlInput1" class="form-label">Docteur<span
                                                        style="color:red;">*</span></label>
                                                <select class="form-select select2" data-toggle="select2" name="doctor_id"
                                                    id="doctor_id" required>
                                                    <option>Sélectionner le Docteur</option>
                                                    @foreach ($doctors as $doctor)
                                                        <option value="{{ $doctor->id }}">
                                                            {{ $doctor->firstname . ' ' . $doctor->firstname }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">Please select a valid event category</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="control-label form-label">Heure de rendez-vous</label>
                                            <input class="form-control" type="datetime-local" id="time" required>
                                            <div class="invalid-feedback">Please provide a valid event name</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="control-label form-label">Priorité</label>
                                            <select class="form-select" name="priority" id="priority" required="">
                                                <option value="normal" selected="">Normal</option>
                                                <option value="urgent">Urgent</option>
                                                <option value="tres urgent">Très urgent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="control-label form-label">Commentaire</label>
                                            <textarea name="message" id="message" class="form-control" cols="30" rows="10"></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <button type="button" class="btn btn-light me-1"
                                                data-bs-dismiss="modal">Annuler</button>
                                        </div>
                                        <div class="col-6 text-end">

                                            <button type="submit" class="btn btn-success" id="btn-save-event">Ajouter un
                                                nouvel RDV</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div> <!-- end modal-content-->
                    </div> <!-- end modal dialog-->
                </div>
                <div class="modal fade" id="event-modal-edit" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form class="needs-validation" name="event-form" id="form-event-edit" novalidate="">
                                @csrf
                                <div class="modal-header border-bottom-0 py-3 px-4">
                                    <h5 class="modal-title" id="modal-title">Modifier le Rendez-vous</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body px-4 pb-4 pt-0">
                                    <div class="row">
                                        <input type="hidden" name="appointement_id2" id="appointement_id2">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="exampleFormControlInput1" class="form-label">Patient<span
                                                        style="color:red;">*</span></label>
                                                <select class="form-select select2" data-toggle="select2"
                                                    name="patient_id2" id="patient_id2" required>
                                                    <option>Sélectionner le nom du patient</option>
                                                    @foreach ($patients as $patient)
                                                        <option value="{{ $patient->id }}">{{ $patient->code }} -
                                                            {{ $patient->firstname }}
                                                            {{ $patient->lastname }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">Please select a valid event category</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="exampleFormControlInput1" class="form-label">Docteur<span
                                                        style="color:red;">*</span></label>
                                                <select class="form-select select2" data-toggle="select2"
                                                    name="doctor_id2" id="doctor_id2" required>
                                                    <option>Sélectionner le Docteur</option>
                                                    @foreach ($doctors as $doctor)
                                                        <option value="{{ $doctor->id }}">
                                                            {{ $doctor->firstname . ' ' . $doctor->firstname }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">Please select a valid event category</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="control-label form-label">Heure de rendez-vous</label>
                                            <input class="form-control" type="datetime-local" id="time2" required>
                                            <div class="invalid-feedback">Insérer une date valide</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="control-label form-label">Priorité</label>
                                            <select class="form-select" name="priority2" id="priority2" required="">
                                                <option value="normal" selected="">Normal</option>
                                                <option value="urgent">Urgent</option>
                                                <option value="tres urgent">Très urgent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="control-label form-label">Commentaire</label>
                                            <textarea name="message2" id="message2" class="form-control" cols="30" rows="10"></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <button type="button" class="btn btn-danger"
                                                id="deleteAppointement">Supprimer</button>
                                        </div>
                                        <div class="col-8 text-end">
                                            <button type="button" class="btn btn-light me-1"
                                                data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-success" id="btn-save-event">Mettre à
                                                jour </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div> <!-- end modal-content-->
                    </div> <!-- end modal dialog-->
                </div>
                <!-- end modal-->
            </div>
            <!-- end col-12 -->
        </div> <!-- end row -->

    </div>
@endsection

@push('extra-js')
    {{-- <script src="{{ asset('/adminassets/js/pages/demo.calendar.js') }}"></script> --}}

    <script>
        $('#patient_id').select2({
            dropdownParent: $('#event-modal')
        });
        $('#doctor_id').select2({
            dropdownParent: $('#event-modal')
        });

        // Edit modal
        $('#patient_id2').select2({
            dropdownParent: $('#event-modal-edit')
        });
        $('#doctor_id2').select2({
            dropdownParent: $('#event-modal-edit')
        });
        $(document).ready(function() {

            // $('#form-event').on('submit', function(e) {
            //     e.preventDefault();
            //     let doctorId = $('#doctor_id').val();
            //     let patientId = $('#patient_id').val();
            //     let priority = $('#priority').val();
            //     let message = $('#message').val();
            //     let time = $('#time').val();
            //     // console.log(time);
            //     $.ajax({
            //         url: "{{ route('appointement.store') }}",
            //         type: "POST",
            //         data: {
            //             "_token": "{{ csrf_token() }}",
            //             doctorId: doctorId,
            //             patientId: patientId,
            //             priority: priority,
            //             message:message,
            //             time:time
            //         },
            //         success: function(data) {
            //             calendar.refetchEvents();
            //             $('#form-event').trigger("reset");
            //             $('#event-modal').modal('hide');
            //             toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
            //             console.log(data)   ;         
            //         },
            //         error: function(data) {
            //             console.log(data);
            //         },

            //     });

            // });
        });
    </script>
@endpush
