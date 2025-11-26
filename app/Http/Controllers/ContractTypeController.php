<?php

namespace App\Http\Controllers;

use App\Models\ContractType;
use App\Models\Language;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContractTypeController extends Controller
{
    public function index(): View
    {
        return view('pages.contract_types.index');
    }

    public function ajax(): JsonResponse
    {
        return Datatables()->of(ContractType::query())
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
//                $html = '';
                $html = $btn = '';
                // if (currentUser()->can('blog-edit')) {
                $html .= $btn . ' <a class="btn btn-primary shadow btn-xs sharp mr-1" href="'.route('companies.edit',$data->id).'"><i class="fa fa-edit"></i></a>';
                // }
                // if (currentUser()->can('blog-delete')) {
                $html .= $btn . ' <a class="btn btn-danger shadow btn-xs sharp mr-1" href="javascript:void(0)" onclick="deleteSlide(' . $data->id . ')"><i class="fa fa-trash"></i></a>';
                // }


                return $html;
            })
            ->rawColumns(['role', 'action'])
            ->make(true);
    }

    public function create(): View
    {
        $languages = Language::all();
        return view('pages.contract_types.create',compact('languages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->except('translations');

        $blog = ContractType::create($data);

        foreach ($request->translations as $locale => $title) {
            foreach ($title as $key => $value) {
                $secondParameterName = $key;
                $secondParameterValue = $value;
                $blog->translations()->create([
                    'locale' => $locale,
                    'content' => [
                        $secondParameterName => $secondParameterValue,
                    ],
                ]);
            }
        }
        return redirect(route('contract_types.index'))->with('success','ხელშეკრულების ტიპი წარმატებით დაემატა!');
    }
}
