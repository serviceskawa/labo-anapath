@extends('layouts.app2')

@section('title', 'Onco | Consultations')

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
                    window.location.href = " {{ url('/') }}/consultations/show/"+id;

                },
                events:"{{route('consultation.getConsultations')}}",
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
                    <a class="btn btn-lg font-16 btn-danger" href="{{route('consultation.create')}}"><i
                            class="mdi mdi-plus-circle-outline"></i> Ajouter une consultation</a>
                </div>
                <h4 class="page-title">Consultations</h4>
            </div>

        </div>
    </div>
    <!-- end page title -->
    @include('layouts.alerts')

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
                                <table id="datatable1" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Code</th>
                                            <th>Patient</th>
                                            <th>Docteur</th>
                                            <th>Type consultation</th>
                                            <th>Actions</th>

                                        </tr>
                                    </thead>


                                </table>
                            </div>
                        </div> <!-- end col -->

                    </div> <!-- end row -->
                </div> <!-- end card body-->
            </div> <!-- end card -->


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
    
        /* DATATABLE */
        $(document).ready(function() {

            var table = $('#datatable1').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('consultation.getConsultations') }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },{
                        data: 'code',
                        name: 'code'
                    },{
                        data: 'patient',
                        name: 'patient'
                    },
                    {
                        data: 'doctor',
                        name: 'doctor'
                    },
                    {
                        data: 'type',
                        name: 'type'
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

        });
</script>
@endpush