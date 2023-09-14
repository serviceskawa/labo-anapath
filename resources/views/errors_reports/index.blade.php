@extends('layouts.app2')

@section('title', 'Signaler un problème')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#standard-modal">Signaler un problème</button> --}}
                <a href="{{route('probleme.report.create')}}" type="button" class="btn btn-primary">Signaler un problème</a>
            </div>
            <h4 class="page-title">Problèmes signalés</h4>
        </div>

        <!----MODAL---->

        {{-- @include('errors_reports.create_modal') --}}

        @include('errors_reports.edit')

    </div>
</div>


<div class="">


    @include('layouts.alerts')


    <div class="card mb-md-0 mb-3">
        <div class="card-body">
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false" aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            <h5 class="card-title mb-0">Liste des problèmes signalés</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Demande d'examen</th>
                            <th>Description</th>
                            <th>Catégorie</th>
                            <th>Status</th>
                            @foreach (getRolesByUser(Auth::user()->id) as $role)

                                @if ($role->name == "rootuser")
                                    <th>Traité</th>
                                    @break
                                @endif
                            @endforeach

                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($problemReports as $key=>$item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->order->code }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->categorie->name }}</td>
                            <td>
                                @if ($item->status==1)
                                <span class="badge bg-success">Traité</span>
                                @else
                                <span class="badge bg-warning">En attente</span>
                                @endif
                            </td>
                            @foreach (getRolesByUser(Auth::user()->id) as $role)
                                {{-- //Lorsque l'utilisateur n'a pas le role nécessaire. --}}

                                @if ($role->name == "rootuser")
                                    <td>
                                        <div>
                                            <input type="checkbox" {{ $item->status != 0 ? 'checked' : '' }} id="switch3" data-switch="success"/>
                                            <label for="switch3"  onclick="updateStatus({{$item->id}})" data-on-label="oui" data-off-label="non" class="mb-0 d-block"></label>
                                        </div>
                                    </td>
                                    @break
                                @endif
                            @endforeach


                        </tr>
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
        var checkbox = document.getElementById("switch3");
        checkbox.addEventListener('change', function () {
            if (checkbox.checked) {
                console.log('yes');
            } else {
                console.log('no');;
            }
        });

        function updateStatus(id)
        {
            console.log('cc');
            console.log('cc');
            var status = '';
            if (!checkbox.checked) {
                status=1;
            } else {
                status=0;
            }

            var e_id = id;
            console.log(e_id,status);

            $.ajax({
            url: "{{ route('probleme.report.update') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                id: e_id,
                status:status,
            },
            success: function (data) {
                toastr.success("Mis à jour avec succès", 'Ajout réussi');
                location.reload();

            },
            error: function (error) {
                console.log(error);
            }
        })

        }
    </script>


@endpush
