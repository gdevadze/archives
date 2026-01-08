<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ContractType;
use App\Models\Document;
use App\Models\DocumentChange;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(): View
    {
        $companies = Company::all();
        $contractTypes = ContractType::all();
        return view('pages.documents.index',compact('companies','contractTypes'));
    }

    public function ajax(Request $request): JsonResponse
    {
        $documents = Document::query();
        $companyIds = $request->company_ids;
        if ($companyIds){
            $documents = $documents->whereHas('companies', function ($q) use ($companyIds) {
                $q->whereIn('companies.id', $companyIds);
            });
        }
        if ($request->contract_type){
            $documents = $documents->where('contract_type_id',$request->contract_type);
        }
        if ($request->year){
            $documents = $documents->where('year',$request->year);
        }
        if ($request->contract_date){
            $documents = $documents->where('contract_date',$request->contract_date);
        }
        return Datatables()->of($documents)
            ->addIndexColumn()
            ->addColumn('company_names', function ($data) {
                $html = $data->companies->implode('company_name',',<br>');
                return $html;
            })
            ->addColumn('formatted_contract_date', function ($data) {
                $html = Carbon::parse($data->contract_date)->format('d.m.Y');
                return $html;
            })
            ->addColumn('action', function ($data) {
                $html = '';
                $html .= ' <a class="btn btn-primary shadow btn-xs sharp mr-1" href="javascript:void()" onclick="previewPDF(\'' . Storage::url($data->file_path) . '\')"><i class="fa fa-eye"></i></a>';
                $html .= ' <a class="btn btn-primary shadow btn-xs sharp mr-1" href="'. route('documents.download', $data->id) .'"><i class="fa fa-download"></i></a>';
                $html .= ' <a class="btn btn-primary shadow btn-xs sharp mr-1" href="'. route('documents.print', $data->id) .'"><i class="fa fa-print"></i></a>';

                return $html;
            })
            ->rawColumns(['company_names', 'action'])
            ->make(true);
    }

    public function create(): View
    {
        $companies     = Company::all();
        $contractTypes = ContractType::all();

        return view('pages.documents.create', compact('companies', 'contractTypes'));
    }

    public function uploadTemp(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:51200'
        ]);

        $file = $request->file('file');

        $originalName = $file->getClientOriginalName();
        $tempName = time() . '_' . $originalName;

        $tempDir = storage_path('app/public/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $file->move($tempDir, $tempName);

        return response()->json([
            'status'     => 'success',
            'temp_file'  => $tempName,
            'file_name'  => $originalName,
            'temp_url'   => asset('storage/temp/' . $tempName),
        ]);
    }



    public function storeAjax(Request $request)
    {
        $request->validate([
            'company_id'        => 'required|exists:companies,id',
            'contract_type_id'  => 'required|exists:contract_types,id',
            'year'              => 'required|digits:4',
            'contract_date'     => 'nullable|date',
            'comment'     => 'nullable',
            'document_no'     => 'nullable',
            'amount'     => 'nullable',
            'file_original_name'=> 'required|string',
            'temp_file'         => 'required|string'
        ]);

        $tempPath = storage_path("app/public/temp/" . $request->temp_file);

        if (!file_exists($tempPath)) {
            return response()->json([
                "status" => "error",
                "message" => "დროებითი ფაილი ვერ მოიძებნა"
            ], 404);
        }

        $company = Company::findOrFail($request->company_id);
        $contractType = ContractType::findOrFail($request->contract_type_id);
        $companySlug = Str::slug($company->company_name, '_');
        $year = $request->year;

        // შექმნა საბოლოო საქაღალდე
        $finalDir = storage_path("app/public/documents/{$companySlug}/{$year}");
        if (!file_exists($finalDir)) {
            mkdir($finalDir, 0777, true);
        }

        // ორიგინალი სახელი: example.pdf
        $original = $request->file_original_name;

        // basename + timestamp → example_1737312345.pdf
        $nameWithoutExt = pathinfo($original, PATHINFO_FILENAME);
        $ext = pathinfo($original, PATHINFO_EXTENSION);

        $finalName = $nameWithoutExt . "_" . time() . "." . $ext;

        // ფაილის საბოლოო მისამართი
        $finalPath = $finalDir . "/" . $finalName;

        // გადატანა temp → documents
        rename($tempPath, $finalPath);

        $title = Carbon::parse($request->contract_date)->format('d-m-Y').'-'.getTrx(3);

        $document = Document::create([
//            'company_id'       => $request->company_id,
            'contract_type_id' => $request->contract_type_id,
            'year'             => $year,

            // TITLE არის "ორიგინალი სახელი" (example.pdf)
            'title'            => $title,
            'comment'          => $request->comment,
            'document_no'      => $request->document_no,
            'amount'           => $request->amount,

            'file_path'        => "documents/{$companySlug}/{$year}/{$finalName}",
            'original_name'    => $original,
            'mime_type'        => 'application/pdf',
            'size'             => filesize($finalPath),
            'contract_date'    => $request->contract_date,
            'uploaded_by'      => Auth::id(),
        ]);

        $document->companies()->sync($request->company_ids);

        return response()->json([
            'status'  => 'success',
            'message' => 'დოკუმენტი წარმატებით შეინახა! - დოკუმენტის ნომერი: '.$title.' <a href="'.route('documents.print',$document->id).'">ბეჭდვა</a>'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id'        => 'required|exists:companies,id',
            'contract_type_id'  => 'required|exists:contract_types,id',
            'year'              => 'required|digits:4|integer',
            'title'             => 'required|string|max:255',
            'contract_date'     => 'nullable|date',
            'file'              => 'required|file|max:51200', // 50MB
        ]);

        if (! $request->hasFile('file')) {
            return back()->withErrors(['file' => 'ფაილი ვერ მოიძებნა მოთხოვნაში.']);
        }

        $file    = $request->file('file');
        $year    = $request->year;
        $company = Company::findOrFail($request->company_id);

// საქაღალდეების სტრუქტურა: storage/app/public/documents/company_slug/year/
        $companySlug = Str::slug($company->company_name, '_');

// უკეთესი: სახელის გენერაცია
        $originalName = $file->getClientOriginalName();
        $filenameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        $storedFileName = time().'_'.Str::slug($filenameWithoutExt, '_').'.'.$extension;

// ფაილის შენახვა PUBLIC დისკზე
        $path = $file->storeAs(
            "documents/{$companySlug}/{$year}",
            $storedFileName,
            'public' // <<< მნიშვნელოვანი
        );

// სურვილის მიხედვით შეგიძლიათ ლოგიც:
        if (! $path) {
            return back()->withErrors(['file' => 'ფაილი ვერ შეინახა დისკზე.']);
        }

        $document = Document::create([
            'company_id'       => $request->company_id,
            'contract_type_id' => $request->contract_type_id,
            'year'             => $year,
            'title'            => $request->title,
            'file_path'        => $path, // напр. documents/asianet_2025/123_file.pdf
            'original_name'    => $originalName,
            'mime_type'        => $file->getMimeType(),
            'size'             => $file->getSize(),
            'contract_date'    => $request->contract_date,
            'uploaded_by'      => Auth::id(),
        ]);

        return redirect()
            ->route('documents.index')
            ->with('success', 'დოკუმენტი წარმატებით აიტვირთა.');
    }

    public function download(Document $document)
    {
        if (empty($document->file_path)) {
            abort(404, "ფაილის გზა ცარიელია (file_path = NULL)");
        }

        $disk = Storage::disk('public');

        if (!$disk->exists($document->file_path)) {
            abort(404, "ფაილი ვერ მოიძებნა დისკზე: " . $document->file_path);
        }

        return $disk->download($document->file_path, $document->original_name);
    }

    public function requestChange(Document $document)
    {
        $companies     = Company::all();
        $contractTypes = ContractType::all();
        return view('pages.documents.edit',compact('document','companies','contractTypes'));
    }

    public function updateRequestChange(Request $request, Document $document)
    {
        $data = $request->only([
            'contract_type_id',
            'year',
            'contract_date',
            'comment',
            'document_no',
            'amount',
        ]);

        $old = $document->only(array_keys($data));

        if ($old == $data) {
            return response()->json([
                'message' => 'ცვლილება არ დაფიქსირდა'
            ]);
        }

        $document->changes()->create([
            'old_data' => $old,
            'new_data' => $data,
            'requested_by' => auth()->id(),
        ]);

//        $document->update(['status' => 'pending_change']);

        return response()->json([
            'message' => 'ცვლილება გადაგზავნილია დასადასტურებლად'
        ]);
    }

    public function changesShow(DocumentChange $change)
    {
        $change->load(['document', 'requester']);
//        return $change;
        $contractTypes = ContractType::all()->keyBy('id');

        return view('pages.documents.changes.show', compact('change', 'contractTypes'));
    }

    public function print(Document $document)
    {
        $document->load('companies', 'contractType');

        return view('pages.documents.print', compact('document'));
    }

    public function destroy(Document $document)
    {
        $document->delete();

        return back()->with('success', 'დოკუმენტი წაშლილია (შესაძლებელია აღდგენა)');
    }

    public function trash()
    {
        $documents = Document::onlyTrashed()
            ->with(['companies', 'contractType'])
            ->latest('deleted_at')
            ->paginate(20);

        return view('pages.documents.trash', compact('documents'));
    }

    public function restore($id)
    {
        $document = Document::onlyTrashed()->findOrFail($id);
        $document->restore();

        return back()->with('success', 'დოკუმენტი აღდგენილია');
    }

    /**
     * Permanent delete (DB + File)
     */
    public function forceDelete($id)
    {
        $document = Document::onlyTrashed()->findOrFail($id);

        // ფაილის წაშლა storage-დან
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // pivot cleanup
        $document->companies()->detach();

        // change history წაშლა (ან შეგიძლია დატოვო)
        $document->changes()->delete();

        $document->forceDelete();

        return back()->with('success', 'დოკუმენტი სრულად წაიშალა');
    }


}
