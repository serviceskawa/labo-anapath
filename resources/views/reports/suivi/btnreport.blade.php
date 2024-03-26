@php
    $response = App\Models\TestOrderAssignmentDetail::where('test_order_id', $data->test_order_id)->exists();
@endphp

@if($data->status == 1)
<button class="btn btn-success">Terminer</button>
@elseif($response == true)
<button class="btn btn-warning">Affecter</button>
@elseif($response == false)
<button class="btn btn-danger">En attente</button>
@endif