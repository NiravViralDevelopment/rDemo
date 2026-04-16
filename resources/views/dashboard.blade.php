@extends('layouts.admin.app')
@section('content')
    <style>
        .dashboard {
            --erp-bg: #f8fafc;
            --erp-card-border: #e2e8f0;
            --erp-title: #0f172a;
            --erp-muted: #64748b;
            --erp-indigo: #4f46e5;
            --erp-emerald: #059669;
            --erp-amber: #d97706;
            --erp-sky: #0284c7;
            --erp-violet: #7c3aed;
            --erp-rose: #e11d48;
        }

        .dashboard .erp-header {
            border: 1px solid var(--erp-card-border);
            border-left: 4px solid var(--erp-indigo);
            background: linear-gradient(90deg, #eef2ff 0%, #f8fafc 100%);
            border-radius: 12px;
            padding: 0.8rem 1rem;
        }

        .dashboard .erp-subtitle {
            font-size: 0.86rem;
            color: var(--erp-muted);
            margin: 0;
        }

        .dashboard .erp-title {
            margin: 0 0 4px;
            color: var(--erp-title);
            font-weight: 700;
            font-size: 1.05rem;
        }

        .dashboard .kpi-card {
            border: 1px solid var(--erp-card-border);
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
        }

        .dashboard .kpi-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 0.8rem 0.95rem;
        }

        .dashboard .kpi-label {
            margin: 0;
            font-size: 0.78rem;
            color: var(--erp-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .dashboard .kpi-value {
            margin: 2px 0 0;
            font-size: 1.45rem;
            font-weight: 700;
            line-height: 1.15;
            color: var(--erp-title);
        }

        .dashboard .kpi-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .dashboard .erp-chart-card {
            border: 1px solid var(--erp-card-border);
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
        }

        .dashboard .erp-chart-title {
            margin: 0;
            color: var(--erp-title);
            font-weight: 700;
            font-size: 0.95rem;
        }

        .dashboard .erp-chart-subtitle {
            margin: 2px 0 0;
            color: var(--erp-muted);
            font-size: 0.8rem;
        }

        .dashboard .erp-chart-head {
            padding: 0.95rem 1rem 0.1rem;
        }

        .dashboard .erp-chart-wrap {
            padding: 0.4rem 0.75rem 0.75rem;
        }

        .dashboard .chart-sm {
            height: 240px;
        }

        .dashboard .bg-indigo { background: linear-gradient(135deg, #4f46e5, #4338ca); }
        .dashboard .bg-emerald { background: linear-gradient(135deg, #10b981, #059669); }
        .dashboard .bg-amber { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .dashboard .bg-sky { background: linear-gradient(135deg, #0ea5e9, #0284c7); }
        .dashboard .bg-violet { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .dashboard .bg-rose { background: linear-gradient(135deg, #f43f5e, #e11d48); }

        @media (max-width: 768px) {
            .dashboard .chart-sm {
                height: 220px;
            }
        }
    </style>
    <section class="section dashboard">
        @php
            $isProjectUser = !empty($user->project_id);
        @endphp
        <div class="row">
            <div class="col-lg-12">
                <div class="row diffBxRw g-3">
                    <div class="col-12">
                        <div class="erp-header">
                            <h6 class="erp-title">Operations Dashboard</h6>
                            <p class="erp-subtitle mb-0"><strong>Current Project:</strong> {{ $user->project?->name ?? 'GLOBAL (All Projects)' }}</p>
                        </div>
                    </div>

                    @unless($isProjectUser)
                        <div class="col-md-4 col-sm-6">
                            <div class="card kpi-card">
                                <div class="kpi-body">
                                    <div>
                                        <p class="kpi-label">Users</p>
                                        <h3 class="kpi-value">{{ $totalUsers }}</h3>
                                    </div>
                                    <span class="kpi-icon bg-indigo">U</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <div class="card kpi-card">
                                <div class="kpi-body">
                                    <div>
                                        <p class="kpi-label">Roles</p>
                                        <h3 class="kpi-value">{{ $totalRoles }}</h3>
                                    </div>
                                    <span class="kpi-icon bg-emerald">R</span>
                                </div>
                            </div>
                        </div>
                    @endunless

                    <div class="col-md-4 col-sm-6">
                        <div class="card kpi-card">
                            <div class="kpi-body">
                                <div>
                                    <p class="kpi-label">Houses</p>
                                    <h3 class="kpi-value">{{ $totalHouses }}</h3>
                                </div>
                                <span class="kpi-icon bg-amber">H</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="card kpi-card">
                            <div class="kpi-body">
                                <div>
                                    <p class="kpi-label">Shops</p>
                                    <h3 class="kpi-value">{{ $totalShops }}</h3>
                                </div>
                                <span class="kpi-icon bg-sky">S</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="card kpi-card">
                            <div class="kpi-body">
                                <div>
                                    <p class="kpi-label">Bookings</p>
                                    <h3 class="kpi-value">{{ $totalBookings }}</h3>
                                </div>
                                <span class="kpi-icon bg-violet">B</span>
                            </div>
                        </div>
                    </div>

                    @unless($isProjectUser)
                        <div class="col-md-4 col-sm-6">
                            <div class="card kpi-card">
                                <div class="kpi-body">
                                    <div>
                                        <p class="kpi-label">Total Properties</p>
                                        <h3 class="kpi-value">{{ $totalHouses + $totalShops }}</h3>
                                    </div>
                                    <span class="kpi-icon bg-rose">P</span>
                                </div>
                            </div>
                        </div>
                    @endunless

                    <div class="col-lg-6">
                        <div class="card erp-chart-card">
                            <div class="erp-chart-head">
                                <h5 class="erp-chart-title">Core Metrics Overview</h5>
                                <p class="erp-chart-subtitle">
                                    {{ $isProjectUser ? 'Compare house, shop and booking volume' : 'Compare user, role and booking volume' }}
                                </p>
                            </div>
                            <div class="erp-chart-wrap">
                                <div class="chart-sm">
                                    <canvas id="metricsBarChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card erp-chart-card">
                            <div class="erp-chart-head">
                                <h5 class="erp-chart-title">Property Split</h5>
                                <p class="erp-chart-subtitle">Houses vs shops distribution</p>
                            </div>
                            <div class="erp-chart-wrap">
                                <div class="chart-sm">
                                    <canvas id="propertyDonutChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            const dashboardPalette = [
                '#4f46e5', '#10b981', '#f59e0b', '#0ea5e9', '#8b5cf6',
                '#ef4444', '#14b8a6', '#f97316', '#6366f1', '#22c55e',
                '#eab308', '#06b6d4', '#a855f7'
            ];
            const chartGridColor = '#e2e8f0';

            const metricsBarCanvas = document.getElementById('metricsBarChart');
            const isProjectUser = @json($isProjectUser);
            if (metricsBarCanvas) {
                new Chart(metricsBarCanvas, {
                    type: 'bar',
                    data: {
                        labels: isProjectUser ? ['Houses', 'Shops', 'Bookings'] : ['Users', 'Roles', 'Bookings'],
                        datasets: [{
                            label: 'Count',
                            data: isProjectUser
                                ? [{{ (int) $totalHouses }}, {{ (int) $totalShops }}, {{ (int) $totalBookings }}]
                                : [{{ (int) $totalUsers }}, {{ (int) $totalRoles }}, {{ (int) $totalBookings }}],
                            backgroundColor: isProjectUser
                                ? [dashboardPalette[2], dashboardPalette[3], dashboardPalette[4]]
                                : [dashboardPalette[0], dashboardPalette[1], dashboardPalette[4]],
                            borderColor: isProjectUser
                                ? [dashboardPalette[2], dashboardPalette[3], dashboardPalette[4]]
                                : [dashboardPalette[0], dashboardPalette[1], dashboardPalette[4]],
                            borderWidth: 1.2,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: chartGridColor
                                },
                                ticks: {
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            const propertyDonutCanvas = document.getElementById('propertyDonutChart');
            if (propertyDonutCanvas) {
                new Chart(propertyDonutCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: ['Houses', 'Shops'],
                        datasets: [{
                            data: [{{ (int) $totalHouses }}, {{ (int) $totalShops }}],
                            backgroundColor: [dashboardPalette[2], dashboardPalette[3]],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '62%',
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

        </script>
    @endpush
@endsection