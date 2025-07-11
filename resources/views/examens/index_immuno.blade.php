@extends('layouts.app2')

@section('title', 'Examens2')

@section('content')
<div class="row">

    <div class="page-title-box">
        <div class="page-title-right mr-3">
            <a href="{{ route('test_order.create') }}"><button type="button" class="btn btn-primary">Ajouter une
                    nouvelle demande d'examen</button></a>
        </div>
        <h4 class="page-title">Demandes d'examen IMMUNO</h4>
    </div>

    <div class="">

        <!----MODAL---->
        @include('layouts.alerts')
        @include('examens.signals.create')

        <div class="card mb-md-0 mb-3">
            <div class="card-body">
                <div class="card-widgets">
                    <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                    <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                        aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                    <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                </div>
                <h5 class="card-title mb-0">Liste des demandes d'examen IMMUNO</h5>

                <div id="cardCollpase1" class="show collapse pt-3">

                    <div class="row mb-3">

                        <div class="col-lg-3">

                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Contrat</label>
                                <select name="contrat_immuno_id" id="contrat_immuno_id" class="form-control">
                                    <option value="">Tous les contrats</option>
                                    @forelse ($contrats as $contrat)
                                    <option value="{{ $contrat->id }}">{{ $contrat->name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>

                        </div> <!-- end col -->

                        <div class="col-lg-3">

                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Status</label>
                                <select name="exams_immuno_status" id="exams_immuno_status" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="1">Valider</option>
                                    <option value="0">En attente</option>
                                    <option value="livrer">Livrer</option>
                                    <option value="non_livrer">Non Livrer</option>
                                </select>
                            </div>

                        </div> <!-- end col -->

                        <div class="col-lg-3">

                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Type d'examen</label>
                                <select name="type_immuno_examen" id="type_immuno_examen" class="form-control">
                                    <option value="">Tous</option>
                                    @forelse ($types_orders as $type)
                                    @if ($type->slug == "immuno-interne" || $type->slug == "immuno-exterme")
                                    <option value="{{ $type->id }}">{{ $type->title }}</option>
                                @endif
                                    @empty
                                    Ajouter un Type d'examen
                                    @endforelse
                                </select>
                            </div>

                        </div> <!-- end col -->

                        <div class="col-lg-3">

                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Urgent</label>
                                <select name="cas_immuno_status" id="cas_immuno_status" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="1">Urgent</option>
                                </select>
                            </div>

                        </div> <!-- end col -->


                        <div class="col-lg-3">

                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Docteur</label>
                                <select name="doctor_immuno_signataire" id="doctor_immuno_signataire" class="form-control">
                                    <option value="">Tous</option>
                                    @foreach (getUsersByRole('docteur') as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->lastname }} {{ $item->firstname }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                        </div> <!-- end col -->

                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Rechercher</label>
                                <input type="text" name="contenu_immuno" id="contenu_immuno" class="form-control">
                            </div>
                        </div> <!-- end col -->

                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Date Début</label>
                                <input type="date" name="dateBegin_immuno" id="dateBegin_immuno" class="form-control">
                            </div>
                        </div> <!-- end col -->

                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Date fin</label>
                                <input type="date" name="dateEnd_immuno" id="dateEnd_immuno" class="form-control">
                            </div>
                        </div> <!-- end col -->

                        {{-- <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Status appel</label>
                                <select name="appel_immuno_status" id="appel_immuno_status" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="voice.completed">Décroché</option>
                                    <option value="voice.cancelled">Manqué</option>
                                    <option value="voice.busy">Racroché</option>
                                </select>
                            </div>
                        </div>  --}}

                    </div>
                    {{-- </form> --}}

                    <div class="row mb-3">
                        <div class="col-lg-2 p-1 alert alert-success rounded-pill"
                            style="margin-right: 5px; text-align:center;">
                            Livrer : {{$finishTest}}
                        </div>
                        <div class="col-lg-2 p-1 alert alert-warning rounded-pill"
                            style="margin-right: 5px; text-align:center;">
                            Valider : {{$noFinishTest}}
                        </div>
                        <div class="col-lg-2 p-1 ml-3 alert alert-danger rounded-pill"
                            style="margin-right: 5px; text-align:center;">
                            Cas urgent : {{$is_urgent}}
                        </div>
                    </div>

                    <table id="datatable3" class="dt-responsive nowrap w-100 table">
                        <thead>
                            <tr>
                                <th>Actions</th>
                                <th>Appel</th>
                                <th>Date</th>
                                <th>Code</th>
                                <th>Affecter à</th>
                                <th>Patient</th>
                                <th>Examens</th>
                                <th>Contrat</th>
                                {{-- <th>Pièce jointe</th> --}}
                                {{-- <th>Examens demandés</th> --}}
                                <th>Montant</th>
                                <th>Compte rendu</th>
                                {{-- <th>Type examen</th> --}}
                                <th>Urgent</th>
                            </tr>
                        </thead>

                    </table>

                </div>
            </div>
        </div>

      
    </div>
</div>
    @endsection

    @push('extra-js')
        <script>
            var baseUrl = "{{ url('/') }}"
            var ROUTETESTORDERDATATABLE3 = "{{ route('test_order.getTestOrdersforDatatable3') }}"
            var ROUTETESTORDERDATATABLE4 = "{{ route('test_order.getTestOrdersforDatatable4') }}"
            var URLupdateAttribuate = "{{ url('attribuateDoctor') }}" + '/' + doctor_id + '/' + order_id
            var URLUPDATEDELIVER = "{{ route('report.updateDeliver',"+id+") }}"
        </script>
        <script src="{{ asset('viewjs/test/order/immuno.js') }}"></script>
    @endpush
