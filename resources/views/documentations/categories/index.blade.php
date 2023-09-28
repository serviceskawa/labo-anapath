@extends('layouts.app2')

@section('title', 'Catégories')

@section('content')
<style>
    .hidden-div {
        display: none;
    }

    .hidden-div-file {
        display: none;
    }
</style>

<div class="card my-3 hidden-div" id="myDiv">
    @include('documentations.categories.create')
</div>

<div class="card my-3 hidden-div-file" id="myDivFile">
    @include('documentations.docs.create')
</div>




<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                Toutes les documentations
            </div>
            <h4 class="page-title">Documentations</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- Liste des Categories communement appeler categorie --}}
                <div class="page-aside-left">

                    <div class="btn-group d-block mb-2">
                        <button type="button" class="btn btn-success dropdown-toggle w-100" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false"><i class="mdi mdi-plus"></i> Nouveau </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" id="toggle-button"><i
                                    class="mdi mdi-folder-plus-outline me-1"></i>
                                Dossier</a>
                            <a class="dropdown-item" href="#" id="toggle-button-file"><i
                                    class="mdi mdi-file-document me-1"></i> Importer un
                                fichier </a>
                        </div>
                    </div>
                    <div class="email-menu-list mt-3">
                        <a href="#" class="list-group-item border-0"><i
                                class="mdi mdi-clock-outline font-18 align-middle me-2"></i>Recent</a>
                        <div class="list-of-categorie">

                        </div>
                        <a href="#" class="list-group-item border-0"><i
                                class="mdi mdi-delete font-18 align-middle me-2"></i>Deleted Files</a>
                    </div>
                </div>

                <div class="page-aside-right">
                    <h5 class="mb-2 category-name"></h5>
                    <div class="row mx-n1 g-0" id="category-content">
                    </div>
                </div>

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




{{-- Le code js qui permet de retourner toutes les categories --}}
<script>
    $(document).ready(function() {
    $.ajax({
    // URL de la route vers le contrôleur
    url: '/getcategorie-doc',
    type: 'GET',
    dataType: 'json',
    success: function(data) {
    // "data" est déjà un objet JavaScript, pas besoin de le repasser avec JSON.parse

    var categoriesHtml = ''; // Initialisez la variable categoriesHtml

    $.each(data, function(index, category) {
    categoriesHtml += '<a  href="#" class="list-group-item border-0 category-link" data-category-id="' + category.id + '">';
        categoriesHtml += '<i class="mdi mdi-folder-outline font-18 align-middle me-2"></i>';
        categoriesHtml += category.name;
        categoriesHtml += '</a>';
    });

    $('.list-of-categorie').html(categoriesHtml);

    // Ajoutez un gestionnaire de clic aux liens de catégorie
    $('.category-link').click(function(event) {
    event.preventDefault();
    var categoryId = $(this).data('category-id');
    // Récupérez le nom de la catégorie du texte du lien
    var categoryName = $(this).text(); 
    $('.category-name').text(categoryName);
    
    // Utilisez categoryId pour récupérer le contenu de la catégorie depuis le serveur
    $.ajax({
        url: '/get-docs-by-category/' + categoryId,
        type: 'GET',
        dataType: 'json', // Le contenu est au format JSON
        success: function(categoryContent) {


        // Créez une variable pour stocker le contenu HTML
        var contentHtml = '';
        
        // Utilisez $.each pour parcourir les objets dans le tableau JSON
        $.each(categoryContent, function(index, entry) {
        // Créez un élément HTML pour chaque entrée
        contentHtml += '<div class="col-xxl-3 col-lg-6"><div class="card m-1 shadow-none border"><div class="p-2"><div class="row align-items-center"><div class="col-auto"><div class="avatar-sm"><span class="avatar-title bg-light text-secondary rounded"><i class="mdi mdi-folder-zip font-16"></i></span></div></div><div class="col ps-0"><a href="javascript:void(0);" class="text-muted fw-bold">'+entry.title+'</a><p class="mb-0 font-13">2.3 MB</p></div></div></div></div></div></div>';
        });

        // Mettez à jour le contenu de la div "category-content"
        $('#category-content').html(contentHtml);

        },
            error: function(xhr, status, error) {
            console.error(error);
            }
        });
    });

    },
        error: function(xhr, status, error) {
        // Gérez les erreurs si nécessaire
        console.error(error);
        }
    });
});
</script>



{{-- Le code js qui permet d'afficher le formulaire de creation de categorie --}}
<script>
    $(document).ready(function() {
    // Ciblez le lien ou le bouton avec l'identifiant "toggle-button"
    $('#toggle-button').click(function(event) {
        event.preventDefault(); // Empêchez le comportement par défaut du lien

        // Ciblez la div avec l'identifiant "myDiv" et basculez sa visibilité
        $('#myDiv').toggle();
        });
    });



    $(document).ready(function() {
    // Ciblez le lien ou le bouton avec l'identifiant "toggle-button"
    $('#toggle-button-file').click(function(event) {
    event.preventDefault(); // Empêchez le comportement par défaut du lien
    
        // Ciblez la div avec l'identifiant "myDiv" et basculez sa visibilité
        $('#myDivFile').toggle();
        });
    });
</script>




@endpush
{{-- @extends('layouts.app2')

@section('title', 'Catégories')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#bs-example-modal-lg-create">Ajouter une nouvelle catégorie</button>
            </div>
            <h4 class="page-title">Catégories</h4>
        </div>
        @include('documentations.categories.create')
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
            <h5 class="card-title mb-0">Liste des catégories</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($documents_categories as $document)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $document->name}}</td>
                            <td>
                                @include('documentations.categories.edit',['document' => $document])
                                <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-edit-{{ $document->id }}"
                                    class="btn btn-info"><i class="mdi mdi-lead-pencil"></i>
                                </button>
                                <button type="button" onclick="deleteModalDocCategorie({{ $document->id }})"
                                    class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
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
@endpush --}}