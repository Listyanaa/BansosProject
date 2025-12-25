@extends('layouts.app')

@section('title', 'Data Syarat')

@section('content')
<div class="sp-container">

    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="header-title">Kelola Data Syarat</h1>
            <p class="header-subtitle">Kelola syarat menerima bantuan</p>
        </div>
    </div>

    <div class="sp-actions">
        <a href="{{ route('sp.syarat.create') }}" class="btn-add">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah</span>
        </a>
    </div>

    @if($items->isEmpty())
        <div class="empty-state">
            — Belum ada data syarat —
        </div>
    @else
        <div class="table-container">
            <table id="tabel-syarat" class="display nowrap data-table" style="width:100%">
                <thead>
                    <tr>
                        <th style="width:140px;">Kode</th>
                        <th>Teks</th>
                        <th style="width:120px; text-align:center;">Aktif</th>
                        <th style="width:190px;" class="no-sort">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $it)
                        <tr>
                            <td data-order="{{ $it->kode }}">
                                <span class="badge-kode">{{ $it->kode }}</span>
                            </td>

                            <td>{{ $it->teks }}</td>

                            <td style="text-align:center;" data-order="{{ $it->aktif ? 1 : 0 }}">
                                @if($it->aktif)
                                    <span class="badge-status badge-yes">Ya</span>
                                @else
                                    <span class="badge-status badge-no">Tidak</span>
                                @endif
                            </td>

                            <td style="white-space:nowrap;">
                                <div class="row-actions">
                                    <a href="{{ route('sp.syarat.edit',$it) }}" class="btn-edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>Edit</span>
                                    </a>

                                    <form method="post" action="{{ route('sp.syarat.destroy',$it) }}" style="display:inline;"
                                          onsubmit="return confirm('Yakin ingin menghapus syarat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Vendor: sama seperti Index Cek Kelayakan --}}
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

{{-- Kalau layouts.app SUDAH memuat jQuery, hapus script jQuery di bawah ini --}}
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

/* Container */
.sp-container{
    background:#fff;
    padding:22px;
    border-radius:14px;
    box-shadow:0 6px 14px rgba(0,0,0,.08);
}

/* Actions (sama konsep: tombol di kanan atas) */
.sp-actions{
    display:flex;
    justify-content:flex-end;
    margin: 10px 0 10px;
}

/* Tombol tambah (gaya primary seperti halaman lain) */
.btn-add{
    display:inline-flex;
    align-items:center;
    gap:8px;
    border:none;
    cursor:pointer;
    padding:10px 14px;
    border-radius:12px;
    font-weight:800;
    color:#fff;
    background:#2563EB;
    text-decoration:none;
    box-shadow: 0 8px 16px rgba(37,99,235,.20);
    transition: transform .2s ease, filter .2s ease;
    white-space:nowrap;
}
.btn-add:hover{ transform: translateY(-2px); filter: brightness(.96); }

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
.data-table{
    width:100%;
    border-collapse:separate;
    border-spacing:0;
    font-size:.95rem;
}
.data-table thead th{
    background: linear-gradient(180deg, #1E3A8A, #2563EB) !important;
    color:#ffffff !important;
    text-align:left;
    padding:12px 14px;
    border-bottom: 1px solid rgba(255,255,255,.18) !important;
    font-weight:700;
    white-space:nowrap;
}
.data-table td{
    padding:12px 14px;
    border-bottom:1px solid #f1f5f9;
    color:#0f172a;
    vertical-align:middle;
}
.data-table tbody tr:nth-child(even){ background:#f9fbff; }
.data-table tbody tr:hover{ background:#eef2ff; }
table.dataTable thead th { position: static; }
table.dataTable{ white-space: nowrap; }

/* Badge kode (tetap, tapi menyatu dengan tema) */
.badge-kode{
    display:inline-block;
    padding:6px 10px;
    border-radius:999px;
    background:#e0ecff;
    color:#1d4ed8;
    font-weight:700;
    font-size:.85rem;
}

/* Badge status */
.badge-status{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:6px 10px;
    border-radius:999px;
    font-weight:800;
    font-size:.85rem;
    border:1px solid transparent;
}
.badge-yes{ background:#dcfce7; color:#166534; border-color:#86efac; }
.badge-no { background:#fee2e2; color:#991b1b; border-color:#fecaca; }

/* Aksi tombol (tidak mengubah fungsi form/href) */
.row-actions{
    display:flex;
    justify-content:center;
    gap:8px;
    align-items:center;
}
.btn-edit,
.btn-delete{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:8px 12px;
    border-radius:10px;
    border:none;
    font-weight:800;
    font-size:.85rem;
    cursor:pointer;
    text-decoration:none;
    transition:transform .15s ease, filter .15s ease;
    white-space:nowrap;
}
.btn-edit{ background:#f59e0b; color:#fff; }
.btn-delete{ background:#e11d48; color:#fff; }
.btn-edit:hover,
.btn-delete:hover{ transform:translateY(-1px); filter:brightness(.98); }

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
  .row-actions{ justify-content:flex-start; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

  const $tbl = $('#tabel-syarat');
  if ($tbl.length) {
    const dt = $tbl.DataTable({
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
      order: [[0, 'asc']], // urutkan berdasarkan Kode
      initComplete: function () {
        const $search = $(this.api().table().container()).find('div.dt-search input');
        $search.attr('placeholder', 'Kata kunci (kode/teks)');
      }
    });

    const initialSearch = @json(request('search'));
    if (initialSearch) {
      dt.search(initialSearch).draw();
    }
  }

});
</script>
@endsection
