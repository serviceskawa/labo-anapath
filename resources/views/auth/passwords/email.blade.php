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
                        <div class="card-header pt-4 pb-4 text-center ">
                            <a href="#">
                                <span>
                                    <img src="{{ $setting ? Storage::url($setting->logo) : '' }}" alt="" width="250px"></span>

                            </a>
                        </div>

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center mt-0 fw-bold">Mot de passe oublié</h4>
                                <p class="text-muted mb-4">Merci de renseigner votre adresse e-mail. Vous recevrez un e-mail contenant les instructions vous permettant de réinitialiser votre mot de passe.</p>
                            </div>

                            @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Adresse e-mail</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email"
                                        name="email" id="email" required="" placeholder="Entrez votre email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-0 text-center">
                                    <button class="btn btn-primary" type="submit">Réinitialiser le mot de passe
                                    </button>
                                </div>
                            </form>
                        </div> <!-- end card-body-->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted">Revenir en arrière? <a href="{{ route('login') }}"
                                    class="text-muted ms-1"><b>Connexion</b></a></p>
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
