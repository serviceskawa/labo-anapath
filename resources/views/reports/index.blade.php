@extends('layouts.app2')

@section('title', 'Reports')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Comptes rendu</h4>

        </div>

        <!----MODAL---->

    </div>
</div>

<div class="">

    @include('layouts.alerts')

    <div class="card mb-md-0 mb-3">
        <div class="card-body">
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                    aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            <h5 class="card-title mb-0">Liste des comptes rendu</h5>

            <div id="cardCollpase1" class="show collapse pt-3">

                <div class="row mb-3">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Rechercher</label>
                            <input type="text" name="contenu" id="contenu" class="form-control">
                        </div>
                    </div> <!-- end col -->


                    <div class="col-lg-3">

                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Statut</label>
                            <select name="statusquery" id="statusquery" class="form-control">
                                <option value="">Tous</option>
                                <option value="0">Attente</option>
                                <option value="1">Valider</option>
                            </select>
                        </div>

                    </div> <!-- end col -->

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Date Début</label>
                            <input type="date" name="dateBegin" id="dateBegin" class="form-control">
                        </div>
                    </div> <!-- end col -->

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Date fin</label>
                            <input type="date" name="dateEnd" id="dateEnd" class="form-control">
                        </div>
                    </div> <!-- end col -->

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Tags</label>
                            <select name="tag_id" id="tag_id" class="form-control">
                                <option value="">Tous</option>
                                @forelse ($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>



                <table id="datatable1" class="table-striped dt-responsive nowrap w-100 table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Code patient</th>
                            <th>Nom Patient</th>
                            <th>Telephone</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th hidden style="display: none"></th>
                            <th>Actions</th>
                        </tr>
                    </thead>


                </table>

            </div>
        </div>
    </div> <!-- end card-->





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
                    <a href="#demandes" style="text-decoration : none; color:inherit;">Performances médecin</a>
                </div>

                <div class="col-lg-3 p-1 ml-3 alert alert-success rounded-pill"
                    style="margin-right: 5px; text-align:center;">
                    <a href="#rapports" style="text-decoration : none; color:inherit;">Rapports</a>
                </div>

                <form action="" id="rapports-datatables-report">
                    <div class="row mb-3 d-flex">
                        <div class="col-lg-3">
                            <label for="example-fileinput" class="form-label">Année</label>
                            <select name="year" id="year" class="form-control">
                                <option value="">Tous</option>
                                @foreach ($list_years as $list_year)
                                <option value="{{ $list_year->year }}" {{ $list_year->year == $year ? 'selected':''}}>{{
                                    $list_year->year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label for="example-fileinput" class="form-label">Mois</label>
                            <select name="month" id="month" class="form-control">
                                <option value="">Tous</option>
                                <option value="01" {{ $month=='01' ? 'selected' :''}}>Janvier</option>
                                <option value="02" {{ $month=='02' ? 'selected' :''}}>Février</option>
                                <option value="03" {{ $month=='03' ? 'selected' :''}}>Mars</option>
                                <option value="04" {{ $month=='04' ? 'selected' :''}}>Avril</option>
                                <option value="05" {{ $month=='05' ? 'selected' :''}}>Mai</option>
                                <option value="06" {{ $month=='06' ? 'selected' :''}}>Juin</option>
                                <option value="07" {{ $month=='07' ? 'selected' :''}}>Juillet</option>
                                <option value="08" {{ $month=='08' ? 'selected' :''}}>Août</option>
                                <option value="09" {{ $month=='09' ? 'selected' :''}}>Septembre</option>
                                <option value="10" {{ $month=='10' ? 'selected' :''}}>Octobre</option>
                                <option value="11" {{ $month=='11' ? 'selected' :''}}>Novembre</option>
                                <option value="12" {{ $month=='12' ? 'selected' :''}}>Décembre</option>
                            </select>
                        </div>


                        <div class="col-lg-3">
                            <label for="example-fileinput" class="form-label">Doctor</label>
                            <select name="doctor" id="doctor" class="form-control">
                                <option value="">Tous</option>
                                @foreach (getUsersByRole('docteur') as $item)
                                <option value="{{ $item->id }}" {{ $doctor==$item->id ? 'selected' : ''}}>
                                    {{ $item->lastname }} {{ $item->firstname }}
                                </option>
                                @endforeach
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
                    <table class="table table-centered w-100 dt-responsive nowrap" id="">
                        <thead class="table-light">
                            <tr>
                                <th>Période</th>
                                <th>Comptes sortis</th>
                                <th>Délai respecté</th>
                                <th>Hors Délai</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td>{{ $month }} - {{ $year }}</td>
                                <td>{{ $report_nbres }}</td>
                                <td>{{ $percentageIn_Deadline }}%</td>
                                <td>{{ $percentageOver_Deadline }}%</td>
                            </tr>
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
       var ROUTEGETDATATABLE = "{{ route('report.getReportsforDatatable') }}"

</script>
<script src="{{asset('viewjs/report/index.js')}}"></script>
@endpush