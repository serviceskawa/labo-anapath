@extends('layouts.app2')

@section('title', 'Paramètres système')

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
                <a href="{{ route('home') }}" type="button" class="btn btn-primary">Acceuil</a>
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
                <h4 class="header-title">Paramètres du Systeme</h4>
                <p class="text-muted font-14">

                </p>

                <ul class="nav nav-tabs nav-bordered mb-3">
                    <li class="nav-item">
                        <a href="#input-types-preview" data-bs-toggle="tab" aria-expanded="false"
                            class="nav-link active">
                            Informations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#input-types-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                            Api key
                        </a>
                    </li>
                </ul> <!-- end nav-->

                <div class="tab-content">
                    <div class="tab-pane show active" id="input-types-preview">
                        <form action="{{ route('settings.app-store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <label for="" class="form-label">Nom du site</label>
                                    <div class="">
                                        <input type="text" name="titre" id="" class="form-control"
                                            value="{{ $setting ? $setting->titre : '' }}">
                                    </div>
                                </div>

                            </div>
                            <div class="row mb-3">



                                <div class="col-lg-4">

                                    <div class="mb-3">
                                        <label for="example-fileinput" class="form-label">Logo</label>
                                        <input type="file" class="dropify" name="logo"
                                            data-default-file="{{ $setting ? Storage::url($setting->logo) : '' }}"
                                            data-max-file-size="3M" />
                                    </div>


                                </div> <!-- end col -->

                                <div class="col-lg-4">

                                    <div class="mb-3">
                                        <label for="example-fileinput" class="form-label">Favicon</label>
                                        <input type="file" class="dropify" name="favicon"
                                            data-default-file="{{ $setting ? Storage::url($setting->favicon) : '' }}"
                                            data-max-file-size="3M" />
                                    </div>


                                </div> <!-- end col -->

                                <div class="col-lg-4">

                                    <div class="mb-3">
                                        <label for="example-fileinput" class="form-label">Logo Blanc</label>
                                        <input type="file" class="dropify" name="img3"
                                            data-default-file="{{ $setting ? Storage::url($setting->logo_blanc) : '' }}"
                                            data-max-file-size="3M" />
                                    </div>


                                </div> <!-- end col -->
                            </div>

                            <div class="row mb-3">
                                <span class="mb-3 header-title">Horaires de travail</span>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">Heure début</label>
                                    <div class="">
                                        <input type="text"name="begining_date" class="form-control"
                                        value="{{ $setting ? $setting->begining_date : '' }}" data-toggle="input-mask" data-mask-format="00:00:00">
                                        <span class="font-13 text-muted">e.g "HH:MM:SS"</span>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="" class="form-label">Heure fin</label>
                                    <div class="">
                                        <input type="text" name="ending_date" class="form-control"
                                        value="{{ $setting ? $setting->ending_date : '' }}" data-toggle="input-mask" data-mask-format="00:00:00">
                                        <span class="font-13 text-muted">e.g "HH:MM:SS"</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <span class="mb-3 header-title">Clés API</span>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">Server SMS</label>

                                    <input type="text" name="server_sms" id="" class="form-control"
                                        value="{{ $setting ? $setting->server_sms : '' }}">
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">Api Key SMS</label>

                                    <input type="text" name="api_key_sms" id="" class="form-control"
                                        value="{{ $setting ? $setting->api_key_sms : '' }}">
                                </div>

                                <div class="col-lg-12 mt-3">
                                    <label for="" class="form-label">Api Key Ourvoice</label>

                                    <input type="text" name="api_key_ourvoice" id="" class="form-control"
                                        value="{{ $setting ? $setting->api_key_ourvoice : '' }}">
                                </div>

                            </div>

                            <div class="card-footer">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-xs btn-success">Mettre à jour</button>
                                </div>
                            </div> <!-- end card-body -->
                        </form>
                        <!-- end row-->
                    </div> <!-- end preview-->


                    {{-- <div class="tab-pane" id="input-types-code">
                        <div class="card-body">
                            <form action="{{route('report.footer-update')}}" method="post">
                                @csrf
                                <div class="row mb-3">
                                    <label for="" class="form-label">Pied de page</label>
                                    <div class="col-lg-12">
                                        <textarea name="footer" id="footer" class="form-control" cols="30" rows="5">{{ $setting->footer ? $setting->footer : '' }}</textarea>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-xs btn-success">Mettre à jour</button>
                                    </div>
                                </div> <!-- end card-body -->
                            </form>
                        </div>
                    </div> --}}

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
<script src="{{asset('viewjs/setting/app.js')}}"></script>
@endpush
