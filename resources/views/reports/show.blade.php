@extends('layouts.app2')

@section('title', 'Details')

@section('css')

    <style>
        .ck-editor__editable[role="textbox"] {
            /* editing area */
            min-height: 200px;
        }

        .ck-editor__editable2[role="textbox"] {

            min-height: 200px;
        }
    </style>

    <!-- Quill css -->
    <link href="{{ asset('adminassets/css/vendor/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('adminassets/css/vendor/quill.snow.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('adminassets/css/vendor/simplemde.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('adminassets/js/vendor/simplemde.min.js') }}"></script>
    <script src="{{ asset('adminassets/js/vendor/quill.min.js') }}"></script>
    {{-- <script src="{{asset('adminassets/js/pages/demo.simplemde.js')}}"></script> --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/super-build/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>


@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3 mb-1">
                    <a href="{{ route('report.index') }}" type="button" class="btn btn-primary">Retour à la liste des
                        comptes rendus</a>
                </div>
                <h4 class="page-title">Compte rendu : {{ $report->code }}</h4>
            </div>
        </div>
    </div>

    <div class="">

        @include('layouts.alerts')

        <div class="row">
            <!-- Contenu du compte rendu -->
            <div class="col-9">
                <div style="text-align:right; margin-bottom:10px;"><span style="color:red;">*</span>champs obligatoires
                </div>

                <form action="{{ route('report.store') }}" id="reportForm" method="post">
                    @csrf
                    <input type="hidden" name="report_id" value="{{ $report->id }}">

                    <div class="card mb-md-0 mb-3">
                        <div class="card-body">
                            <div class="row">
                                <label for="simpleinput" class="form-label">Titre <span style="color:red;">*</span></label>
                                <select class="form-select" id="title" name="title" required>
                                    {{-- <option value="">Sélectionner un titre</option> --}}
                                    @forelse ($titles as $title)
                                        @if ($report->title == '')
                                            <option {{ $title->status != 0 ? 'selected' : '' }} style="font-weight:{{ $title->status !=0 ? 'bold':'' }}"
                                                value="{{ $title->title }}">
                                                {{ $title->title }}
                                            </option>
                                        @else
                                            <option value="{{ $title->title }}" {{ $report->title == $title->title ? 'selected' : '' }}>

                                                <span style="font-weight:{{ $title->status !=0 ? 'bold':'' }} font-style:{{ $title->status !=0 ? 'italic':'' }}">
                                                    {{ $title->title }}
                                                </span>
                                            </option>
                                        @endif
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="card mb-md-0 mb-3 mt-3">
                        <h5 class="card-header">Contenu de base</h5>
                        <div class="card-body">

                            <div class="row">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Template</label>
                                    <select class="form-select" id="template" name="template">
                                        <option value="">Sélectionner un template</option>
                                        @forelse ($templates as $template)
                                            <option value="{{ $template->id }}">{{ $template->title }} </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Récapitulatifs<span
                                            style="color:red;">*</span></label>
                                    <textarea name="content" id="editor" class="form-control mb-3" cols="30" rows="50" style="height: 500px;">{{ $report->description }}</textarea>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div id="show-field" style="{{ $report->description_supplementaire == null ? 'display: none;' : '' }}"
                        class="card mb-md-0 mb-3 mt-3">
                        <h5 class="card-header">Contenu complémentaire</h5>
                        <div class="card-body">

                            <div class="row">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Template</label>
                                    <select class="form-select" id="template_supplementaire" name="">
                                        <option value="">Sélectionner un template</option>
                                        @forelse ($templates as $template)
                                            <option value="{{ $template->id }}">{{ $template->title }} </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 supplementaireid">
                                    <label for="simpleinput" class="form-label">Récapitulatifs<span
                                            style="color:red;">*</span></label>
                                    {{-- <div id="snow-editor" style="height: 300px;">
                                        {{ $report->description_supplementaire }}
                                    </div> --}}
                                    {{-- <textarea id="simplemde1">{{ $report->description_supplementaire }}</textarea> --}}
                                    <textarea name="description_supplementaire" id="editor2" class="form-control mb-3" cols="15" rows="10"
                                        style="height: 250px;">{{ $report->description_supplementaire }}</textarea>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card mb-md-0 mb-3 mt-3">
                        <h5 class="card-header">Signataires du compte rendu<span style="color:red;">*</span></h5>
                        <div class="card-body">
                            <div class="row my-3">
                                <div class="">
                                    <div class="my-3  form-check-inline">
                                        <label for="example-fileinput" class="form-label">Signatiare 1</label>
                                        <select name="doctor_signataire1" id="doctor_signataire1" class="form-control">
                                            <option value="">Selectionner un docteur</option>
                                            @foreach (getUsersByRole('docteur') as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $report->signatory1 == $item->id ? 'selected' : '' }}>
                                                    {{ $item->lastname }} {{ $item->firstname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="m-3 form-check-inline">
                                        <label for="example-fileinput" class="form-label">Signatiare 2</label>
                                        <select name="doctor_signataire2" id="doctor_signataire2" class="form-control">
                                            <option value="">Selectionner un docteur</option>
                                            @foreach (getUsersByRole('docteur') as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $report->signatory2 == $item->id ? 'selected' : '' }}>
                                                    {{ $item->lastname }} {{ $item->firstname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="m-3 form-check-inline">
                                        <label for="example-fileinput" class="form-label">Signataire 3</label>
                                        <select name="doctor_signataire3" id="doctor_signataire3" class="form-control">
                                            <option value="">Selectionner un docteur</option>
                                            @foreach (getUsersByRole('docteur') as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $report->signatory3 == $item->id ? 'selected' : '' }}>
                                                    {{ $item->lastname }} {{ $item->firstname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label mb-3">Etat du compte rendu<span
                                            style="color:red;">*</span></label>
                                    <select class="form-select" name="status">
                                        <option value="0" {{ $report->status == 0 ? 'selected' : '' }}>En attente
                                            de
                                            relecture</option>
                                        <option value="1" {{ $report->status == 1 ? 'selected' : '' }}>Terminé
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning w-100 mt-3">Mettre à jour</button>
                        </div>
                    </div>


                </form>

            </div>

            <!-- Colonne laterale -->
            <div class="col-3">

                <!-- Etat du compte rendu -->
                <div class="card mb-md-0 mb-3">
                    <h5 class="card-header">État du compte rendu</h5>
                    <div class="card-body">
                        <p><b>État</b> : {{ $report->status == 0 ? 'En attente de relecture' : 'Terminé' }}</p>
                        <p><b>Créé le</b> : {{ date_format($report->created_at, 'd/m/Y') }}</p>
                        <label class="form-label">Complémentaire</label><br>
                        <input type="checkbox" id="switch3" class="form-control"
                            {{ $report->description_supplementaire != null ? 'checked' : '' }} data-switch="success" />
                        <label for="switch3" data-on-label="oui" data-off-label="non"></label>
                        <p><b>Dernière mise à jour</b> : {{ date_format($report->updated_at, 'd/m/Y') }}</p>
                    </div>
                </div>

                <!-- Info patient -->
                <div class="card mb-md-0 mb-3 mt-3">
                    <h5 class="card-header">Informations patient</h5>

                    <div class="card-body">
                        <p><b>Nom</b> : {{ $report->patient->lastname }} {{ $report->patient->firstname }}</p>
                        <p><b>Code patient</b> : {{ $report->patient->code }}</p>
                        <p><b>Téléphone</b> : {{ $report->patient->telephone1 }}</p>
                    </div>
                </div>

                <div class="card mb-md-0 mb-3 mt-3">
                    <h5 class="card-header">Signataires</h5>

                    <div class="card-body">
                        <p><b>Signature 1</b> :
                            {{ $report->signatory1 == null ? 'Inactif' : getSignatory1($report->signatory1) }}</p>
                        <p><b>Signature 2</b> :
                            {{ $report->signatory2 == null ? 'Inactif' : getSignatory1($report->signatory2) }}</p>
                        <p><b>Signature 3</b> :
                            {{ $report->signatory3 == null ? 'Inactif' : getSignatory1($report->signatory3) }}</p>
                    </div>

                </div>

                <div class="card mb-md-0 mb-3 mt-3">
                    <h5 class="card-header">Code ANAPATH</h5>

                    <div class="card-body">
                        <div style="margin-left: 30px" id="qrcode"></div>
                    </div>

                </div>

                <div class="mb-md-0 mb-3 mt-3">
                    <div class="page-title">
                        {{-- <button type="button" class="btn btn-secondary" onclick="passwordTest({{ $item->id }})"><i
                                class="uil uil-print">Imprimer le compte rendu</i></button> --}}
                        <a href="{{ route('report.pdf', $report->id) }}" target="_blank" rel="noopener noreferrer"
                            type="button" class="btn btn-secondary">Imprimer le compte rendu
                            </i>
                        </a>
                    </div>
                </div>

            </div>

        </div>

        <div class="col-12">
            @if ($logs)
                <div class="card mb-md-0 mb-3 mt-3">
                    <h5 class="card-header">Historiques</h5>
                    <div class="card-body">
                        <table id="datatable1" class="table-striped dt-responsive nowrap w-100 table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Opération</th>
                                    <th>Utilisateur</th>

                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($logs as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td>{{ $log->created_at }}</td>
                                        <td>{{ $log->operation }}</td>
                                        <td>{{ $log->user->lastname }} {{ $log->user->firstname }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection

@push('extra-js')
    <script>
        const report = {!! json_encode($report)!!}
        var code = {!! json_encode($report -> code)!!}
        var ROUTEGETTEMPLATE = "{{ route('template.report-getTemplate') }}"
        var TOKENGETTEMPLATE = "{{ csrf_token() }}"

    </script>
    <script src="{{asset('viewjs/report/show.js')}}"></script>
@endpush
