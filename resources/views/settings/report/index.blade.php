@extends('layouts.app2')

@section('title', 'Paramètre du système')

@section('css')
    <link href="{{ asset('/adminassets/css/vendor/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminassets/css/vendor/quill.snow.css') }}" rel="stylesheet" type="text/css" />

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
                        <a href="{{ route('report.index') }}" type="button" class="btn btn-primary">Retour à la liste des comptes rendu</a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#standard-modal">Ajouter un nouveau titre</button>
                    </div>
                    <h4 class="page-title"></h4>

                    @include('settings.report.create')

                    @include('settings.report.edit')
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
                                Titres
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#input-types-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Placeholder
                            </a>
                        </li>
                    </ul> <!-- end nav-->

                    <div class="tab-content">
                        <div class="tab-pane active" id="input-types-preview">
                            <div class="card-body">
                                <div class="card-widgets">
                                    <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                    <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                                        aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                                    <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                                </div>
                                <h5 class="card-title mb-0">Liste des Titres</h5>

                                <div id="cardCollpase1" class="collapse pt-3 show">


                                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                                        <thead class="col-lg-12" style="text-align: center;">
                                            <tr>
                                                <th class="col-lg-2">#</th>
                                                <th class="col-lg-6">Titres</th>
                                                <th class="col-lg-4">Actions</th>

                                            </tr>
                                        </thead>


                                        <tbody>

                                            @foreach ($titles as $item)
                                                <tr>
                                                    <td>{{ $item->id }}</td>
                                                    <td style="font-weight:{{ $item->status !=0 ? 'bold':'' }}">{{ $item->title }} {{ $item->status !=0 ? '(Par defaut)':'' }}</td>
                                                    <td>
                                                        <button type="button" onclick="edit({{ $item->id }})"
                                                            class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </button>
                                                        <button type="button" onclick="deleteModal({{ $item->id }})"
                                                            class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                                                    </td>

                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>

                        <div class="tab-pane" id="input-types-code">
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
                        </div>
                    </div> <!-- end tab-content-->
                </div>

            </div> <!-- end card -->
        </div>
    </div>
@endsection


@push('extra-js')
    <script>
        var baseUrl = "{{ url('/') }}"
    </script>
    <script src="{{asset('viewjs/setting/report.js')}}"></script>
@endpush
