@extends('layouts.app2')

@section('title', 'Mise à jour consultation')

@section('css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css"
        integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .ck-editor__editable[role="textbox"] {
            /* editing area */
            min-height: 200px;
        }
    </style>

    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/super-build/ckeditor.js"></script>

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3 mb-1">
                    <a href="{{ route('consultation.index') }}" type="button" class="btn btn-primary">Retour à la liste des
                        consultations</a>
                </div>
                <h4 class="page-title">Consultation : {{ $consultation->code }} </h4>
            </div>
        </div>
    </div>

    <div class="">

        @include('layouts.alerts')

        <div class="row">
            <!-- Contenu du compte rendu -->
            <div class="col-9">
                {{-- Card données --}}
                <div class="card mb-md-0 mb-3">
                    <h5 class="card-header">Consultation</h5>
                    <div class="card-body">
                        <div id="cardCollpase1" class="show collapse pt-3">

                            <form action="{{ route('consultation.updateDoctor', $consultation->id) }}" id="consultationForm"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                <div id="basicwizard">

                                    <ul class="nav nav-pills nav-justified form-wizard-header mb-4">
                                        <li class="nav-item">
                                            <a href="#basictab1" data-bs-toggle="tab" data-toggle="tab"
                                                class="nav-link rounded-0 pt-2 pb-2">
                                                <i class="mdi mdi-account-circle me-1"></i>
                                                <span class="d-none d-sm-inline">Categorie de consultation</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#basictab2" data-bs-toggle="tab" data-toggle="tab"
                                                class="nav-link rounded-0 pt-2 pb-2">
                                                <i class="mdi mdi-face-profile me-1"></i>
                                                <span class="d-none d-sm-inline">Observations</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#basictab3" data-bs-toggle="tab" data-toggle="tab"
                                                class="nav-link rounded-0 pt-2 pb-2">
                                                <i class="mdi mdi-checkbox-marked-circle-outline me-1"></i>
                                                <span class="d-none d-sm-inline">Fichiers attachés</span>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content b-0 mb-0">
                                        <div class="tab-pane" id="basictab1">
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <label for="exampleFormControlInput1" class="form-label">Type de
                                                        consultation<span style="color:red;">*</span></label>
                                                    <select class="form-select select2" data-toggle="select2" name="type_id"
                                                        onchange="update_type_consultation({{ $consultation->id }}, this)"
                                                        id="type_id" required>
                                                        <option>Sélectionner le type de consultation</option>
                                                        @foreach ($types as $type)
                                                            <option value="{{ $type->id }}"
                                                                {{ $consultation->type_consultation_id == $type->id ? 'selected' : '' }}>
                                                                {{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div> <!-- end col -->
                                            </div> <!-- end row -->
                                        </div>

                                        <div class="tab-pane" id="basictab2">
                                            <div class="row mb-3">

                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label class="control-label form-label">Motif</label>
                                                        <textarea name="motif" class="form-control" id="" cols="30" rows="5"> {{ $consultation->motif }} </textarea>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label class="control-label form-label">Anamnèse</label>
                                                        <textarea name="anamnese" class="form-control" id="" cols="30" rows="5">{{ $consultation->anamnese }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label class="control-label form-label">Antécédent</label>
                                                        <textarea name="antecedent" class="form-control" id="" cols="30" rows="5"> {{ $consultation->antecedent }} </textarea>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label class="control-label form-label">Examen physique</label>
                                                        <textarea name="examen_physique" class="form-control" id="" cols="30" rows="5">{{ $consultation->examen_physique }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label class="control-label form-label">Diagnostic</label>
                                                        <textarea name="diagnostic" class="form-control" id="" cols="30" rows="5">{{ $consultation->diagnostic }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label class="control-label form-label">Traitement</label>
                                                        <textarea name="traitement" class="form-control" id="" cols="30" rows="5">{{ $consultation->diagnostic }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="basictab3">
                                            <div class="row mb-3">
                                                @if (!empty($consultation->type))
                                                    @forelse ($consultation->type->type_files as $type_file)
                                                        <div class="col-md-6 mb-3">
                                                            <label
                                                                class="control-label form-label">{{ $type_file->title }}</label>
                                                            <input type="file" class="dropify"
                                                                name="type_file[{{ $type_file->id }}]"
                                                                data-default-file="{{ !empty(getConsultationTypeFiles($consultation->id, $type_file->id)->path) ? Storage::url(getConsultationTypeFiles($consultation->id, $type_file->id)->path) : '' }}"
                                                                data-max-file-size="3M" />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Comment<span
                                                                    style="color:red;">*</span></label>
                                                            <textarea name="comment[{{ $type_file->id }}]" class="form-control" id="" cols="30" rows="8">{{ !empty(getConsultationTypeFiles($consultation->id, $type_file->id)->comment) ? getConsultationTypeFiles($consultation->id, $type_file->id)->comment : '' }}</textarea>
                                                        </div>
                                                    @empty
                                                    @endforelse
                                                @endif
                                            </div>
                                        </div>

                                        <ul class="list-inline wizard mb-0">
                                            <li class="previous list-inline-item">
                                                <a href="#" class="btn btn-info">Retour</a>
                                            </li>

                                            @if (!empty($consultation->type))
                                                <li class="next list-inline-item float-end">
                                                    <a href="#" class="btn btn-info">Suivant</a>
                                                </li>
                                            @endif

                                        </ul>

                                    </div> <!-- tab-content -->
                                </div> <!-- end #basicwizard-->
                            </form>

                        </div>
                    </div>
                </div>

            </div>

            <!-- Colonne laterale -->
            <div class="col-3">

                <!-- Etat du compte rendu -->
                <div class="card mb-md-0 mb-3">
                    <h5 class="card-header">Info Consultation</h5>
                    <div class="card-body">
                        <p><b>Code</b> : {{ $consultation->code }}</p>
                        <p><b>Statut</b> : {{ $consultation->status == 'pending' ? 'En cours' : 'Terminé' }}</p>
                        <p><b>Créé le</b> :
                            {{ empty($consultation->created_at) ? '' : date_format($consultation->created_at, 'd/m/Y') }}
                        </p>
                        <p><b>Dernière mise à jour</b> :
                            {{ empty($consultation->updated_at) ? '' : date_format($consultation->updated_at, 'd/m/Y') }}
                        </p>
                        <p><b>Categorie de consultation</b> :
                            {{ !empty($consultation->type) ? $consultation->type->name : '' }}</p>
                        <p><b>Prestation</b> :
                            {{ empty($consultation->prestation) ? '' : $consultation->prestation->name }}</p>
                        <p><b>Prochaine consultation</b> :
                            {{ empty($consultation->next_appointment) ? '' : date_format($consultation->next_appointment, 'd/m/Y') }}
                        </p>
                    </div>
                </div>

                <!-- Info patient -->
                <div class="card mb-md-0 mb-3 mt-3">
                    <h5 class="card-header">Informations patient</h5>

                    <div class="card-body">
                        <p><b>Nom</b> : {{ $consultation->patient->lastname }} {{ $consultation->patient->firstname }}</p>
                        <p><b>Code patient</b> : {{ $consultation->patient->code }}</p>
                        <p><b>Téléphone</b> : {{ $consultation->patient->telephone1 }}</p>
                    </div>
                </div>

                <div class="mb-md-0 mb-3 mt-3">
                    <div class="page-title">
                        <a href="#" target="_blank" rel="noopener noreferrer" type="button"
                            class="btn btn-secondary">
                            <i class="uil uil-print"> Dossier Patient</i>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection

@push('extra-js')
    {{-- <script src="{{ asset('adminassets/js/pages/demo.form-wizard.js') }}"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('.dropify').dropify();
    </script>

    <script>
        const ck_options = {
            // https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format
            toolbar: {
                items: [

                    'findAndReplace', 'selectAll', '|',
                    'heading', '|',
                    'bold', 'italic', 'underline', 'code', 'removeFormat', '|',
                    'bulletedList', 'numberedList', 'todoList', '|',
                    'outdent', 'indent', '|',
                    'undo', 'redo', '-',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                    'alignment', '|',
                    'link', 'blockQuote', '|',
                    'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                    'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            },
            // Changing the language of the interface requires loading the language file using the <script> tag.
            // language: 'es',
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
            heading: {
                options: [{
                        model: 'paragraph',
                        title: 'Paragraph',
                        class: 'ck-heading_paragraph'
                    },
                    {
                        model: 'heading1',
                        view: 'h1',
                        title: 'Heading 1',
                        class: 'ck-heading_heading1'
                    },
                    {
                        model: 'heading2',
                        view: 'h2',
                        title: 'Heading 2',
                        class: 'ck-heading_heading2'
                    },
                    {
                        model: 'heading3',
                        view: 'h3',
                        title: 'Heading 3',
                        class: 'ck-heading_heading3'
                    },
                    {
                        model: 'heading4',
                        view: 'h4',
                        title: 'Heading 4',
                        class: 'ck-heading_heading4'
                    },
                    {
                        model: 'heading5',
                        view: 'h5',
                        title: 'Heading 5',
                        class: 'ck-heading_heading5'
                    },
                    {
                        model: 'heading6',
                        view: 'h6',
                        title: 'Heading 6',
                        class: 'ck-heading_heading6'
                    }
                ]
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-family-feature
            fontFamily: {
                options: [
                    'default',
                    'Arial, Helvetica, sans-serif',
                    'Courier New, Courier, monospace',
                    'Georgia, serif',
                    'Lucida Sans Unicode, Lucida Grande, sans-serif',
                    'Tahoma, Geneva, sans-serif',
                    'Times New Roman, Times, serif',
                    'Trebuchet MS, Helvetica, sans-serif',
                    'Verdana, Geneva, sans-serif'
                ],
                supportAllValues: true
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
            fontSize: {
                options: [10, 12, 14, 'default', 18, 20, 22],
                supportAllValues: true
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/link.html#custom-link-attributes-decorators
            link: {
                decorators: {
                    addTargetToExternalLinks: true,
                    defaultProtocol: 'https://',
                    toggleDownloadable: {
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'file'
                        }
                    }
                }
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html#configuration
            mention: {
                feeds: [{
                    marker: '@',
                    feed: [
                        '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes',
                        '@chocolate', '@cookie', '@cotton', '@cream',
                        '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread',
                        '@gummi', '@ice', '@jelly-o',
                        '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding',
                        '@sesame', '@snaps', '@soufflé',
                        '@sugar', '@sweet', '@topping', '@wafer'
                    ],
                    minimumCharacters: 1
                }]
            },
            // The "super-build" contains more premium features that require additional configuration, disable them below.
            // Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
            removePlugins: [
                // These two are commercial, but you can try them out without registering to a trial.
                // 'ExportPdf',
                // 'ExportWord',
                'CKBox',
                'CKFinder',
                'EasyImage',
                // This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
                // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
                // Storing images as Base64 is usually a very bad idea.
                // Replace it on production website with other solutions:
                // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
                // 'Base64UploadAdapter',
                'RealTimeCollaborativeComments',
                'RealTimeCollaborativeTrackChanges',
                'RealTimeCollaborativeRevisionHistory',
                'PresenceList',
                'Comments',
                'TrackChanges',
                'TrackChangesData',
                'RevisionHistory',
                'Pagination',
                'WProofreader',
                // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                // from a local file system (file://) - load this site via HTTP server if you enable MathType
                'MathType'
            ]
        };
        CKEDITOR.ClassicEditor.create(document.getElementById("editor"), ck_options);
    </script>

    <script>
        function update_type_consultation(consultationId, typeId) {
            var consultationId = consultationId;
            var typeId = typeId.value;
            // console.log(typeId.value)
            $.ajax({
                url: "{{ route('consultation.updateTypeConsultation') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    consultation_id: consultationId,
                    type_consultation_id: typeId
                },
                success: function(data) {

                    toastr.success("Categorie de consultation", 'Consultation mis a jour');
                    window.location.reload()
                },
                error: function(data) {
                    console.log(data)
                },
                // processData: false,
            });

        }
    </script>

    {{-- Form wizard personal script --}}
    <script>
        $(function() {
            "use strict";
            // $("#basicwizard").bootstrapWizard()
            $("#basicwizard").bootstrapWizard({
                onNext: function(t, r, a) {
                    var o = $($(t).data("targetForm"));
                    var x = document.getElementsByClassName("tab-pane");

                    if (a >= x.length) {
                        // ... the form gets submitted:
                        document.getElementById("consultationForm").submit();
                        // return false;
                    }

                },
            });
        });
    </script>
@endpush
