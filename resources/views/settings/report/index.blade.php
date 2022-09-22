@extends('layouts.app2')

@section('title', 'REPORTS SETTINGS')

@section('css')
    <link href="{{ asset('/adminassets/css/vendor/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminassets/css/vendor/quill.snow.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css"
        integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .ck-editor__editable[role="textbox"] {
            /* editing area */
            min-height: 200px;
        }
    </style>
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
                    <h4 class="header-title">Paramètres du compte rendu</h4>
                    <p class="text-muted font-14">

                    </p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#input-types-preview" data-bs-toggle="tab" aria-expanded="false"
                                class="nav-link active">
                                Signatures
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="#input-types-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Placeholder
                            </a>
                        </li> --}}
                    </ul> <!-- end nav-->

                    <div class="tab-content">
                        <div class="tab-pane show active" id="input-types-preview">
                            <form action="{{ route('report.report-store') }}" method="post" enctype="multipart/form-data">
                                <div class="row">

                                    @csrf
                                    <div class="col-lg-4">

                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Signator 1</label>
                                            <input type="text" id="simpleinput" name="Signator1" class="form-control"
                                                value="{{ $setting ? $setting->signatory1 : '' }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="example-fileinput" class="form-label">Signature 1</label>
                                            <input type="file" class="dropify" name="img1"
                                                data-default-file="{{ $setting ? Storage::url($setting->signature1) : '' }}"
                                                data-max-file-size="3M" />
                                        </div>


                                    </div> <!-- end col -->

                                    <div class="col-lg-4">

                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Signator 2</label>
                                            <input type="text" id="simpleinput" name="Signator2"
                                                value="{{ $setting ? $setting->signatory2 : '' }}" class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label for="example-fileinput" class="form-label">Signature 2</label>
                                            <input type="file" class="dropify" name="img2"
                                                data-default-file="{{ $setting ? Storage::url($setting->signature2) : '' }}"
                                                data-max-file-size="3M" />
                                        </div>


                                    </div> <!-- end col -->

                                    <div class="col-lg-4">

                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Signator 3</label>
                                            <input type="text" id="simpleinput" name="Signator3"
                                                value="{{ $setting ? $setting->signatory3 : '' }}" class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label for="example-fileinput" class="form-label">Signature 3</label>
                                            <input type="file" class="dropify" name="img3"
                                                data-default-file="{{ $setting ? Storage::url($setting->signature3) : '' }}"
                                                data-max-file-size="3M" />
                                        </div>


                                    </div> <!-- end col -->

                                    <div class="row mb-3">
                                        <label for="" class="form-label">Placeholder 1</label>
                                        <div class="col-lg-12">
                                            <textarea class="form-control" name="placeholder" placeholder="Description" id="editor" style="height: 100px;">{{ $setting ? $setting->placeholder : '' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label for="" class="form-label">Placeholder 2</label>
                                        <div class="col">
                                            <textarea class="form-control" id="editor2" name="placeholder2" placeholder="Description" style="height: 100px;">{{ $setting ? $setting->placeholder2 : '' }}</textarea>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-xs btn-success">Update</button>
                                    </div>
                                </div> <!-- end card-body -->
                            </form>
                            <!-- end row-->
                        </div> <!-- end preview-->
                        <div class="tab-pane" id="input-types-code">
                            <pre class="mb-0">
                                <span class="html escape">

                                </span>
                            </pre> <!-- end highlight-->
                        </div>
                    </div> <!-- end tab-content-->
                </div>

            </div> <!-- end card -->
        </div>
    </div>
@endsection


@push('extra-js')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('.dropify').dropify();
    </script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor2'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
