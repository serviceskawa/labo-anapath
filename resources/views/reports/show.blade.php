@extends('layouts.app2')

@section('title', 'Details')

@section('css')

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
                    <a href="{{ route('report.index') }}" type="button" class="btn btn-primary">Retour à la liste des comptes rendus</a>
                </div>
                <h4 class="page-title">Compte rendu</h4>
            </div>
        </div>
    </div>


    <div class="">


        @include('layouts.alerts')

        
        <div class="row">
            <!-- Contenu du compte rendu -->
            <div class="col-9">
                <div class="card mb-md-0 mb-3">
                   <!-- <h5 class="card-header">Contenu du compte rendu</h5> -->

                    <div class="card-body">
                        <div id="cardCollpase1" class="collapse pt-3 show">
                            <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                            
                            <form action="{{ route('report.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Template</label>
                                        <select class="form-select" id="template" name="">
                                            <option value="">Sélectionner un template</option>
                                            @forelse ($templates as $template)
                                                <option value="{{ $template->id }}">{{ $template->title }} </option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" name="report_id" value="{{ $report->id }}">

                                <div class="row">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label ">Récapitulatifs<span style="color:red;">*</span></label>
                                        <textarea name="content" id="editor" class="form-control mb-3" cols="30" rows="50" style="height: 500px;" required>{{ $report->description }}</textarea>
                                    </div> 
                                </div>

                                <div class="row my-3">
                                    <label for="simpleinput" class="form-label ">Cocher les signataires du compte rendu<span
                                            style="color:red;">*</span></label>
                                    <div class="">

                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input"
                                                {{ $report->signatory1 == '1' ? 'checked' : '' }} name="signatory1"
                                                id="customCheck3">
                                            <label class="form-check-label" for="customCheck3">{{ $setting->signatory1 }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input"
                                                {{ $report->signatory2 == '1' ? 'checked' : '' }} name="signatory2"
                                                id="customCheck3">
                                            <label class="form-check-label" for="customCheck3">{{ $setting->signatory2 }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input"
                                                {{ $report->signatory3 == '1' ? 'checked' : '' }} name="signatory3"
                                                id="customCheck3">
                                            <label class="form-check-label" for="customCheck3">{{ $setting->signatory3 }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label mb-3">Etat du compte rendu<span
                                                style="color:red;">*</span></label>
                                        <select class="form-select" name="status">
                                            <option value="0" {{ $report->status == 0 ? 'selected' : '' }}>En attente de relecture</option>
                                            <option value="1" {{ $report->status == 1 ? 'selected' : '' }}>Terminé</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class=" mt-3 btn btn-warning w-100">Mettre à jour</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne laterale -->
            <div class="col-3">

                <!-- Etat du compte rendu -->
                <div class="card mb-md-0 mb-3">
                    <h5 class="card-header">État du compte rendu</h5>
                    <div class="card-body">
                        <p><b>État</b> : {{ $report->status == 0 ? 'En attente de relecture' : 'Terminé' }}</p>
                        <p><b>Créé le</b> : {{ date_format($report->created_at,"d/m/Y") }}</p>
                        <p><b>Dernière mise à jour</b> :  {{ date_format($report->updated_at,"d/m/Y") }}</p>
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
                        <p><b>Signature 1</b> : {{ $report->signatory1 == 0 ? 'Inactif' : getSignatory1(1) }}</p>
                        <p><b>Signature 2</b> : {{ $report->signatory2 == 0 ? 'Inactif' : getSignatory2(1) }}</p>
                        <p><b>Signature 3</b> : {{ $report->signatory3 == 0 ? 'Inactif' : getSignatory3(1) }}</p>
                    </div>

                </div>

                <div class="mb-md-0 mb-3 mt-3">
                    <div class="page-title">
                        <a href="{{ route('report.pdf', $report->id) }}" target="_blank" rel="noopener noreferrer" type="button" class="btn btn-secondary">
                                <i class="uil uil-print">Imprimer le compte rendu</i>
                        </a>
                    </div>
                </div>

            </div>

            
        </div>


    </div>
@endsection


@push('extra-js')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                // console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    </script>

    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#template').on('change', function(e) {
                var template_id = $('#template').val();
                const report = {!! json_encode($report) !!};

                $.ajax({
                    url: "{{ route('template.report-getTemplate') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: template_id,
                    },
                    success: function(data) {
                        console.log(data);
                        // $('#page_id').val()
                        if (data) {
                            $('#editor').val(data.content);

                            ClassicEditor
                                .create(document.querySelector('#editor'))
                                .then(editor => {})
                                .catch(error => {
                                    console.error(error);
                                });

                            document.querySelector('.ck-editor__editable').ckeditorInstance
                                .destroy()

                        } else {
                            $('#editor').val("Texte");

                            ClassicEditor
                                .create(document.querySelector('#editor'))
                                .then(editor => {})
                                .catch(error => {
                                    console.error(error);
                                });

                            document.querySelector('.ck-editor__editable').ckeditorInstance
                                .destroy()

                        }

                    },
                    error: function(error) {
                        $('#editor').val(report.description);

                        ClassicEditor
                            .create(document.querySelector('#editor'))
                            .then(editor => {})
                            .catch(error => {
                                console.error(error);
                            });

                        document.querySelector('.ck-editor__editable').ckeditorInstance
                            .destroy()

                    }
                })
            });
        });
    </script>
@endpush
