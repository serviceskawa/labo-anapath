@extends('layouts.app2')

@section('title', 'Ajouter')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css"
        integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    <div class="">
        @include('layouts.alerts')
        <div class="card my-3">
            <div class="card-header">
                Ajouter une macroscopie
            </div>
            <div class="card-body">
                <form action="{{ route('macro.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Laborantins<span
                                        style="color:red;">*</span></label>
                                <select name="id_employee" id="id_employee" class="form-select select2" required
                                    data-toggle="select2">
                                    <option value="">Tous les laborantins</option>
                                    @forelse ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->fullname() }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="example-fileinput" class="form-label">Date</label>
                            <input type="date" class="form-control" name="date" id="inputDate" />
                        </div>

                        <div class="mb-3">
                            <label for="example-select" class="form-label">Demandes d'examen<span
                                    style="color:red;">*</span></label>
                            {{-- <select class="form-select select2" data-toggle="select2" required name="orders[]" id=""
                                multiple> --}}
                            <select class="form-select select2-ajax" required name="orders[]" id="orders" multiple>
                                {{-- <option>Sélectionner les demandes</option>
                                @forelse ($orders as $order)
                                    @if (!isMacro($order->id))
                                        <option value="{{ $order->id }}">{{ $order->code }}</option>
                                    @endif
                                @empty
                                    Ajouter une demande
                                @endforelse --}}
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-warning">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('extra-js')
    <script>
        var SEARCHMACRO = "{{ route('search.macroscopie') }}"

        $('#orders').select2({
            ajax: {
                url: SEARCHMACRO,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                        limit: 20
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.data.map(function(order) {
                            return {
                                id: order.id,
                                text: order.code
                            };
                        }),
                        pagination: {
                            more: data.has_more
                        }
                    };
                }
            },
            minimumInputLength: 2,
            placeholder: 'Tapez pour rechercher des demandes...',
            allowClear: true,
            multiple: true, // Important pour le select multiple
            closeOnSelect: false // Garde le dropdown ouvert après sélection
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var vals = <?= isset($user->roles) ? json_encode($user->roles) : '' ?>
    </script>
    <script src="{{ asset('viewjs/user/edit.js') }}"></script>
    <script>
        // Obtenir la date actuelle au format YYYY-MM-DD
        const currentDate = new Date().toISOString().split('T')[0];

        // Définir la valeur du champ de saisie de date sur la date actuelle
        document.getElementById('inputDate').value = currentDate;
    </script>
@endpush
