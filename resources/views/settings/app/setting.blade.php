@extends('layouts.app2')

@section('title', 'Paramètres système')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css"
    integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3 mb-1">
                <a href="{{ route('home') }}" type="button" class="btn btn-primary">Acceuil</a>
            </div>
            <h4 class="page-title"></h4>
        </div>
        @include('settings.report.create')

        @include('settings.report.edit')

    </div>
</div>

<div class="">
    <div class="col-12 bg-primary rounded-top" style="padding-left: 50px; padding-bottom: 15px; padding-top: 15px">
        <div class="page-title-box">
            <h4 class="page-title text-white" id="" style="line-height: 25px">Paramètres</h4>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row" id="basicwizard">

                <div class="col-3">
                    <ul class="mb-4" style="display: grid">
                        <li class="side-nav-item">
                            <a href="#basictab1" id="general" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                <i class="mdi mdi-account-circle me-1"></i>
                                <span class="d-none d-sm-inline">Général</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="#basictab2" id="email" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                <i class="mdi mdi-email-outline me-1"></i>
                                <span class="d-none d-sm-inline">Email</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="#basictab3" id="mobile" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                <i class="mdi mdi-checkbox-marked-circle-outline me-1"></i>
                                <span class="d-none d-sm-inline">Communication Mobile</span>
                            </a>
                        </li>

                        <li class="side-nav-item">
                            <a href="#basictab5" id="mobile" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                <i class="mdi mdi-checkbox-marked-circle-outline me-1"></i>
                                <span class="d-none d-sm-inline">Compte rendu</span>
                            </a>
                        </li>

                        <li class="side-nav-item">
                            <a href="#basictab4" id="bank" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                <i class="mdi mdi-bank me-1"></i>
                                <span class="d-none d-sm-inline">Banques</span>
                            </a>
                        </li>

                        <li class="side-nav-item">
                            <a href="#basictab7" id="payment" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                <i class="mdi mdi-cash-multiple me-1"></i>
                                <span class="d-none d-sm-inline">Parametres de paiement</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-9">
                    <div class="col-12 bg-primary rounded-top" style="padding-left: 50px; padding-bottom: 15px; padding-top: 15px">
                        <div class="page-title-box">
                            <h4 class="page-title text-white" id="titleHeader" style="line-height: 25px">Général</h4>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body tab-content b-0 mb-0">

                            {{-- Moyen de paiement debut --}}
                            <div class="tab-pane" id="basictab7">
                                <form action="{{route('settings.store')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="nbrform" value="7">
                                    <div class="row">
                                      
                                        <div class="col-md-12 mb-3">
                                            <label for="simpleinput" class="form-label">Token de paiement</label>
                                            <input type="text" id="simpleinput" placeholder="Cle de paiement" value="{{$token_payment->value ?? ""}}" name="token_payment" class="form-control">
                                        </div>

                                        {{-- <div class="col-6 mb-3">
                                            <label for="simpleinput" class="form-label">Cle publique</label>
                                            <input type="text" placeholder="Cle public" value="{{$public_key->value}}" name="public_key" id="simpleinput" class="form-control">
                                        </div>

                                        <div class="col-6 mb-3">
                                            <label for="simpleinput" class="form-label">Cle privee</label>
                                            <input type="text" placeholder="Cle privee" value="{{$private_key->value}}" name="private_key" id="simpleinput" class="form-control">
                                        </div>

                                        <div class="col-6 mb-3">
                                            <label for="simpleinput" class="form-label">Cle secret</label>
                                            <input type="text" placeholder="Cle secret" value="{{$secret_key->value}}" name="secret_key" id="simpleinput" class="form-control">
                                        </div> --}}

                                    </div> 
                                    <div style="padding-bottom: 10px;padding-top:10px">
                                        <button class="btn btn-primary">Enregistrer <i class="mdi mdi-check-all"></i></button>
                                    </div>
                                </form>
                            </div>
                            {{-- Moyen de paiement fin --}}



                            <div class="tab-pane" id="basictab1">
                                <form action="{{route('settings.store')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="nbrform" value="1">
                                    <div id="basicwizard5">

                                        <ul class="nav nav-pills nav-justified form-wizard-header mb-4">
                                            <li class="nav-item">
                                                <a href="#general1" id="generalLi" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                    <i class="mdi mdi-account-circle me-1"></i>
                                                    <span class="d-none d-sm-inline">Général</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#general2" id="logo" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                    <i class="mdi mdi-face-profile me-1"></i>
                                                    <span class="d-none d-sm-inline">Logos</span>
                                                </a>
                                            </li>
                                        </ul>

                                        <div class="tab-content1 b-0 mb-0">
                                            <div class="tab-pane" id="general1" style="display: block">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <div class="col-6 mb-3">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-book-edit"></i></span>
                                                                    <input type="text" class="form-control" placeholder="Nom du laboratoire" value="{{$app_name->value}}" aria-label="name" name="app_name" aria-describedby="basic-addon1">
                                                                </div>
                                                            </div>
                                                            <div class="col-6 mb-3">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-comment-edit"></i></span>
                                                                    <input type="text" class="form-control" placeholder="Devise" value="{{$devise->value}}" aria-label="devise" name="devise" aria-describedby="basic-addon1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-6 mb-3">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-google-maps"></i></span>
                                                                    <input type="text" class="form-control" value="{{$adress->value}}" placeholder="Adresse" aria-label="adress" name="adress" aria-describedby="basic-addon1">
                                                                </div>
                                                            </div>
                                                            <div class="col-6 mb-3">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-phone"></i></span>
                                                                    <input type="text" class="form-control" value="{{$phone->value}}" placeholder="Téléphone" aria-label="telephone" name="phone" aria-describedby="basic-addon1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-6 mb-3">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-email"></i></span>
                                                                    <input type="text" class="form-control" value="{{$email->value}}" placeholder="Email" aria-label="email" name="email" aria-describedby="basic-addon1">
                                                                </div>
                                                            </div>
                                                            <div class="col-6 mb-3">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-web"></i></span>
                                                                    <input type="text" class="form-control" value="{{$web_site->value}}"  placeholder="Site web" aria-label="web_site" name="web_site" aria-describedby="basic-addon1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="input-group flex-nowrap">
                                                                <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-copyright"></i></span>
                                                                <input type="text" class="form-control" value="{{$footer->value}}" placeholder="Pied de page" aria-label="footer" name="footer" aria-describedby="basic-addon1">
                                                            </div>
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row -->
                                            </div>

                                            <div class="tab-pane" id="general2" style="display: none">
                                                <div class="mb-3">
                                                    <label class="form-label">Logo</label>
                                                    <input class="form-control" type="file" name="logo" id="inputGroupFile04">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Favicon</label>
                                                    <input class="form-control" type="file" name="favicon" id="inputGroupFile04">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Logo Blanc</label>
                                                    <input class="form-control" type="file" name="logo_white" id="inputGroupFile04">
                                                </div>
                                            </div>
                                        </div> <!-- tab-content -->
                                    </div> <!-- end #basicwizard-->
                                    <div style="padding-bottom: 10px;padding-top:10px">
                                        <button type="submit" class="btn btn-primary">Enrégistrer <i class="mdi mdi-check-all"></i></button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="basictab2">
                                <form action="{{route('settings.store')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="nbrform" value="2">
                                    <div class="row">
                                        <div class="col-4 mb-3">
                                            <label for="simpleinput" class="form-label">Hôte</label>
                                            <input type="text" id="simpleinput" name="email_host" value="{{$email_host->value}}" class="form-control">
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label for="simpleinput" class="form-label">Port </label>
                                            <input type="text" id="simpleinput" name="email_port" value="{{$email_port->value}}" class="form-control">
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label for="simpleinput" class="form-label">Nom d'utilisateur</label>
                                            <input type="text" id="simpleinput" value="{{$username->value}}" name="username" class="form-control">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label for="simpleinput" class="form-label">Mot de passe</label>
                                            <input type="password" id="simpleinput" value="{{$password->value}}" name="password" class="form-control">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label for="simpleinput" class="form-label">Chiffrement</label>
                                            <input type="text" id="simpleinput" value="{{$encryption->value}}" name="encryption" class="form-control">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label for="simpleinput" class="form-label">Adresse de l'expéditeur</label>
                                            <input type="text" id="simpleinput" value="{{$from_adresse->value}}" name="from_adresse" class="form-control">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label for="simpleinput" class="form-label">Nom de l'expéditeur</label>
                                            <input type="text" id="simpleinput" value="{{$from_name->value}}" name="from_name" class="form-control">
                                        </div>

                                    </div> <!-- end row -->

                                    <div class="mb-3">
                                        <label for="example-select" class="form-label">Roles<span style="color:red;">*</span></label>
                                        <select class="form-select select2" data-toggle="select2" required name="mails[]" multiple>
                                            <option>Sélectionner les roles</option>
                                            @foreach (App\Models\User::all() as $user)
                                                @php
                                                    $selectedMails = explode('|', $mail->value);
                                                @endphp
                                            <option value="{{$user->email}}" {{in_array($user->email, $selectedMails)  ? 'selected' : ''}} >{{$user->email}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="example-select" class="form-label">Roles<span style="color:red;">*</span></label>
                                        <select class="form-select select2" data-toggle="select2" required name="services[]" multiple>
                                            <option>Sélectionner les services</option>
                                                @php
                                                    $selectedServices = explode('|', $service->value);
                                                @endphp
                                            <option value="remboursement" {{ in_array("remboursement", $selectedServices) ? 'selected':'' }}>Service de demande de remboursement</option>
                                            <option value="boncaisse" {{ in_array("boncaisse", $selectedServices) ? 'selected':'' }}>Service de bon de caisse</option>
                                            <option value="ticket" {{ in_array("ticket", $selectedServices) ? 'selected':'' }}>Service de signaler un problème</option>
                                            <option value="conge" {{ in_array("conge", $selectedServices) ? 'selected':'' }}>Service de demande de congé</option>
                                        </select>
                                    </div>
                                    <div style="padding-bottom: 10px;padding-top:10px">
                                        <button class="btn btn-primary">Enregistrer <i class="mdi mdi-check-all"></i></button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="basictab3">
                                <form action="{{route('settings.store')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="nbrform" value="3">
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label for="simpleinput" class="form-label">SMS API</label>
                                            <input type="password" id="simpleinput" placeholder="Le jeton de l'API SMS" value="{{$api_sms->value}}" name="api_sms" class="form-control">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label for="simpleinput" class="form-label">Lien API</label>
                                            <input type="text" placeholder="Lien de l'API sms" value="{{$link_api_sms->value}}" name="link_api_sms" id="simpleinput" class="form-control">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="simpleinput" class="form-label">OURVOICE API</label>
                                            <input type="password" placeholder="Le jeton de l'API OURVOICE" value="{{$key_ourvoice->value}}" name="key_ourvoice" id="simpleinput" class="form-control">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label for="simpleinput" class="form-label">Lien ourvoice appel</label>
                                            <input type="text" value="{{$link_ourvoice_call->value}}" name="link_ourvoice_call" placeholder="Lien de l'API OURVOICE pour les appels" id="simpleinput" class="form-control">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label for="simpleinput" class="form-label">Lien ourvoice SMS</label>
                                            <input type="text" value="{{$link_ourvoice_sms->value}}" name="link_ourvoice_sms" placeholder="Lien de l'API OURVOICE pour les SMS" id="simpleinput" class="form-control">
                                        </div>
                                    </div> 
                                    <div style="padding-bottom: 10px;padding-top:10px">
                                        <button class="btn btn-primary">Enrégistrer <i class="mdi mdi-check-all"></i></button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="basictab4">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="page-title-box">
                                            <div class="page-title-right mr-3">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#standard-modal">Ajouter une nouvelle banque</button>
                                            </div>
                                            <h4 class="page-title">Liste des banques</h4>
                                        </div>

                                        <!----MODAL---->

                                        @include('bank.create')

                                        @include('bank.edit')

                                    </div>
                                </div>
                                <div class="">

                                    <div class="card mb-md-0 mb-3">
                                        <div class="card-body">
                                            <div class="card-widgets">
                                                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                                                    aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                                                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                                            </div>
                                            <h5 class="card-title mb-0">Liste des banques</h5>

                                            <div id="cardCollpase1" class="collapse pt-3 show">


                                                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>Nom</th>
                                                            <th>Numéro de compte</th>
                                                            <th>Description</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>


                                                    <tbody>

                                                        @foreach ($banks as $item)
                                                            <tr>
                                                                <td>{{ $item->name }}</td>
                                                                <td>{{ $item->account_number }}</td>
                                                                <td>{{ $item->description}}</td>
                                                                <td>
                                                                    <button type="button" onclick="edit({{$item->id}})" class="btn btn-primary">
                                                                        <i class="mdi mdi-lead-pencil"></i>
                                                                    </button>
                                                                    <button type="button" onclick="deleteModal({{$item->id}})" class="btn btn-danger">
                                                                        <i class="mdi mdi-trash-can-outline"></i>
                                                                    </button>
                                                                </td>

                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div> <!-- end card-->


                                </div>
                            </div>

                            <div class="tab-pane" id="basictab5">

                                    <input type="hidden" name="nbrform" value="1">
                                    <div id="basicwizard5">
                                        <ul class="nav nav-pills nav-justified form-wizard-header mb-4">
                                            <li class="nav-item">
                                                <a href="#general3" id="generalcr" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                    <i class="mdi mdi-account-circle me-1"></i>
                                                    <span class="d-none d-sm-inline">Général</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#general4" id="titre" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                    <i class="mdi mdi-face-profile me-1"></i>
                                                    <span class="d-none d-sm-inline">Titre</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content1 b-0 mb-0">
                                            <div class="tab-pane" id="general3" style="display: block">
                                                <form action="{{route('settings.store')}}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="nbrform" value="4">
                                                    <div class="row">
                                                        <div class=" mb-3">
                                                            <div class="input-group flex-nowrap">
                                                                <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-book-edit"></i></span>
                                                                <input type="text" class="form-control" placeholder="Pied de page du rapport" value="{{$report_footer->value}}" aria-label="report_footer" name="report_footer" aria-describedby="basic-addon1">
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="input-group flex-nowrap">
                                                                <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-google-maps"></i></span>
                                                                <input type="text" class="form-control" value="{{$report_review_title->value}}" placeholder="Revue du rapport" aria-label="report_review_title" name="report_review_title" aria-describedby="basic-addon1">
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Entete</label>
                                                            <input class="form-control" type="file" name="entete" id="inputGroupFile04">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Prefixe code demande d'examen</label>
                                                            <input type="text" class="form-control" value="{{$prefixe_code_demande_examen->value}}" placeholder="Prefixe code demande d'examen" aria-label="prefixe_code_demande_examen" name="prefixe_code_demande_examen" aria-describedby="basic-addon1">
                                                        </div>

                                                    </div>
                                                    <div style="padding-bottom: 10px;padding-top:10px">
                                                        <button type="submit" class="btn btn-primary">Enregistrer <i class="mdi mdi-check-all"></i></button>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="tab-pane" id="general4" style="display: none">
                                                <div class="row">
                                                    <div class="col-12">
                                                            <div class="page-title-box">
                                                                <div class="page-title-right mr-3 mb-1">
                                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                                        data-bs-target="#standard-modal">Ajouter un nouveau titre</button>
                                                                </div>
                                                                <h4 class="page-title"></h4>
                                                            </div>


                                                    </div>
                                                </div>
                                                <div class="card mb-md-0 mb-3">
                                                    <div class="card-body">
                                                        <div class="card-widgets">
                                                            <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                                            <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                                                                aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                                                            <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                                                        </div>
                                                        <h5 class="card-title mb-0">Liste des Titres</h5>

                                                        <div id="cardCollpase1" class="collapse pt-3 show">


                                                            <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                                                                <thead class="col-lg-12" style="text-align: center;">
                                                                    <tr>
                                                                        <th class="col-lg-2">#</th>
                                                                        <th class="col-lg-6">Titres</th>
                                                                        <th class="col-lg-4">Actions</th>

                                                                    </tr>
                                                                </thead>


                                                                <tbody>

                                                                    @foreach ($titles as $item)
                                                                        <tr>
                                                                            <td>{{ $item->id }}</td>
                                                                            <td style="font-weight:{{ $item->status !=0 ? 'bold':'' }}">{{ $item->title }} {{ $item->status !=0 ? '(Par defaut)':'' }}</td>
                                                                            <td>
                                                                                <button type="button" onclick="edit({{ $item->id }})"
                                                                                    class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </button>
                                                                                <button type="button" onclick="deleteModal({{ $item->id }})"
                                                                                    class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                                                                            </td>

                                                                        </tr>
                                                                    @endforeach

                                                                </tbody>
                                                            </table>

                                                        </div>

                                                    </div>
                                                </div> <!-- end card-->
                                            </div>
                                        </div> 
                                    </div>
                            </div>


                            


                            

                        </div> <!-- tab-content -->
                    </div>
                </div>
            </div> <!-- end #basicwizard-->
        </div>
    </div>
</div>

@endsection


@push('extra-js')

<script>
    var baseUrl = "{{ url('/') }}";
</script>
<script src="{{asset('viewjs/setting/app.js')}}"></script>
<script src="{{asset('viewjs/setting/report.js')}}"></script>

<script>
    var titleHeader = document.getElementById('titleHeader');
    var general1 = document.getElementById('general1');
    var general2 = document.getElementById('general2');
    var general3 = document.getElementById('general3');
    var general4 = document.getElementById('general4');
    var general7 = document.getElementById('general7');
    $('#general').on('click', function(){
        titleHeader.textContent = "Général"
    })
    $('#email').on('click', function(){
        titleHeader.textContent = "Email"
    })
    $('#mobile').on('click', function(){
        titleHeader.textContent = "Communication mobile"
    })
    $('#payment').on('click', function(){
        titleHeader.textContent = "Parametres de paiement"
    })
    $('#bank').on('click', function(){
        titleHeader.textContent = "Banques"
    })
    $('#generalLi').on('click', function(){
        general2.style.display = "none"
        general1.style.display = "block";
    })
    $('#logo').on('click', function(){
        general1.style.display = "none"
        general2.style.display = "block";
    })

    $('#generalcr').on('click', function(){
        general4.style.display = "none"
        general3.style.display = "block";
    })
    $('#titre').on('click', function(){
        general3.style.display = "none"
        general4.style.display = "block";
    })


</script>

@endpush
