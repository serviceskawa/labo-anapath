@extends('layouts.app2')

@section('title', 'Signaler un problème')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#standard-modal"><i class="mdi mdi-ticket"></i> Créer un ticket</button>
                {{-- <a href="{{route('probleme.report.create')}}" type="button" class="btn btn-primary"><i class="mdi mdi-ticket"></i> Créer un ticket</a> --}}
            </div>
            <h4 class="page-title"> <i class="mdi mdi-ticket"></i> Tickets</h4>
        </div>

        <!----MODAL---->

        @include('errors_reports.create_modal')

        {{-- @include('errors_reports.edit') --}}

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
            <h5 class="card-title mb-0">Liste des Tickets</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Numéro du ticket</th>
                            <th>Objet</th>
                            <th>Dernière actualisation</th>
                            <th>Status</th>
                           

                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($tickets as $key=>$item)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td><a href="{{route('probleme.report.edit',$item->id)}}" style="color: rgb(126, 122, 122)">{{ $item->ticket_code }}</a></td>
                                <td>{{ $item->subject }}</td>
                                <td><a href="{{route('probleme.report.edit',$item->id)}}" style="color: rgb(126, 122, 122)">{{ date_format($item->created_at, 'y-m-d (H:i)') }}</a></td>
                                <td>
                                    <a href="{{route('probleme.report.edit',$item->id)}}" style="color: rgb(126, 122, 122)">
                                        @if ($item->status == "ouvert")
                                            <span class="badge bg-success">Ouvert</span>
                                        @elseif ($item->status == "repondu")
                                            <span class="badge bg-info">Répondu</span>
                                        @elseif ($item->status == "fermé")
                                            <span class="badge bg-warning">Fermé</span>
                                        @endif
                                    </a>

                                </td>

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
