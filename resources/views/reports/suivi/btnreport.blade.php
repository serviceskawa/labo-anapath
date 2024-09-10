@php
$response = App\Models\TestOrderAssignmentDetail::where('test_order_id', $data->test_order_id)->exists();
@endphp

@if($data->status == 1)
<button class="btn btn-success">Terminer</button>
<br> <small style="text-transform: uppercase;"><b>{{ $data?->order?->doctor->name}}</b></small>
@elseif($response == true)
<button class="btn btn-warning">Affecter</button>
<br> <small style="text-transform: uppercase;"><b>{{ $data?->order?->doctor->name}}</b></small>
@elseif($response == false)
<button class="btn btn-danger">Non affecter</button>
<br> <small style="text-transform: uppercase;"><b>{{ $data?->order?->doctor->name}}</b></small>
@endif