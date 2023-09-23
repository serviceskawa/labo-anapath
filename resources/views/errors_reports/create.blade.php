@extends('layouts.app2')

@section('title', 'Signaler un problème')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Signaler un problème</h4>
        </div>


    </div>
</div>

<div class="">

    @include('layouts.alerts')

    <div class="card my-3">

        <div class="card-body">

            <form action="{{route('probleme.report.store')}} " method="post" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Demande d'examen<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="test_orders_id"
                            id="test_orders_id" required>
                            <option>Sélectionner une demande d'examen</option>
                            @foreach ($testOrders as $testOrder)
                            <option value="{{ $testOrder->id }}">{{ $testOrder->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="simpleinput" class="form-label">Catégorie de problème<span
                                style="color:red;">*</span></label>
                        <select name="errorCategory_id" id="errorCategory_id" class="form-control" required>
                            <option value="">Toutes les catégories</option>
                            @foreach ($problemCategories as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-group">
                        <label for="simpleinput" class="form-label">Description<span style="color:red;">*</span></label>
                        {{-- <textarea name="description" id="editor" rows="10"></textarea> --}}
                        <textarea name="description" id="" rows="6" class="form-control" required></textarea>
                    </div>
                </div>
        </div>

        <div class="modal-footer">
            <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Signaler un problème</button>
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
@endpush