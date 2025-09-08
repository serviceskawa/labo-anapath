{{-- Vue btn_delete.blade.php --}}
@if (!isAffecte($data->order->id))
    <button type="button" class="btn btn-danger delete-btn" data-id="{{ $data->id }}" title="Supprimer">
        <i class="mdi mdi-trash-can-outline"></i>
    </button>
@endif

