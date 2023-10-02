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
                                <i class="mdi mdi-face-profile me-1"></i>
                                <span class="d-none d-sm-inline">Email</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="#basictab3" id="mobile" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                <i class="mdi mdi-checkbox-marked-circle-outline me-1"></i>
                                <span class="d-none d-sm-inline">Communication Mobile</span>
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
                                    <div style="padding-bottom: 10px;padding-top:10px">
                                        <button class="btn btn-primary">Enrégistrer <i class="mdi mdi-check-all"></i></button>
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
                                            <label for="simpleinput" class="form-label">Lien ourvoice SMS</label>
                                            <input type="text" value="{{$link_ourvoice_call->value}}" name="link_ourvoice_call" placeholder="Lien de l'API OURVOICE pour les sms" id="simpleinput" class="form-control">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label for="simpleinput" class="form-label">Lien ourvoice appel</label>
                                            <input type="text" value="{{$link_ourvoice_sms->value}}" name="link_ourvoice_sms" placeholder="Lien de l'API OURVOICE pour les appels" id="simpleinput" class="form-control">
                                        </div>
                                    </div> <!-- end row -->
                                    <div style="padding-bottom: 10px;padding-top:10px">
                                        <button class="btn btn-primary">Enrégistrer <i class="mdi mdi-check-all"></i></button>
                                    </div>
                                </form>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
    integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    var titleHeader = document.getElementById('titleHeader');
    var general1 = document.getElementById('general1');
    var general2 = document.getElementById('general2');
    $('#general').on('click', function(){
        titleHeader.textContent = "Général"
    })
    $('#email').on('click', function(){
        titleHeader.textContent = "Email"
    })
    $('#mobile').on('click', function(){
        titleHeader.textContent = "Communication mobile"
    })
    $('#generalLi').on('click', function(){
        general2.style.display = "none"
        general1.style.display = "block";
    })
    $('#logo').on('click', function(){
        general1.style.display = "none"
        general2.style.display = "block";
    })


</script>

@endpush
