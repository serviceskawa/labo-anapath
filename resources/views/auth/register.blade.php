<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $setting = \App\Models\Setting::orderBy('id', 'desc')->first();
    @endphp
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ $setting ? Storage::url($setting->favicon) : '' }}">

    <!-- App css -->
    <link href="{{ asset('/adminassets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminassets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('/adminassets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />

</head>

<body class="loading authentication-bg"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">

                        <!-- Logo -->
                        <div class="card-header pt-4 pb-4 text-center ">
                            <a href="#">
                                <span>
                                    <img src="{{ $setting ? Storage::url($setting->logo) : '' }}" alt=""></span>

                            </a>
                        </div>


                        <div class="card-body p-4">

                            <form method="POST" action="{{ route('register') }}">
                                @csrf


                                @if ($errors->any())
                                    <div class="alert alert-danger" role="alert">
                                        <i class="dripicons-wrong me-2"></i><strong>Erreur!!</strong>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                        </div>
                        @endif

                        <div class="text-center w-75 m-auto">
                            <h4 class="text-dark-50 text-center pb-0 fw-bold">Inscription</h4>
                            <p class="text-muted mb-4">Veillez remplir le formulaiore pour créer un compte utilisateur
                            </p>
                        </div>

                        <form action="#">

                            <div class="mb-3">
                                <label for="emailaddress" class="form-label">Nom</label>
                                <input class="form-control" type="text" name="firstname" id="emailaddress"
                                    required="" placeholder="Entrer votre nom">
                            </div>
                            <div class="mb-3">
                                <label for="emailaddress" class="form-label">Prénom</label>
                                <input class="form-control" type="text" name="lastname" required=""
                                    placeholder="Entrer votre prénom">
                            </div>

                            <div class="mb-3">
                                <label for="emailaddress" class="form-label">Email</label>
                                <input class="form-control" type="email" name="email" id="emailaddress"
                                    required="" placeholder="Entrer votre adresse email">
                            </div>

                            <div class="mb-3">

                                <label for="password" class="form-label">Mot de passe</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" name="password" class="form-control"
                                        placeholder="Entrer votre mot de passe">
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="emailaddress" class="form-label">Confirmation du mot de passe</label>
                                <input class="form-control" type="password" name="password_confirmation"
                                    id="emailaddress" required="" placeholder="Entrer à nouveau le mot de passe">
                            </div>


                            <div class="mb-3 mb-0 text-center">
                                <button class="btn btn-primary" type="submit"> Connexion </button>
                            </div>

                        </form>
                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-muted">Vous souhaitez retourner à la page de connexion ? <a
                                href="{{ route('login') }}" class="text-muted ms-1"><b>Connexion</b></a></p>
                    </div> <!-- end col -->
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
        Labocaap
    </footer>

    <!-- bundle -->
    <script src="{{ asset('/adminassets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('/adminassets/js/app.min.js') }}"></script>

</body>

</html>
