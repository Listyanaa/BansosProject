@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="header-title">Dashboard Bantuan Sosial</h1>
            <p class="header-subtitle">Ringkasan data penerima bantuan</p>
        </div>
        <div class="header-date">
            <span class="date">{{ date('d M Y') }}</span>
        </div>
    </div>

    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Total Penerima</span>
                    <span class="stat-value">{{ $count['total'] }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon layak">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Penerima Layak</span>
                    <span class="stat-value text-success">{{ $count['kedaruratan'] }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon tidak-layak">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Tidak Layak</span>
                    <span class="stat-value text-danger">{{ $count['tidak_layak'] ?? 0 }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon persentase">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                        <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Persentase Layak</span>
                    <span class="stat-value text-primary">{{ $count['persen_layak'] ?? 0 }}%</span>
                </div>
            </div>
        </div>
    </section>

    {{-- === GRAFIK === --}}
    <section class="charts-section">
        <div class="section-header">
            <h3 class="section-title">Visualisasi Data</h3>
            <p class="section-subtitle">Analisis distribusi penerima bantuan</p>
        </div>
        
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <h4 class="chart-title">Penerima per Kecamatan</h4>
                </div>
                <div class="chart-body">
                    <canvas id="chartPenerima"></canvas>
                </div>
            </div>
            
            <div class="chart-card">
                <div class="chart-header">
                    <h4 class="chart-title">Trend per Bulan</h4>
                </div>
                <div class="chart-body">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
            
            <div class="chart-card">
                <div class="chart-header">
                    <h4 class="chart-title">Jenis Kelamin</h4>
                </div>
                <div class="chart-body">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
            
            <div class="chart-card">
                <div class="chart-header">
                    <h4 class="chart-title">Distribusi Kelayakan</h4>
                </div>
                <div class="chart-body">
                    <canvas id="chartKelayakan"></canvas>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const centerTextPlugin = {
        id: 'centerText',
        afterDraw(chart, args, pluginOptions) {
            if (chart.config.type !== 'doughnut') return;

            const { ctx, chartArea } = chart;
            if (!chartArea) return;

            const dataset = chart.data.datasets[0];
            if (!dataset || !dataset.data || dataset.data.length === 0) return;

            const data = dataset.data;
            const total = data.reduce((a, b) => a + b, 0);
            if (!total) return;

            let maxIndex = 0;
            data.forEach((v, i) => {
                if (v > data[maxIndex]) maxIndex = i;
            });

            const label = chart.data.labels[maxIndex];
            const value = data[maxIndex];
            const percent = Math.round((value / total) * 100);

            const centerX = (chartArea.left + chartArea.right) / 2;
            const centerY = (chartArea.top + chartArea.bottom) / 2;

            ctx.save();
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            ctx.font = '600 20px "Inter", sans-serif';
            ctx.fillStyle = '#111827';
            ctx.fillText(percent + '%', centerX, centerY - 8);

            ctx.font = '500 12px "Inter", sans-serif';
            ctx.fillStyle = '#4b5563';
            ctx.fillText(label, centerX, centerY + 14);
            ctx.restore();
        }
    };

    Chart.register(centerTextPlugin);

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { 
                position: 'bottom', 
                labels: { 
                    boxWidth: 12, 
                    boxHeight: 12, 
                    usePointStyle: true, 
                    padding: 12,
                    font: { 
                        size: 12,
                        family: '"Inter", sans-serif',
                        weight: '500'
                    }
                } 
            },
            tooltip: { 
                mode: 'index', 
                intersect: false,
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                titleColor: '#f9fafb',
                bodyColor: '#f3f4f6',
                borderColor: '#374151',
                borderWidth: 1,
                cornerRadius: 6,
                padding: 10,
                titleFont: { size: 12 },
                bodyFont: { size: 12 }
            }
        },
        layout: { 
            padding: { top: 10, right: 10, bottom: 10, left: 10 } 
        }
    };

    new Chart(document.getElementById('chartPenerima').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData->keys()) !!},
            datasets: [{
                label: 'Jumlah Penerima',
                data: {!! json_encode($chartData->values()) !!},
                backgroundColor: 'rgba(29, 78, 216, 0.9)',
                borderColor: 'rgba(30, 58, 138, 1)',
                borderWidth: 1.5,
                borderRadius: 6,
                maxBarThickness: 22
            }]
        },
        options: {
            ...commonOptions,
            indexAxis: 'y',
            scales: {
                x: { 
                    beginAtZero: true,
                    grid: { 
                        color: 'rgba(0, 0, 0, 0.08)',
                        drawBorder: false
                    },
                    ticks: {
                        font: { size: 11 },
                        color: '#4b5563'
                    }
                },
                y: { 
                    grid: { display: false },
                    ticks: {
                        font: { size: 12 },
                        color: '#111827'
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('lineChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: {!! json_encode($lineData->keys()) !!},
            datasets: [{
                label: 'Jumlah Penerima',
                data: {!! json_encode($lineData->values()) !!},
                borderColor: 'rgba(124, 58, 237, 1)',
                backgroundColor: 'rgba(124, 58, 237, 0.15)',
                borderWidth: 2.5,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: 'rgba(255, 255, 255, 1)',
                pointBorderColor: 'rgba(124, 58, 237, 1)',
                pointBorderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                x: { 
                    grid: { display: false },
                    ticks: {
                        font: { size: 11 },
                        color: '#4b5563'
                    }
                },
                y: { 
                    beginAtZero: true, 
                    ticks: { 
                        stepSize: 1,
                        font: { size: 11 },
                        color: '#4b5563'
                    }, 
                    grid: { 
                        color: 'rgba(0, 0, 0, 0.06)',
                        drawBorder: false
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('pieChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($pieData->keys()) !!},
            datasets: [{
                data: {!! json_encode($pieData->values()) !!},
                backgroundColor: [
                    'rgba(59, 130, 246, 1)',
                    'rgba(219, 39, 119, 1)'
                ],
                borderColor: [
                    'rgba(29, 78, 216, 1)',
                    'rgba(190, 18, 60, 1)'
                ],
                borderWidth: 2,
                hoverOffset: 8
            }]
        },
        options: { 
            ...commonOptions, 
            cutout: '60%',
            plugins: {
                ...commonOptions.plugins,
                legend: {
                    ...commonOptions.plugins.legend,
                    position: 'right'
                }
            }
        }
    });


    const rawKelayakanLabels = {!! json_encode($kelayakanData->keys()->values()) !!};
    const rawKelayakanValues = {!! json_encode($kelayakanData->values()) !!};

    const normKelayakanLabel = (l) => {
    const s = (l === null || l === undefined) ? '' : String(l).trim();
    return s === '' ? 'Belum Diperiksa' : s; 
    };

    const agg = {};
    rawKelayakanLabels.forEach((l, i) => {
    const key = normKelayakanLabel(l);
    const val = Number(rawKelayakanValues[i] ?? 0);
    agg[key] = (agg[key] ?? 0) + val;
    });

    const kelayakanLabels = Object.keys(agg);
    const kelayakanValues = Object.values(agg);

    const kelayakanColorMap = {
    'Layak': {
        bg: 'rgba(34, 197, 94, 1)',
        border: 'rgba(21, 128, 61, 1)'
    },
    'Tidak Layak': {
        bg: 'rgba(239, 68, 68, 1)',
        border: 'rgba(185, 28, 28, 1)'
    },
    'Belum Diperiksa': {
        bg: 'rgba(148, 163, 184, 1)',
        border: 'rgba(100, 116, 139, 1)'
    }
    };

    new Chart(document.getElementById('chartKelayakan').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: kelayakanLabels,
        datasets: [{
        data: kelayakanValues,
        backgroundColor: kelayakanLabels.map(l => (kelayakanColorMap[l]?.bg ?? 'rgba(156, 163, 175, 1)')),
        borderColor: kelayakanLabels.map(l => (kelayakanColorMap[l]?.border ?? 'rgba(107, 114, 128, 1)')),
        borderWidth: 2,
        hoverOffset: 8
        }]
    },
    options: {
        ...commonOptions,
        cutout: '60%',
        plugins: {
        ...commonOptions.plugins,
        legend: {
            ...commonOptions.plugins.legend,
            position: 'right'
        }
        }
    }
    });

</script>
<style>

.dashboard-container {
    background: #ffffff;
    padding: 22px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 28px;
    padding-bottom: 18px;
    border-bottom: 2px solid #1d4ed8;
}

.header-content {
    flex: 1;
}

.header-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 6px 0;
    letter-spacing: -0.025em;
}

.header-subtitle {
    font-size: 0.9rem;
    color: #6b7280;
    margin: 0;
    font-weight: 500;
}

.header-date .date {
    font-size: 0.85rem;
    color: #374151;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 8px 14px;
    border-radius: 20px;
    border: 1px solid #d1d5db;
    font-weight: 600;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.stats-section {
    margin-bottom: 32px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
    margin-bottom: 8px;
}

@media (min-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
    }
}

.stat-card {
    background: linear-gradient(145deg, #ffffff 0%, #f9fafb 100%);
    border-radius: 12px;
    padding: 18px;
    border: 2px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: all 0.25s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.stat-card:hover {
    border-color: #d1d5db;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.stat-icon.total {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #ffffff;
    border: 2px solid #1d4ed8;
}

.stat-icon.layak {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff;
    border: 2px solid #059669;
}

.stat-icon.tidak-layak {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #ffffff;
    border: 2px solid #dc2626;
}

.stat-icon.persentase {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: #ffffff;
    border: 2px solid #7c3aed;
}

.stat-info {
    flex: 1;
}

.stat-label {
    display: block;
    font-size: 0.75rem;
    font-weight: 700;
    color: #4b5563;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 6px;
}

.stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 800;
    color: #111827;
    line-height: 1.2;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.text-success { 
    color: #059669 !important; 
    text-shadow: 0 1px 2px rgba(5, 150, 105, 0.2);
}
.text-danger { 
    color: #dc2626 !important; 
    text-shadow: 0 1px 2px rgba(220, 38, 38, 0.2);
}
.text-primary { 
    color: #1d4ed8 !important; 
    text-shadow: 0 1px 2px rgba(29, 78, 216, 0.2);
}

.charts-section {
    margin-top: 28px;
}

.section-header {
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e5e7eb;
}

.section-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 6px 0;
}

.section-subtitle {
    font-size: 0.9rem;
    color: #6b7280;
    margin: 0;
    font-weight: 500;
}

.charts-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 18px;
}

@media (min-width: 768px) {
    .charts-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.chart-card {
    background: #ffffff;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.06);
    transition: all 0.25s ease;
}

.chart-card:hover {
    border-color: #d1d5db;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
}

.chart-header {
    padding: 16px 20px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 2px solid #e5e7eb;
}

.chart-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
    letter-spacing: -0.01em;
}

.chart-body {
    padding: 16px;
    height: 220px;
    position: relative;
    background: #ffffff;
}

canvas {
    max-width: 100% !important;
    height: 100% !important;
}

::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
}
</style>
@endsection