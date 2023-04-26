@extends('layouts.app2')

@section('title', 'Factures')

@section('content')

    <div class="">

        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <a href="{{ route('invoice.index') }}"><button type="button" class="btn btn-primary">Retour à la listes des
                        factures</button></a>
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

                    <div class="col-lg-4">

                        {{-- <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Sélectionner une période</label>
                            <select name="periode" id="periode" class="form-control">
                                <option value="">Tous</option>
                                <option value="nowMonth">Mois en cours</option>
                                <option value="lastMonth">Mois précédent</option>
                                <option value="nowTrimestres">Trimestre en cours</option>
                                <option value="lastTrimestre">Trimestre précédent</option>
                                <option value="nowYear">Année en cours</option>
                                <option value="lastYear">Année précédent</option>
                            </select>
                        </div> --}}

                    </div> <!-- end col -->
                    <table id="datatable1" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Mois</th>
                                <th>Facturés</th>
                                <th>Avoirs</th>
                                <th>Chiffre d'affaires</th>
                                <th>Encaissements</th>

                            </tr>
                        </thead>
                        {{-- <thead>
                            <tr>
                                <th>Mois</th>
                                <th>Facturés</th>
                                <th>Avoir</th>
                                <th>Chiffre d'affaires</th>
                                <th>Encaissements</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach (['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'] as $key => $mois)
                                @if ($key+1<= Carbon\Carbon::now()->format('m'))
                                <tr>
                                    <td>{{ $mois }} {{Carbon\Carbon::now()->formatLocalized('%G')}}</td>
                                    <td>
                                        {{ App\Models\Invoice::whereMonth('updated_at', $key+1)->sum('total')? App\Models\Invoice::whereMonth('updated_at', $key+1)->sum('total').' Fr':'Néant'; }}
                                    </td>
                                    <td>  </td>
                                    <td>
                                        {{ App\Models\Invoice::whereMonth('updated_at', $key+1)->where('paid','=',1)->sum('total') ? App\Models\Invoice::whereMonth('updated_at', $key+1)->where('paid','=',1)->sum('total'). ' Fr': 'Néant'; }}
                                    </td>
                                    <td>
                                        {{ App\Models\Invoice::whereMonth('updated_at', $key+1)->where('paid','=',1)->sum('total')? App\Models\Invoice::whereMonth('updated_at', $key+1)->where('paid','=',1)->sum('total').' Fr': 'Néant'; }}
                                    </td>
                                </tr>
                                @endif
                            @endforeach

                            </tbody>
                        --}}

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

                    "columnDefs": [{
                        "targets": [0],
                        "searchable": false
                    }],
                    "bFilter": false,
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
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('invoice.getTestOrdersforDatatable') }}",
                        data: function(d) {
                            // d.periode = $('#periode').val()

                        }
                    },
                    columns: [
                        // {
                        //     data: 'created_at',
                        //     name: 'created_at'
                        // },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                         {
                            data: 'factures',
                            name: 'factures',
                        },
                        {
                            data: 'avoirs',
                            name: 'avoirs'
                        },
                        {
                            data: 'chiffres',
                            name: 'chiffres'
                        },
                        {
                            data: 'encaissements',
                            name: 'encaissements'
                        }
                    ],
                    order: [

                    ],
                });

                // Recherche selon les docteurs signataires
                // $("#periode").on("change", function() {
                //     // alert(this.value)
                //     table.draw();
                // });

                // $('#periode').on('change', function() {
                //     var periode = $(this).val(); // Récupère la valeur de la liste déroulante

                //     // Effectue une requête AJAX pour récupérer les données filtrées
                //     $.ajax({
                //         type: "POST",
                //         data: {
                //             "_token": "{{ csrf_token() }}",
                //             periode: periode,
                //         },
                //         url: '{{ route('invoice.filter') }}',
                //         success: function(data) {
                //             alert(data);
                //             // Met à jour le tableau avec les nouvelles données
                //             $('#datatable1').DataTable().clear().rows.add(data).draw();
                //         },
                //         error: function (error) {
                //             alert
                //         }
                //     });
                // });
            });
    </script>
@endpush
