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

        /**
         * 1️⃣ ყველა დოკუმენტი პერიოდის მიხედვით
         * + კომპანიები
         * + ხელშეკრულების ტიპი
         */
        $documents = Document::with(['companies', 'contractType'])
            ->whereBetween('created_at', [$start, $end])
            ->get();

        /**
         * 2️⃣ Report (Company × ContractType)
         */
        $report = collect();

        foreach ($documents as $document) {
            foreach ($document->companies as $company) {

                $key = $company->id . '_' . $document->contract_type_id;

                if (! $report->has($key)) {
                    $report[$key] = (object)[
                        'company_id'        => $company->id,
                        'company'           => $company,
                        'contract_type_id'  => $document->contract_type_id,
                        'contractType'      => $document->contractType,
                        'total'             => 0,
                    ];
                }

                $report[$key]->total++;
            }
        }

        $report = $report->values();

        /**
         * 3️⃣ Chart – Company totals
         */
        $companies = $report
            ->groupBy('company_id')
            ->map(fn ($items) => $items->sum('total'));

        /**
         * 4️⃣ Chart – Contract Type totals
         */
        $types = $report
            ->groupBy('contract_type_id')
            ->map(fn ($items) => $items->sum('total'));

        /**
         * 5️⃣ Months / Years
         */
        $months = [
            1=>'იანვარი',2=>'თებერვალი',3=>'მარტი',4=>'აპრილი',
            5=>'მაისი',6=>'ივნისი',7=>'ივლისი',8=>'აგვისტო',
            9=>'სექტემბერი',10=>'ოქტომბერი',11=>'ნოემბერი',12=>'დეკემბერი'
        ];

        $years = range(date('Y'), date('Y') - 10);

        return view('pages.reports.documents', compact(
            'report',
            'month',
            'year',
            'months',
            'years',
            'start',
            'companies',
            'types'
        ));
    }

}
