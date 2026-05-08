<?php

namespace App\Exports;

use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DocumentsReportExport implements FromCollection, WithHeadings
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $start = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $end   = Carbon::create($this->year, $this->month, 1)->endOfMonth();

        $documents = Document::with(['companies', 'contractType'])
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $report = collect();

        foreach ($documents as $document) {

            foreach ($document->companies as $company) {

                $key = $company->id . '_' . $document->contract_type_id;

                if (!$report->has($key)) {

                    $report[$key] = (object)[
                        'company_id'       => $company->id,
                        'company'          => $company,
                        'contract_type_id' => $document->contract_type_id,
                        'contractType'     => $document->contractType,
                        'total'            => 0,
                    ];
                }

                $report[$key]->total++;
            }
        }

        return collect($report->values()->map(function ($row) {

            return [
                'company_name'        => $row->company->company_name ?? '-',
                'contract_type_name' => $row->contractType->contract_type_name ?? '-',
                'total'              => $row->total,
            ];
        }));
    }

    public function headings(): array
    {
        return [
            'კომპანია',
            'ხელშეკრულების ტიპი',
            'დოკუმენტების რაოდენობა',
        ];
    }
}
