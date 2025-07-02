<?php
namespace App\Services;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportFilterService
{
    public static function getFilteredQuery(Request $request)
    {
        $query = Report::with([
            'order',
            'order.patient',
            'order.hospital',
            'order.contrat',
            'order.doctorExamen',
            'order.type',
        ]);

        if ($request->filled('type_examen_ids')) {
            $query->whereHas('order', fn($q) =>
                $q->whereIn('type_order_id', (array) $request->get('type_examen_ids')));
        }

        if ($request->filled('contrat_ids')) {
            $query->whereHas('order', fn($q) =>
                $q->whereIn('contrat_id', (array) $request->get('contrat_ids')));
        }

        if ($request->filled('patient_ids')) {
            $query->whereHas('order', fn($q) =>
                $q->whereIn('patient_id', (array) $request->get('patient_ids')));
        }

        if ($request->filled('doctor_ids')) {
            $query->whereHas('order', fn($q) =>
                $q->whereIn('doctor_id', (array) $request->get('doctor_ids')));
        }

        if ($request->filled('hospital_ids')) {
            $query->whereHas('order', fn($q) =>
                $q->whereIn('hospital_id', (array) $request->get('hospital_ids')));
        }

        if ($request->filled('reference_hopital')) {
            $query->whereHas('order', fn($q) =>
                $q->where('reference_hopital', 'like', '%' . $request->get('reference_hopital') . '%'));
        }

        if ($request->filled('status_urgence_test_order_id')) {
            $query->where('status', $request->get('status_urgence_test_order_id') == 1 ? 1 : 0);
        }

        if ($request->filled('content')) {
            $content = $request->get('content');
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

        if ($request->filled('dateBegin')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->get('dateBegin')));
        }

        if ($request->filled('dateEnd')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->get('dateEnd')));
        }

        return $query;
    }
}

