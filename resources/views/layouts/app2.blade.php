<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>@yield('title') | {{ App\Models\SettingApp::where('key','lab_name')->first()->value }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
    $setting = \App\Models\Setting::orderBy('id', 'desc')->first();
    $logo = \App\Models\SettingApp::where('key', 'logo')->first();
    $favicon = \App\Models\SettingApp::where('key', 'favicon')->first();
    @endphp
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ $favicon ? Storage::url($favicon->value) : '' }}">

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

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
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
                    <img src="{{ $logo ? Storage::url($logo->value) : '' }}" alt="" width="200px">
                </span>
                <span class="logo-sm">
                    <img src="{{ $favicon ? Storage::url($favicon->value) : '' }}" alt="" width="50px">
                </span>
            </a>


            <div class="h-100" id="leftside-menu-container" data-simplebar="">

                <!--- Sidemenu -->
                <ul class="side-nav">
                    <li class="side-nav-title side-nav-item">TABLEAU DE BORD</li>
                    <!--- Tableau de bord -->
                    <li class="side-nav-item">
                        <a href="{{ route('home') }}" class="side-nav-link">
                            <i class="uil-home-alt"></i>
                            <span> Tableau de bord </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('chat.bot')}}" class="side-nav-link">
                            <i class="uil-comments-alt"></i>
                            <span class="badge bg-warning float-end">{{ getUnreadMessageCount(Auth::user()->id) ? getUnreadMessageCount(Auth::user()->id) :'' }}</span>
                            <span> Messages </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('Appointment.index') }}" class="side-nav-link">
                            <i class="uil-calender"></i>
                            <span> Agenda</span>
                        </a>
                    </li>

                    {{-- Examens --}}
                    <li class="side-nav-title side-nav-item">EXAMENS</li>
                    @if (getOnlineUser()->can('view-tests'))
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarEcommerce" aria-expanded="false"
                            aria-controls="sidebarEcommerce" class="side-nav-link">
                            <i class="uil-files-landscapes"></i>
                            <span> Catalogue d'examen </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarEcommerce">
                            <ul class="side-nav-second-level">
                                @if (getOnlineUser()->can('view-tests'))
                                <li>
                                    <a href="{{ route('examens.index') }}">Tous les examens</a>
                                </li>
                                @endif

                                @if (getOnlineUser()->can('view-category-tests'))
                                <li>
                                    <a href="{{ route('examens.categories.index') }}">Catégories</a>
                                </li>
                                @endif

                            </ul>
                        </div>
                    </li>
                    @endif

                    @if (getOnlineUser()->can('view-test-orders'))
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" style="padding-right: 2px;" href="#sidebarEcommerce1" aria-expanded="false"
                            aria-controls="sidebarEcommerce1" class="side-nav-link">
                            <i class="uil-syringe"></i>
                            <span> Demandes d'examen</span>
                            <span class="menu-arrow"></span>
                            @if (getnbrTestOrderpending()!=0)
                                <span class="badge bg-warning mb-1" style="margin-left: 25px;">{{getnbrTestOrderpending()}}</span>
                            @endif
                        </a>
                        <div class="collapse" id="sidebarEcommerce1">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="{{ route('test_order.index') }}">Toutes les demandes </a>
                                </li>
                                @if (getOnlineUser()->can('view-doctors'))
                                <li>
                                    <a href="{{ route('myspace.index', Auth::user()->id) }}">Mon espace</a>
                                </li>
                                @endif
                                @if (getOnlineUser()->can('create-test-orders'))
                                <li>
                                    <a href="{{ route('test_order.create') }}">Ajouter</a>
                                </li>
                                @endif
                                @if (getOnlineUser()->can('view-test-order-assignments'))
                                <li>
                                    <a href="{{ route('macro.index') }}">Macroscopie </a>
                                </li>
                                @endif

                                @if (getOnlineUser()->can('view-test-order-assignments'))
                                <li>
                                    <a href="{{ route('report.assignment.index') }}">Affectation </a>
                                </li>
                                @endif

                                @if (getOnlineUser()->can('view-test-order-assignments'))
                                <li>
                                    <a href="{{ route('report.index.suivi') }}">Suivi</a>
                                </li>
                                @endif


                            </ul>
                        </div>
                    </li>
                    @endif

                    @if (getOnlineUser()->can('view-test-orders'))
                        <li class="side-nav-item">
                            <a href="{{ route('test_order.immuno.index') }}" class="side-nav-link">
                                <i class="uil-syringe"></i>
                                <span> Immuno</span>
                                @if (getnbrTestOrderImmunopending()!=0)
                                    <span class="badge bg-warning mb-1" style="margin-left: 15px;">{{getnbrTestOrderImmunopending()}}</span>
                                @endif
                            </a>
                        </li>
                    @endif

                    @if (getOnlineUser()->can('view-reports'))
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarForms" aria-expanded="false"
                            aria-controls="sidebarForms" class="side-nav-link">
                            <i class="dripicons-gear noti-icon"></i>
                            <span>Comptes rendu</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarForms">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="{{ route('report.index') }}">Tous les comptes rendu</a>
                                </li>
                                @if (Route::current()->getName() == 'report.show')
                                <a style="display:none" href="">show</a>
                                @endif

                                @if (getOnlineUser()->can('view-setting-report-templates'))
                                <li>
                                    <a href="{{ route('template.report-index') }}">
                                        Templates
                                    </a>
                                </li>
                                @endif

                                {{-- <li>
                                    <a href="{{ route('log.report-index') }}">
                                        <span> Historiques</span>
                                    </a>
                                </li> --}}
                                <li>
                                    <a href="{{ route('settings.report-index') }}">Paramètres </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    @endif

                    @if (getOnlineUser()->can('view-hospitals'))
                    <li class="side-nav-item">
                        <a href="{{ route('hopitals.index') }}" class="side-nav-link">
                            <i class="uil-building"></i>
                            <span> Hopitaux</span>
                        </a>
                    </li>
                    @endif

                    @if (getOnlineUser()->can('view-doctors'))
                    <li class="side-nav-item">
                        <a href="{{ route('doctors.index') }}" class="side-nav-link">
                            <i class="uil-users-alt"></i>
                            <span> Médecins traitants </span>
                        </a>
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


















                    {{-- Finances --}}
                    <li class="side-nav-title side-nav-item">COMPTABILITÉS</li>
                    {{-- Facture --}}
                    @if (getOnlineUser()->can('view-invoices'))
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" style="padding-right: 2px;" href="#sidebarProjects" aria-expanded="false"
                            aria-controls="sidebarProjects" class="side-nav-link">
                            <i class="uil-user-check"></i>
                            <span> Factures </span>
                            <span class="menu-arrow"></span>
                            @if (getnbrInvoicepending() !=0)
                                <span class="badge bg-warning" style="margin-left: 105px"> {{getnbrInvoicepending()}} </span>
                            @endif
                        </a>
                        <div class="collapse" id="sidebarProjects">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="{{ route('invoice.index') }}">Toutes les Factures</a>
                                </li>
                                <li>
                                    <a href="{{ route('invoice.create') }}">Créer</a>
                                </li>
                                @if (getOnlineUser()->can('view-setting-invoice'))
                                <li>
                                    <a href="{{ route('invoice.business') }}">Rapports</a>
                                </li>
                                @endif
                                @if (getOnlineUser()->can('view-setting-invoice'))
                                <li>
                                    <a href="{{ route('invoice.setting.index') }}">Paramètre</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif

                    {{-- Caisses --}}
                    @if (getOnlineUser()->can('view-cashboxes'))
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" style="padding-right: 2px;" href="#sidebarEcommerce9" aria-expanded="false"
                            aria-controls="sidebarEcommerce9" class="side-nav-link">
                            <i class="uil-balance-scale"></i>
                            <span> Caisses </span>
                            <span class="menu-arrow"></span>
                            @if (getnbrBonCaissePending())
                                <span class="badge bg-warning" style="margin-left:110px" > {{getnbrBonCaissePending()}} </span>
                            @endif
                        </a>
                        <div class="collapse" id="sidebarEcommerce9">
                            <ul class="side-nav-second-level">
                                @if (getOnlineUser()->can('view-cashbox-adds'))
                                <li>
                                    <a href="{{ route('cashbox.vente.index') }}">Caisse de vente</a>
                                </li>
                                @endif

                                @if (getOnlineUser()->can('view-cashbox-adds'))
                                <li>
                                    <a href="{{ route('cashbox.depense.index') }}">Caisse de dépense</a>
                                </li>
                                @endif

                                @if (getOnlineUser()->can('view-cashbox-tickets'))
                                <li>
                                    <a href="{{ route('cashbox.ticket.index') }}">
                                        Bon de caisse
                                        @if (getnbrBonCaissePending())
                                            <span class="badge bg-warning float-end" > {{getnbrBonCaissePending()}} </span>
                                        @endif
                                    </a>
                                </li>
                                @endif

                                @if (getOnlineUser()->can('view-cashbox-dailies'))
                                <li>
                                    <a href="{{ route('daily.index') }}">Ouverture et fermeture</a>
                                </li>
                                @endif

                            </ul>
                        </div>
                    </li>
                    @endif

                    {{-- Contrats --}}
                    @if (getOnlineUser()->can('view-contrats'))
                    <li class="side-nav-item">
                        <a href="{{ route('contrats.index') }}" class="side-nav-link">
                            <i class="uil-folder-heart"></i>
                            <span>Contrats </span>
                        </a>
                    </li>
                    @endif

                    {{-- Dépenses --}}
                    @if (getOnlineUser()->can('view-expenses'))
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarForms-expense" aria-expanded="false"
                            aria-controls="sidebarForms" class="side-nav-link">
                            <i class="uil-balance-scale"></i>
                            <span>Dépenses</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarForms-expense">
                            <ul class="side-nav-second-level">
                                <li class="side-nav-item">
                                    <a href="{{ route('all_expense.index') }}">
                                        <span>Toutes les dépenses</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{{ route('expense.index') }}">
                                        <span>Catégories</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endif

                    {{-- Articles --}}
                    @if (getOnlineUser()->can('view-articles'))
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" style="padding-right: 2px;" href="#sidebarForms-articles" aria-expanded="false"
                            aria-controls="sidebarForms" class="side-nav-link">
                            <i class="uil-shop"></i>
                            <span>Stocks</span>
                            <span class="menu-arrow"></span>
                            @if (getnbrStockMinim() !=0)
                                <span class="badge bg-warning" style="margin-left: 105px"> {{getnbrStockMinim()}} </span>
                            @endif
                        </a>
                        <div class="collapse" id="sidebarForms-articles">
                            <ul class="side-nav-second-level">
                                @if (getOnlineUser()->can('view-movements'))
                                <li class="side-nav-item">
                                    <a href="{{ route('movement.index') }}">
                                        <span>Historique des stocks</span>
                                    </a>
                                </li>
                                @endif
                                <li class="side-nav-item">
                                    <a href="{{ route('article.index') }}">
                                        <span>Tous les articles</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{{ route('unit.index') }}">
                                        <span>Unité de mesure</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    @endif

                    {{-- Fournisseurs --}}
                    @if (getOnlineUser()->can('view-suppliers'))
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarEcommerce8" aria-expanded="false"
                            aria-controls="sidebarEcommerce8" class="side-nav-link">
                            <i class="uil-files-landscapes"></i>
                            <span> Fournisseurs </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarEcommerce8">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="{{ route('supplier.index') }}">Tous les fournisseurs</a>
                                </li>
                                <li>
                                    <a href="{{ route('supplier.categories.index') }}">Catégories</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endif

                    {{-- Remboursements --}}
                    @if (getOnlineUser()->can('view-refund-requests'))
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarEcommerce5" style="padding-right: 2px;" aria-expanded="false"
                            aria-controls="sidebarEcommerce5" class="side-nav-link">
                            <i class="uil-balance-scale"></i>
                            <span> Remboursements</span>
                            <span class="menu-arrow"></span>
                            @if (getnbrRefundRequestPending() !=0)
                                <span class="badge bg-warning" style="margin-left: 50px"> {{getnbrRefundRequestPending()}} </span>
                            @endif
                        </a>
                        <div class="collapse" id="sidebarEcommerce5">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="{{ route('refund.request.index') }}">Historiques</a>
                                </li>

                                <li>
                                    <a href="{{ route('refund.request.create') }}">Ajouter</a>
                                </li>

                                <li>
                                    <a href="{{ route('refund.request.categorie.index') }}">Paramètres</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    @endif

                    @if (getOnlineUser()->can('view-clients'))
                    <li class="side-nav-item">
                        <a href="{{ route('clients.index') }}" class="side-nav-link">
                            <i class="uil-users-alt"></i>
                            <span>Clients Professionnels</span>
                        </a>
                    </li>
                    @endif

                    {{-- Administration --}}
                    <li class="side-nav-title side-nav-item">ADMINISTRATIONS</li>

                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" style="padding-right: 2px;" href="#sidebarEcommerce3" aria-expanded="false"
                            aria-controls="sidebarEcommerce3" class="side-nav-link">
                            <i class="uil-question-circle"></i>
                            <span> Signaler un problème</span>
                            <span class="menu-arrow"></span>
                            @if (getnbrTicketPending(Auth::user()->id) !=0)
                                <span class="badge bg-warning" style="margin-left: 20px"> {{getnbrTicketPending(Auth::user()->id)}} </span>
                            @endif
                        </a>
                        <div class="collapse" id="sidebarEcommerce3">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="{{ route('probleme.report.index') }}">Historiques</a>
                                </li>

                                <li>
                                    <a href="{{ route('probleme.report.create') }}">Signaler</a>
                                </li>

                            </ul>
                        </div>
                    </li>


                    @if (getOnlineUser()->can('edit-users'))
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarProjects2" aria-expanded="false"
                            aria-controls="sidebarProjects2" class="side-nav-link">
                            <i class="uil-user-check"></i>
                            <span> Utilisateurs </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarProjects2">
                            <ul class="side-nav-second-level">
                                @if (getOnlineUser()->can('view-permissions'))
                                <li>
                                    <a href="{{ route('user.permission-index') }}">Permissions</a>
                                </li>
                                @endif
                                @if (getOnlineUser()->can('view-roles'))
                                <li class="side-nav-item" style="margin-left: 38px">
                                    <a href="{{ route('user.role-index') }}" class="side-nav-link">
                                        <span> Rôles </span>
                                    </a>
                                    <div class="collapse">
                                        <ul class="side-nav-second-level">
                                            @if (Route::current()->getName() == 'user.role-create')
                                            <li style="display:none">
                                                <a href="">show</a>
                                            </li>
                                            @endif
                                            @if (Route::current()->getName() == 'user.role-show')
                                            <li style="display:none">
                                                <a href="">show</a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                                @endif
                                @if (getOnlineUser()->can('view-users'))
                                <li class="side-nav-item" style="margin-left: 38px">
                                    <a href="{{ route('user.index') }}" class="side-nav-link">
                                        <span> Tous les utilisateurs </span>
                                    </a>
                                    <div class="collapse">
                                        <ul class="side-nav-second-level">
                                            @if (Route::current()->getName() == 'user.edit')
                                            <li style="display:none">
                                                <a href="">show</a>
                                            </li>
                                            @endif
                                            @if (Route::current()->getName() == 'user.create')
                                            <li style="display:none">
                                                <a href="">show</a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif

                    @if (getOnlineUser()->can('view-settings') || getOnlineUser()->can('view-setting-report-templates'))
                    <li class="side-nav-item">
                        <a href="{{ route('settings.index') }}" class="side-nav-link">
                            <i class="uil-document-layout-right"></i>
                            <span>Paramètres</span>
                        </a>
                    </li>
                    @endif
                    {{-- @if (getOnlineUser()->can('view-settings') || getOnlineUser()->can('view-setting-report-templates'))
                    <li class="side-nav-item">
                        <a href="{{ route('settings.app-index') }}" class="side-nav-link">
                            <i class="uil-document-layout-right"></i>
                            <span>Paramètres</span>
                        </a>
                    </li>
                    @endif --}}




















































                    {{-- Equipes --}}

                    <li class="side-nav-title side-nav-item">EQUIPES</li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarProjects6" aria-expanded="false"
                            aria-controls="sidebarProjects6" class="side-nav-link">
                            <i class="uil-user-check"></i>
                            <span>Equipes</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarProjects6">
                            <ul class="side-nav-second-level">
                                @if (getOnlineUser()->can('view-employees'))
                                <li>
                                    <a href="{{ route('employee.index') }}">Tous les employés</a>
                                </li>
                                @endif
                                <li>

                                    <a href="#"data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-timeoffs-create2">Demande de congé</a>

                                    <a href="#"data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-timeoffs-see">Toutes les demandes</a>
                                </li>
                            </ul>
                        </div>
                    </li>


























































                    {{-- Documentations --}}
                    {{-- <li class="side-nav-item">
                        <a href="{{ route('doc.categorie.index') }}" class="side-nav-link">
                            <i class="uil-document-layout-right"></i>
                            <span>Documentations</span>
                        </a>
                    </li> --}}
                    <li class="side-nav-title side-nav-item">DOCUMENTATIONS</li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarProjects66" aria-expanded="false"
                            aria-controls="sidebarProjects66" class="side-nav-link">
                            <i class="mdi mdi-file-document"></i>
                            <span>Documentations</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarProjects66">
                            <ul class="side-nav-second-level">
                                @if (getOnlineUser()->can('view-docs'))
                                <li>
                                    <a href="{{ route('doc.index') }}">Tous les documents</a>
                                </li>
                                @endif
                                <li>
                                    <a href="{{ route('doc.share.with.me') }}">Partagé avec moi</a>
                                </li>
                                <li>
                                    <a href="{{ route('doc.categorie.index') }}">Toutes les catégories</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                </ul>
                <div class="clearfix"></div>
            </div>
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

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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

                    @include('employee_timeoffs.create2')
                    @include('employee_timeoffs.see')
                    @yield('content')

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            {{-- <script>
                                document.write(new Date().getFullYear())
                            </script> © Labocaap --}}
                            {{ Carbon\Carbon::now()->formatLocalized('%G') }} © {{ App\Models\SettingApp::where('key','footer')->first()->value }}
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
    <script src="{{ asset('/adminassets/js/demo.form-wizard.js') }}"></script>
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

            return year + '-' + month + '-' + day ;
        }


        $('#see-all-order').on('click', function() {
            var user = document.getElementById('user_id_employee')
            console.log(user.value);
            if (user.value == 'Selectionner un employé') {
                toastr.error('Veuillez sélectionner un employé');
                return false;
            }
            // Sélectionnez le corps du tableau
            var tableBody = document.querySelector("#datatable8 tbody");

            // Supprimez toutes les lignes existantes dans le tableau
            while (tableBody.firstChild) {
                tableBody.removeChild(tableBody.firstChild);
            }
            $.ajax({
                url: '/employee-my-timeoff/',
                type: 'GET',
                data: {
                    user_id: user.value
                },
                success: function(data) {
                    console.log(data);
                    // <span class="badge badge-outline-success"> Active</span>

                    // Parcourez les données et ajoutez-les au tableau
                    data.forEach(function(item) {
                        var row = tableBody.insertRow(); // Créez une nouvelle ligne

                        // Créez des cellules pour chaque propriété de l'objet
                        var cell1 = row.insertCell(0); // Première cellule (id)
                        var cell2 = row.insertCell(1); // Deuxième cellule (name)
                        var cell3 = row.insertCell(2); // Troisième cellule (description)
                        var cell4 = row.insertCell(3); // Troisième cellule (description)

                        cell1.textContent = formatTime(item.start_date);
                        cell2.textContent = formatTime(item.end_date);
                        cell3.textContent = item.message;
                         var span = document.createElement('span');
                         var style = 'badge badge-outline-';
                         style +=item.status != 'active' ? 'warning' : 'success';

                         span.className = style;
                         span.textContent = item.status;
                         cell4.appendChild(span);

                    })
                }
            })
        })

    </script>
</body>

</html>
