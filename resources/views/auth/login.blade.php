<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }}</title>
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
                        <div class="card-header pt-2 pb-2 text-center ">
                            <a href="#">
                                <span>
                                    <img src="{{ $setting ? Storage::url($setting->logo) : '' }}" alt="" width="250px"></span>
                            </a>
                        </div>

                        <!--@include('layouts.alerts')-->
                        <div class="card-body p-4">

                            <form method="POST" action="{{ route('login') }}">
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

                                @endif

                                <div class="text-center w-75 m-auto">
                                    <h4 class="text-dark-50 text-center pb-0 fw-bold">Se connecter</h4>
                                    <p class="text-muted mb-4">Renseignez vos identifiants de connexion.</p>
                                </div>

                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Adresse e-mail</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email"
                                        name="email" id="emailaddress" required="" placeholder="julie@exemple.com">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">

                                    <label for="password" class="form-label">Mot de passe</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Mot de passe">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
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
                            <p class="text-muted"><a href="{{ route('password.request') }}"
                                    class="text-muted ms-1"><b>Mot
                                        de
                                        passe oublié ?</b></a></p>
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
        <a href="mailto:serviceskawa@gmail.com?subject=Le sujet&body=Le corps du message">Cliquez ici pour contacter le
            Support Technique</a>
    </footer>

    <!-- bundle -->
    <script src="{{ asset('/adminassets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('/adminassets/js/app.min.js') }}"></script>

</body>

</html>
