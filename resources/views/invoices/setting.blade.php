@extends('layouts.app2')

@section('title', 'INVOICES SETTINGS')

@section('css')
    <link href="{{ asset('/adminassets/css/vendor/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminassets/css/vendor/quill.snow.css') }}" rel="stylesheet" type="text/css" />
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


    @include('layouts.alerts')

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Paramètres des Factures</h4>
                <p class="text-muted font-14">

                </p>

                <ul class="nav nav-tabs nav-bordered mb-3">
                    <li class="nav-item">
                        <a href="#input-types-preview" data-bs-toggle="tab" aria-expanded="false"
                            class="nav-link active">
                            Informations
                        </a>
                    </li>

                </ul> <!-- end nav-->

                <div class="tab-content">
                    <div class="tab-pane show active" id="input-types-preview">
                        <form id="addDetailForm" action="{{route('invoice.setting.update')}}" method="post" autocomplete="off">
                            @csrf
                            <div class="row mb-3">
                                <label for="" class="form-label">IFU</label>
                                <div class="col-lg-12">
                                    <input type="text" name="ifu" id="ifu" class="form-control"
                                        value="{{ $settingInvoice ? $settingInvoice->ifu : '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="" class="form-label">Token</label>
                                <div class="col-lg-12">
                                    <textarea name="token" id="token" class="form-control" cols="30" rows="5">{{ $settingInvoice ? $settingInvoice->token : '' }}</textarea>
                                </div>
                            </div>
                            <label class="form-label mt-3">Activé</label> <br>
                            @if( $settingInvoice)
                            <input type="checkbox" id="status" name="status" class="form-control" data-switch="success"
                            {{ $settingInvoice->status!=0 ? 'checked':'' }}>
                            @else
                            <input type="checkbox" id="status" name="status" class="form-control" data-switch="success">
                            @endif
                            <label for="status" data-on-label="oui" data-off-label="non"></label>

                            <div class="card-footer">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-xs btn-success">Mettre à jour</button>
                                </div>
                            </div> <!-- end card-body -->
                        </form>
                        <!-- end row-->
                    </div> <!-- end preview-->
                </div> <!-- end tab-content-->
            </div>

        </div> <!-- end card -->
    </div>
</div>
@endsection

@push('extra-js')
    <script>

        // $('#addDetailForm').on('submit', function(e) {
        //     e.preventDefault();

        //     let ifu = $('#ifu').val();
        //     let token = $('#token').val();
        //     let status = $('#status').val();

        //     $.ajax({
        //         url: "{{ route('invoice.setting.update') }}",
        //         type: "POST",
        //         data: {
        //             "_token": "{{ csrf_token() }}",
        //             ifu :ifu,
        //             token :token,
        //             status :status,

        //         },
        //         success: function(response) {
        //            // $('#addDetailForm').trigger("reset")

        //             if (response) {
        //                 console.log(response);
        //                 toastr.success("Mise à jour effectué avec succès", 'Ajout réussi');
        //                 location.reload();
        //             }
        //             //$('#datatable1').DataTable().ajax.reload();
        //             // $('#addDetailForm').trigger("reset")
        //             // updateSubTotal();
        //         },
        //         error: function(response) {
        //             console.log(response)
        //         },
        //     });

        // });

        $(document).ready(function() {
            console.log($('#status').val());
        })
    </script>
@endpush
