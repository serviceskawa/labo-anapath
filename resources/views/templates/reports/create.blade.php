@extends('layouts.app2')

@section('title', 'Templates comptes rendus')

@section('css')
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
                <div class="page-title-right mr-3">
                    <a type="button" class="btn btn-primary" href="{{ route('template.report-index') }} ">Tout les templates
                    </a>
                </div>
                <h4 class="page-title">Creer une nouveau template</h4>
            </div>


        </div>
    </div>
    <div class="">

        @include('layouts.alerts')

        <div class="card my-3">
            <div class="card-header">

                <div class="card-body">

                    <form action="{{ route('template.report-store') }}" method="post" autocomplete="off">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Titre</label>
                                    <input type="hidden" name="id" value="{{ $template ? $template->id : '' }}">
                                    <input type="text" class="form-control" name="titre"
                                        value="{{ $template ? $template->title : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Description</label>
                                    <input type="text" class="form-control" name="description"
                                        value="{{ $template ? $template->description : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Content</label>
                                    <textarea name="content" id="editor" cols="30" rows="10">{{ $template ? $template->content : '' }}</textarea>
                                </div>
                            </div>
                        </div>

                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit"
                        class="btn {{ $template ? 'bg-warning text-white' : 'bg-primary' }}">{{ $template ? 'Mettre à jour' : 'Créer' }}</button>
                </div>


                </form>
            </div>


        </div>

    </div>
@endsection



@push('extra-js')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor2'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
