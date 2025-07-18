@extends('layouts.app2')

@section('title', 'Catégories documentations')

@section('content')
<style>
    .hidden-div {
        display: none;
    }

    .hidden-div-file {
        display: none;
    }

    .active-category {
        font-weight: bold;
        color: red;
        font-size: 50px;
    }
</style>

<div class="card my-3 hidden-div" id="myDiv">
    {{-- @include('documentations.categories.create') --}}
</div>

<div class="card my-3 hidden-div-file" id="myDivFile">
    {{-- @include('documentations.docs.create') --}}
</div>

@include('layouts.alerts')
@include('documentations.docs.share')
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

                    <div class="btn-group">
                        <button type="button" class="btn btn-success mx-2" data-bs-toggle="modal"
                            data-bs-target="#bs-example-modal-lg-create-folder"><i
                                class="uil-folder-medical"></i>Dossier</button>
                        @include('documentations.categories.create')

                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#bs-example-modal-lg-create-docs"><i
                                class="uil-file-plus-alt"></i>Fichier</button>
                        @include('documentations.docs.create')
                    </div>

                    @include('documentations.docs_versions.create')
                    @include('documentations.docs_versions.history_version')
                    @include('documentations.docs_versions.edit')
                    <div class="email-menu-list mt-3">
                        <a href="#" class="list-group-item border-0" id="recent-link"><i
                                class="mdi mdi-clock-outline font-18 align-middle me-2"></i>Récent</a>

                        {{-- liste des categorie --}}
                        <div class="list-of-categorie">

                        </div>
                        <a href="#" class="list-group-item border-0" id="files-delete"><i class="mdi mdi-delete font-18 align-middle me-2"></i>Fichiers supprimés</a>
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

{{-- Debut du script --}}
{{-- <script>
    $(document).ready(function() {
    // Chargement initial des catégories
    loadCategories();

    // Gestionnaire de clic pour les liens de catégorie
    $(document).on('click', '.category-link', function(event) {
    event.preventDefault();
    var categoryId = $(this).data('category-id');
    var categoryName = $(this).text();
    $('.category-name').text(categoryName);

    // Chargement du contenu de la catégorie sélectionnée
    loadCategoryContent(categoryId);
    });

    // Gestionnaire de clic pour les liens "Voir plus"
    $(document).on('click', '.voir-plus-link', function(event) {
    event.preventDefault();
    var entryId = $(this).data('entry-id');
    var route = '/documents/detail/' + entryId; // Remplacez par votre route réelle
    window.location.href = route;
    });

    // Gestionnaire de clic pour le lien "Recent"
    $('#recent-link').click(function(event) {
    event.preventDefault();
    loadRecentFiles();
    });

    // Fonction pour charger les catégories initiales
    function loadCategories() {
    $.ajax({
    url: '/getcategorie-doc',
    type: 'GET',
    dataType: 'json',
    success: function(data) {
    var categoriesHtml = '';
    $.each(data, function(index, category) {
    categoriesHtml += '<a href="#" class="list-group-item border-0 category-link" data-category-id="' + category.id + '">';
        categoriesHtml += '<i class="mdi mdi-folder-outline font-18 align-middle me-2"></i>';
        categoriesHtml += category.name;
        categoriesHtml += '</a>';
    });
    $('.list-of-categorie').html(categoriesHtml);
    },
    error: function(xhr, status, error) {
    console.error(error);
    }
    });
    }

    // Fonction pour charger le contenu d'une catégorie
    function loadCategoryContent(categoryId) {
    $.ajax({
    url: '/get-docs-by-category/' + categoryId,
    type: 'GET',
    dataType: 'json',
    success: function(categoryContent) {
    var contentHtml = '';
    $.each(categoryContent, function(index, entry) {
    contentHtml += '<div class="col-xxl-3 col-lg-6">';
    contentHtml += '<div class="card m-1 shadow-none border">';
    contentHtml += '<div class="p-2"><div class="row align-items-center">';
    contentHtml += '<div class="col-auto"><div class="avatar-sm">';
    contentHtml += '<span class="avatar-title bg-light text-secondary rounded">';
    contentHtml += '<i class="mdi mdi-folder-zip font-16"></i></span></div></div>';
    contentHtml += '<div class="col ps-0"><a href="javascript:void(0);" class="text-muted fw-bold">' +entry.title + '</a>';
    contentHtml += '<p class="mb-0 font-13">2.3 MB</p></div>';
    contentHtml += '<div class="col-auto">';
    contentHtml += '<a href="#" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-xs" data-bs-toggle="dropdown" aria-expanded="false">';
    contentHtml += '<i class="mdi mdi-dots-horizontal"></i></a>';
    contentHtml += '<div class="dropdown-menu dropdown-menu-end">';
    contentHtml += '<a class="dropdown-item voir-plus-link" href="#" data-entry-id="' + entry.id + '">';
    contentHtml += '<i class="uil-eye me-2 text-muted vertical-middle"></i>Voir plus</a>';
    contentHtml += '<a class="dropdown-item download-button" data-bs-toggle="modal" data-bs-target="#downloadModal" data-category-id="' + entry.id + '" href="#">';
    contentHtml += '<i class="mdi mdi-download me-2 text-muted vertical-middle"></i>Download</a>';
    contentHtml += '</div></div></div></div></div></div></div>';
    });
    $('#category-content').html(contentHtml);
    },
    error: function(xhr, status, error) {
    console.error(error);
    }
    });
    }

    // Fonction pour charger les fichiers récents
    function loadRecentFiles() {
    $.ajax({
    url: '/documents-recents',
    type: 'GET',
    dataType: 'json',
    success: function(data) {
    var recentFiles = '';
    $.each(data, function(index, recentFile) {
    recentFiles += '<a href="#" data-category-id="' + recentFile.id + '">' + recentFile.title + '</a>';
    });
    $('#category-content').html(recentFiles);
    },
    error: function(xhr, status, error) {
    console.error(error);
    }
    });
    }
    });
</script> --}}
{{-- Fin du script --}}

{{-- Le code js qui permet de retourner toutes les categories --}}
<script>
    $(document).ready(function() {


    var user_connect = {!! json_encode(Auth::user()) !!}
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


    // Supprimez la classe "active-category" de toutes les catégories
    $('.category-link').removeClass('active-category');

    // Ajoutez la classe "active-category" à la catégorie cliquée
    $(this).addClass('active-category');


    // Utilisez categoryId pour récupérer le contenu de la catégorie depuis le serveur
    $.ajax({
        url: '/get-docs-by-category/' + categoryId,
        type: 'GET',
        dataType: 'json', // Le contenu est au format JSON
        success: function(categoryContent) {
        // Créez une variable pour stocker le contenu HTML
        var contentHtml = '';

        var promises = []; // Créez un tableau de promesses

        // Supposons que vous ayez un tableau d'objets représentant les éléments du menu.
        var menuItems = [
            {
                label: 'Editer',
                iconClass: 'mdi mdi-pencil',
                id: 'doc-edit-button',
                dataCategoryId: function(id) {
                    return id
                },
                dataCategoryName: function(name) {
                    return name
                },
                href: function(doc) {
                    return '#';
                }
            },
            {
                label: 'Partager',
                iconClass: 'mdi mdi-share-variant',
                id: 'share-item',
                dataCategoryId: function(id) {
                    return id
                },
                href: function(doc) {
                    return '#';
                }
            },
            {
                label: 'Visualiser',
                iconClass: 'mdi mdi-eye',
                type:"application/pdf",
                attachment:true,
                target:true,
                href: function(doc) {
                    return '{{asset('storage/')}}/' + doc;
                }
            },
            {
                label: 'Télécharger',
                iconClass: 'mdi mdi-download',
                download:true,
                attachment:true,
                href: function(doc) {
                    return '{{asset('storage/')}}/' + doc;
                }
            },
            {
                label: 'Nouvelle version',
                iconClass: 'mdi mdi-upload',
                id: 'download-button',
                dataCategoryId: function(id) {
                    return id
                },
                dataCategoryName: function(name) {
                    return name
                },

                href: function(doc) {
                    return '#';
                }
            },
            {
                label: 'Historique version',
                iconClass: 'mdi mdi-history',
                id: 'all-version-button',
                dataCategoryId: function(id) {
                    return id
                },
                dataCategoryName: function(name) {
                    return name
                },
                href: function(doc) {
                    return '#';
                }
            },
            {
                label: 'Supprimer',
                iconClass: 'mdi mdi-delete',
                href: function(doc) {
                    return '/document-supprimer/'+doc;
                }
            }
        ];
       


        // Utilisez $.each pour parcourir les objets dans le tableau JSON
            $.each(categoryContent, function(index, entry) {
                console.log(entry);
                var attachment = entry.attachment;
                console.log(attachment);
                var role_id = entry.role_id;
                var hasRole = false

                var promise = $.ajax({
                    url: '/users/check-role/' + role_id, // Remplacez '/check-role/' par l'URL de votre route Laravel
                    type: 'GET',
                    dataType: 'json',
                }).then(function(user){
                        // Vous pouvez maintenant utiliser la valeur de "user" pour afficher les informations appropriées
                        console.log(user);
                        console.log(user_connect);
                        if (user) {
                            // Le reste de votre code pour créer l'élément HTML
                            contentHtml += '<div class="col-xxl-3 col-lg-6">'
                            contentHtml += '<div class="card m-1 shadow-none border">'
                            contentHtml += '<div class="p-2"><div class="row align-items-center">'
                            contentHtml += '<div class="col-auto"><div class="avatar-sm">'
                            contentHtml += '<span class="avatar-title bg-light text-secondary rounded">'
                            contentHtml += '<i class="mdi mdi-folder-zip font-16"></i></span></div></div>'
                            contentHtml += '<div class="col ps-0"><a href="javascript:void(0);" class="text-muted fw-bold">'+entry.title+'</a>'
                            contentHtml += '<p class="mb-0 font-13"> ' + (entry.file_size / (1024 * 1024)).toFixed(2) + ' MB</p></div>'
                            contentHtml += '<div class="col-auto">'
                            contentHtml += '<a href="#" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-xs" data-bs-toggle="dropdown" aria-expanded="false">'
                            contentHtml += '<i class="mdi mdi-dots-horizontal"></i></a>'
                            contentHtml += '<div class="dropdown-menu dropdown-menu-end">'
                                for (var j = 0; j < menuItems.length; j++) {
                                    var item = menuItems[j];
                                    contentHtml += '<a class="dropdown-item" ';
                                    if (item.dataBsToggle) {
                                        contentHtml += 'data-bs-toggle="' + item.dataBsToggle + '" ';
                                    }
                                    if (item.id) {
                                        contentHtml += 'id="' + item.id + '" ';
                                    }
                                    if (item.dataBsTarget) {
                                        contentHtml += 'data-bs-target="' + item.dataBsTarget(entry.id) + '" ';
                                    }
                                    if (item.dataEntryId) {
                                        contentHtml += 'data-entry-id="' + item.dataEntryId(entry.id) + '" ';
                                    }
                                    if (item.dataCategoryId) {
                                        contentHtml += 'data-category-id="' + item.dataCategoryId(entry.id) + '" ';
                                    }
                                    if (item.dataCategoryName) {
                                        contentHtml += 'data-category-name="' + item.dataCategoryName(entry.title) + '" ';
                                    }
                                    if (item.type) {
                                        contentHtml += 'type="' + item.type + '" ';
                                    }
                                    if (item.download) {
                                        contentHtml += 'download ';
                                    }
                                    if (item.href && !item.attachment && !item.target) {
                                        contentHtml += 'href="' + item.href(entry.id) + '">';
                                    }
                                    if (item.href && item.attachment && !item.target) {
                                        contentHtml += 'href="' + item.href(attachment) + '">';
                                    }
                                    if (item.href && item.attachment && item.target) {
                                        contentHtml += 'href="' + item.href(attachment) + '" target="_blank">';
                                    }
                                    contentHtml += '<i class="' + item.iconClass + '"></i>';
                                    contentHtml += item.label + '</a>';
                                }


                            // contentHtml += '<a class="dropdown-item" id="share-item" data-entry-id="' + entry.id + '" href="#">'
                            // contentHtml += '<i class="mdi mdi-share-variant me-2 text-muted vertical-middle" ></i>Partager</a>'
                            // contentHtml += '<a class="dropdown-item voir-plus-link" href="#" data-entry-id="' + entry.id + '"><i class="uil-eye me-2 text-muted vertical-middle"></i>Voir plus</a>'
                            // // contentHtml += '<a class="dropdown-item" href="#"><i class="mdi mdi-pencil me-2 text-muted vertical-middle"></i>Rename</a>'
                            // contentHtml += '<a class="dropdown-item download-button" data-bs-toggle="modal" data-bs-target="#downloadModal" data-category-id="' + entry.id + '" href="#"><i class="mdi mdi-download me-2 text-muted vertical-middle"></i>Nouvelle version</a>'
                            // contentHtml += '<a class="dropdown-item delete-link" data-entry-id="' + entry.id + '" href="#"><i class="mdi mdi-delete me-2 text-muted vertical-middle"></i>Supprimer</a></div></div></div>'

                            contentHtml += '</div></div></div></div>'
                            contentHtml += '</div></div></div>';
                            console.log('cc user');
                        } else {
                            console.log(user_connect);
                            if (entry.user_id == user_connect.id ) {
                                contentHtml += '<div class="col-xxl-3 col-lg-6">'
                                contentHtml += '<div class="card m-1 shadow-none border">'
                                contentHtml += '<div class="p-2"><div class="row align-items-center">'
                                contentHtml += '<div class="col-auto"><div class="avatar-sm">'
                                contentHtml += '<span class="avatar-title bg-light text-secondary rounded">'
                                contentHtml += '<i class="mdi mdi-folder-zip font-16"></i></span></div></div>'
                                contentHtml += '<div class="col ps-0"><a href="javascript:void(0);" class="text-muted fw-bold">'+entry.title+'</a>'
                                contentHtml += '<p class="mb-0 font-13">' + (entry.file_size / (1024 * 1024)).toFixed(2) + ' MB</p></div>'
                                contentHtml += '<div class="col-auto">'
                                contentHtml += '<a href="#" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-xs" data-bs-toggle="dropdown" aria-expanded="false">'
                                contentHtml += '<i class="mdi mdi-dots-horizontal"></i></a>'

                                contentHtml += '<div class="dropdown-menu dropdown-menu-end">'
                                    for (var j = 0; j < menuItems.length; j++) {
                                    var item = menuItems[j];
                                    contentHtml += '<a class="dropdown-item" ';
                                    if (item.dataBsToggle) {
                                        contentHtml += 'data-bs-toggle="' + item.dataBsToggle + '" ';
                                    }
                                    if (item.id) {
                                        contentHtml += 'id="' + item.id + '" ';
                                    }
                                    if (item.dataBsTarget) {
                                        contentHtml += 'data-bs-target="' + item.dataBsTarget(entry.id) + '" ';
                                    }
                                    if (item.dataEntryId) {
                                        contentHtml += 'data-entry-id="' + item.dataEntryId(entry.id) + '" ';
                                    }
                                    if (item.dataCategoryId) {
                                        contentHtml += 'data-category-id="' + item.dataCategoryId(entry.id) + '" ';
                                    }
                                    if (item.dataCategoryName) {
                                        contentHtml += 'data-category-name="' + item.dataCategoryName(entry.title) + '" ';
                                    }
                                    if (item.type) {
                                        contentHtml += 'type="' + item.type + '" ';
                                    }
                                    if (item.download) {
                                        contentHtml += 'download ';
                                    }
                                    if (item.href && !item.attachment && !item.target) {
                                        contentHtml += 'href="' + item.href(entry.id) + '">';
                                    }
                                    if (item.href && item.attachment && !item.target) {
                                        contentHtml += 'href="' + item.href(attachment) + '">';
                                    }
                                    if (item.href && item.attachment && item.target) {
                                        contentHtml += 'href="' + item.href(attachment) + '" target="_blank">';
                                    }
                                    contentHtml += '<i class="' + item.iconClass + '"></i>';
                                    contentHtml += item.label + '</a>';
                                }
                                contentHtml += '</div></div></div></div>'
                                contentHtml+=  '</div></div></div>';
                                console.log('cc');
                            }
                        }
                })
                promises.push(promise);
                    // success: function(user) {

                    // },
                    // error: function(xhr, status, error) {
                    //     console.error(error);
                    // }

                // });
            // Créez un élément HTML pour chaque entrée
            });

            // Mettez à jour le contenu de la div "category-content"
            // $('#category-content').html(contentHtml);

            // Utilisez Promise.all pour attendre que toutes les promesses soient résolues
            Promise.all(promises).then(function() {
                // Mettez à jour le contenu de la div "category-content" une fois que toutes les promesses sont résolues
                $('#category-content').html(contentHtml);
            }).catch(function(error) {
                console.error(error);
            });

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
    $(document).on('click', '#share-item', function(event) {

        // Récupère l'ID de l'entrée à partir de l'attribut "data-entry-id"
        console.log(this);
        var entryId = this.getAttribute("data-category-id");
        console.log(entryId);
        $('#doc_id5').val(entryId);
        $('#shareModal').modal('show');

    });



    $(document).on('click', '#download-button', function(event) {
        event.preventDefault();
        var categoryId = this.getAttribute("data-category-id");
        var categoryName = this.getAttribute("data-category-name");

        // Définissez la valeur du champ input avec la classe "category-id-input"
        $('#first_doc_id').val(categoryId);
        $('#title').val(categoryName);

        // Affichez la fenêtre modale
        var modal = $('#downloadModal');
        modal.modal('show');
    });


    $(document).on('click', '#doc-edit-button', function(event) {
        event.preventDefault();
        var categoryId = this.getAttribute("data-category-id");
        var categoryName = this.getAttribute("data-category-name");

        console.log(categoryName);
        console.log(categoryId);

        // Définissez la valeur du champ input avec la classe "category-id-input"
        $('#doc_id8').val(categoryId);
        $('#title8').val(categoryName);

        // Affichez la fenêtre modale
        var modal = $('#doc-edit');
        modal.modal('show');
    });

    $('#edit-doc-form').on('submit', function() {
        event.preventDefault();
        // var formData = new FormData(this);
        var id = $('#doc_id8').val();
        var title = $('#title8').val();
        console.log(title);
        console.log(id);
        var attachments = $('#attachment').val();
        $.ajax({
            url: "{{ route('doc.update') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "doc_id": id,
                "title": title,
                "attachment": attachments
            },
            success: function(data) {
                console.log(data);
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    function formatTime(dateTimeString) {
        var date = new Date(dateTimeString);
        var day = date.getDate();
        var month = date.getMonth() + 1; // Mois commence à 0, donc nous ajoutons 1
        var year = date.getFullYear();
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();

        // Ajoutez un zéro devant les chiffres uniques (par exemple, 03 au lieu de 3)
        day = day < 10 ? '0' + day : day;
        month = month < 10 ? '0' + month : month;
        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        return day + '/' + month + '/' + year + ' ' + hours + ':' + minutes + ':' + seconds;
    }


    $(document).on('click', '#all-version-button', function(event) {
        event.preventDefault();
        var categoryId = this.getAttribute("data-category-id");
        var categoryName = this.getAttribute("data-category-name");
       

        // Définissez la valeur du champ input avec la classe "category-id-input"
        // $('#name_doc').val(categoryName);
        name_doc = document.getElementById('name_doc');
        name_doc.innerHTML = categoryName;
        $.ajax({
            url: '/document-all-version/'+categoryId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log(data);
                
                // Sélectionnez le corps du tableau
                var tableBody = document.querySelector("#datatable5 tbody");

                
                // Parcourez les données et ajoutez-les au tableau
                data.forEach(function(item) {
                    var menuVersionItems = [
                        {
                            label: 'Visualiser',
                            iconClass: 'mdi mdi-eye',
                            type: 'application/pdf',
                            attachment: true,
                            target: true,
                            action: 'view', // Ajout d'une action pour chaque élément du menu
                            href: function(doc) {
                                    return '{{asset('storage/')}}/' + item.attachment;
                                }
                        },
                        {
                            label: 'Télécharger',
                            iconClass: 'mdi mdi-download',
                            download: true,
                            attachment: true,
                            action: 'download', // Ajout d'une action pour chaque élément du menu
                            href: function(doc) {
                                    return '{{asset('storage/')}}/' +  item.attachment;
                                }
                        },
                    ];
                    var row = tableBody.insertRow(); // Créez une nouvelle ligne

                    // Créez des cellules pour chaque propriété de l'objet
                    var cell1 = row.insertCell(0); // Première cellule (id)
                    var cell2 = row.insertCell(1); // Deuxième cellule (name)
                    var cell3 = row.insertCell(2); // Troisième cellule (description)
                    var cell4 = row.insertCell(3); // Troisième cellule (description)

                    // Remplissez les cellules avec les données
                    cell1.textContent = formatTime(item.created_at);
                    cell2.textContent = item.title;
                    $.ajax({
                        url: '/document-get-user/'+item.id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            cell3.textContent = 'Ajouté par '+ data;
                        }

                    })
                  // Créez une div pour contenir les éléments du menu
                    var menuCell = document.createElement('div');
                    menuCell.className = 'd-flex justify-start';
                    // Parcourez les éléments du menu
                    menuVersionItems.forEach(function(menuItem) {
                        var link = document.createElement('a');
                        link.className = 'dropdown-item';
                        
                        if (menuItem.action === 'view') {
                            link.href = menuItem.href(item);
                            link.target = '_blank';
                        } else if (menuItem.action === 'download') {
                            link.href = menuItem.href(item);
                            link.download = true;
                        }
                        
                        link.innerHTML = '<i class="' + menuItem.iconClass + '"></i> ';
                        
                        // Ajoutez le lien à la div du menu
                        menuCell.appendChild(link);
                    });

                    // Insérez la div du menu dans la cellule
                    cell4.appendChild(menuCell);


                });
            }

        })

        // Affichez la fenêtre modale
        var modal = $('#allVersion');
        modal.modal('show');
    });



    // Écoute les clics sur les liens avec la classe "voir-plus-link"
    $(document).on('click', '.voir-plus-link', function(event) {
    event.preventDefault();

    // Récupère l'ID de l'entrée à partir de l'attribut "data-entry-id"
    var entryId = $(this).data('entry-id');

    // Construit la route côté client
    var route = '/documents/detail/' + entryId; // Remplacez '/doc/detail/' par votre route réelle

    // Redirige l'utilisateur
    window.location.href = route;
    });



    // Écoute les clics sur les liens avec la classe "voir-plus-link"
    $(document).on('click', '.delete-link', function(event) {
    event.preventDefault();

    // Récupère l'ID de l'entrée à partir de l'attribut "data-entry-id"
    var entryId = $(this).data('entry-id');

    // Construit la route côté client
    var route = '/document-supprimer/' + entryId; // Remplacez '/document-supprimer/' par votre route réelle

    // Redirige l'utilisateur
    window.location.href = route;
    });

    // Écoute les clics sur les liens avec la classe "voir-plus-link"
    $(document).on('click', '#share-item', function(event) {

        // Récupère l'ID de l'entrée à partir de l'attribut "data-entry-id"
        var entryId = $(this).data('entry-id');

        $('#doc_id').val(entryId)
        $('#shareModal').modal('show');

    });



    // Les fichiers recents comment les recuperees
    // Debut du code
        $(document).on('click', '#recent-link', function(event) {
        event.preventDefault();

                $.ajax({
                    // URL de la route vers le contrôleur
                    url: '/documents-recents',
                    type: 'GET',
                    dataType: 'json',
                        success: function(data) {
                        // "data" contiendra les fichiers récents retournés par la méthode du contrôleur

                        var recentFiles = ''; // Initialisez la variable recentFiles

                        // Utilisez une boucle pour parcourir les fichiers récents
                        $.each(data, function(index, recentFile) {
                            recentFiles += '<a href="#" data-category-id="' + recentFile.id + '">';
                            recentFiles += recentFile.title; // Assurez-vous que le titre du fichier est correct
                            recentFiles += '</a>';
                            recentFiles += '</br>';
                        });

                        // Mettez à jour le contenu de la div "category-content"
                        $('.category-name').html('Fichiers récents')
                        $('#category-content').html(recentFiles);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    // Fin du code






    // Les fichiers supprimes
    // Debut du code
    $(document).on('click', '#files-delete', function(event) {
    event.preventDefault();

    $.ajax({
    // URL de la route vers le contrôleur
    url: '/documents-delete-files',
    type: 'GET',
    dataType: 'json',
    success: function(data) {
    // "data" contiendra les fichiers récents retournés par la méthode du contrôleur

        var deletedFiles = ''; // Initialisez la variable recentFiles

        // Utilisez une boucle pour parcourir les fichiers récents
        $.each(data, function(index, deletedFile) {
            deletedFiles += '<a href="#" data-category-id="' + deletedFile.id + '">';
            deletedFiles += deletedFile.title;
            deletedFiles += '</a>';
        });

        // Mettez à jour le contenu de la div "category-content"
        $('.category-name').html('Fichiers supprimés')
        $('#category-content').html(deletedFiles);
        },
                error: function(xhr, status, error) {
                console.error(error);
                }
        });
    });
    // Fin du code
</script>



{{-- Le code js qui permet d'afficher le formulaire de creation de categorie
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
</script> --}}

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
