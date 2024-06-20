@if ($data->is_close == 0)
<button type="button" onclick="edit({{ $data->id }})" class="btn btn-primary" title="Editer contrat"><i
        class="mdi mdi-lead-pencil"></i> </button>
<button type="button" onclick="deleteModal({{ $data->id }})" class="btn btn-danger" title="Supprimer contrat"><i
        class="mdi mdi-trash-can-outline"></i> </button>
<button type="button" onclick="closeModal({{ $data->id }})" class="btn btn-secondary" title="Clôturer contrat"><i
        class="mdi mdi-block-helper"></i> </button>
@endif
