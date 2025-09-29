@extends('layouts.app2')

@section('title', 'Utilisateurs')

@section('css')
    <link href="{{ asset('/adminassets/css/vendor/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminassets/css/vendor/quill.snow.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css"
        integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .ck-editor__editable[role="textbox"] {
            /* editing area */
            min-height: 200px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3 mb-1">
                    <a href="{{ route('user.index') }}" type="button" class="btn btn-primary"> <i
                            class="dripicons-reply"></i> Retour</a>
                </div>
                <h4 class="page-title">Utilisateur</h4>
            </div>
        </div>
    </div>
    <div class="">

        @include('layouts.alerts')

        <div class="card my-3">
            <div class="card-header">
                Ajouter un nouvel utilisateur
            </div>

            <form action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
                <div class="card-body">

                    @csrf

                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="exampleFormControlInput1" class="form-label">Nom<span
                                    style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="firstname" required>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="exampleFormControlInput1" class="form-label">Prénoms<span
                                    style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="lastname" required>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="exampleFormControlInput1" class="form-label">Email<span
                                    style="color:red;">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>

                    <!-- Commission Field -->
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="commission" class="form-label">Commission (%)</label>
                            <input type="number" class="form-control" name="commission" id="commission" min="0"
                                max="100" step="0.01" placeholder="0.00">
                        </div>
                    </div>

                    <!-- WhatsApp Field -->
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="whatsapp" class="form-label">WhatsApp</label>
                            <input type="tel" class="form-control" name="whatsapp" id="whatsapp"
                                placeholder="+2290120202222">
                        </div>
                    </div>

                    <!-- Telephone Field -->
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="telephone" class="form-label">Telephone</label>
                            <input type="tel" class="form-control" name="telephone" id="telephone"
                                placeholder="+2290120202222">
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="example-fileinput" class="form-label">Signature</label>
                        <input type="file" class="form-control" name="signature" id="signature" />

                        {{-- <input type="file" class="dropify" name="signature" id="signature" data-default-file=""
                            data-max-file-size="3M" /> --}}
                    </div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Roles<span style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" required name="roles[]" multiple>
                            <option>Sélectionner les roles</option>
                            @forelse ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @empty
                                Ajouter un role
                            @endforelse
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Branches</label>
                        <select class="form-select select2" data-toggle="select2" required name="branches[]" multiple>
                            <option>Sélectionner les branches</option>
                            @forelse ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @empty
                                Ajouter une branche
                            @endforelse
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter un nouvel utilisateur</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- <div class="modal-footer">
            <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Creer</button>
        </div> --}}
    </div>
@endsection

@push('extra-js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('.dropify').dropify();
    </script>
@endpush
