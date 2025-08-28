<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Sélection de branche - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $setting = \App\Models\Setting::orderBy('id', 'desc')->first();
        $logo = \App\Models\SettingApp::where('key', 'logo')->first();
        $favicon = \App\Models\SettingApp::where('key', 'favicon')->first();
    @endphp

    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ $favicon ? Storage::url($favicon->value) : '' }}">

    <!-- App css -->
    <link href="{{ asset('/adminassets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminassets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('/adminassets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />

    <style>
        .branch-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
            min-height: 120px;
        }

        .branch-card:hover {
            border-color: #5369f8;
            box-shadow: 0 4px 12px rgba(83, 105, 248, 0.15);
            transform: translateY(-2px);
        }

        .branch-card.selected {
            border-color: #5369f8;
            background-color: #f8f9ff;
        }

        .branch-icon {
            font-size: 2.5rem;
            color: #5369f8;
        }

        .branch-default {
            background-color: #28a745;
            color: white;
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 12px;
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>

<body class="loading authentication-bg"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>

    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="card">
                        <!-- Logo -->
                        <div class="card-header pt-4 pb-4 text-center">
                            <a href="#">
                                <span>
                                    <img src="{{ $logo ? Storage::url($logo->value) : '' }}" alt="" width="250px">
                                </span>
                            </a>
                        </div>

                        <div class="card-body p-4">

                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    <i class="dripicons-wrong me-2"></i><strong>Erreur!</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="text-center mb-4">
                                <h4 class="text-dark-50 fw-bold">Bienvenue {{ $user->firstname }} {{ $user->lastname }}</h4>
                                <p class="text-muted mb-4">Sélectionnez la branche avec laquelle vous souhaitez travailler</p>
                            </div>

                            <form method="POST" action="{{ route('store.branch') }}" id="branchForm">
                                @csrf
                                <input type="hidden" name="branch_id" id="selectedBranchId" value="">

                                <div class="row mx-auto justify-content-center text-center">
                                    @foreach($branches as $branch)
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="card branch-card" data-branch-id="{{ $branch->id }}" style="position: relative;">
                                                @if($branch->is_default)
                                                    <div class="branch-default">
                                                        <i class="mdi mdi-star"></i> Par défaut
                                                    </div>
                                                @endif

                                                <div class="card-body text-center">
                                                    <div class="branch-icon mb-3">
                                                        <i class="mdi mdi-office-building-outline"></i>
                                                    </div>
                                                    <h5 class="card-title mb-2">{{ $branch->name }}</h5>

                                                    @if($branch->code)
                                                        <p class="card-text text-muted small">{{ $branch->code }}</p>
                                                    @endif

                                                    <div class="mt-3">
                                                        <i class="mdi mdi-map-marker text-muted me-1"></i>
                                                        <small class="text-muted">{{ $branch->location ?? 'Adresse non définie' }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg" id="confirmBtn" disabled>
                                        <i class="mdi mdi-check-circle me-2"></i>
                                        Continuer vers le tableau de bord
                                    </button>
                                </div>

                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        <i class="mdi mdi-information-outline me-1"></i>
                                        Vous pourrez changer de branche ultérieurement depuis votre profil
                                    </small>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted">
                                <a href="{{ route('logout') }}" class="text-muted"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="mdi mdi-logout me-1"></i>
                                    Se déconnecter
                                </a>
                            </p>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        <a href="mailto:serviceskawa@gmail.com?subject=Le sujet&body=Le corps du message">
            Cliquez ici pour contacter le Support Technique
        </a>
    </footer>

    <!-- bundle -->
    <script src="{{ asset('/adminassets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('/adminassets/js/app.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const branchCards = document.querySelectorAll('.branch-card');
            const selectedBranchId = document.getElementById('selectedBranchId');
            const confirmBtn = document.getElementById('confirmBtn');

            // Gérer la sélection des branches
            branchCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Retirer la sélection de tous les autres cards
                    branchCards.forEach(c => c.classList.remove('selected'));

                    // Ajouter la sélection au card cliqué
                    this.classList.add('selected');

                    // Mettre à jour la valeur cachée
                    const branchId = this.getAttribute('data-branch-id');
                    selectedBranchId.value = branchId;

                    // Activer le bouton
                    confirmBtn.disabled = false;
                    confirmBtn.classList.remove('btn-secondary');
                    confirmBtn.classList.add('btn-primary');
                });
            });

            // Sélectionner automatiquement la branche par défaut si elle existe
            const defaultBranch = document.querySelector('.branch-card [class*="branch-default"]')?.closest('.branch-card');
            if (defaultBranch) {
                defaultBranch.click();
            }

            // Gérer la soumission du formulaire
            document.getElementById('branchForm').addEventListener('submit', function(e) {
                if (!selectedBranchId.value) {
                    e.preventDefault();
                    alert('Veuillez sélectionner une branche');
                    return false;
                }

                // Désactiver le bouton pour éviter les double clics
                confirmBtn.disabled = true;
                confirmBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Connexion en cours...';
            });
        });
    </script>
</body>

</html>
