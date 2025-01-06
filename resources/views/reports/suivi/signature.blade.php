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
            <input type="hidden" name="report_id" id="report_id" value="{{ $report->id }}">
            <div class="col-md-12">
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
                            <div style="text-align:">
                                <label class="" for="">Signature<span style="color:red;">*</span></label>
                                <canvas id="signature-pad"
                                    style="border:1px solid #000000; width: 100%; height: 200px;"></canvas>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <button id="clear" class="btn btn-danger" onclick="clearSignature()">Effacer</button>
                            <button class="btn btn-primary" onclick="saveSignature()">Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/2.3.2/signature_pad.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.3.4/signature_pad.js"></script>
    <script>
        var canvas = document.getElementById('signature-pad');
        var signaturePad = new SignaturePad(canvas);

        function resizeCanvas() {
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;
            signaturePad.clear(); // Effacer la signature après redimensionnement
        }

        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        function clearSignature() {
            signaturePad.clear();
        }

        function saveSignature() {
            if (!signaturePad.isEmpty()) {
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
                        var signatureData = signaturePad.toDataURL('image/png');
                        let signatorName = $('#retriever_name').val();
                        let reportId = $('#report_id').val();
                        console.log(signatorName, reportId, signatureData);

                        // Envoyer la signature encodée en base64 vers le backend Laravel
                        fetch('/report/suivi/store/signature', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    signature: signatureData,
                                    signatorName: signatorName,
                                    reportId: reportId
                                })
                            }).then(response => response.text())
                            .then(data => {
                                console.log('Signature enregistrée avec succès:', data);
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: "top-end",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.onmouseenter = Swal.stopTimer;
                                        toast.onmouseleave = Swal.resumeTimer;
                                    }
                                });
                                Toast.fire({
                                    icon: "success",
                                    title: "Signature enregistrée avec succès"
                                });

                                executeWithPause();
                                // Rediriger vers la page de suivi du rapport
                                window.location.href = "{{ route('report.index.suivi') }}";
                            }).catch(error => function name(error) {
                                Swal.fire({
                                    title: 'Erreur',
                                    text: error,
                                    icon: 'error',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Oui, continuer',
                                    cancelButtonText: 'Fermer'
                                });
                            });
                    }
                });
            } else {
                // Afficher la boîte de dialogue de confirmation
                Swal.fire({
                    title: 'Erreur',
                    text: "Veuillez remplir tous les champs !",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, continuer',
                    cancelButtonText: 'Fermer'
                });
            }
        }

        // Désactiver le défilement sur canvas lors de la signature
        document.body.addEventListener("touchstart", function(e) {
            if (e.target === canvas) {
                e.preventDefault();
            }
        }, {
            passive: false
        });

        document.body.addEventListener("touchmove", function(e) {
            if (e.target === canvas) {
                e.preventDefault();
            }
        }, {
            passive: false
        });

        async function executeWithPause() {
            console.log("Début de l'exécution");
            await sleep(5000); // Pause de 3000 ms
        }
    </script>
@endpush
