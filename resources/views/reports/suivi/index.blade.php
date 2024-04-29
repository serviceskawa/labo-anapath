@extends('layouts.app2')

@section('title', 'Reports')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Suivi des demandes</h4>

        </div>

        <!----MODAL---->

    </div>
</div>

<div class="">

    @include('layouts.alerts')

    <div class="card mb-md-0 mb-3"  id="demandes">
        <div class="card-body">
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                    aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            {{-- <h5 class="card-title mb-0">Liste des demandes suivi</h5> --}}

            <div class="row d-flex">
                <div class="col-lg-3 p-1 ml-3 alert alert-success rounded-pill"
                    style="margin-right: 5px; text-align:center;">
                    <a href="#demandes" style="text-decoration : none; color:inherit;">Liste des demandes suivi</a>
                </div>

                <div class="col-lg-3 p-1 ml-3 alert alert-light rounded-pill"
                    style="margin-right: 5px; text-align:center;">
                    <a href="#rapports" style="text-decoration : none; color:inherit;">Rapports</a>
                </div>
            </div>

            <div id="cardCollpase1" class="show collapse pt-3">

                <div class="row mb-3">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Rechercher</label>
                            <input type="text" name="contenu" id="contenu" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Status</label>
                            <select name="statusquery" id="statusquery" class="form-control">
                                <option value="">Tous</option>
                                <option value="1">Livrée</option>
                                <option value="2">Informée</option>
                                <option value="3">En attente</option>
                                <option value="4">Terminée</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Urgent</label>
                            <select name="cas_status" id="cas_status" class="form-control">
                                <option value="">Tous</option>
                                <option value="Urgent">Urgent</option>
                                <option value="Retard">Retard</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Type d'examen</label>
                            <select name="type_examen" id="type_examen" class="form-control">
                                <option value="">Tous</option>
                                @forelse ($types_orders as $type)
                                <option value="{{ $type->id }}">{{ $type->title }}</option>
                                @empty
                                Ajouter un Type d'examen
                                @endforelse
                            </select>
                        </div>
                    </div>


                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Date début</label>
                            <input type="date" name="dateBegin" id="dateBegin" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Date fin</label>
                            <input type="date" name="dateEnd" id="dateEnd" class="form-control">
                        </div>
                    </div>
                </div>

                <table id="datatablesuivi" class="table-striped dt-responsive nowrap w-100 table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Code</th>
                            <th>Macro</th>
                            <th>Compte rendu</th>
                            <th>Patient informé</th>
                            <th>Patient livré</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

        {{-- Deuxieme DataTables Histologie et Biopsie --}}
        <div class="card mb-md-0 mt-5">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-3">

                    <div class="">
                        <button type="button" class="btn btn-primary" id="deleteSelectedRows"
                            style="display: none;">Terminer la Macroscopie</button>
                    </div>
                </div>

                <div class="row mb-3 d-flex">
                    <div class="col-lg-3 p-1 ml-3 alert alert-light rounded-pill"
                        style="margin-right: 5px; text-align:center;">
                        <a href="#demandes" style="text-decoration : none; color:inherit;">Liste des demandes suivi</a>
                    </div>

                    <div class="col-lg-3 p-1 ml-3 alert alert-success rounded-pill"
                        style="margin-right: 5px; text-align:center;">
                        <a href="#rapports" style="text-decoration : none; color:inherit;">Rapports</a>
                    </div>

                   <form action="" id="rapports-datatables">
                        <div class="row mb-3 d-flex">
                            <div class="col-lg-3">
                                <label for="example-fileinput" class="form-label">Année</label>
                                <select name="year" id="year" class="form-control">
                                    <option value="">Tous</option>
                                   @foreach ($list_years as $list_year)
                                    <option value="{{ $list_year->year }}" {{ $list_year->year == $year ? 'selected':''}}>{{  $list_year->year }}</option>
                                   @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3">
                                <label for="example-fileinput" class="form-label">Mois</label>
                                    <select name="month" id="month" class="form-control">
                                        <option value="">Tous</option>
                                        <option value="01" {{ $month == '01' ? 'selected':''}}>Janvier</option>
                                        <option value="02" {{ $month == '02' ? 'selected':''}}>Février</option>
                                        <option value="03" {{ $month == '03' ? 'selected':''}}>Mars</option>
                                        <option value="04" {{ $month == '04' ? 'selected':''}}>Avril</option>
                                        <option value="05" {{ $month == '05' ? 'selected':''}}>Mai</option>
                                        <option value="06" {{ $month == '06' ? 'selected':''}}>Juin</option>
                                        <option value="07" {{ $month == '07' ? 'selected':''}}>Juillet</option>
                                        <option value="08" {{ $month == '08' ? 'selected':''}}>Août</option>
                                        <option value="09" {{ $month == '09' ? 'selected':''}}>Septembre</option>
                                        <option value="10" {{ $month == '10' ? 'selected':''}}>Octobre</option>
                                        <option value="11" {{ $month == '11' ? 'selected':''}}>Novembre</option>
                                        <option value="12" {{ $month == '12' ? 'selected':''}}>Décembre</option>
                                    </select>
                            </div>

                            <div class="col-lg-3" style="margin-top : 25px;">
                                <button type="submit" class="btn btn-success">Filtrer</button>
                            </div>
                        </div>
                   </form>
                </div>

                <div class="row" id="rapports">
                    <div class="table-responsive">
                        <table class="table table-centered w-100 dt-responsive nowrap"
                            id="">
                            <thead class="table-light">
                                <tr>
                                    <th>Période</th>
                                    <th>Demande d'examen</th>
                                    <th>Macro</th>
                                    <th>Compte rendu</th>
                                    <th>Patient informé</th>
                                    <th>Patient livré</th>
                                </tr>
                            </thead>
                            <tfoot>
                                @foreach ($examens as $exam)
                                    <tr>
                                        <td>{{ $month }} - {{ $year }}</td>
                                        <td>
                                            Total : {{ $exam->total_general }} <br>
                                            Histologie : {{ $exam->histologie  }} <br>
                                            Immuno Externe : {{ $exam->immuno_externe }} <br>
                                            Immuno Interne : {{ $exam->immuno_interne }} <br>
                                            Cytologie : {{ $exam->cytologie }}
                                        </td>
                                        @foreach ( $macros as $macro)
                                            <td>Réalisé : {{ $macro->pathology }} <br> Non Réalisé : {{ $exam->total_general  -  $macro->pathology }}</td>

                                        @endforeach
                                        @foreach ($rapports as $rapport)
                                            <td>
                                                En attente : {{ $rapport->attente }} <br>
                                                Affecté : {{ $rapport->affecte  }} <br>
                                                Terminée : {{ $rapport->termine  }}
                                            </td>
                                        @endforeach

                                        <td>
                                            @foreach ($patient_called as $patient)
                                                En attente : {{ $exam->total_general - $patient->called }} <br> Informé : {{ $patient->called  }}
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($patient_called as $patient)
                                                En attente : {{ $patient->not_deliver }} <br> Livré : {{ $patient->deliver  }}
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

</div>
@endsection


@push('extra-js')
<script>
    var baseUrl = "{{url('/')}}"
       var ROUTEGETDATATABLE = "{{ route('report.getTestOrdersforDatatableSuivi') }}"
       var ROUTEREPORTRAPPORTDATATABLE = "{{ route('report.getReportsRapportsforDatatable') }}"
       var TOKENSTOREDOCTOR = "{{ csrf_token() }}"

</script>
<script src="{{asset('viewjs/report/suivi.js')}}"></script>
<script src="{{asset('viewjs/report/rapport.js')}}"></script>


@endpush
