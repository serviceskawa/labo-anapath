@extends('layouts.app2')

@section('title', 'Reports')

@section('css')
    <link href="{{ asset('adminassets/css/vendor/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('adminassets/css/vendor/quill.snow.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('adminassets/css/vendor/simplemde.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .signature-pad {
            width: 100%;
            height: auto;
            max-height: 200px;
        }

        #signatureCanvas {
            width: 100%;
            height: 200px;
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
            <form action="{{ route('suivi.report.signature.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="report_id" value="{{ $report->id }}">

                <div class="col-md-12">
                    <div class="card mb-md-0 mb-3">
                        <div class="col-md-12 justify-content-between d-flex p-3">
                            <div class="col-md-6 text-start">
                                Demande d'examen : <span style="font-weight: bold;">{{ $report->order->code }}</span>
                            </div>
                            <div class="col-md-6 text-end">
                                <span style="color:red;">*</span>champs obligatoires
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="col-md-6 mb-2">
                                <label for="simpleinput" class="form-label">Nom du récupérateur <span
                                        style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="retriever_name" id="retriever_name"
                                        value="">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <input type="checkbox" id="use_patient_name">
                                            <label for="use_patient_name" class="ml-2 mb-0">&nbsp;Patient lui-même</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="" for="">Signature<span style="color:red;">*</span></label>
                                <div id="signature-pad" class="signature-pad">
                                    <canvas></canvas>
                                </div>
                                <textarea id="signature" name="retriever_signature" style="display: none;"></textarea>
                                <br>
                                <img id="signature-image" src="" alt="Signature"
                                    style="max-width: 100%; height: auto; display: none;">
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
    <script>
        var signaturePad = new SignaturePad(document.querySelector('#signature-pad canvas'));

        var dataURL = signaturePad.toDataURL('image/png');

        console.log(dataURL);

        document.querySelector('#submit').addEventListener('click', function(e) {
            // Update the hidden field with the base64 image data
            var dataURL = signaturePad.toDataURL('image/png');
            // Make an AJAX request to submit the data
            var formData = new FormData(document.querySelector('#signatureForm'));
            formData.append('retriever_signature', dataURL);

            fetch('{{ route('suivi.report.signature.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Success', 'Operation réussie avec succès!', 'success').then(() => {
                            window.location.href = '{{ route('report.index.suivi') }}';
                        });
                    } else {
                        Swal.fire('Error', 'Une erreur est survenue. Veuillez réessayer.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Une erreur est survenue. Veuillez réessayer.', 'error');
                });
        });


        // document.querySelector('form').addEventListener('submit', function(e) {
        //     // Update the hidden field with the base64 image data
        //     var dataURL = signaturePad.toDataURL('image/png');
        //     document.querySelector('textarea[name="retriever_signature"]').value = dataURL;

        //     // Optional: Show the image preview
        //     document.querySelector('#signature-image').src = dataURL;
        //     document.querySelector('#signature-image').style.display = 'block';
        // });

        document.querySelector('#clear').addEventListener('click', function(e) {
            e.preventDefault();
            signaturePad.clear();
            document.querySelector('textarea[name="retriever_signature"]').value = '';
            document.querySelector('#signature-image').src = '';
            document.querySelector('#signature-image').style.display = 'none';
        });

        document.querySelector('#use_patient_name').addEventListener('change', function() {
            if (this.checked) {
                document.querySelector('#retriever_name').value =
                    '{{ $report->order->patient?->firstname }} {{ $report->order->patient?->lastname }}';
            } else {
                document.querySelector('#retriever_name').value = '';
            }
        });
    </script>

    {{-- <script>
        var signaturePad = new SignaturePad(document.querySelector('#signature-pad canvas'));

        document.querySelector('form').addEventListener('submit', function(e) {
            // Update the hidden field with the base64 image data
            console.log(signaturePad.toDataURL(('image/png')));

            // Update the hidden field with the base64 image data
            var dataURL = canvas.toDataURL('image/png'); // Assurez-vous que l'extension est correcte
            document.querySelector('textarea[name="retriever_signature"]').value = dataURL;
        });

        document.querySelector('#clear').addEventListener('click', function(e) {
            e.preventDefault();
            signaturePad.clear();
            document.querySelector('textarea[name="retriever_signature"]').value = '';
        });

        document.querySelector('#use_patient_name').addEventListener('change', function() {
            if (this.checked) {
                document.querySelector('#retriever_name').value =
                    '{{ $report->order->patient?->firstname }} {{ $report->order->patient?->lastname }}';
            } else {
                document.querySelector('#retriever_name').value = '';
            }
        });
    </script> --}}
@endpush
