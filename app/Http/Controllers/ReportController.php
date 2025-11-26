<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function monthlyDocuments(Request $request)
    {
        // Default: current month
        $month = $request->month ?? Carbon::now()->month;
        $year  = $request->year ?? Carbon::now()->year;

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end   = Carbon::create($year, $month, 1)->endOfMonth();

        $report = Document::selectRaw('company_id, contract_type_id, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('company_id', 'contract_type_id')
            ->with(['company', 'contractType'])
            ->orderBy('company_id')
            ->get();

        // Chart – Company totals
        $companies = $report->groupBy('company_id')->map(function ($items) {
            return $items->sum('total');
        });

        // Chart – Contract Type totals
        $types = $report->groupBy('contract_type_id')->map(function ($items) {
            return $items->sum('total');
        });

        $months = [
            1=>'იანვარი',2=>'თებერვალი',3=>'მარტი',4=>'აპრილი',5=>'მაისი',6=>'ივნისი',
            7=>'ივლისი',8=>'აგვისტო',9=>'სექტემბერი',10=>'ოქტომბერი',11=>'ნოემბერი',12=>'დეკემბერი'
        ];

        $years = range(date('Y'), date('Y') - 10);

        return view('pages.reports.documents', compact(
            'report', 'month', 'year', 'months', 'years', 'start','companies','types'
        ));
    }

}
