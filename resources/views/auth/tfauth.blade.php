<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="author" />
    @php
        $setting = \App\Models\Setting::orderBy('id', 'desc')->first();
    @endphp
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
                                    <img src="{{ $setting ? Storage::url($setting->logo) : '' }}" alt=""
                                        width="250px"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center mt-0 fw-bold">Vérifiez votre e-mail pour un code
                                </h4>
                                <p class="text-muted mb-4">Nous avons envoyé un code à 6 caractères à
                                    <strong>{{ $user->email }}</strong> . Le code expire sous peu, veuillez donc le
                                    saisir rapidement.</p>
                            </div>

                            <form id="codeForm" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <input class="form-control" type="number" id="code" name="code"
                                        required="" placeholder="Entrer le code">
                                </div>

                                <div class="mb-0 text-center">
                                    <button class="btn btn-primary" type="submit">Confirmer</button>
                                </div>
                            </form>
                        </div> <!-- end card-body-->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted">Vous n'aviez pas reçu le code ?<a href="pages-login.html"
                                    class="text-muted ms-1"><b>Renvoyer</b></a></p>
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

<script>
    $('#codeForm').on('submit', function (e) {
        e.preventDefault();
        let code = $('#code').val();
        $.ajax({
            url: "{{route('login.postAuth')}}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                code: code,
            },
            success: function (data) {
                console.log(data);
                if (data) {
                    window.location.href = "{{url('/home')}}" ;
                }
            },
            error: function (data) {
                console.log(data)
            },
            // processData: false,
        });

    });
</script>
