@extends('layouts.app2')

@section('title', 'Profile')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Mon compte</h4>
            </div>

        </div>
    </div>
    <div class="">

        @include('layouts.alerts')

        <div class="row">

            <div class="col-xl-8 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update-name') }}" enctype="multipart/form-data">
                            @csrf
                            <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i>INFORMATIONS DE BASE

                            </h5>

                            <div class="row">  
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="lastname" class="form-label">Nom<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" name="lastname"
                                            value="{{ Auth::user()->lastname }}">
                                    </div>
                                </div> <!-- end col -->
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="firstname" class="form-label">Prénom<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" name="firstname"
                                            value="{{ Auth::user()->firstname }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="example-fileinput" class="form-label">Signature</label>
                                        <input type="file" class="dropify" name="signature" data-default-file="{{ $user->signature ? Storage::url('app/public/'.$user->signature) : '' }}"
                                            data-max-file-size="3M" />
                                    </div>
                                </div>
                            </div>

                             <!-- end row -->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="useremail" class="form-label">E-mail</label>
                                        <input type="email" class="form-control" id="useremail"
                                            value="{{ Auth::user()->email }}" >
                                    </div>
                                </div>
                            </div> <!-- end row -->

                            <div class="text-end">
                                <button type="submit" class="btn btn-success mt-2"><i class="mdi mdi-content-save"></i>
                                    Mettre à jour</button>
                            </div>
                        </form>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->

        
            <div class="col-xl-4 col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }} ">
                            @csrf
                            <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Mot de passe</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">

                                        <label for="password" class="form-label">Ancien Mot de passe</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password" name="oldpassword"
                                                class="form-control @error('oldpassword') is-invalid @enderror"
                                                 required>
                                            <div class="input-group-text" data-password="false">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                        @error('oldpassword')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">

                                        <label for="password" class="form-label">Nouveau Mot de passe</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password" name="newpassword"
                                                class="form-control @error('newpassword') is-invalid @enderror"
                                                 required>
                                            <div class="input-group-text" data-password="false">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                        @error('newpassword')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->



                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">

                                        <label for="password" class="form-label">Confirmez le mot de passe</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password" name="password_confirmation"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                 required>
                                            <div class="input-group-text" data-password="false">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                        @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                            </div> <!-- end row -->

                            <div class="text-end">
                                <button type="submit" class="btn btn-success mt-2"><i class="mdi mdi-content-save"></i>
                                    Mettre à jour</button>
                            </div>
                        </form>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
        </div>
    </div>

    </div>
@endsection


@push('extra-js')
@endpush
