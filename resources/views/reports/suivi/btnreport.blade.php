@php
$doctorAssign = App\Models\TestOrderAssignmentDetail::where('test_order_id', $data->test_order_id)->first();
@endphp

@if($data->status == 1)
<button class="btn btn-success">Terminer</button>
<br> <small style="text-transform: uppercase;"><b>{{ $doctorAssign?->assignment?->user?->firstname.' '.$doctorAssign?->assignment?->user?->lastname }}</b></small>
@elseif($doctorAssign == true)
<button class="btn btn-warning">Affecter</button>
<br> <small style="text-transform: uppercase;"><b>{{ $doctorAssign?->assignment?->user?->firstname.' '.$doctorAssign?->assignment?->user?->lastname }}</b></small>
@elseif($doctorAssign == false)
<button class="btn btn-danger">Non affecter</button>
<br> <small style="text-transform: uppercase;"><b>{{ $doctorAssign?->assignment?->user?->firstname.' '.$doctorAssign?->assignment?->user?->lastname }}</b></small>
@endif
