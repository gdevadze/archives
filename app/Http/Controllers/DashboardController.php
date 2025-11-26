<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ContractType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $companies = Company::withCount('documents')->get();
        return view('pages.dashboard',compact('companies'));
    }

    public function company(Request $request, $id): View
    {
        $company = Company::findOrFail($id);
        $contractTypes = ContractType::all();

        $documents = $company->documents()
            ->when($request->contract_type_id, fn($q) =>
            $q->where('contract_type_id', $request->contract_type_id)
            )
            ->when($request->year, fn($q) =>
            $q->where('year', $request->year)
            )
            ->when($request->q, fn($q) =>
            $q->where('title', 'like', "%{$request->q}%")
            )
            ->orderByDesc('year')
            ->paginate(20);

        return view('pages.company',compact('company','documents','contractTypes'));
    }
}
