@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <h4 class="mb-4">
                ცვლილებების დადასტურება
            </h4>

            <div class="card shadow-sm p-4">

                {{-- Header info --}}
                <div class="mb-4">
                    <strong>დოკუმენტი:</strong> {{ $change->document->document_no }} <br>
                    <strong>ცვლილების ინიციატორი:</strong> {{ $change->requester->full_name }} <br>
                    <strong>თარიღი:</strong> {{ $change->created_at->format('d.m.Y H:i') }}
                </div>

                @php
                    $labels = [
                        'document_no'       => 'დოკუმენტის ნომერი',
                        'contract_type_id'  => 'ხელშეკრულების ტიპი',
                        'year'              => 'წელი',
                        'contract_date'     => 'ხელშეკრულების თარიღი',
                        'amount'            => 'თანხა',
                        'comment'           => 'კომენტარი',
                    ];

                    $format = function ($field, $value) use ($contractTypes) {

                        if ($field === 'contract_type_id') {
                            return $contractTypes[$value]->contract_type_name ?? '-';
                        }

                        if ($field === 'contract_date' && $value) {
                            return \Carbon\Carbon::parse($value)->format('d.m.Y');
                        }

                        if ($value === null || $value === '') {
                            return '-';
                        }

                        return $value;
                    };
                @endphp

                {{-- Diff blocks --}}
                <div class="row g-3">
                    @foreach($change->new_data as $field => $newValue)

                        @continue(!isset($labels[$field]))

                        @php
                            $oldValue = $change->old_data[$field] ?? null;
                        @endphp

                        <div class="col-12">
                            <div class="border rounded p-3">

                                <div class="fw-bold mb-2">
                                    {{ $labels[$field] }}
                                </div>

                                <div class="row">
                                    {{-- OLD --}}
                                    <div class="col-md-6">
                                        <div class="p-3 rounded bg-danger bg-opacity-10 border border-danger">
                                            <div class="text-danger fw-semibold mb-1">
                                                ძველი მნიშვნელობა
                                            </div>
                                            <div>
                                                {{ $format($field, $oldValue) }}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- NEW --}}
                                    <div class="col-md-6">
                                        <div class="p-3 rounded bg-success bg-opacity-10 border border-success">
                                            <div class="text-success fw-semibold mb-1">
                                                ახალი მნიშვნელობა
                                            </div>
                                            <div>
                                                {{ $format($field, $newValue) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    @endforeach
                </div>

                {{-- Actions --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <form method="POST" action="{{ route('document.changes.reject', $change->id) }}">
                        @csrf
                        <button class="btn btn-outline-danger">
                            უარყოფა
                        </button>
                    </form>

                    <form method="POST" action="{{ route('document.changes.approve', $change->id) }}">
                        @csrf
                        <button class="btn btn-success">
                            დადასტურება
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
