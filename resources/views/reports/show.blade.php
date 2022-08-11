@extends('layouts.app2')

@section('css')
    {{-- <link href="{{ asset('/adminassets/css/vendor/quill.bubble.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('/adminassets/css/vendor/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminassets/css/vendor/quill.snow.css') }}" rel="stylesheet" type="text/css" /> --}}

    <link href="{{ asset('/adminassets/css/vendor/simplemde.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3 mb-1">
                    <a href="{{ route('report.index') }}" type="button" class="btn btn-primary">Retour à la liste des
                        comptes rendu</a>
                </div>
                <h4 class="page-title"></h4>
            </div>


        </div>
    </div>


    <div class="">


        @include('layouts.alerts')

        <div class="card mt-3">
            <h5 class="card-header">Patient : {{ $report->patient->name }}</h5>

            <div class="card-body">
                <p><b>Code patient</b> : {{ $report->patient->code }}</p>
                <p><b>Téléphone</b> : {{ $report->patient->telephone1 }}</p>
                <p><b>Statut</b> : {{ $report->status }}</p>
                <p><b>Signature</b> : {{ $report->signature_date }}</p>
            </div>
        </div>

        <div class="card mb-md-0 mb-3">


            <div class="card-body">

                <h5 class="card-title mb-0">Contenu du compte rendu</h5>

                <div id="cardCollpase1" class="collapse pt-3 show">
                <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                    <form action="{{ route('report.store') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Titre<span style="color:red;">*</span></label>
                            <input type="text" id="simpleinput" name="title" class="form-control"
                                value="{{ $report ? $report->title : '' }}">
                        </div>

                        <input type="hidden" name="report_id" value="{{ $report->id }}">
                        
                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Description<span style="color:red;">*</span></label>
                            <textarea name="content" id="simplemde1" class="form-control" cols="30" rows="5" style="height: 300px;">{{ $report->description }}</textarea>
                        </div>

                        <button type="submit" class=" mt-3 btn btn-warning w-100">Enregistrer</button>
                    </form>

                </div>
            </div>
        </div> <!-- end card-->


    </div>
@endsection


@push('extra-js')
    {{-- <!-- quill js -->
    <script src="{{ asset('/adminassets/js/vendor/quill.min.js') }}"></script>
    <!-- quill Init js-->
    <script src="{{ asset('/adminassets/js/pages/demo.quilljs.js') }}"></script> --}}

    <script src="{{ asset('/adminassets/js/vendor/simplemde.min.js') }}"></script>
    <!-- SimpleMDE demo -->
    <script src="{{ asset('/adminassets/js/pages/demo.simplemde.js') }}"></script>
    <script>
        // SUPPRESSION
        function deleteModal(id) {

            Swal.fire({
                title: "Voulez-vous supprimer l'élément ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Oui ",
                cancelButtonText: "Non !",
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ url('contrats_details/delete') }}" + "/" + id;
                    Swal.fire(
                        "Suppression !",
                        "En cours de traitement ...",
                        "success"
                    )
                }
            });
        }





        //EDITION
        function edit(id) {
            var e_id = id;

            // Populate Data in Edit Modal Form
            $.ajax({
                type: "GET",
                url: "{{ url('getcontratdetails') }}" + '/' + e_id,
                success: function(data) {

                    $('#category_test_id2').val(data.category_test_id).change();
                    $('#pourcentage2').val(data.pourcentage);
                    $('#contrat_id2').val(data.contrat_id);
                    $('#contrat_details_id2').val(data.id);



                    console.log(data);
                    $('#editModal').modal('show');
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }
    </script>
@endpush
