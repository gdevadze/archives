@extends('layouts.app')

@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <h3 class="fw-bold mb-4">📊 დოკუმენტების რეპორტი</h3>

            {{-- FILTER --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">

                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small">თვე</label>
                            <select name="month" class="form-select">
                                @foreach($months as $num => $m)
                                    <option value="{{ $num }}" @selected($month == $num)>
                                        {{ $m }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small">წელი</label>
                            <select name="year" class="form-select">
                                @foreach($years as $y)
                                    <option value="{{ $y }}" @selected($year == $y)>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100">ძიება</button>
                        </div>

                        {{-- EXCEL EXPORT --}}
                        <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ route('reports.documents.export', [
                        'month' => $month,
                        'year' => $year
                    ]) }}"
                               class="btn btn-success w-100">
                                <i class="fas fa-file-excel me-1"></i>
                                Excel Export
                            </a>
                        </div>
                    </form>

                </div>
            </div>

            {{-- REPORT TABLE --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        რეპორტი - {{ $start->format('F Y') }}
                    </h5>
                </div>

                <div class="card-body p-0">

                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>კომპანია</th>
                            <th>ხელშეკრულების ტიპი</th>
                            <th class="text-center">ატვირთული დოკუმენტის რაოდენობა</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($report as $row)
                            <tr>
                                <td>{{ $row->company->company_name }}</td>
                                <td>{{ $row->contractType->contract_type_name }}</td>
                                <td class="text-center fw-bold">{{ $row->total }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    No uploads for this period.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="fw-bold mb-0">📊 სტატისტიკა ({{ $start->format('F Y') }})</h5>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="companyChart" height="250"></canvas>
                        </div>
                        <div class="col-md-6">
                            <canvas id="typeChart" height="250"></canvas>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
        // ============================
        // Company Upload Bar Chart
        // ============================
        const companyCtx = document.getElementById('companyChart').getContext('2d');

        new Chart(companyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(
                $companies->keys()->map(fn($id) => \App\Models\Company::find($id)->company_name)
            ) !!},
                datasets: [{
                    label: 'Uploads',
                    data: {!! json_encode($companies->values()) !!},
                    backgroundColor: '#4e73df',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {display: false}
                },
                scales: {
                    y: {beginAtZero: true}
                }
            }
        });


        // ============================
        // Contract Type Pie Chart
        // ============================
        const typeCtx = document.getElementById('typeChart').getContext('2d');

        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(
                $types->keys()->map(fn($id) => \App\Models\ContractType::find($id)->contract_type_name)
            ) !!},
                datasets: [{
                    data: {!! json_encode($types->values()) !!},
                    backgroundColor: [
                        '#1cc88a', // green
                        '#36b9cc', // teal
                        '#f6c23e', // yellow
                        '#e74a3b', // red
                        '#4e73df', // blue
                        '#858796', // gray
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {position: 'bottom'}
                }
            }
        });

    </script>
@endsection
