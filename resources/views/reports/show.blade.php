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
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/super-build/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/37.0.0/classic/ckeditor.js"></script>
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
                                            <option {{ $title->status != 0 ? 'selected' : '' }} value="{{ $title->title }}">
                                                {{ $title->title }} {{ $title->status != 0 ? '(Par defaut)' : '' }}</option>
                                        @else
                                            <option value="{{ $title->title }}"
                                                {{ $report->title == $title->title ? 'selected' : '' }}>
                                                {{ $title->title }} {{ $title->status != 0 ? '(Par defaut)' : '' }}
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
                    <h5 class="card-header">Votre code</h5>

                    <div class="card-body">
                        <div style="margin-left: 30px" id="qrcode"></div>
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
        const ck_options2 = {
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
        var checkbox = document.getElementById("switch3");
        var textField = document.getElementById("show-field");

        checkbox.addEventListener('change', function() {
            if (checkbox.checked) {
                textField.style.display = 'block';
            } else {
                textField.style.display = 'none';
            }
        });
        var code = {!! json_encode($report->order->code) !!}
        var codeqr = new QRCode(document.getElementById("qrcode"), {
            text: code,
            width: 120,
            height: 120,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            var report = {!! json_encode($report) !!}
            $('#datatable1').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "columnDefs": [{
                    "targets": [0],
                    "searchable": false
                }],
                "language": {
                    "lengthMenu": "Afficher _MENU_ enregistrements par page",
                    "zeroRecords": "Aucun enregistrement disponible",
                    "info": "Afficher page _PAGE_ sur _PAGES_",
                    "infoEmpty": "Aucun enregistrement disponible",
                    "infoFiltered": "(filtré à partir de _MAX_ enregistrements au total)",
                    "sSearch": "Rechercher:",
                    "paginate": {
                        "previous": "Précédent",
                        "next": "Suivant"
                    }
                },
            });

            function autoSave(editor) {
                // Sauvegarder les données de l'éditeur dans la base de données ou tout autre système de stockage
                const editorData = editor;
                // Exemple de sauvegarde des données avec Ajax et jQuery
                $.ajax({
                    type: 'POST',
                    url: 'report/auto',
                    data: {
                        content: editorData
                        report_id: report.id
                    },
                    success: function(data) {
                        console.log(data);
                        // Afficher un message à l'utilisateur ou effectuer d'autres actions si la sauvegarde a réussi
                    },
                    error: function(error) {
                        console.log(error);
                        // Afficher un message d'erreur à l'utilisateur ou effectuer d'autres actions si la sauvegarde a échoué
                    }
                });
            }

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
                                .create(document.querySelector('#editor'),
                                    plugins: [
                                        Autosave,
                                    ],
                                    autosave: {
                                        waitingTime: 5000,
                                        save(editor) {
                                            return autoSave(editor.getData());
                                        }
                                    }
                                    ck_options)
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



            $('#template_supplementaire').on('change', function(e) {
                var template_id = $('#template_supplementaire').val();
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
                            $('#editor2').val(data.content);

                            CKEDITOR.ClassicEditor
                                .create(document.querySelector('#editor2'), ck_options2)
                                .then(editor => {})
                                .catch(error => {
                                    console.error(error);
                                });

                            document.querySelector('.ck-editor__editable2').ckeditorInstance
                                .destroy()

                        } else {
                            $('#editor2').val("Texte");

                            ClassicEditor
                                .create(document.querySelector('#editor2'))
                                .then(editor => {})
                                .catch(error => {
                                    console.error(error);
                                });

                            document.querySelector('.ck-editor__editable2').ckeditorInstance
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
    {{-- <script src="{{ asset('adminassets/js/vendor/quill.min.js') }}"></script> --}}
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
