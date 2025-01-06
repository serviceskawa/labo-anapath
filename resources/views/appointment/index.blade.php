@extends('layouts.app2')

@section('title', 'Onco | Rendez-vous')
@section('css')

    <link href="{{ asset('adminassets/css/fullcalendar/main.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('adminassets/js/fullcalendar/main.js') }}"></script>

    <script>
        var SITEURL = "{{ url('/') }}";
        var ROUTEGETAPPOINTMENT = "{{ route('Appointment.getAppointments') }}"
        var ROUTEAPPOINTMENTSTORE = "{{ route('Appointment.store') }}"
        var ROUTEAPPOINTMENTUPDATE = "{{ route('Appointment.update')}}"
        var TOKENSUBMIT = "{{ csrf_token() }}"
        var TOKENUPDATE = "{{ csrf_token() }}"
        VA
    </script>

    <script src="{{asset('viewjs/appointment/index.js')}}"></script>

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
                                                    <option value="">Sélectionner le nom du patient</option>
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
                                                    <option value="">Sélectionner le Docteur</option>
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
                                        <input type="hidden" name="Appointment_id2" id="Appointment_id2">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="exampleFormControlInput1" class="form-label">Patient<span
                                                        style="color:red;">*</span></label>
                                                <select class="form-select select2" data-toggle="select2"
                                                    name="patient_id2" id="patient_id2" required>
                                                    <option value="">Sélectionner le nom du patient</option>
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
                                                    <option value="">Sélectionner le Docteur</option>
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
                                            <input class="form-control" type="datetime-local" id="time2" name="time2" required>
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
                                            <textarea name="message" id="message2" class="form-control" cols="30" rows="10"></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <button type="button" class="btn btn-danger"
                                                id="deleteAppointment">Supprimer</button>
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
   <script src="{{asset('viewjs/appointment/drop.js')}}"></script>
@endpush
