@extends('layouts.app2')

@section('title', 'Reports')

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

            <form class="row" action="{{ route('report.store') }}" id="reportForm" method="post">
                @csrf

                <input type="hidden" name="report_id" value="{{ $report->id }}">

                <div class="col-9">
                    <div style="text-align:right; margin-bottom:10px;"><span style="color:red;">*</span>champs obligatoires
                    </div>

                    <div class="card mb-md-0 mb-3">
                        <div class="card-body">
                            <div class="row">
                                <label for="simpleinput" class="form-label">Titre <span style="color:red;">*</span></label>
                                <select class="form-select" id="title" name="title" required>
                                    @forelse ($titles as $title)
                                        @if ($report->title == '')
                                            <option {{ $title->status != 0 ? 'selected' : '' }}
                                                style="font-weight:{{ $title->status != 0 ? 'bold' : '' }}"
                                                value="{{ $title->title }}">
                                                {{ $title->title }}
                                            </option>
                                        @else
                                            <option value="{{ $title->title }}"
                                                {{ $report->title == $title->title ? 'selected' : '' }}>

                                                <span
                                                    style="font-weight:{{ $title->status != 0 ? 'bold' : '' }} font-style:{{ $title->status != 0 ? 'italic' : '' }}">
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

                            <div>
                                <h4>Macro</h4>
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Template</label>
                                        <select class="form-select select2" data-toggle="select2" id="template"
                                            name="template">
                                            <option value="">Sélectionner un template</option>
                                            @forelse ($templates as $template)
                                                <option value="{{ $template->id }}">{{ $template->title }} </option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <label for="simpleinput" class="form-label">Commentaire</label>

                                <textarea name="comment" class="form-control mb-3" rows="5">{{ $report->comment }}</textarea>

                                <div class="row">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Récapitulatifs<span
                                                style="color:red;">*</span></label>
                                        <textarea name="content" id="editor" class="form-control mb-3" cols="30" rows="50" style="height: 500px;">{{ $report->description }}</textarea>

                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4>Micro</h4>
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Template</label>
                                        <select class="form-select select2" data-toggle="select2" id="template_micro"
                                            name="template">
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
                                        <textarea name="content_micro" id="editor_micro" class="form-control mb-3" cols="30" rows="50"
                                            style="height: 500px;">{{ $report->description_micro }}</textarea>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div id="show-field" style="{{ $report->description_supplementaire == null ? 'display: none;' : '' }}"
                        class="card mb-md-0 mb-3 mt-3">
                        <h5 class="card-header">Contenu complémentaire</h5>
                        <div class="card-body">

                            <div>
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

                                <label for="simpleinput" class="form-label">Commentaire supplémentaire<span< /label>

                                        <textarea name="comment_sup" class="form-control mb-3" rows="5">{{ $report->comment }}</textarea>

                                        <div class="row">
                                            <div class="mb-3 supplementaireid">
                                                <label for="simpleinput" class="form-label">Récapitulatifs<span
                                                        style="color:red;">*</span></label>
                                                <textarea name="description_supplementaire" id="editor2" class="form-control mb-3" cols="15" rows="10"
                                                    style="height: 250px;">{{ $report->description_supplementaire }}</textarea>

                                            </div>
                                        </div>

                            </div>

                            <div>
                                <h4>Micro</h4>
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Template</label>
                                        <select class="form-select" id="template_supplementaire_micro" name="">
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
                                        <textarea name="description_supplementaire_micro" id="editor_micro2" class="form-control mb-3" cols="15"
                                            rows="10" style="height: 250px;">{{ $report->description_supplementaire_micro }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card mb-md-0 mb-3 mt-3">
                        <h5 class="card-header">Pièces jointes</h5>
                        <div class="card-body">

                            <!-- The grid: four columns -->
                            <div class="row">

                                <div class="col-lg-12">

                                    <!-- The expanding image container -->
                                    <div class="container mb-3">
                                        <!-- Close the image -->
                                        <span onclick="this.parentElement.style.display='none'"
                                            class="closebtn">&times;</span>

                                        <!-- Expanded image -->
                                        <img id="expandedImg" style="width:50%">

                                        <!-- Image text -->
                                        <div id="imgtext"></div>
                                    </div>

                                    <div class="d-lg-flex d-none justify-content-center">
                                        @foreach (explode('|', $report->order->files_name) as $file_name)
                                            <img src="/storage/examen_images/{{ $report->order->code }}/{{ $file_name }}"
                                                onclick="myFunction(this)" class="img-fluid img-thumbnail p-2"
                                                style="max-width: 75px;" alt="{{ $file_name }}">
                                        @endforeach
                                    </div>
                                </div> <!-- end col -->

                            </div>

                        </div>
                    </div>

                    @if ($report->order->assigned_to_user_id == Auth::user()->id || getOnlineUser()->can('view-super-doctor'))
                        <div class="card mb-md-0 mb-3 mt-3">
                            <h5 class="card-header">Signature<span style="color:red;">*</span></h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5 form-check-inline">
                                        <label for="example-fileinput" class="form-label">Signé par</label>
                                        <select name="doctor_signataire1" id="doctor_signataire1" class="form-control">
                                            <option value="">Sélectionner un docteur</option>
                                            @foreach (getUsersByRole('docteur') as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $report->order->assigned_to_user_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->lastname }} {{ $item->firstname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-5 form-check-inline">
                                        <label for="example-fileinput" class="form-label">Second avis de relecture donné
                                            et validé par :</label>
                                        <select name="reviewed_by_user_id"
                                            {{ App\Models\SettingApp::where('key', 'report_review_title')->first()->value == '' ? 'disabled' : '' }}
                                            id="reviewed_by_user_id" class="form-control">
                                            <option value="">Sélectionner un docteur</option>
                                            @foreach (getUsersByRole('docteur') as $item)
                                                @if ($report->order->assigned_to_user_id != $item->id)
                                                    <option value="{{ $item->id }}"
                                                        {{ $report->reviewed_by_user_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->lastname }} {{ $item->firstname }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-10" style="padding-right: 1px; flex-wrap:nowrap">
                                    @if (App\Models\SettingApp::where('key', 'report_review_title')->first()->value == '')
                                        <div class="col-6 mt-2 alert alert-warning alert-dismissible fade show"
                                            role="alert">
                                            <strong>Revue de rapport - </strong> Veuillez enregistrer un revue de rapport.
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="mb-3 mt-3">
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

                                <button type="submit" @if ($report->status === 1 && $report->signature_date) disabled @endif
                                    class="btn btn-warning w-100 mt-3">Mettre à jour</button>
                            </div>
                        </div>
                    @else
                        <button type="submit" @if ($report->status === 1 && $report->signature_date) disabled @endif
                            class="btn btn-warning w-100 mt-3">Mettre à jour</button>
                    @endif
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
                                {{ $report->description_supplementaire != null ? 'checked' : '' }}
                                data-switch="success" />
                            <label for="switch3" data-on-label="oui" data-off-label="non"></label>
                            <p><b>Dernière mise à jour</b> : {{ date_format($report->updated_at, 'd/m/Y') }}</p>

                            <div class="mb-3">
                                <label for="example-select" class="form-label">Tags<span
                                        style="color:red;">*</span></label>
                                <select class="form-select select2" data-toggle="select2" name="tags[]" id="tag_id"
                                    multiple>
                                    <option><b>Sélectionner les tags</b></option>
                                    @forelse ($tags as $tag)
                                        <option value="{{ $tag->id }}"
                                            {{ $report->hasTag($tag->name) ? 'selected' : '' }}>
                                            {{ $tag->name }}</option>

                                    @empty
                                        Ajouter un tag
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Info patient -->
                    <div class="card mb-md-0 mb-3 mt-3">
                        <h5 class="card-header">Informations patient</h5>

                        <div class="card-body">
                            <p><b>Nom</b> :
                                {{ DB::table('patients')->where('id', $test_order->patient_id)->value('lastname') }}
                                {{ DB::table('patients')->where('id', $test_order->patient_id)->value('firstname') }}
                            </p>
                            <p><b>Code patient</b> :
                                {{ DB::table('patients')->where('id', $test_order->patient_id)->value('code') }}
                            </p>
                            <p><b>Téléphone</b> :
                                {{ DB::table('patients')->where('id', $test_order->patient_id)->value('telephone1') }}
                            </p>
                        </div>
                    </div>

                    <div class="card mb-md-0 mb-3 mt-3">
                        <h5 class="card-header">Signataires</h5>

                        <div class="card-body">
                            <p><b>Signature 1</b> :
                                {{ $report->order->assigned_to_user_id == null ? 'Inactif' : getSignatory1($report->order->assigned_to_user_id) }}
                            </p>
                            <p><b>Avis de relecture</b> :
                                {{ $report->reviewed_by_user_id == null ? 'Inactif' : getSignatory1($report->reviewed_by_user_id) }}
                            </p>
                            {{-- <p><b>Signature 3</b> :
                            {{ $report->signatory3 == null ? 'Inactif' : getSignatory1($report->signatory3) }}</p> --}}
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

                            {{-- @php
                        $facture = App\Models\Invoice::where('test_order_id', $report->test_order_id)->value('paid');
                        @endphp

                        @if ($facture == 1)
                        <a href="{{ route('report.pdf', $report->id) }}" target="_blank" rel="noopener noreferrer"
                            type="button" class="btn btn-secondary">Imprimer le compte rendu
                            </i>
                        </a>
                        @elseif($facture == 0)
                        <a href="#" id="showAlertButton" onclick="fenetre()" rel="noopener noreferrer" type="button"
                            class="btn btn-secondary">Imprimer le compte rendu
                            </i>
                        </a>
                        @endif --}}

                            <a href="{{ route('report.pdf', $report->id) }}" target="_blank" rel="noopener noreferrer"
                                type="button" class="btn btn-secondary">Imprimer le compte rendu
                                </i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
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
                                        <td>{{ $log->user ? $log->user->lastname : '' }}
                                            {{ $log->user ? $log->user->firstname : '' }}
                                        </td>
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
        /* DATATABLE */
        $(document).ready(function() {
            var status = $('#status').val()
            var btnStatus = document.getElementById('btn-status');
            if (status == 0) {
                if (btnStatus.classList.contains("btn-warning")) {
                    btnStatus.classList.remove("btn-warning");
                }
                btnStatus.classList.add("btn-success");
                btnStatus.textContent = "Marqué comme Terminé"
            } else {
                if (btnStatus.classList.contains("btn-success")) {
                    btnStatus.classList.remove("btn-success");
                }
                btnStatus.classList.add("btn-warning");
                btnStatus.textContent = "Marqué comme En attente"
            }
            $('#btn-status').on('click', function() {
                if ($('#status').val() == 0) {
                    $('#status').val(1)
                    if (btnStatus.classList.contains("btn-success")) {
                        btnStatus.classList.remove("btn-success");
                    }
                    btnStatus.classList.add("btn-warning");
                    btnStatus.textContent = "Marqué comme En attente"
                } else {
                    $('#status').val(0)
                    if (btnStatus.classList.contains("btn-warning")) {
                        btnStatus.classList.remove("btn-warning");
                    }
                    btnStatus.classList.add("btn-success");
                    btnStatus.textContent = "Marqué comme Terminé"
                }
            })
        })
    </script>

    <script>
        const report = {!! json_encode($report) !!}
        var code = {!! json_encode($report->code) !!}
        var ROUTEGETTEMPLATE = "{{ route('template.report-getTemplate') }}"
        var TOKENGETTEMPLATE = "{{ csrf_token() }}"
    </script>

    <script>
        var baseUrl = "{{ url('/') }}"
        var ROUTESTOREREPORTTAGS = "{{ route('report.storeTags') }}"
        var TOKENSTOREREPORTTAGS = "{{ csrf_token() }}"
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    </script>

    <script src="{{ asset('viewjs/report/show.js') }}"></script>
    <script src="{{ asset('viewjs/report/gallery.js') }}"></script>

    <script>
        function fenetre() {
            event.preventDefault();

            // Afficher le Sweet Alert
            Swal.fire({
                icon: "warning",
                title: "Attention",
                text: "Impossible d'imprimer le compte rendu, la facture n'est pas payée",
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#reportForm').on('submit', function(e) {
                e.preventDefault();

                var form = this;
                var statusElement = $('select[name="status"]');
                var status = null;
                var statusText = 'en cours de modification';

                if (statusElement.length > 0) {
                    status = statusElement.val();
                    statusText = status == '1' ? 'terminé' : 'en attente de relecture';
                } else {
                    if (typeof report !== 'undefined') {
                        status = report.status;
                        statusText = status == 1 ? 'terminé' : 'en attente de relecture';
                    }
                }

                var title = $('#title').val() || 'Non défini';

                Swal.fire({
                    title: 'Confirmer la mise à jour',
                    html: `
                        <div style="text-align: left; padding: 10px;">
                            <p><strong>Vous êtes sur le point de mettre à jour ce compte rendu.</strong></p>
                            <hr style="margin: 15px 0;">
                            <p><strong>Titre :</strong> ${title}</p>
                            <p><strong>État :</strong> <span style="color: ${status == 1 ? '#28a745' : '#ffc107'}; font-weight: bold;">${statusText}</span></p>
                            <hr style="margin: 15px 0;">
                            <p>Cette action modifiera définitivement les données du rapport et sera irréversible.</p>
                        </div>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ffc107',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, mettre à jour',
                    cancelButtonText: 'Annuler',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Mise à jour en cours...',
                            html: 'Veuillez patienter pendant la sauvegarde.',
                            didOpen: () => {
                                Swal.showLoading();
                            },
                            allowOutsideClick: false
                        });

                        setTimeout(() => form.submit(), 300);
                    }
                });
            });
        });
    </script>

    {{-- <script>
        $(document).ready(function() {
            // Intercepter la soumission du formulaire
            $('#reportForm').on('submit', function(e) {
                e.preventDefault(); // Empêcher la soumission normale

                var form = this; // Référence au formulaire
                var status = $('select[name="status"]').val();
                var statusText = status == '1' ? 'terminé' : 'en attente de relecture';

                Swal.fire({
                    title: 'Confirmer la mise à jour',
                    html: `
                <div style="text-align: left;">
                    <p><strong>Vous êtes sur le point de mettre à jour ce compte rendu.</strong></p>
                    <p>État du rapport : <span style="color: ${status == '1' ? '#28a745' : '#ffc107'}; font-weight: bold;">${statusText}</span></p>
                    <p>Cette action modifiera définitivement les données du rapport et sera irréversible.</p>
                </div>
            `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, mettre à jour',
                    cancelButtonText: 'Annuler',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Afficher un loader pendant la soumission
                        Swal.fire({
                            title: 'Mise à jour en cours...',
                            html: 'Veuillez patienter pendant la sauvegarde.',
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });

                        // Soumettre le formulaire
                        form.submit();
                    }
                });
            });
        });
    </script> --}}
@endpush
