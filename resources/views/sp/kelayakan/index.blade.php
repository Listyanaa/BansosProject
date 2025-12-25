@extends('layouts.app')

@section('title', 'Cek Kelayakan')

@section('content')
<div class="sp-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="header-title">Daftar Penerima Belum Dicek</h1>
            <p class="header-subtitle">Pilih penerima dari tabel untuk memulai cek kelayakan</p>
        </div>
    </div>

    <div class="sp-actions">
        <a href="{{ route('sp.kelayakan.history') }}" class="sp-btn-back">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>Lihat Riwayat</span>
        </a>
    </div>

    @if($penerima->isEmpty())
        <div class="empty-state">
            — Semua penerima sudah pernah dicek kelayakannya —
        </div>
    @else
        <div class="table-container">
            <table id="tabel-kelayakan" class="display nowrap data-table" style="width:100%">
                <thead>
                    <tr>
                        <th style="width:220px;">NIK</th>
                        <th>Nama</th>
                        <th style="width:160px;" class="no-sort">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penerima as $p)
                        <tr>
                            <td data-order="{{ $p->nik }}">
                                <span class="nik-mono nik-display" data-nik="{{ $p->nik }}">{{ $p->nik }}</span>
                            </td>
                            <td>{{ $p->nama }}</td>
                            <td style="white-space:nowrap;">
                                <form method="post" action="{{ route('sp.kelayakan.start') }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="penerima_id" value="{{ $p->id }}">
                                    <button type="submit" class="btn-mulai">
                                        <i class="fa-solid fa-play"></i> Mulai
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

{{-- Kalau layout kamu SUDAH ada jQuery, hapus script jQuery di bawah ini --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>

<style>

.dashboard-header{
    display:flex;
    justify-content:flex-start;
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

/* Layout container (seragam gaya manajemen data) */
.sp-container{
    background:#fff;
    padding:22px;
    border-radius:14px;
    box-shadow:0 6px 14px rgba(0,0,0,.08);
}

/* Actions */
.sp-actions{
    display:flex;
    justify-content:flex-end;
    margin: 10px 0 10px; /* lebih rapat */
}
.sp-btn-back{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:10px 14px;
    border-radius:12px;
    border:1px solid #e5e7eb;
    background:#ffffff;
    color:#111827;
    font-weight:700;
    text-decoration:none;
    box-shadow: 0 6px 14px rgba(2,6,23,.06);
    transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
}
.sp-btn-back:hover{
    background:#f8fafc;
    box-shadow: 0 10px 22px rgba(2,6,23,.10);
    transform: translateY(-1px);
}

/* Empty state */
.empty-state{
    text-align:center;
    padding:18px 12px;
    border:1px dashed #cbd5e1;
    border-radius:12px;
    background:#f8fafc;
    color:#64748b;
    font-weight:700;
}


.table-container{
    overflow-x:auto; overflow-y:visible;
    border:1px solid #e5e7eb;
    border-radius:10px;
    background:#fff;
}

/* tabel */
.data-table{
    width:100%;
    border-collapse:separate;
    border-spacing:0;
    font-size:.95rem;
}

/* header: biru gradient (seragam) */
.data-table thead th{
    background: linear-gradient(180deg, #1E3A8A, #2563EB) !important;
    color:#ffffff !important;
    text-align:left;
    padding:12px 14px;
    border-bottom: 1px solid rgba(255,255,255,.18) !important;
    font-weight:700;
    white-space:nowrap;
}

/* isi */
.data-table td{
    padding:12px 14px;
    border-bottom:1px solid #f1f5f9;
    color:#0f172a;
    vertical-align:middle;
}
.data-table tbody tr:nth-child(even){ background:#f9fbff; }
.data-table tbody tr:hover{ background:#eef2ff; }

/* DataTables sering menimpa posisi header */
table.dataTable thead th { position: static; }
table.dataTable{ white-space: nowrap; }

/* NIK lebih rapi, konsisten */
.nik-mono{
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    font-weight: 400;   /* tidak bold, mirip manajemen data */
    color:#0f172a;      /* warna teks normal */
}

/* Tombol "Mulai" seragam gaya primary */
.btn-mulai{
    display:inline-flex;
    align-items:center;
    gap:8px;
    border:none;
    cursor:pointer;
    padding:8px 12px;
    border-radius:10px;
    font-weight:800;
    color:#fff;
    background:#2563EB;
    box-shadow: 0 8px 16px rgba(37,99,235,.20);
    transition: transform .2s ease, filter .2s ease;
}
.btn-mulai:hover{ transform: translateY(-2px); filter: brightness(.96); }

/* ===== Toolbar DataTables seragam (seperti manajemen data) ===== */
.dt-toolbar{
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:12px;
  padding:10px 12px 6px;
  flex-wrap:wrap;
}
.dt-toolbar-left,
.dt-toolbar-right{
  display:flex;
  align-items:center;
  gap:10px;
}
.dt-search > label{ display:none; }
.dt-search input.dt-input{
  height:36px;
  min-width:280px;
  padding:8px 12px;
  border:1px solid #d1d5db;
  border-radius:8px;
  outline:none;
}
.dt-search input.dt-input:focus{
  border-color:rgba(37,99,235,.7);
  box-shadow:0 0 0 3px rgba(37,99,235,.15);
}

/* Footer info & paging */
.dt-footer{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:6px 12px 10px;
  gap:10px;
  flex-wrap:wrap;
}
.dt-footer-left, .dt-footer-right{ display:flex; align-items:center; gap:10px; }

/* Responsive kecil */
@media (max-width:768px){
  .dt-toolbar{ flex-direction:column; align-items:stretch; }
  .dt-toolbar-right{ justify-content:flex-start; }
  .dt-search input.dt-input{ width:100%; min-width:0; }
  .sp-actions{ justify-content:flex-start; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

  function formatNIK(nik) {
    if (!nik) return '';
    return String(nik).replace(/(\d{4})(?=\d)/g, '$1 ');
  }

  // format NIK di tabel
  document.querySelectorAll('.nik-display').forEach(el => {
    const raw = el.getAttribute('data-nik') || el.textContent;
    el.textContent = formatNIK(raw);
  });

  // init DataTables hanya jika tabel ada
  const $tbl = $('#tabel-kelayakan');
  if ($tbl.length) {
    $tbl.DataTable({
      responsive: false,
      scrollX: true,
      autoWidth: false,
      deferRender: true,
      stateSave: true,
      searchDelay: 250,
      pageLength: 25,
      lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'Semua']],
      dom:
        "<'dt-toolbar'<'dt-toolbar-left'l><'dt-toolbar-right'f>>" +
        "rt" +
        "<'dt-footer'<'dt-footer-left'i><'dt-footer-right'p>>",
      language: {
        url: 'https://cdn.datatables.net/plug-ins/2.1.7/i18n/id.json'
      },

      columnDefs: [
        { targets: 'no-sort', orderable: false, searchable: false }
      ],

      order: [[1, 'asc']], // urutkan berdasarkan Nama
      initComplete: function () {
        const $search = $(this.api().table().container()).find('div.dt-search input');
        $search.attr('placeholder', 'Kata kunci pencarian (nama/NIK)');
      }
    });
  }

});
</script>
@endsection
