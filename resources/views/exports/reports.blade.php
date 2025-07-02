<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Code Rapport</th>
            <th>Code Examen</th>
            <th>Nom Patient</th>
            <th>Hôpital</th>
            <th>Contrat</th>
            <th>Médecin</th>
            <th>Type Examen</th>
            <th>Urgence</th>
            <th>Date Création</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $index => $report)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $report->code }}</td>
                <td>{{ $report->order?->code }}</td>
                <td>{{ $report->order?->patient?->firstname }} {{ $report->order?->patient?->lastname }}</td>
                <td>{{ $report->order?->hospital?->name }}</td>
                <td>{{ $report->order?->contrat?->name }}</td>
                <td>{{ $report->order?->doctorExamen?->name }}</td>
                <td>{{ $report->order?->type?->title }}</td>
                <td>{{ $report->status == 1 ? 'Oui' : 'Non' }}</td>
                <td>{{ $report->created_at->format('d/m/Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
