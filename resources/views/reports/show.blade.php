@extends('layouts.app2')

@section('title', 'Details')

@section('css')

    <style>
        .ck-editor__editable[role="textbox"] {
            /* editing area */
            min-height: 200px;
        }
    </style>

    <!-- Quill css -->
    <link href="{{ asset('adminassets/css/vendor/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('adminassets/css/vendor/quill.snow.css') }}" rel="stylesheet" type="text/css" />
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/super-build/ckeditor.js"></script>

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
                <div class="card mb-md-0 mb-3">
                    <!-- <h5 class="card-header">Contenu du compte rendu</h5> -->

                    <div class="card-body">
                        <div id="cardCollpase1" class="show collapse pt-3">
                            <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                            <form action="{{ route('report.store') }}" id="reportForm" method="post">
                                @csrf
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Template</label>
                                        <select class="form-select" id="template" name="">
                                            <option value="">Sélectionner un template</option>
                                            @forelse ($templates as $template)
                                                <option value="{{ $template->id }}"
                                                    >{{ $template->title }} </option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" name="report_id" value="{{ $report->id }}">

                                <div class="row">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Récapitulatifs<span
                                                style="color:red;">*</span></label>
                                        <textarea name="content" id="editor" class="form-control mb-3" cols="30" rows="50" style="height: 500px;">{{ $report->description }}</textarea>
                                        {{-- <div id="snow-editor" style="height: 300px;">
                                            {!! $report->description !!}
                                        </div>
                                        <textarea name="content" hidden id="snow-editor-hidden" class="form-control mb-3" cols="30" rows="50"
                                            style="height: 500px;"></textarea> --}}
                                    </div>
                                </div>

                                <div class="row my-3">
                                    <label for="simpleinput" class="form-label">Selectionner les signataires du compte rendu<span
                                            style="color:red;">*</span></label>
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
                        <p><b>Créé le</b> : {{ date_format($report->created_at, 'd/m/Y') }}</p>
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
                            {{ $report->signatory2 == null ? 'Inactif' : getSignatory2($report->signatory2) }}</p>
                        <p><b>Signature 3</b> :
                            {{ $report->signatory3 == null ? 'Inactif' : getSignatory3($report->signatory3) }}</p>
                    </div>

                </div>

                <div class="mb-md-0 mb-3 mt-3">
                    <div class="page-title">
                        <a href="{{ route('report.pdf', $report->id) }}" target="_blank" rel="noopener noreferrer"
                            type="button" class="btn btn-secondary">
                            <i class="uil uil-print">Imprimer le compte rendu</i>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection

@push('extra-js')
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script> --}}
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/super-build/ckeditor.js"></script> --}}
    {{-- <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                // console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    </script> --}}

    <script>

    </script>

    <script>
        // This sample still does not showcase all CKEditor 5 features (!)
        // Visit https://ckeditor.com/docs/ckeditor5/latest/features/index.html to browse all the features.
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

                            CKEDITOR.ClassicEditor
                                .create(document.querySelector('#editor'), ck_options)
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

    <!-- quill js -->
    <script src="{{ asset('adminassets/js/vendor/quill.min.js') }}"></script>
    <!-- quill Init js-->
    {{-- <script src="{{ asset('adminassets/js/pages/demo.quilljs.js') }}"></script> --}}
    <script>
        var quill = new Quill("#snow-editor", {
                theme: "snow",
                modules: {
                    toolbar: [
                        [{
                            font: []
                        }, {
                            size: []
                        }],
                        ["bold", "italic", "underline", "strike"],
                        [{
                            color: []
                        }, {
                            background: []
                        }],
                        [{
                            script: "super"
                        }, {
                            script: "sub"
                        }],
                        [{
                            header: [!1, 1, 2, 3, 4, 5, 6]
                        }, "blockquote", "code-block"],
                        [{
                            list: "ordered"
                        }, {
                            list: "bullet"
                        }, {
                            indent: "-1"
                        }, {
                            indent: "+1"
                        }],
                        ["direction", {
                            align: []
                        }],
                        ["link", "image", "video"],
                        ["clean"]
                    ]
                }
            }),
            quill = new Quill("#bubble-editor", {
                theme: "bubble"
            });

        var form = document.querySelector("reportForm");
        var hiddenInput = document.querySelector('#snow-editor-hidden');

        form.onsubmit = function() {
            alert('a')
            // // Populate hidden form on submit
            // var comment = document.querySelector('input[name=comment]');

            // //comment.value = JSON.stringify(quill.getContents());
            // comment.value = quill.root.innerHTML;
            // console.log(comment.value)

            // // console.log("Submitted", $(form).serialize(), $(form).serializeArray());

            // // No back end to actually submit to!
            // // alert('Open the console to see the submit data!')
            // // return false;

        };
    </script>
@endpush
