<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Services\ReportFilterService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;


class ReportsGlobalSearchExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Report::with([
            'order',
            'order.patient',
            'order.hospital',
            'order.contrat',
            'order.doctorExamen',
            'order.type',
        ]);

        $req = $this->request;

        // Convertisseur de chaînes en tableau propre
        $cleanArray = function ($input) {
            if (is_array($input)) {
                // Si déjà un tableau avec une chaîne unique séparée par virgule
                if (count($input) === 1 && str_contains($input[0], ',')) {
                    return array_filter(explode(',', $input[0]));
                }
                return array_filter($input);
            }

            if (is_string($input)) {
                return array_filter(explode(',', $input));
            }

            return [];
        };

        // Appliquer le filtre sur chaque type d’ID
        $typeExamenIds = $cleanArray($req->get('type_examen_ids'));
        if ($typeExamenIds) {
            $query->whereHas('order', fn($q) => $q->whereIn('type_order_id', $typeExamenIds));
        }

        $contratIds = $cleanArray($req->get('contrat_ids'));
        if ($contratIds) {
            $query->whereHas('order', fn($q) => $q->whereIn('contrat_id', $contratIds));
        }

        $patientIds = $cleanArray($req->get('patient_ids'));
        if ($patientIds) {
            $query->whereHas('order', fn($q) => $q->whereIn('patient_id', $patientIds));
        }

        $doctorIds = $cleanArray($req->get('doctor_ids'));
        if ($doctorIds) {
            $query->whereHas('order', fn($q) => $q->whereIn('doctor_id', $doctorIds));
        }

        $hospitalIds = $cleanArray($req->get('hospital_ids'));
        if ($hospitalIds) {
            $query->whereHas('order', fn($q) => $q->whereIn('hospital_id', $hospitalIds));
        }

        if (!empty($req->get('reference_hopital'))) {
            $query->whereHas('order', fn($q) =>
                $q->where('reference_hopital', 'like', '%' . $req->get('reference_hopital') . '%'));
        }

        if (!empty($req->get('status_urgence_test_order_id'))) {
            $query->where('status', $req->get('status_urgence_test_order_id') == 1 ? 1 : 0);
        }

        if (!empty($req->get('content'))) {
            $content = $req->get('content');
            $query->where(function ($q) use ($content) {
                $q->where('code', 'like', "%$content%")
                    ->orWhere('description', 'like', "%$content%")
                    ->orWhere('description_supplementaire', 'like', "%$content%")
                    ->orWhere('title', 'like', "%$content%")
                    ->orWhereHas('order', fn($q) => $q->where('code', 'like', "%$content%"));
            });
        }

        if (!empty($req->get('dateBegin'))) {
            $newDate = Carbon::createFromFormat('Y-m-d', $req->get('dateBegin'));
            $query->whereDate('created_at', '>=', $newDate);
        }

        if (!empty($req->get('dateEnd'))) {
            $newDate = Carbon::createFromFormat('Y-m-d', $req->get('dateEnd'));
            $query->whereDate('created_at', '<=', $newDate);
        }
        
        // Map les colonnes que tu veux exporter
        return $query->get()->map(function ($report) {
            return [
                $report?->code,
                $report?->order?->code,
                $report?->order?->type?->name,
                $report?->order?->contrat?->name,
                $report?->order?->patient?->firstname . ' ' . $report?->order?->patient?->lastname,
                $report?->order?->doctorExamen?->name,
                $report?->order?->hospital?->name,
                $report?->order?->reference_hopital,
                $report?->created_at ? $report?->created_at?->format('Y-m-d') : '',
                $report?->status == 1 ? 'Urgent' : 'Normal',

                trim(str_replace("\u{00A0}", ' ', strip_tags(html_entity_decode($report?->description)))),
                trim(str_replace("\u{00A0}", ' ', strip_tags(html_entity_decode($report?->description_supplementaire)))),
                trim(str_replace("\u{00A0}", ' ', strip_tags(html_entity_decode($report?->description_micro)))),
                trim(str_replace("\u{00A0}", ' ', strip_tags(html_entity_decode($report?->description_supplementaire_micro)))),
                trim(str_replace("\u{00A0}", ' ', strip_tags(html_entity_decode($report?->comment)))),
                trim(str_replace("\u{00A0}", ' ', strip_tags(html_entity_decode($report?->comment_sup)))),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Code Rapport',
            'Code Examen',
            'Type d\'examen',
            'Contrat',
            'Patient',
            'Docteur',
            'Hôpital',
            'Hôpital de Référence',
            'Date de création',
            'Statut d\'urgence',

            'Description',
            'Description supplémentaire',
            'Description micro',
            'Description supplementaire micro',
            'Commentaire',
            'Commentaire supplementaire',
        ];
    }
}
