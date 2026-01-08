@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <h4 class="mb-4">დასადასტურებელი ცვლილებები</h4>

            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>დოკუმენტი</th>
                            <th>ინიციატორი</th>
                            <th>თარიღი</th>
                            <th class="text-end">მოქმედება</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($changes as $change)
                            <tr>
                                <td>
                                    <strong>{{ $change->document->title }}</strong><br>
                                    <small class="text-muted">
                                        {{ $change->document->document_no ?? '' }}
                                    </small>
                                </td>

                                <td>
                                    {{ $change->requester->full_name }}
                                </td>

                                <td>
                                    {{ $change->created_at->format('d.m.Y H:i') }}
                                </td>

                                <td class="text-end">
                                    <a href="{{ route('document.changes.show', $change->id) }}"
                                       class="btn btn-sm btn-primary">
                                        ნახვა
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    დასადასტურებელი ცვლილებები არ არის
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                {{ $changes->links() }}
            </div>

        </div>
    </div>
@endsection
