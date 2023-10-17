@extends('layouts.app2')

@section('title', 'Reports')

@section('content')
<div class="">
        <div class="page-title-box">

            <h4 class="page-title">Affectation {{ $assignment->code }}
                {{-- {{ $doctor->firstname }} {{ $doctor->lastname }} --}}
            </h4>
        </div>

        <form action="{{ route('report.assignment.update') }}" method="post">
            <div class="card my-3">
                <div class="card-body">
                    <input type="hidden" name="id" value="{{$assignment->id}}">

                    <div class="row">
                        <div class="col-6">
                            <label for="example-select" class="form-label">Docteur</label>
                            <select class="form-select select2" data-toggle="select2" name="user_id" id="user_id" required>
                                <option value="">Sélectionner le docteur</option>
                                @foreach (getUsersByRole('docteur') as $doctor)
                                    <option value="{{ $doctor->id }}" {{ $assignment->user_id == $doctor->id ? 'selected' :'' }} >{{ $doctor->fullname() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6">
                            <label for="example-select" class="form-label">Date</label>
                            <input type="date" id="date_assignment" class="form-control" name="date"
                            value="{{ $assignment->date ? $assignment->date : now()->format('Y-m-d') }}">
                        </div>

                    </div>

                </div>
            </div>

             {{-- Debut du bloc pour faire les l'ajout des articles  --}}
             <div class="card mb-md-0 mb-3">
                <div class="card-header">
                    Liste des demandes d'examens affectées
                </div>
                <h5 class="card-title mb-0"></h5>

                <div class="card-body">
                    {{-- @if ($ticket->status == 'en attente') --}}
                        <form method="POST" id="addDetailForm" autocomplete="on">
                            @csrf
                            <div class="row d-flex align-items-end">
                                <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                        <label for="example-select" class="form-label">Code</label>
                                        <select class="form-select select2" data-toggle="select2" id="test_order_id" name="test_order_id">
                                            <option value="">Sélectionner une demande d'examen</option>
                                            @foreach ($testOrders as $order)
                                                <option value="{{ $order->id }}">{{ $order->code }} {{ isAffecte($order->id) ? '('.isAffecte($order->id)->fullname().')' :'' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">

                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Note</label>
                                        <input type="text" name="note" id="note"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-2 col-12">
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-primary"
                                            id="add_detail">Ajouter</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    <div id="cardCollpase1" class="show collapse pt-3">

                        <table id="datatable1"
                            class="detail-list-table table-striped dt-responsive nowrap w-100 table">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Demande d'examen</th>
                                    <th>Note</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                </tr>
                            </tfoot>
                        </table>

                        <div class="modal-footer">
                            <button type="submit"
                                class="btn w-100 btn-success">Soumettre</button>
                        </div>
                    </div>

                </div>

            </div> <!-- end card-->



        </form>


</div>

@endsection

@push('extra-js')
<script>
    var assignment = {!! json_encode($assignment) !!}


    var baseUrl = "{{ url('/') }}"
    var ROUTESTOREDETAILTICKET = "{{ route('report.assignment.detail.store') }}"
    var TOKENSTOREDETAILTICKET = "{{ csrf_token() }}"
    // var ROUTEUPDATETOTALTICKET = "{{ route('cashbox.ticket.updateTotal') }}"
    // var TOKENUPDATETOTALTICKET = "{{ csrf_token() }}"
    var ROUTEGETDETAIL = "{{ route('report.assignment.getDetail',$assignment->id)}}"
    </script>
    <script src="{{asset('viewjs/report/assignment.js')}}"></script>
@endpush
