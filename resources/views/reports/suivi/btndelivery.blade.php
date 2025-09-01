@if ($data->is_delivered == 0)
    @if ($data->status == 1)
        <a class="btn btn-danger" href="{{ route('report.delivered.patient', $data->id) }}">Non</a>
    @else
        <button class="btn btn-danger" disabled>Non</button>
    @endif
@elseif($data->is_delivered == 1)
    <button class="btn btn-success" data-bs-toggle="modal"
        data-bs-target="#bs-example-modal-lg-detail-{{ $data->id }}">Détail</button>
    @include('reports.suivi.detail_signature', ['data' => $data])
@endif
