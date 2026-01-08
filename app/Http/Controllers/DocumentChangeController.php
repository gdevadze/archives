<?php

namespace App\Http\Controllers;

use App\Models\ContractType;
use App\Models\DocumentChange;
use Illuminate\Http\Request;

class DocumentChangeController extends Controller
{
    public function index()
    {
        $changes = DocumentChange::with([
            'document',
            'requester',
        ])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('pages.documents.changes.index', compact('changes'));
    }

    /**
     * Diff View – ერთი ცვლილება
     */
    public function show(DocumentChange $change)
    {
        $change->load([
            'document',
            'requester',
        ]);

        // ContractType მოდელები (თარგმნებით)
        $contractTypes = ContractType::all()->keyBy('id');

        return view('pages.documents.changes.show', compact(
            'change',
            'contractTypes'
        ));
    }

    /**
     * Approve ცვლილება
     */
    public function approve(DocumentChange $change)
    {
        if ($change->status !== 'pending') {
            return back()->with('error', 'ცვლილება უკვე დამუშავებულია');
        }

        $document = $change->document;

        // ცვლილებების გამოყენება
        $document->update($change->new_data);

        $change->update([
            'status'       => 'approved',
            'approved_by'  => auth()->id(),
        ]);

        $document->update([
            'status' => 'approved',
        ]);

        return redirect()
            ->route('documents.changes.index')
            ->with('success', 'ცვლილება წარმატებით დამტკიცდა');
    }

    /**
     * Reject ცვლილება
     */
    public function reject(DocumentChange $change)
    {
        if ($change->status !== 'pending') {
            return back()->with('error', 'ცვლილება უკვე დამუშავებულია');
        }

        $change->update([
            'status'      => 'rejected',
            'approved_by'=> auth()->id(),
        ]);

        $change->document->update([
            'status' => 'approved',
        ]);

        return redirect()
            ->route('documents.changes.index')
            ->with('info', 'ცვლილება უარყოფილია');
    }

}
