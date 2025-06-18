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

    // public function view(): View
    // {
    //     // dd($this->request); getFilteredQuery
    //     $reports = ReportFilterService::getFilteredQuery($this->request)->get();
    //     dd($reports);
    //     return view('exports.reports', compact('reports'));
    // }

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

        // type_examen_ids
        $ids = array_filter((array) $req->get('type_examen_ids'));
        if ($ids) {
            $query->whereHas('order', fn($q) => $q->whereIn('type_order_id', $ids));
        }

        // contrat_ids
        $ids = array_filter((array) $req->get('contrat_ids'));
        if ($ids) {
            $query->whereHas('order', fn($q) => $q->whereIn('contrat_id', $ids));
        }

        // patient_ids
        $ids = array_filter((array) $req->get('patient_ids'));
        if ($ids) {
            $query->whereHas('order', fn($q) => $q->whereIn('patient_id', $ids));
        }

        // doctor_ids
        $ids = array_filter((array) $req->get('doctor_ids'));
        if ($ids) {
            $query->whereHas('order', fn($q) => $q->whereIn('doctor_id', $ids));
        }

        // hospital_ids
        $ids = array_filter((array) $req->get('hospital_ids'));
        if ($ids) {
            $query->whereHas('order', fn($q) => $q->whereIn('hospital_id', $ids));
        }

        // reference_hopital
        if ($req->filled('reference_hopital')) {
            $query->whereHas('order', fn($q) =>
                $q->where('reference_hopital', 'like', '%' . $req->get('reference_hopital') . '%'));
        }

        // status
        if ($req->filled('status_urgence_test_order_id')) {
            $query->where('status', $req->get('status_urgence_test_order_id') == 1 ? 1 : 0);
        }

        // content
        if ($req->filled('content')) {
            $content = $req->get('content');
            $query->where(function ($q) use ($content) {
                $q->where('code', 'like', "%$content%")
                  ->orWhere('description', 'like', "%$content%")
                  ->orWhere('description_supplementaire', 'like', "%$content%")
                  ->orWhere('title', 'like', "%$content%")
                  ->orWhere('delivery_date', 'like', "%$content%")
                  ->orWhere('signature_date', 'like', "%$content%")
                  ->orWhere('retriever_date', 'like', "%$content%")
                  ->orWhere('description_micro', 'like', "%$content%")
                  ->orWhere('description_supplementaire_micro', 'like', "%$content%")
                  ->orWhere('comment', 'like', "%$content%")
                  ->orWhere('comment_sup', 'like', "%$content%")
                  ->orWhereHas('order', function ($q) use ($content) {
                      $q->where('code', 'like', "%$content%")
                        ->orWhere('prelevement_date', 'like', "%$content%")
                        ->orWhereHas('patient', function ($q) use ($content) {
                            $q->where('firstname', 'like', "%$content%")
                              ->orWhere('lastname', 'like', "%$content%")
                              ->orWhere('code', 'like', "%$content%");
                        })
                        ->orWhereHas('hospital', function ($q) use ($content) {
                            $q->where('name', 'like', "%$content%")
                              ->orWhere('email', 'like', "%$content%")
                              ->orWhere('telephone', 'like', "%$content%")
                              ->orWhere('adresse', 'like', "%$content%");
                        })
                        ->orWhereHas('doctorExamen', fn($q) =>
                            $q->where('name', 'like', "%$content%"))
                        ->orWhereHas('contrat', function ($q) use ($content) {
                            $q->where('name', 'like', "%$content%")
                              ->orWhere('type', 'like', "%$content%")
                              ->orWhere('description', 'like', "%$content%");
                        });
                  });
            });
        }

        // Dates
        if ($req->filled('dateBegin')) {
            $query->whereDate('created_at', '>=', Carbon::parse($req->get('dateBegin')));
        }

        if ($req->filled('dateEnd')) {
            $query->whereDate('created_at', '<=', Carbon::parse($req->get('dateEnd')));
        }

        // Map les colonnes que tu veux exporter
        return $query->get()->map(function ($report) {
            return [
                $report?->code,
                $report?->order?->code,
                $report?->order?->type?->name,
                $report?->order?->contrat?->name,
                $report?->order?->patient?->firstname . ' '. $report?->order?->patient?->lastname,
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
