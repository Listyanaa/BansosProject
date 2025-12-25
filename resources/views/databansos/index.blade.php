@extends('layouts.app')

@section('title', 'Manajemen Data')

@section('content')
<div class="container-bansos">
  <div class="dashboard-header">
      <div class="header-content">
          <h1 class="header-title">Data Penerima Bantuan</h1>
          <p class="header-subtitle">Kelola data penerima bantuan</p>
      </div>
  </div>
    
    <div class="toolbar-container">
        @php
            use Illuminate\Support\Facades\Auth;
            $role = Auth::user()->role ?? 'Pimpinan';
        @endphp

        @if(in_array($role, ['Admin', 'Staf']))
            <button id="btnTambahData" class="btn-tambah-data">
                + Tambah Data
            </button>
        @endif
    </div>

    @if($penerima->isEmpty())
        <p style="text-align:center;">Belum ada data penerima bantuan.</p>
    @else
    <div class="table-container">
        <table id="tabel-penerima" class="display nowrap data-table" style="width:100%">
            <thead>
                <tr>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Tempat / Tgl. Lahir</th> 
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
                    <th>Tanggal Layanan</th>
                    <th>Tanggal Meninggal</th>
                    <th>Kelayakan</th>
                    @if(in_array($role, ['Admin', 'Staf']))
                        <th class="noVis">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
            @foreach ($penerima as $data)
                <tr>
                    <td>{{ $data->nik }}</td>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->tempat_tgl_lahir }}</td> 
                    <td>{{ $data->jenis_kelamin }}</td>
                    <td>{{ $data->agama }}</td>    
                    <td>{{ $data->status_pernikahan }}</td>
                    <td>{{ $data->status_kepala_keluarga }}</td>
                    <td>{{ $data->kecamatan }}</td>
                    <td>{{ $data->kelurahan }}</td>
                    <td class="cell-wrap">{{ $data->alamat_lengkap }}</td>
                    <td>{{ $data->latitude }}</td>
                    <td>{{ $data->longitude }}</td>
                    <td>{{ $data->pekerjaan }}</td>
                    <td>{{ $data->tanggal_menerima_layanan ?? '-' }}</td>
                    <td>{{ $data->tanggal_meninggal ?? '-' }}</td>
                    <td>{{ $data->kelayakan }}</td>

                    @if(in_array($role, ['Admin', 'Staf']))
                    <td style="white-space: nowrap;">
                        <button
                            class="btn-edit"
                            data-id="{{ $data->id }}"
                            data-item='@json($data)'>
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </button>

                        <form action="{{ route('penerimabantuan.destroy', $data->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    @include('databansos._form_tambah')
    @endif
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/4.0.1/css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/colreorder/2.0.3/css/colReorder.dataTables.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/4.0.1/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/colreorder/2.0.3/js/dataTables.colReorder.min.js"></script>

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

/* --- Layout --- */
.container-bansos{
    background:#fff;
    padding:22px;
    border-radius:14px;
    box-shadow:0 6px 14px rgba(0,0,0,.08);
}

.table-container {
    overflow-x: auto; overflow-y: visible;
    border: 1px solid #e5e7eb; border-radius: 10px; background: #fff;
}

/* Tabel */
.data-table { width:100%; border-collapse:separate; border-spacing:0; font-size:.95rem; }
.data-table thead th {
    position: sticky; top:0; z-index:2;
    background:#f8fafc; color:#0b1324; text-align:left;
    padding:12px 14px; border-bottom:1px solid #e5e7eb; font-weight:700; white-space:nowrap;
}
table.dataTable thead th { position: static; } /* hindari bentrok header DT */

.data-table td { padding:12px 14px; border-bottom:1px solid #f1f5f9; color:#0f172a; vertical-align:top; }
.cell-wrap { white-space: normal !important; word-break: break-word; }

.data-table tbody tr:nth-child(even){ background:#f9fbff; }
.data-table tbody tr:hover{ background:#eef2ff; }

/* Alerts */
.alert-success{
    background:#d1fae5; color:#065f46; padding:10px 12px; border-radius:8px;
    border:1px solid #a7f3d0; margin:10px 0 14px; font-weight:600;
}

/* Toolbar */
.toolbar-container{ display:flex; justify-content:flex-end; align-items:center; margin-bottom:14px; gap:10px; }

/* Tombol Tambah */
.btn-tambah-data{
  background:#2563EB; color:#fff; border:none; padding:8px 16px; border-radius:10px; font-weight:700; cursor:pointer;
  box-shadow: 0 8px 16px rgba(37,99,235,.25); transition: transform .2s ease, filter .2s ease;
}
.btn-tambah-data:hover{ transform: translateY(-2px); filter: brightness(.96); }


.dt-toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  padding: 10px 12px 6px;
  flex-wrap: wrap;
}
.dt-toolbar-left,
.dt-toolbar-right {
  display: flex;
  align-items: center;
  gap: 10px;
}
.dt-search > label { display: none; }
.dt-search input.dt-input {
  height: 36px;
  min-width: 280px;
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  outline: none;
}
.dt-buttons .dt-button {
  height: 36px;
  padding: 0 12px;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
  background: #f3f4f6;
  color: #0b1324;
  font-weight: 600;
  line-height: 34px;
}
.dt-buttons .dt-button:hover { background:#e5e7eb; }
div.dt-button-collection{
  max-height: 60vh;
  overflow-y: auto;
  overscroll-behavior: contain;
  border-radius: 10px;
}

.dt-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 6px 12px 10px;
  gap: 10px;
  flex-wrap: wrap;
}
.dt-footer-left, .dt-footer-right { display:flex; align-items:center; gap:10px; }


.btn-edit, .btn-delete{
  display:inline-flex; align-items:center; gap:8px;
  border:none; cursor:pointer; padding:8px 12px;
  border-radius:10px; font-weight:700; color:#fff;
  transition: transform .2s ease, filter .2s ease;
}

.btn-edit{ background:#f59e0b; }
.btn-edit:hover{ transform: translateY(-2px); filter: brightness(.95); }

.btn-delete{ background:#e11d48; }
.btn-delete:hover{ transform: translateY(-2px); filter: brightness(.95); }

#tabel-penerima td .btn-edit { margin-right:6px; }


table.dataTable{ white-space: nowrap; }


@media (max-width:768px){
  .data-table th, .data-table td{ padding:10px 12px; white-space:nowrap; }
  .cell-wrap{ white-space: normal !important; }
  .dt-toolbar { flex-direction: column; align-items: stretch; }
  .dt-toolbar-right { justify-content: flex-start; }
  .dt-search input.dt-input { width: 100%; min-width: 0; }
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {

  const modal      = document.getElementById('popupForm');
  const modalTitle = modal?.querySelector('h2, h3');
  const form       = modal?.querySelector('form');

  const btnTambah  = document.getElementById('btnTambahData');
  const btnClose   = document.getElementById('closePopup');
  const btnCancel  = modal?.querySelector('.btn-cancel');

  const kecSelect  = modal?.querySelector('#kecamatan');
  const kelSelect  = modal?.querySelector('#kelurahan');

  window.showPopupForm = function () {
    if (!modal) return;

    modal.style.display = 'block';

    if (window.mapPickerInstance) {
      setTimeout(function () {
        window.mapPickerInstance.invalidateSize();
      }, 200);
    }
  };

  [btnClose, btnCancel].forEach(btn => {
    btn?.addEventListener('click', () => { modal.style.display = 'none'; });
  });
  
  window.addEventListener('click', (e) => {
    if (e.target === modal) modal.style.display = 'none';
  });

  const daftarKelurahan = {
    "Bacukiki": ["Galung Maloang", "Lemoe", "Lompoe", "Watang Bacukiki"],
    "Bacukiki Barat": ["Cappa Galung", "Kampung Baru", "Lumpue", "Sumpang Minangae", "Tiro Sompe", "Bumi Harapan"],
    "Soreang": ["Bukit Harapan", "Bukit Indah", "Kampung Pisang", "Lakessi", "Ujung Baru", "Ujung Lare", "Watang Soreang"],
    "Ujung": ["Labukkang", "Lapadde", "Mallusetasi", "Ujung Bulu", "Ujung Sabbang"]
  };

  function updateKelurahanOptions(kecamatan){
    if (!kelSelect) return;
    const selectedBefore = kelSelect.value;
    kelSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
    (daftarKelurahan[kecamatan] || []).forEach(kel => {
      const opt = document.createElement('option');
      opt.value = kel;
      opt.textContent = kel;
      kelSelect.appendChild(opt);
    });
    if ((daftarKelurahan[kecamatan] || []).includes(selectedBefore)){
      kelSelect.value = selectedBefore;
    }
  }

  kelSelect?.addEventListener('change', function(){
    if (!kecSelect) return;
    const val = this.value;
    for (const [kec, list] of Object.entries(daftarKelurahan)){
      if (list.includes(val)){
        if (kecSelect.value !== kec){
          kecSelect.value = kec;
          updateKelurahanOptions(kec);
          kelSelect.value = val;
        }
        break;
      }
    }
  });

  if (btnTambah){
    btnTambah.addEventListener('click', () => {
      if (!modal || !form) return;

      form.reset();
   
      form.querySelectorAll('input[name="status_kerentanan[]"]').forEach(cb => cb.checked = false);

      form.action = "{{ url('/data-bansos') }}";
      form.querySelector('input[name="_method"]')?.remove();
      updateKelurahanOptions(kecSelect?.value || '');
      if (modalTitle) modalTitle.textContent = 'Tambah Data Penerima Bantuan';

      showPopupForm();
    });
  }

  kecSelect?.addEventListener('change', function(){ updateKelurahanOptions(this.value); });

  const hasActions = @json(in_array($role, ['Admin','Staf']));
  const colVisBtn = hasActions
    ? { extend:'colvis', text:'Pilih Kolom', columns: ':not(.noVis)' }
    : { extend:'colvis', text:'Pilih Kolom' };

  const dt = $('#tabel-penerima').DataTable({
    responsive: false,
    scrollX: true,
    autoWidth: false,
    deferRender: true,
    stateSave: true,
    searchDelay: 300,
    orderMulti: true,
    pageLength: 25,
    lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'Semua']],
    dom:
      "<'dt-toolbar'<'dt-toolbar-left'B><'dt-toolbar-right'f>>" +
      "rt" +
      "<'dt-footer'<'dt-footer-left'i><'dt-footer-right'p>>",
    buttons: [
      'pageLength',
      colVisBtn
    ],
    language: {
      url: 'https://cdn.datatables.net/plug-ins/2.1.7/i18n/id.json'
    },
    columnDefs: hasActions ? [
      { targets: -1, orderable: false, searchable: false }
    ] : [],
    initComplete: function () {
      const $search = $(this.api().table().container()).find('div.dt-search input');
      $search.attr('placeholder', 'Kata kunci pencarian');
    }
  });

  const tableEl = document.getElementById('tabel-penerima');
  tableEl?.addEventListener('click', function(e){
    const btn = e.target.closest('.btn-edit');
    if (!btn) return;
    if (!modal || !form) return;

    const data = JSON.parse(btn.getAttribute('data-item') || '{}');

    form.action = `/data-bansos/${data.id}`;
    let methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput){
      methodInput = document.createElement('input');
      methodInput.type = 'hidden';
      methodInput.name = '_method';
      form.appendChild(methodInput);
    }
    methodInput.value = 'PUT';

    Object.keys(data).forEach(key => {
      if (['id','created_at','updated_at','deleted_at','status_kerentanan'].includes(key)) return;
      const el = form.querySelector(`[name="${key}"]`);
      if (!el) return;
      if (el.type === 'radio'){
        form.querySelectorAll(`input[name="${key}"]`).forEach(radio => {
          radio.checked = (String(radio.value) === String(data[key] ?? ''));
        });
      } else if (el.tagName === 'SELECT'){
        el.value = data[key] ?? '';
      } else {
        el.value = data[key] ?? '';
      }
    });

    if (typeof data.kecamatan !== 'undefined' && kecSelect){
      kecSelect.value = data.kecamatan ?? '';
      updateKelurahanOptions(kecSelect.value);
    }
    if (typeof data.kelurahan !== 'undefined' && kelSelect){
      kelSelect.value = data.kelurahan ?? '';
    }

    (function prefillKerentanan(){
      const raw = data.status_kerentanan;
      const values = Array.isArray(raw)
        ? raw.map(v => typeof v === 'string' ? v.trim() : String(v))
        : String(raw ?? '')
            .split(',')
            .map(s => s.trim())
            .filter(Boolean);
      form.querySelectorAll('input[name="status_kerentanan[]"]').forEach(cb => {
        cb.checked = values.includes(cb.value);
      });
    })();

    if (modalTitle) modalTitle.textContent = 'Edit Data Penerima Bantuan';
    showPopupForm();
  });

});
</script>
@endsection
