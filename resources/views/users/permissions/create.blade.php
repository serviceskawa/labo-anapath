@extends('layouts.app2')

@section('content')
    <div class="">

        @include('layouts.alerts')

        <div class="card my-3">
            <div class="card-header">
                Creer une nouvelle permission
            </div>
            <div class="card-body">

                <form action="{{ route('user.permission-store') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Titre</label>
                                <input type="text" class="form-control" name="titre">
                            </div>
                        </div>

                    </div>

            </div>

            <div class="modal-footer">
                <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Creer</button>
            </div>


            </form>
        </div>

        <div class="card mb-md-0 mb-3">
            <div class="card-body">
                <div class="card-widgets">
                    <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                    <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                        aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                    <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                </div>
                <h5 class="card-title mb-0">Liste des comptes rendu</h5>

                <div id="cardCollpase1" class="collapse pt-3 show">


                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Slug</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>


                        <tbody>

                            @foreach ($permissions as $item)
                                <tr>
                                    <td>{{ $item->titre }} </td>
                                    <td>{{ $item->slug }} </td>
                                    <td>{{ $item->created_at }} </td>
                                    <td> <a type="button" href="{{ route('report.show', $item->id) }}"
                                            class="btn btn-primary"><i class="mdi mdi-eye"></i> </a>
                                        <a type="button" href="{{ route('report.send-sms', $item->id) }}"
                                            class="btn btn-danger"><i class="mdi mdi-lead-pencil"></i> </a>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    </div>
@endsection


@push('extra-js')
@endpush
