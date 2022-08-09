@extends('layouts.app2')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3 mb-1">
                    <a href="{{ route('contrats.index') }}" type="button" class="btn btn-primary">Retour à la liste des
                        contrats</a>
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
                <p><b>code</b> : {{ $report->patient->code }}</p>
                <p><b>telephone</b> : {{ $report->telephone1 }}</p>
                <p><b>Statut</b> : {{ $report->status }}</p>
                <p><b>Description</b> : </p>
            </div>
        </div>

        <div class="card mb-md-0 mb-3">


            <div class="card-body">

                <h5 class="card-title mb-0">Rediger le compte rendu de l'examen</h5>


                <div id="cardCollpase1" class="collapse pt-3 show">




                </div>
            </div>
        </div> <!-- end card-->


    </div>
@endsection


@push('extra-js')
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
