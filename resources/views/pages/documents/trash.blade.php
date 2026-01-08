@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>წაშლილი დოკუმენტები</h4>

                <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                    ← დოკუმენტების სია
                </a>
            </div>

            <div class="card shadow-sm">

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>დოკუმენტის №</th>
                            <th>ტიპი</th>
                            <th>კომპანიები</th>
                            <th>წაშლის თარიღი</th>
                            <th class="text-end">მოქმედება</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($documents as $document)
                            <tr>
                                <td>
                                    <strong>{{ $document->title }}</strong><br>
                                    <small class="text-muted">
                                        {{ $document->document_no }}
                                    </small>
                                </td>

                                <td>
                                    {{ $document->contractType->contract_type_name ?? '-' }}
                                </td>

                                <td>
                                    @foreach($document->companies as $company)
                                        <span class="badge bg-secondary">
                                {{ $company->company_name }}
                            </span>
                                    @endforeach
                                </td>

                                <td>
                                    {{ $document->deleted_at->format('d.m.Y H:i') }}
                                </td>

                                <td class="text-end">

                                    {{-- Restore --}}
                                    <form method="POST"
                                          action="{{ route('documents.restore', $document->id) }}"
                                          class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">
                                            აღდგენა
                                        </button>
                                    </form>

                                    {{-- Force delete --}}
                                    <form method="POST"
                                          action="{{ route('documents.forceDelete', $document->id) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('დოკუმენტი სრულად წაიშლება და აღდგენა შეუძლებელი იქნება!')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            სრულად წაშლა
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    წაშლილი დოკუმენტები არ მოიძებნა
                                </td>
                            </tr>
                        @endforelse
                        </tbody>

                    </table>
                </div>

            </div>

            <div class="mt-3">
                {{ $documents->links() }}
            </div>

        </div>
    </div>
@endsection
