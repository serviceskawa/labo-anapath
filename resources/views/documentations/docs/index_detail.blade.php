@extends('layouts.app2')

@section('title', 'Documents')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <a href="{{ route('doc.categorie.index') }}" type="button" class="btn btn-primary"> <i
                        class="dripicons-reply"></i>
                    Retour</a>
            </div>
            <h4 class="page-title">Documents</h4>
        </div>

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
            <h5 class="card-title mb-0">Liste des differentes versions du documents</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Nom</th>
                            <th>Utilisateur</th>
                            <th>Fichier</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($cat as $doc)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $doc->created_at}}</td>
                            <td>{{ $doc->title}}</td>
                            <td>{{ App\Models\User::find($doc->user_id)->firstname }} {{
                                App\Models\User::find($doc->user_id)->lastname }}</td>
                            <td><a href="{{asset('storage/'.$doc->attachment)}}" download>Telecharger</a></td>
                            <td>
                                @include('documentations.docs.edit')
                                <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-edit-{{ $doc->id }}" class="btn btn-info"><i
                                        class="mdi mdi-lead-pencil"></i>
                                </button>
                                <button type="button" onclick="deleteModalDoc({{ $doc->id }})" class="btn btn-danger"><i
                                        class="mdi mdi-trash-can-outline"></i> </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


@push('extra-js')
<script>
    var baseUrl = "{{url('/')}}"
</script>
<script src="{{asset('viewjs/patient/index.js')}}"></script>
@endpush