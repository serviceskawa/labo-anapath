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
                                    <img src="{{ $setting ? Storage::url($setting->logo) : '' }}" alt=""></span>
                            </a>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center">
                                <h1 class="text-error">4<i class="mdi mdi-emoticon-sad"></i>4</h1>
                                <h4 class="text-uppercase text-danger mt-3">Page Not Found</h4>
                                <p class="text-muted mt-3">
                                    Il semble que vous ayez pris un mauvais virage. Ne vous inquiétez pas... ça arrive
                                    aux meilleurs d'entre nous. Voici une petite astuce qui pourrait vous aider à vous
                                    remettre sur la bonne voie.</p>

                                <a class="btn btn-info mt-3" href="{{ route('login') }} ><i class="mdi mdi-reply"></i>
                                    Aller à Acceuil</a>
                            </div>
                        </div> <!-- end card-body-->
                    </div>
                    <!-- end card -->

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
