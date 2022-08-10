@extends('layouts.app2')

@section('css')
    <link href="{{ asset('/adminassets/css/vendor/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminassets/css/vendor/quill.snow.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3 mb-1">
                    <a href="{{ route('report.index') }}" type="button" class="btn btn-primary">Retour à la liste des
                        reports</a>
                </div>
                <h4 class="page-title"></h4>
            </div>


        </div>
    </div>


    <div class="">


        @include('layouts.alerts')

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Reports settings</h4>
                    <p class="text-muted font-14">

                    </p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#input-types-preview" data-bs-toggle="tab" aria-expanded="false"
                                class="nav-link active">
                                Signatures
                            </a>
                        </li>
                    </ul> <!-- end nav-->

                    <div class="tab-content">
                        <div class="tab-pane show active" id="input-types-preview">
                            <form action="{{ route('report.report-store') }}" method="post" enctype="multipart/form-data">
                                <div class="row">

                                    @csrf
                                    <div class="col-lg-6">


                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Signator 1</label>
                                            <input type="text" id="simpleinput" name="Signator1" class="form-control"
                                                value="{{ $setting ? $setting->signatory1 : '' }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="example-fileinput" class="form-label">Signature 1</label>
                                            <input type="file" id="example-fileinput" name="img1"
                                                class="form-control">
                                        </div>


                                    </div> <!-- end col -->

                                    <div class="col-lg-6">

                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Signator 2</label>
                                            <input type="text" id="simpleinput" name="Signator2"
                                                value="{{ $setting ? $setting->signatory2 : '' }}" class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label for="example-fileinput" class="form-label">Signature 2</label>
                                            <input type="file" id="example-fileinput" name="img2"
                                                class="form-control">
                                        </div>


                                    </div> <!-- end col -->

                                </div>
                                <div class="card-footer">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-xs btn-success">Update</button>
                                    </div>
                                </div> <!-- end card-body -->
                            </form>
                            <!-- end row-->
                        </div> <!-- end preview-->

                    </div> <!-- end tab-content-->
                </div>

            </div> <!-- end card -->
        </div>
    </div>
@endsection


@push('extra-js')
    <!-- quill js -->
    <script src="{{ asset('/adminassets/js/vendor/quill.min.js') }}"></script>
    <!-- quill Init js-->
    <script src="{{ asset('/adminassets/js/pages/demo.quilljs.js') }}"></script>
@endpush
