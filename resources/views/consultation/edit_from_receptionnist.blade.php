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
                    <h5 class="card-header">Contenu du compte rendu</h5>
                    <div class="card-body">
                        <div id="cardCollpase1" class="show collapse pt-3">
                            <form action="#" method="post" enctype="multipart/form-data">
                                @csrf

                                {{-- <div class="row mb-3">

                                    <div class="col-md-6">
                                        <label for="exampleFormControlInput1" class="form-label">Patient <span
                                                style="color:red;">*</span></label>
                                        <select class="form-select select2" data-toggle="select2" name="patient_id"
                                            id="patient_id" required disabled>
                                            <option>Sélectionner le nom du patient</option>
                                            @foreach ($patients as $patient)
                                                <option value="{{ $patient->id }}"
                                                    {{ $consultation->patient_id == $patient->id ? 'selected' : '' }}>
                                                    {{ $patient->code }} - {{ $patient->firstname }}
                                                    {{ $patient->lastname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleFormControlInput1" class="form-label">Médecin traitant<span
                                                style="color:red;">*</span></label>
                                        <select class="form-select select2" data-toggle="select2" name="doctor_id"
                                            id="doctor_id" required disabled>
                                            <option>Sélectionner le médecin traitant</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}"
                                                    {{ $consultation->doctor_id == $doctor->id ? 'selected' : '' }}>
                                                    {{ $doctor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div> --}}

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="exampleFormControlInput1" class="form-label">Type de consultation<span
                                                style="color:red;">*</span></label>
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
                                    </div>
                                    {{-- <div class="col-md-3">
                                        <label for="exampleFormControlInput1" class="form-label">Prestations<span
                                                style="color:red;">*</span></label>
                                        <select class="form-select select2" data-toggle="select2" name="prestation_id"
                                            id="prestation_id" required disabled>
                                            <option>Sélectionner la prestation</option>
                                            @foreach ($prestations as $prestation)
                                                <option value="{{ $prestation->id }}"
                                                    {{ $consultation->prestation_id == $prestation->id ? 'selected' : '' }}>
                                                    {{ $prestation->name }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="col-md-6">
                                        <label class="control-label form-label">Statut</label>
                                        <select class="form-select" name="status" id="priority" required="">
                                            <option value="pending"
                                                {{ $consultation->status == 'pending' ? 'selected' : '' }}>En cours
                                            </option>
                                            <option value="approved"
                                                {{ $consultation->status == 'approved' ? 'selected' : '' }}>
                                                Approuvé
                                            </option>
                                            <option value="cancel"
                                                {{ $consultation->status == 'cancel' ? 'selected' : '' }}>Rejetter
                                            </option>
                                        </select>
                                    </div>
                                    {{-- <div class="col-md-3">
                                        <label class="form-label">Heure <span style="color:red;">*</span></label>
                                        <input type="datetime-local" class="form-control" name="date"
                                            value="{{ $consultation->date }}" disabled>
                                    </div> --}}
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

                {{-- Card files --}}
                <form action="{{ route('consultation.updateDoctor', $consultation->id) }}" id="consultationForm"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-md-0 mt-3">
                        <h5 class="card-header">Obersvations</h5>

                        <div class="card-body">
                            <div id="cardCollpase1" class="show collapse pt-3">

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
                        </div>

                    </div>
                    <div class="card mb-md-0 mt-3">
                        <h5 class="card-header">Fichiers attachés</h5>

                        <div class="card-body">
                            <div id="cardCollpase1" class="show collapse pt-3">

                                <div class="row mb-3">
                                    @if (!empty($consultation->type))
                                        @forelse ($consultation->type->type_files as $type_file)
                                            <div class="col-md-6 mb-3">
                                                <label class="control-label form-label">{{ $type_file->title }}</label>
                                                <input type="file" class="dropify"
                                                    name="type_file[{{ $type_file->id }}]"
                                                    data-default-file="{{ !empty(getConsultationTypeFiles($consultation->id, $type_file->id)->path) ? Storage::url(getConsultationTypeFiles($consultation->id, $type_file->id)->path) : '' }}"
                                                    data-max-file-size="3M" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Comment<span style="color:red;">*</span></label>
                                                <textarea name="comment[{{ $type_file->id }}]" class="form-control" id="" cols="30" rows="8">{{ !empty(getConsultationTypeFiles($consultation->id, $type_file->id)->comment) ? getConsultationTypeFiles($consultation->id, $type_file->id)->comment : '' }}</textarea>
                                            </div>
                                        @empty
                                        @endforelse
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-warning w-100 mt-3">Mettre à jour</button>

                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <!-- Colonne laterale -->
            <div class="col-3">

                <!-- Etat du compte rendu -->
                <div class="card mb-md-0 mb-3">
                    <h5 class="card-header">Info Consultation</h5>
                    <div class="card-body">
                        <p><b>Code</b> : {{ $consultation->code }}</p>
                        <p><b>Statut</b> : {{ $consultation->status == 'pending' ? 'En cours' : 'Terminé' }}</p>
                        <p><b>Créé le</b> : {{ date_format($consultation->created_at, 'd/m/Y') }}</p>
                        <p><b>Dernière mise à jour</b> : {{ date_format($consultation->updated_at, 'd/m/Y') }}</p>
                        <p><b>Categorie de consultation</b> :
                            {{ !empty($consultation->type) ? $consultation->type->name : '' }}</p>
                        <p><b>Prestation</b> : {{ $consultation->prestation->name }}</p>
                        <p><b>Prochaine consultation</b> : {{ date_format($consultation->updated_at, 'd/m/Y') }}</p>
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
@endpush
