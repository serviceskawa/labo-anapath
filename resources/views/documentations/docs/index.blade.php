@extends('layouts.app2')

@section('title', 'Documents')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#bs-example-modal-lg-create-docs">Ajouter un nouveau document</button>
            </div>
            <h4 class="page-title"> {{$all_docs ? 'Documents' : 'Partagé avec moi'}} </h4>
        </div>
        @include('documentations.docs.create')
    </div>
</div>

<div class="">

    @include('layouts.alerts')
    @include('documentations.docs.share')

    <div class="card mb-md-0 mb-3">
        <div class="card-body">
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                    aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            <h5 class="card-title mb-0">Liste des documents</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Catégorie de document</th>
                            <th>Date de création</th>
                            <th>Crée par</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($docs as $key => $doc)

                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $doc->title}}</td>
                                    <td>{{ $doc->document_categorie->name}}</td>
                                    <td>{{ date_format($doc->created_at,'d/m/Y')}}</td>
                                    <td>{{ $doc->user->fullname()}}</td>
                                    <td>
                                        <div class="conversation-actions dropdown">
                                            <button class="btn btn-sm btn-link" data-bs-toggle="dropdown" aria-expanded="false"><i class='uil uil-ellipsis-v'></i></button>
                                            @include('documentations.docs.edit',['doc' => $doc])
                                            @include('documentations.docs.edit_new_version',['doc' => $doc])
                                            @include('documentations.docs.history_version',['doc' => $doc])

                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a type="button" class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#bs-example-modal-lg-edit-{{ $doc->id }}">
                                                <i class="mdi mdi-pencil"></i>
                                                Editer
                                                </a>

                                                <a class="dropdown-item" id="share-item" data-entry-id="{{ $doc->id }}" href="#">
                                                    <i class="mdi mdi-share-variant"></i>
                                                    Partager
                                                </a>
                                                <a class="dropdown-item"  href="{{asset('storage/'.$doc->attachment)}}" target="_blank" type="application/pdf">
                                                    <i class="mdi mdi-eye"></i>
                                                    Visualiser
                                                </a>
                                                <a class="dropdown-item" href="{{asset('storage/'.$doc->attachment)}}" download>
                                                    <i class="mdi mdi-download"></i>
                                                    Télécharger
                                                </a>
                                                <a class="dropdown-item" type="button" data-bs-toggle="modal"
                                                data-bs-target="#bs-example-modal-lg-edit-new-version-{{ $doc->id }}">
                                                    <i class="mdi mdi-upload"></i>
                                                    Nouvelle version
                                                </a>
                                                <a class="dropdown-item" type="button" data-bs-toggle="modal"
                                                data-bs-target="#bs-example-modal-lg-history-version-{{ $doc->id }}">
                                                    <i class="mdi mdi-history"></i>
                                                    Historique des versions
                                                </a>
                                                <a class="dropdown-item" href="{{route('doc.supprimer',$doc)}}">
                                                    <i class="mdi mdi-delete"></i>
                                                    Supprimer
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    {{-- <td>
                                        @include('documentations.docs.edit',['doc' => $doc])
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#bs-example-modal-lg-edit-{{ $doc->id }}" class="btn btn-info"><i
                                                class="mdi mdi-lead-pencil"></i>
                                        </button>
                                        <button type="button" onclick="deleteModalDoc({{ $doc->id }})" class="btn btn-danger"><i
                                                class="mdi mdi-trash-can-outline"></i> </button>
                                    </td> --}}
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
    // Écoute les clics sur les liens avec la classe "voir-plus-link"
    $(document).on('click', '#share-item', function(event) {

        // Récupère l'ID de l'entrée à partir de l'attribut "data-entry-id"
        var entryId = $(this).data('entry-id');

        $('#doc_id5').val(entryId)
        $('#shareModal').modal('show');

    });
</script>
{{-- <script src="{{asset('viewjs/patient/index.js')}}"></script> --}}
@endpush
