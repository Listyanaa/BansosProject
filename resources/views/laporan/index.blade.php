@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="container-bansos">
  <div class="dashboard-header">
      <div class="header-content">
          <h1 class="header-title">Laporan Data Penerima Bantuan</h1>
          <p class="header-subtitle">Laporan layanan kedaruratan</p>
      </div>
  </div>

    <section class="mb-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <form class="filters-inline" id="laporan-filters">
            <div class="filter-item">
              <label for="kecamatan" class="form-label fw-semibold mb-1">Kecamatan</label>
              <select id="kecamatan" name="kecamatan" class="form-select form-select-sm">
                <option value="">-- Semua Kecamatan --</option>
                @foreach($listKecamatan as $item)
                  <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
              </select>
            </div>

            <div class="filter-item">
              <label for="bulan" class="form-label fw-semibold mb-1">Bulan Layanan</label>
              <input id="bulan" type="month" name="bulan"
                    value="{{ $bulan }}" class="form-control form-control-sm" />
            </div>

            <div class="filter-item">
              <label for="jenis_kelamin" class="form-label fw-semibold mb-1">Jenis Kelamin</label>
              <select id="jenis_kelamin" name="jenis_kelamin" class="form-select form-select-sm">
                <option value="">-- Semua Jenis Kelamin --</option>
                @foreach($listJenisKelamin as $jk)
                  <option value="{{ $jk }}">{{ ucfirst($jk) }}</option>
                @endforeach
              </select>
            </div>

            <div class="filter-item">
              <label for="kelayakan" class="form-label fw-semibold mb-1">Kelayakan</label>
              <select id="kelayakan" name="kelayakan" class="form-select form-select-sm">
                <option value="">-- Semua Kelayakan --</option>
                @foreach($listKelayakan as $k)
                  <option value="{{ $k }}">{{ $k }}</option>
                @endforeach
              </select>
            </div>
          </form>
        </div>
      </div>
    </section>


    {{-- === TABEL LAPORAN === --}}
    <section>
      <div class="dt-wrapper-fix">
        <table id="tabel-laporan" class="display nowrap" style="width:100%">
          <thead>
            <tr>
              <th>No</th>
              <th>NIK</th>
              <th>Nama</th>
              <th>Tempat/ Tgl. Lahir</th>
              <th>Jenis Kelamin</th>
              <th>Agama</th>
              <th>Status Pernikahan</th>
              <th>Status Kepala Keluarga</th>
              <th>Kecamatan</th>
              <th>Kelurahan</th>
              <th>Alamat Lengkap</th>
              <th>Latitude</th>
              <th>Longitude</th>
              <th>Pekerjaan</th>
              <th>Tgl Layanan</th>
              <th>Tgl Meninggal</th>
              <th>Kelayakan</th>
              @foreach($syaratPopup as $s)
                  <th>{{ $s->popup_label ?? $s->teks }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @forelse($data as $item)
              <tr>
                <td></td> 
                <td>{{ $item->nik }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->tempat_tgl_lahir }}</td>
                <td>{{ ucfirst($item->jenis_kelamin) }}</td>
                <td>{{ $item->agama }}</td>
                <td>{{ $item->status_pernikahan }}</td>
                <td>{{ $item->status_kepala_keluarga }}</td>
                <td>{{ $item->kecamatan }}</td>
                <td>{{ $item->kelurahan }}</td>
                <td class="cell-wrap">{{ $item->alamat_lengkap }}</td>
                <td>{{ $item->latitude }}</td>
                <td>{{ $item->longitude }}</td>
                <td>{{ $item->pekerjaan }}</td>
                <td>{{ $item->tanggal_menerima_layanan ? \Carbon\Carbon::parse($item->tanggal_menerima_layanan)->format('d-m-Y') : '-' }}</td>
                <td>{{ $item->tanggal_meninggal ? \Carbon\Carbon::parse($item->tanggal_meninggal)->format('d-m-Y') : '-' }}</td>
                <td><span class="badge {{ $item->kelayakan == 'Layak' ? 'bg-success' : 'bg-danger' }}">
                      {{ $item->kelayakan }}
                    </span>
                </td>
                @foreach($syaratPopup as $s)
                    @php
                        $alias = 'sp_' . strtolower($s->kode);   
                        $val   = $item->$alias ?? null;

                       
                        $kodeRupiah = ['G01']; 
                        $isRupiah   = in_array(strtoupper($s->kode), $kodeRupiah);
                    @endphp

                    <td>
                        @if($s->popup_type === 'number' && !is_null($val))
                            @php
                                $num = is_numeric($val) ? (float) $val : null;
                            @endphp

                            @if(!is_null($num))
                                @if($isRupiah)
                                    Rp {{ number_format($num, 0, ',', '.') }}
                                @else
                                    {{ number_format($num, 0, ',', '.') }}
                                @endif
                            @else
                                {{ $val }}
                            @endif
                        @else
                            {{ $val ?? '-' }}
                        @endif
                    </td>
                @endforeach

              </tr>
            @empty
              <tr><td colspan="{{ 29 + $syaratPopup->count() }}" class="text-center text-muted py-4">
                      Tidak ada data untuk filter ini
                  </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

        {{-- === GRAFIK (tetap) === --}}
    <section class="charts-section mb-4">
        <div class="charts-grid">
            <div class="chart-card card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-2">Jumlah Penerima per Kecamatan</h6>
                    <div class="chart-wrap"><canvas id="chartPenerima"></canvas></div>
                </div>
            </div>
            <div class="chart-card card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-2">Distribusi Berdasarkan Jenis Kelamin</h6>
                    <div class="chart-wrap"><canvas id="pieChart"></canvas></div>
                </div>
            </div>
            <div class="chart-card card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-2">Trend Penerima per Bulan</h6>
                    <div class="chart-wrap"><canvas id="lineChart"></canvas></div>
                </div>
            </div>
            <div class="chart-card card shadow-sm">
              <div class="card-body">
                  <h6 class="card-title mb-2">Distribusi Berdasarkan Kelayakan</h6>
                  <div class="chart-wrap"><canvas id="chartKelayakan"></canvas></div>
              </div>
          </div>
        </div>
    </section>
</div>

{{-- DataTables + Buttons --}}
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>

<!-- Buttons + dependencies -->
<script src="https://cdn.datatables.net/buttons/3.1.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.colVis.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    const filterBulan = $('#bulan').val();   
    if (!filterBulan) return true;           

    const tanggalText = data[14] || '';    
    if (!tanggalText || tanggalText === '-') return false;

    const parts = tanggalText.split('-');   
    if (parts.length !== 3) return true;

    const day   = parts[0];
    const month = parts[1].padStart(2, '0');
    const year  = parts[2];

    const rowKey = `${year}-${month}`;
    return rowKey === filterBulan;
  });

  const table = $('#tabel-laporan').DataTable({
    scrollX: true,
    autoWidth: false,
    responsive: false,
    stateSave: false,
    deferRender: true,
    orderMulti: true,
    pageLength: 10,
    lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'Semua']],
    dom:
    "<'dt-topbar'<'dt-left'B><'dt-right'f>>" +
    "<'dt-table'rt>" +
    "<'dt-bottombar'<'dt-info'i><'dt-paging'p>>",

    buttons: [
      { extend:'pageLength' },
      {
        extend: 'colvis',
        text: 'Pilih Kolom',
        collectionLayout: 'fixed two-column'
      },
      {
        extend: 'copyHtml5',
        text: 'Salin',
        exportOptions: { columns: ':visible', stripHtml: true,
          format: { body: cleanCellForExport }
        }
      },
      {
        extend: 'csvHtml5',
        title: 'laporan-bansos',
        exportOptions: { columns: ':visible', stripHtml: true,
          format: { body: cleanCellForExport }
        }
      },
      {
        extend: 'excelHtml5',
        title: 'laporan-bansos',
        exportOptions: { columns: ':visible', stripHtml: true,
          format: { body: cleanCellForExport }
        }
      },
      {
        extend: 'pdfHtml5',
        title: 'Laporan Data Penerima Bantuan',
        orientation: 'landscape',

        pageSize: 'A3', 

        exportOptions: {
          columns: ':visible',
          stripHtml: true,
          format: { body: cleanCellForExport }
        },

        customize: function (doc) {
          doc.pageMargins = [10, 10, 10, 10];
          doc.defaultStyle.fontSize = 7;

          doc.styles.tableHeader = doc.styles.tableHeader || {};
          doc.styles.tableHeader.fillColor = '#1E3A8A';
          doc.styles.tableHeader.color = '#FFFFFF';
          doc.styles.tableHeader.bold = true;
          doc.styles.tableHeader.fontSize = 7;
          doc.styles.tableHeader.alignment = 'left';

          const tableNode = doc.content.find(n => n.table);
          if (!tableNode) return;

          const body = tableNode.table.body || [];
          if (!body.length) return;

          const cellText = (c) => {
            if (c == null) return '';
            if (typeof c === 'object' && c.text != null) return String(c.text);
            return String(c);
          };

          const headerTexts = body[0].map(cellText);
          const colCount = headerTexts.length;

          const nikIdx    = headerTexts.findIndex(t => /^nik$/i.test(String(t).trim()));
          const alamatIdx = headerTexts.findIndex(t => /alamat/i.test(t));
          const latIdx    = headerTexts.findIndex(t => /latitude/i.test(t));
          const lonIdx    = headerTexts.findIndex(t => /longitude/i.test(t));

          const clamp = (v, min, max) => Math.max(min, Math.min(max, v));
          const widths = new Array(colCount).fill(40);

          const limitRows = Math.min(body.length, 81);

          for (let c = 0; c < colCount; c++) {
            let maxLen = (headerTexts[c] || '').length;

            for (let r = 1; r < limitRows; r++) {
              const t = cellText(body[r][c]).replace(/\s+/g, ' ').trim();
              if (t.length > maxLen) maxLen = t.length;
            }

            if (/^no$/i.test(String(headerTexts[c] || '').trim())) {
              widths[c] = 24;
              continue;
            }

            if (c === nikIdx) {
              widths[c] = clamp(maxLen * 2.2, 90, 120);
              continue;
            }

            if (c === latIdx || c === lonIdx) {
              widths[c] = clamp(maxLen * 2.2, 70, 95);
              continue;
            }

            if (c === alamatIdx) {
              widths[c] = clamp(maxLen * 1.6, 120, 170); 
              continue;
            }

            widths[c] = clamp(maxLen * 2.3, 34, 130);
          }

          tableNode.table.widths = widths;

          for (let r = 0; r < body.length; r++) {
            for (let c = 0; c < colCount; c++) {
              const cell = body[r][c];
              if (cell && typeof cell === 'object') cell.noWrap = false;
            }
          }

          if (nikIdx >= 0) {
            for (let r = 0; r < body.length; r++) {
              const v = body[r][nikIdx];
              if (v && typeof v === 'object') {
                v.noWrap = true;
              } else {
                body[r][nikIdx] = { text: String(v ?? ''), noWrap: true };
              }
            }
          }

          tableNode.layout = {
            hLineWidth: () => 0.6,
            vLineWidth: () => 0.6,
            paddingLeft: () => 2,
            paddingRight: () => 2,
            paddingTop: () => 2,
            paddingBottom: () => 2,
          };
        }

      },
      {
        extend: 'print',
        text: 'Cetak',
        title: 'Laporan Data Penerima Bantuan',
        exportOptions: { columns: ':visible', stripHtml: true,
          format: { body: cleanCellForExport }
        }
      }
    ],
    language: {
      url: 'https://cdn.datatables.net/plug-ins/2.1.7/i18n/id.json',
      searchPlaceholder: 'Cari pada laporan…'
    },
    columnDefs: [
      { targets: 0, searchable: false, orderable: false, width: 56,
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      { targets: 10, createdCell: (td) => td.classList.add('cell-wrap') }
    ]
  });

  function bindSelectFilter(selector, columnIndex) {
    const $el = $(selector);

    function applyFilter(value) {
      if (!value) {
        table.column(columnIndex).search('').draw();
        return;
      }

      const escaped = $.fn.dataTable.util.escapeRegex(value);
      const regex   = '^' + escaped + '$';

      table.column(columnIndex).search(regex, true, false).draw();
    }

    const initial = $el.val();
    if (initial) {
      applyFilter(initial);
    }

    $el.on('change', function () {
      applyFilter(this.value || '');
    });
  }

  bindSelectFilter('#kecamatan', 8);
  bindSelectFilter('#jenis_kelamin', 4);
  bindSelectFilter('#kelayakan', 16);

  const $bulan = $('#bulan');
  if ($bulan.val()) {
    table.draw();    
  }
  $bulan.on('change', function () {
    table.draw();
  });

  function cleanCellForExport (data, row, column, node) {
    if (typeof data !== 'string') return data;

    const tmp = document.createElement('div');
    tmp.innerHTML = data;
    return (tmp.textContent || tmp.innerText || '').trim();
  }
});
</script>

<style>

.dt-wrapper-fix { overflow-x: visible; }

#tabel-laporan td.cell-wrap { white-space: normal; word-break: break-word; }

#tabel-laporan thead th{
  background: linear-gradient(180deg, #1E3A8A, #2563EB) !important;
  color:#fff !important;
}
</style>


{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const centerTextPlugin = {
    id: 'centerText',
    afterDraw(chart) {
      if (chart.config.type !== 'doughnut') return;

      const { ctx, chartArea } = chart;
      if (!chartArea) return;

      const dataset = chart.data.datasets[0];
      if (!dataset || !dataset.data || dataset.data.length === 0) return;

      const data = dataset.data.map(v => Number(v) || 0);
      const total = data.reduce((a, b) => a + b, 0);
      if (!total) return;

      let maxIndex = 0;
      data.forEach((v, i) => { if (v > data[maxIndex]) maxIndex = i; });

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
          grid: { color: 'rgba(0, 0, 0, 0.08)', drawBorder: false },
          ticks: { font: { size: 11 }, color: '#4b5563' }
        },
        y: {
          grid: { display: false },
          ticks: { font: { size: 12 }, color: '#111827' }
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
          ticks: { font: { size: 11 }, color: '#4b5563' }
        },
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1, font: { size: 11 }, color: '#4b5563' },
          grid: { color: 'rgba(0, 0, 0, 0.06)', drawBorder: false }
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
        legend: { ...commonOptions.plugins.legend, position: 'right' }
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
        legend: { ...commonOptions.plugins.legend, position: 'right' }
      }
    }
  });
</script>

<style>
.dashboard-header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    margin-bottom: 18px;
    padding-bottom: 18px;
    border-bottom: 2px solid #1d4ed8;
}
.header-content{ flex:1; }
.header-title{
    font-size: 1.4rem;
    font-weight: 700;
    color:#111827;
    margin:0 0 6px 0;
    letter-spacing:-0.025em;
}
.header-subtitle{
    font-size: 0.9rem;
    color:#6b7280;
    margin:0;
    font-weight: 500;
}
.header-date .date{
    font-size: 0.85rem;
    color:#374151;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 8px 14px;
    border-radius: 20px;
    border: 1px solid #d1d5db;
    font-weight: 600;
    box-shadow: 0 1px 3px rgba(0,0,0,.1);
}

.container-bansos{
  background:#fff;
  padding:22px;
  border-radius:14px;
  box-shadow:0 6px 14px rgba(0,0,0,.08);
}
h2{ font-weight:700; letter-spacing:.2px; }

.container-bansos > section{ margin-bottom:28px; }
@media (min-width:992px){
  .container-bansos > section{ margin-bottom:36px; }
}

.charts-section{
  max-width:1100px;
  margin-inline:auto;
}

.charts-grid{
  display:grid;
  grid-template-columns: 1fr;
  gap:16px;
  grid-auto-rows: 260px;   
}
@media (min-width:768px){
  .charts-grid{
    grid-template-columns: repeat(2, minmax(0,1fr));
    grid-auto-rows: 260px; 
  }
}

.chart-card{
  border:1px solid rgba(0,0,0,.06);
  border-radius:14px;
  display:flex;
  flex-direction:column;
  background:#fff;
  height:100%;   
}
.card-title{ font-weight:600; margin-bottom:.5rem; }

.chart-card .card-body{
  display:flex;
  flex-direction:column;
  height:100%;
  padding:16px 18px;
}
@media (min-width:768px){
  .chart-card .card-body{ padding:18px 20px; }
}

.chart-wrap{
  flex:1;
  min-height:0;
}

.filters-inline{
  display:grid;
  gap:14px;
  grid-template-columns: 1fr;    
}

@media (min-width:576px){
  .filters-inline{
    grid-template-columns: repeat(2, minmax(0,1fr));
  }
}

@media (min-width:992px){
  .filters-inline{
    grid-template-columns: repeat(4, minmax(0,1fr)) auto;
    align-items:end;
  }
}

.filters-inline .filter-item{
  display:flex;
  flex-direction:column;
  gap:6px;
  min-width:0;
}

.filters-inline .actions{
  justify-self:end;
  display:flex;
  gap:8px;
  align-items:center;
  flex-wrap:wrap;
}

@media (max-width:991.98px){
  .filters-inline .actions{
    grid-column:1 / -1;
    justify-self:stretch;
  }
  .filters-inline .actions .btn,
  .filters-inline .actions .btn-reset{
    flex:1 1 180px;    
  }
}

.filters-inline .form-select,
.filters-inline .form-control,
.filters-inline .btn,
.filters-inline .btn-reset{
  height:36px;
  line-height:1.2;
  border-radius:10px;
}

.card .form-label{ font-size:.92rem; color:#1f2a44; }

.btn-primary {
  background: #2563eb;
  color: #fff;
  border: 1px solid #2563eb;
  border-radius: 10px;
  font-weight: 600;
  padding: 6px 16px;
  height: 36px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
  transition: all 0.2s ease;
}
.btn-primary:hover,
.btn-primary:focus {
  background: #1d4ed8;
  border-color: #1d4ed8;
  transform: translateY(-1px);
  box-shadow: 0 3px 6px rgba(0,0,0,0.12);
}

.btn-reset {
  background: #e0e7ff;
  color: #1e3a8a;
  border: 1px solid #c7d2fe;
  border-radius: 10px;
  font-weight: 600;
  padding: 6px 16px;
  height: 36px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
  transition: all 0.2s ease;
  text-decoration: none;
}
.btn-reset:hover,
.btn-reset:focus {
  background: #c7d2fe;
  color: #111827;
  border-color: #a5b4fc;
  transform: translateY(-1px);
  box-shadow: 0 3px 6px rgba(0,0,0,0.12);
}

.data-table thead th{
  background: linear-gradient(180deg, #1E3A8A, #2563EB) !important;
  color: #ffffff !important;
  border-bottom: 1px solid rgba(255,255,255,.18) !important;
}

table.dataTable thead th,
table.dataTable thead td{
  background: linear-gradient(180deg, #1E3A8A, #2563EB) !important;
  color: #ffffff !important;
  border-bottom: 1px solid rgba(255,255,255,.18) !important;
}

.dt-topbar{
  display:grid !important;
  grid-template-columns: 1fr auto;   
  align-items:center;               
  gap:12px;
  margin-bottom:10px;
}

.dt-topbar .dt-left{
  min-width:0;                     
  overflow-x:auto;                  
  overflow-y:hidden;
  -webkit-overflow-scrolling:touch;
  white-space:nowrap;              
}

.dt-topbar .dt-left .dt-buttons{
  float:none !important;
  display:inline-flex !important;   
  align-items:center;
  gap:8px;
  flex-wrap:nowrap !important;      
  white-space:nowrap;
}

.dt-topbar .dt-left .dt-button{
  border-radius:10px !important;
  padding:6px 12px !important;
  border:1px solid rgba(0,0,0,.12) !important;
  white-space:nowrap !important;
}

.dt-topbar .dt-left::-webkit-scrollbar{ height:6px; }
.dt-topbar .dt-left::-webkit-scrollbar-thumb{ background:rgba(0,0,0,.15); border-radius:99px; }
.dt-topbar .dt-left::-webkit-scrollbar-track{ background:transparent; }

.dt-topbar .dt-right{
  justify-self:end;
  white-space:nowrap;
}

.dt-topbar .dt-right .dataTables_filter label,
.dt-topbar .dt-right .dt-search{
  font-size:0 !important;
  margin:0 !important;
  display:flex;
  align-items:center;
}

.dt-topbar .dt-right input[type="search"],
.dt-topbar .dt-right .dt-input{
  height:36px;
  border-radius:10px;
  padding:6px 10px;

  width:240px;          
  max-width:240px;
}

@media (max-width:1200px){
  .dt-topbar .dt-right input[type="search"],
  .dt-topbar .dt-right .dt-input{
    width:200px;
    max-width:200px;
  }
}

@media (max-width:992px){
  .dt-topbar .dt-right input[type="search"],
  .dt-topbar .dt-right .dt-input{
    width:180px;
    max-width:180px;
  }
}

#tabel-laporan th:nth-child(2),
#tabel-laporan td:nth-child(2){
  white-space: nowrap !important;
}
</style>

@endsection
