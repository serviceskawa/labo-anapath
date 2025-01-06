@extends('layouts.app2')

@section('title', 'Reports')

@section('css')
    <link href="{{ asset('adminassets/css/vendor/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('adminassets/css/vendor/quill.snow.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('adminassets/css/vendor/simplemde.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('adminassets/js/vendor/simplemde.min.js') }}"></script>
    <script src="{{ asset('adminassets/js/vendor/quill.min.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/super-build/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <link href="{{ asset('/upload/js/jquery-ui.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/upload/js/jquery.signature.css') }}" rel="stylesheet" type="text/css">

    <style>
        .kbw-signature {
            width: 100%;
            height: 300px;
        }

        #sig canvas {
            width: 100% !important;
            height: auto;
        }
    </style>
@endsection
{{-- 243463 --}}
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3 mb-1">
                    <a href="{{ route('report.index.suivi') }}" type="button" class="btn btn-primary">Retour à la liste des
                        demandes suivi</a>
                </div>
                <h4 class="page-title">Compte rendu : {{ $report->code }}</h4>
            </div>
        </div>
    </div>

    <div class="">
        @include('layouts.alerts')
        <div class="row">
            <form action="{{ route('suivi.report.signature.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="report_id" value="{{ $report->id }}">

                <div class="col-md-12 ">

                    <div class="card mb-md-0 mb-3">

                        <div class="col-md-12 justify-content-between d-flex p-3">
                            <div class="col-md-6 text-start">
                                Demande d'examen : <span style="font-weight: bold;"> {{ $report->order->code }} </span>
                            </div>

                            <div class="col-md-6 text-end">
                                <span style="color:red;">*</span>champs
                                obligatoires
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="col-md-12 mb-2">
                                <label for="simpleinput" class="form-label">Nom du récupérateur <span
                                        style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="retriever_name" id="retriever_name"
                                        value="" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <input type="checkbox" id="use_patient_name">
                                            <label for="use_patient_name" class="ml-2 mb-0">&nbsp;Patient lui-même</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="" for="">Signature<span style="color:red;">*</span></label>
                                <div id="sig"></div>
                                <br><br>
                                <textarea id="signature" name="retriever_signature" style="display: none"></textarea>
                            </div>

                            <div class="col-md-6">
                                <button id="clear" class="btn btn-danger">Effacer</button>
                                <button class="btn btn-primary" type="submit">Enregistrer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('extra-js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#use_patient_name').change(function() {
                if ($(this).is(':checked')) {
                    $('#retriever_name').val(
                        '{{ $report->order->patient?->firstname }} {{ $report->order->patient?->lastname }}'
                    );
                } else {
                    $('#retriever_name').val('');
                }
            });
        });
    </script>


    <script type="text/javascript" src="{{ asset('/upload/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/upload/js/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/upload/js/jquery.signature.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/upload/js/jquery.ui.touch-punch.js') }}"></script>
    <script type="text/javascript">
        var sig = $('#sig').signature({
            syncField: '#signature',
            syncFormat: 'SVG'
        });
        $('#clear').click(function(e) {
            e.preventDefault();
            sig.signature('clear');
            $("#signature").val('');
        });
    </script>

    <script>
        // Intercepter le clic sur le bouton de soumission
        $('form').on('submit', function(e) {
            e.preventDefault(); // Empêcher la soumission immédiate du formulaire

            // Afficher la boîte de dialogue de confirmation
            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: "Vous ne pourrez pas revenir en arrière!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, continuer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si l'utilisateur confirme, soumettez le formulaire
                    this.submit();
                }
            });
        });
    </script>
@endpush
