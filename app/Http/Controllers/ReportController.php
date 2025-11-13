<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //

    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $type = $request->input('type');

        // ✅ Base Query optimisée
        $baseQuery = Invoice::query()
            ->when($type, fn ($q) => $q->where('type', $type))
            ->when($dateFrom, fn ($q) => $q->whereDate('invoice_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('invoice_date', '<=', $dateTo));

        // ✅ Statistiques globales (1 seule requête SQL)
        $stats = $baseQuery->clone()->selectRaw("
            SUM(total_invoice) AS total_factures,
            SUM(total_invoice - balance) AS total_paye,
            SUM(balance) AS total_attente,
            SUM(CASE WHEN status = 'cancelled' THEN total_invoice ELSE 0 END) AS total_annule
        ")->first();

        // ✅ Graphique (évolution par date)
        $chartData = $baseQuery->clone()
            ->selectRaw('DATE(invoice_date) as date, SUM(total_invoice) as total')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // ✅ Liste détaillée des factures
        $invoicesList = $baseQuery->clone()
            ->with('contact') // relation contact = client / fournisseur
            ->orderBy('invoice_date', 'DESC')
            ->paginate(10);

        return view('back.reports.index', compact('stats', 'chartData', 'invoicesList'));
    }

    public function journal() {
        return view("back.reports.journal");
    }
}
