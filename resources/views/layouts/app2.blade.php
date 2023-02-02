<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>@yield('title') | {{ config('app.name', 'Labocaap') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $setting = \App\Models\Setting::orderBy('id', 'desc')->first();
    @endphp
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ $setting ? Storage::url($setting->favicon) : '' }}">

    <link href="{{ asset('/adminassets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/adminassets/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/adminassets/css/vendor/buttons.bootstrap5.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/adminassets/css/vendor/select.bootstrap5.css') }}" rel="stylesheet" type="text/css">

    <!-- App css -->
    <link href="{{ asset('/adminassets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/adminassets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style">
    <link href="{{ asset('/adminassets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style">
    <link href="{{ asset('/adminassets/js/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css"
        integrity="sha512-6S2HWzVFxruDlZxI3sXOZZ4/eJ8AcxkQH1+JjSe/ONCEqR9L4Ysq5JdT5ipqtzU7WHalNwzwBv+iE51gNHJNqQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @yield('css')

</head>

<body class="loading"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <!-- Begin page -->
    <div class="wrapper">
        <!-- ========== Left Sidebar Start ========== -->
        <div class="leftside-menu">

            <!-- LOGO -->
            <a href="{{ route('home') }}" class="logo logo-light text-center">
                <span class="logo-lg">
                    <img src="{{ $setting ? Storage::url($setting->logo_blanc) : '' }}" alt="" width="200px">
                </span>
                <span class="logo-sm">
                    <img src="{{ $setting ? Storage::url($setting->favicon) : '' }}" alt="" width="50px">
                </span>
            </a>

            <div class="h-100" id="leftside-menu-container" data-simplebar="">

                <!--- Sidemenu -->
                <ul class="side-nav">

                    <!--- Tableau de bord -->
                    <li class="side-nav-item">
                        <a href="{{ route('home') }}" class="side-nav-link">
                            <i class="uil-home-alt"></i>
                            <span> Tableau de bord </span>
                        </a>
                    </li>

                    <!--- Examen -->

                    @if (getOnlineUser()->can('view-examens'))
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce" aria-expanded="false"
                                aria-controls="sidebarEcommerce" class="side-nav-link">
                                <i class="uil-files-landscapes"></i>
                                <span> Examens </span>
                                <span class="menu-arrow"></span>
                            </a>

                            <div class="collapse" id="sidebarEcommerce">
                                <ul class="side-nav-second-level">
                                    @if (getOnlineUser()->can('view-examens'))
                                        <li>
                                            <a href="{{ route('examens.index') }}">Tous les examens</a>
                                        </li>
                                    @endif

                                    @if (getOnlineUser()->can('view-examens-categories'))
                                        <li>
                                            <a href="{{ route('examens.categories.index') }}">Catégories</a>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </li>
                    @endif

                    @if (getOnlineUser()->can('view-patients'))
                        <li class="side-nav-item">
                            <a href="{{ route('patients.index') }}" class="side-nav-link">
                                <i class="uil-user-square"></i>
                                <span> Patients </span>
                            </a>
                        </li>
                    @endif

                    @if (getOnlineUser()->can('view-hopitaux'))
                        <li class="side-nav-item">
                            <a href="{{ route('hopitals.index') }}" class="side-nav-link">
                                <i class="uil-building"></i>
                                <span> Hopitaux </span>
                            </a>
                        </li>
                    @endif

                    @if (getOnlineUser()->can('view-medecins-traitants'))
                        <li class="side-nav-item">
                            <a href="{{ route('doctors.index') }}" class="side-nav-link">
                                <i class="uil-users-alt"></i>
                                <span> Médecins traitants </span>
                            </a>
                        </li>
                    @endif

                    @if (getOnlineUser()->can('view-contrats'))
                        <li class="side-nav-item">
                            <a href="{{ route('contrats.index') }}" class="side-nav-link">
                                <i class="uil-folder-heart"></i>
                                <span>Contrats </span>
                            </a>
                        </li>
                    @endif

                    @if (getOnlineUser()->can('view-demandes-examens'))
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce1" aria-expanded="false"
                                aria-controls="sidebarEcommerce1" class="side-nav-link">
                                <i class="uil-syringe"></i>
                                <span> Demandes d'examen</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce1">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{ route('test_order.index') }}">Toutes les demandes </a>
                                    </li>
                                    @if (getOnlineUser()->can('create-demandes-examens'))
                                        <li>
                                            <a href="{{ route('test_order.create') }}">Ajouter</a>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </li>
                    @endif

                    @if (getOnlineUser()->can('view-compte-rendu'))
                        <li class="side-nav-item">
                            <a href="{{ route('report.index') }}" class="side-nav-link">
                                <i class="uil-file-medical"></i>
                                <span> Comptes rendu</span>
                            </a>
                        </li>
                    @endif

                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarProjects" aria-expanded="false"
                            aria-controls="sidebarProjects" class="side-nav-link">
                            <i class="uil-user-check"></i>
                            <span> Factures </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarProjects">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="{{ route('invoice.index') }}">Toutes les Factures</a>
                                </li>
                                <li>
                                    <a href="{{ route('invoice.create') }}">Créer</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('appointement.index') }}" class="side-nav-link">
                            <i class="uil-calender"></i>
                            <span> Agenda</span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarConsultation" aria-expanded="false"
                            aria-controls="sidebarAppoint" class="side-nav-link">
                            <i class="uil-calender"></i>
                            <span> Consultations </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarConsultation">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="{{ route('type_consultation.index') }}">Ajouter un type de
                                        consultation</a>
                                </li>
                                <li>
                                    <a href="{{ route('consultation.index') }}">Consultations</a>
                                </li>
                                <li>
                                    <a href="{{ route('consultation.create') }}">Ajouter une consultation</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarcategoryPrestation" aria-expanded="false"
                            aria-controls="sidebarAppoint" class="side-nav-link">
                            <i class="uil-calender"></i>
                            <span> Prestations </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarcategoryPrestation">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="{{ route('categoryPrestation.index') }}">
                                        Categorie</a>
                                </li>
                                <li>
                                    <a href="{{ route('prestation.index') }}">Toutes les prestations</a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    @if (getOnlineUser()->can('view-template-compte-rendu'))
                        <li class="side-nav-item">
                            <a href="{{ route('template.report-index') }}" class="side-nav-link">
                                <i class="uil-document-layout-right"></i>
                                <span> Templates compte rendu</span>
                            </a>
                        </li>
                    @endif

                    @if (getOnlineUser()->can('view-parametres-systeme') || getOnlineUser()->can('view-template-compte-rendu'))
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarForms" aria-expanded="false"
                                aria-controls="sidebarForms" class="side-nav-link">
                                <i class="dripicons-gear noti-icon"></i>
                                <span>Paramètres</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarForms">
                                <ul class="side-nav-second-level">
                                    @if (getOnlineUser()->can('view-parametres-systeme'))
                                        <li>
                                            <a href="{{ route('settings.app-index') }}">Paramètres Systeme</a>
                                        </li>
                                    @endif
                                    @if (getOnlineUser()->can('view-template-compte-rendu'))
                                        <li>
                                            <a href="{{ route('settings.report-index') }}">Paramètres compte rendu</a>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </li>
                    @endif

                    @if (getOnlineUser()->can('edit-users'))
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarProjects" aria-expanded="false"
                                aria-controls="sidebarProjects" class="side-nav-link">
                                <i class="uil-user-check"></i>
                                <span> Utilisateurs </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarProjects">
                                <ul class="side-nav-second-level">
                                    @if (getOnlineUser()->can('view-permissions'))
                                        <li>
                                            <a href="{{ route('user.permission-index') }}">Permissions</a>
                                        </li>
                                    @endif
                                    @if (getOnlineUser()->can('view-roles'))
                                        <li>
                                            <a href="{{ route('user.role-index') }}">Rôles</a>
                                        </li>
                                    @endif
                                    @if (getOnlineUser()->can('view-users'))
                                        <li>
                                            <a href="{{ route('user.index') }} ">Tout les utilisateurs </a>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </li>
                    @endif

                </ul>

                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">
                <!-- Topbar Start -->
                <div class="navbar-custom">
                    <ul class="list-unstyled topbar-menu float-end mb-0">
                        <li class="dropdown notification-list d-lg-none">
                            <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#"
                                role="button" aria-haspopup="false" aria-expanded="false">
                                <i class="dripicons-search noti-icon"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                                <form class="p-3">
                                    <input type="text" class="form-control" placeholder="Search ..."
                                        aria-label="Recipient's username">
                                </form>
                            </div>
                        </li>

                        <li class="dropdown notification-list">

                            <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown"
                                href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <span class="account-user-avatar">
                                    <img src="{{ asset('/adminassets/images/users/avatar-1.jpg') }}" alt="user-image"
                                        class="rounded-circle">
                                </span>
                                <span>
                                    <span class="account-user-name"> {{ Auth::user()->firstname }}</span>
                                    <span class="account-position">Admin</span>
                                </span>
                            </a>

                            <div
                                class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Bienvenue !</h6>
                                </div>

                                <!-- item-->
                                <a href="{{ route('profile.index') }}" class="dropdown-item notify-item">
                                    <i class="mdi mdi-account-circle me-1"></i>
                                    <span>Mon compte</span>
                                </a>

                                <!-- item-->
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    class="dropdown-item notify-item">
                                    <i class="mdi mdi-logout me-1"></i>
                                    <span>Se déconnecter</span>
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>

                            </div>
                        </li>

                    </ul>
                    <button class="button-menu-mobile open-left">
                        <i class="mdi mdi-menu"></i>
                    </button>

                </div>
                <!-- end Topbar -->

                <!-- Start Content-->
                <div class="container-fluid">

                    @yield('content')

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © Labocaap
                        </div>

                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>

    <div class="rightbar-overlay"></div>
    <!-- /End-bar -->

    <!-- bundle -->
    <script src="{{ asset('/adminassets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('/adminassets/js/app.min.js') }}"></script>

    <script src="{{ asset('/adminassets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/adminassets/js/vendor/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('/adminassets/js/vendor/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/adminassets/js/vendor/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/adminassets/js/vendor/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('/adminassets/js/vendor/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/adminassets/js/vendor/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('/adminassets/js/vendor/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('/adminassets/js/vendor/buttons.print.min.js') }}"></script>
    <script src="{{ asset('/adminassets/js/vendor/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('/adminassets/js/vendor/dataTables.select.min.js') }}"></script>

    <script src="{{ asset('/adminassets/js/pages/demo.datatable-init.js') }}"></script>
    <script src="{{ asset('/adminassets/js/sweetalert2/sweetalert2.min.js') }}"></script>

    {{-- toastr --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"
        integrity="sha512-lbwH47l/tPXJYG9AcFNoJaTMhGvYWhVM9YI43CT+uteTRRaiLCui8snIgyAN8XWgNjNhCqlAUdzZptso6OCoFQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @stack('extra-js')
    <script>
        toastr.options = {
            "progressBar": true,
            "timeOut": "7200",
        };
    </script>
</body>

</html>
