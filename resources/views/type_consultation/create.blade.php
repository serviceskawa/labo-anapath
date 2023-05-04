@extends('layouts.app2')

@section('title', 'Mettre à jour un type de consultation')

@section('content')
<div class="">

    @include('layouts.alerts')

    <div class="card my-3">
        <div class="card-header">
            <div class="col-12">
                <div class="page-title-box">
                    {{-- <div class="page-title-right mt-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#standard-modal">Ajouter un nouveau patient</button>
                    </div> --}}
                    Mettre à jour un type consultation
                </div>


            </div>

        </div>
        <div class="card-body">
            <form action="{{ route('type_consultation.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="row">
                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Ttire <span style="color:red;">*</span></label>
                            <input type="text" name="name" id="name" value="{{$type->name}}" class="form-control"
                                required>
                            <input type="hidden" name="id" id="id" class="form-control" value="{{$type->id}}">
                        </div>
                    </div>
                    <div class="row my-3">
                        <label for="simpleinput" class="form-label ">Cocher les fichiers<span
                                style="color:red;">*</span></label>
                        <div class="">
                            @forelse ($files as $item)
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="type_files[{{ $item->id }}]"
                                    {{checkTypeConsultationFile($item->id,$type->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="customCheck3">{{ $item->title }}</label>
                            </div>
                            @empty

                            @endforelse


                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary">Mettre à jour </button>
                </div>
            </form>
        </div>
    </div>
</div>



</div>
@endsection


@push('extra-js')
<script>
    var baseUrl = "{{url('/')}}"
</script>

@endpush