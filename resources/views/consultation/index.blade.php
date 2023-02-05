@extends('layouts.app2')

@section('title', 'Onco | Consultations')

@section('css')

@endsection
@section('content')

    <div class="">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right mr-3">
                        <a class="btn btn-lg font-16 btn-primary" href="{{ route('consultation.create') }}"><i
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
                                <div class="mt-lg-0 mt-4">
                                    <table id="datatable1" class="dt-responsive nowrap w-100 table">
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
