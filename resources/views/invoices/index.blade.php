@extends('layouts.app2')

@section('title', 'Factures')

@section('content')

<div class="">

    <div class="page-title-box">
        <div class="page-title-right mr-3">
            <a href="{{ route('invoice.create') }}"><button type="button" class="btn btn-primary">Ajouter une
                    nouvelle facture</button></a>
        </div>
        <h4 class="page-title">Factures</h4>
    </div>

    <!----MODAL---->
    @include('layouts.alerts')

    <div class="card mb-md-0 mb-3">
        <div class="card-body">
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                    aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            <h5 class="card-title mb-0">Liste des Factures </h5>

            <div id="cardCollpase1" class="collapse pt-3 show">

                <table id="datatable1" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Demande</th>
                            <th>Patient</th>
                            <th>Total</th>
                            <th>Remise</th>
                            <th>Statut</th>
                            <th>Actions</th>

                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($invoices as $item)

                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>
    </div> <!-- end card-->


</div>
@endsection


@push('extra-js')
<script>
    /* DATATABLE */
    $(document).ready(function() {

        var table = $('#datatable1').DataTable({
            "createdRow": function( row, data, dataIndex ) {
            
                var urgent = $(row).data('mytag');
                    if ( urgent == 1 ) {        
                    $(row).addClass('table-danger');  
                }
            },
            "order": [
                [0, "desc"]
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "searchable": false
                }
            ],
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
  
        // Recherche dans la colonne  contrat
        $("#contrat_id").on("change", function() {
            table
            .columns(4)
            .search(this.value)
            .draw();
        });
        
        // Recherche dans la colonne  compte rendu
        $("#exams_status").on("change", function() {
            table
            .columns(8)
            .search(this.value)
            .draw();
        });
        
        // Recherche dans la colonne  type d'examen
        $("#type_examen").on("change", function() {
            table
            .columns(9)
            .search(this.value)
            .draw();
        });
        
        // Recherche selon les cas
        $("#cas_status").on("change", function() {
            table.draw();
        });
        $.fn.dataTable.ext.search.push(
            function( settings, searchData, index, rowData, counter ) 
            {
                var row = table.row(index).node();
                var filterValue = $(row).data('mytag');
                var e = document.getElementById("cas_status");
                var filter = e.options[e.selectedIndex].value;

                if (filterValue == filter) {
                    return true;
                } else if (filter == "") {
                    return true;
                } else {
                    return false;
                }

            }
        );
    })
</script>
@endpush