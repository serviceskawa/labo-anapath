@extends('layouts.app2')

@section('title', 'Onco | Rendez-vous')
@section('css')

<link href="{{asset('adminassets/css/fullcalendar/main.css')}}" rel="stylesheet" type="text/css">
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
                eventClick:function(info){
                    console.log(info.event.id)
                    var id = info.event.id;
                    window.location.href = " {{ url('/') }}/appointements/show/"+id;

                },
                events:"{{route('appointement.getAppointements')}}",
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
                    message:message,
                    time:time
                },
                success: function(data) {
                    calendar.refetchEvents();
                    $('#form-event').trigger("reset");
                    $('#event-modal').modal('hide');
                    toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
                    console.log(data)   ;         
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
                    <button class="btn btn-lg font-16 btn-danger" data-bs-toggle="modal"
                        data-bs-target="#event-modal"><i class="mdi mdi-plus-circle-outline"></i> Ajouter un
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
                            <div class="mt-4 mt-lg-0">
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
                            <div class="modal-header py-3 px-4 border-bottom-0">
                                <h5 class="modal-title" id="modal-title">Event</h5>
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
                                                <option value="{{ $patient->id }}">{{ $patient->code }} - {{
                                                    $patient->firstname }}
                                                    {{ $patient->lastname }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">Please select a valid event category</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Médecin
                                                traitant<span style="color:red;">*</span></label>
                                            <select class="form-select select2" data-toggle="select2" name="doctor_id"
                                                id="doctor_id" required>
                                                <option>Sélectionner le médecin traitant</option>
                                                @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
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
                                        <label class="control-label form-label">Message</label>
                                        <textarea name="message" id="message" class="form-control" cols="30"
                                            rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        {{-- <button type="button" class="btn btn-danger"
                                            id="btn-delete-event">Delete</button> --}}
                                    </div>
                                    <div class="col-6 text-end">
                                        {{-- <button type="button" class="btn btn-light me-1"
                                            data-bs-dismiss="modal">Close</button> --}}
                                        <button type="submit" class="btn btn-success" id="btn-save-event">Save</button>
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
    $(document).ready(function(){

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