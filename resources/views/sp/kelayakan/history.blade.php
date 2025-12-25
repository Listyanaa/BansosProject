@extends('layouts.app')

@section('title', 'Riwayat Kelayakan')

@section('content')
<div class="sp-container">
    {{-- Header (samakan pola dengan Index) --}}
    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="header-title">Riwayat Kelayakan</h1>
            <p class="header-subtitle">Lihat rekam jejak hasil cek kelayakan per penerima.</p>
        </div>
    </div>

    {{-- Actions --}}
    <div class="sp-actions">
        <a href="{{ route('sp.kelayakan.index') }}" class="sp-btn-back">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Cek Kelayakan</span>
        </a>
    </div>

    {{-- Tabel (samakan dengan Index: table-container + data-table + DataTables v2) --}}
    <div class="table-container">
        <table id="tabel-riwayat" class="display nowrap data-table" style="width:100%">
            <thead>
                <tr>
                    <th style="width:180px;">Tanggal</th>
                    <th>Penerima</th>
                    <th style="width:140px; text-align:center;">Hasil</th>
                    <th style="width:180px;" class="no-sort">Jejak</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $it)
                    @php
                        $facts = (array)($it->jejak ?? []);
                        $ts    = $it->tanggal?->timestamp ?? $it->created_at->timestamp;
                        $tgl   = $it->tanggal?->format('Y-m-d H:i') ?? $it->created_at->format('Y-m-d H:i');
                        $nama  = $it->penerima?->nama ?? '-';
                        $nik   = $it->penerima?->nik ?? null;
                        $hasil = $it->hasil ?? '-';

                        $ya = 0; $tidak = 0;
                        foreach($facts as $k=>$v){ $v ? $ya++ : $tidak++; }
                        $total = count($facts);
                    @endphp

                    <tr>
                        <td data-order="{{ $ts }}">
                            <i class="fa-regular fa-clock"></i>
                            <span>{{ $tgl }}</span>
                        </td>

                        <td data-search="{{ $nama }} {{ $nik }}">
                            <div class="person">
                                <div class="person-name">{{ $nama }}</div>
                                @if($nik)
                                    <div class="person-sub">
                                        NIK:
                                        <span class="nik-mono nik-display" data-nik="{{ $nik }}">{{ $nik }}</span>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td style="text-align:center;">
                            <span class="badge-status {{ $hasil === 'Layak' ? 'badge-yes' : 'badge-no' }}">
                                {{ $hasil }}
                            </span>
                        </td>

                        <td>
                            @if($total > 0)
                                <div class="jejak-row">
                                    <span class="jejak-text">
                                        Ya: {{ $ya }} • Tidak: {{ $tidak }} • Total: {{ $total }}
                                    </span>

                                    <button
                                        type="button"
                                        class="link-detail sp-link-detail"
                                        data-tanggal="{{ $tgl }}"
                                        data-nama="{{ $nama }}"
                                        data-nik="{{ $nik }}"
                                        data-hasil="{{ $hasil }}"
                                        data-jejak='@json($facts)'>
                                        Detail
                                    </button>
                                </div>
                            @else
                                <span style="color:#64748b;">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:18px; color:#6b7280;">
                            Belum ada riwayat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Jejak Detail (tetap) --}}
<div class="sp-modal" id="jejakModal" aria-hidden="true">
    <div class="sp-modal-backdrop" data-close="1"></div>

    <div class="sp-modal-dialog" role="dialog" aria-modal="true">
        <div class="sp-modal-header">
            <div class="sp-modal-title">Detail Jejak Kelayakan</div>
            <button type="button" class="sp-modal-close" data-close="1" aria-label="Tutup">×</button>
        </div>

        <div class="sp-modal-body">
            <div class="sp-modal-summary" id="modalSummary"></div>

            <div class="sp-modal-tools">
                <div class="sp-modal-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input id="modalSearch" type="text" placeholder="Cari kode syarat (contoh: G01)" autocomplete="off">
                </div>
                <div class="sp-modal-hint" id="modalHint"></div>
            </div>

            <div class="sp-modal-sections">
                <details class="sp-acc" open>
                    <summary>
                        Jawaban Ya <span class="sp-acc-count" id="countYes">0</span>
                    </summary>
                    <div class="sp-modal-list" id="modalListYes"></div>
                </details>

                <details class="sp-acc" open>
                    <summary>
                        Jawaban Tidak <span class="sp-acc-count" id="countNo">0</span>
                    </summary>
                    <div class="sp-modal-list" id="modalListNo"></div>
                </details>
            </div>
        </div>

        <div class="sp-modal-footer">
            <button type="button" class="sp-btn-secondary" data-close="1">Tutup</button>
        </div>
    </div>
</div>

{{-- Vendor: samakan dengan Index --}}
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

{{-- CSS modal kamu (tetap) --}}
<link rel="stylesheet" href="{{ asset('css/sp-kelayakan-history.css') }}">

{{-- Jika layout kamu SUDAH memuat jQuery, hapus baris ini --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>

<style>
/* ====== Header & Container (copy dari Index agar konsisten) ====== */
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
.sp-container{
    background:#fff;
    padding:22px;
    border-radius:14px;
    box-shadow:0 6px 14px rgba(0,0,0,.08);
}
.sp-actions{
    display:flex;
    justify-content:flex-end;
    margin: 10px 0 10px;
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

/* ====== Table container + style (sama seperti Index/Manajemen Data) ====== */
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

/* NIK (tidak bold) */
.nik-mono{
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    font-weight: 400;
    color:#0f172a;
}

/* Penerima cell */
.person-name{ font-weight:700; color:#0b1324; }
.person-sub{ margin-top:2px; font-size:.85rem; color:#475569; }

/* Badge hasil */
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

/* Jejak */
.jejak-row{ display:flex; align-items:center; justify-content:space-between; gap:10px; }
.jejak-text{ color:#334155; font-weight:600; font-size:.9rem; }
.link-detail{
    border:none;
    background:transparent;
    color:#2563eb;
    font-weight:800;
    cursor:pointer;
    padding:6px 8px;
    border-radius:10px;
}
.link-detail:hover{ background:rgba(37,99,235,.08); }

/* Toolbar DataTables (sama dengan Index) */
.dt-toolbar{
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:12px;
  padding:10px 12px 6px;
  flex-wrap:wrap;
}
.dt-toolbar-left, .dt-toolbar-right{
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
  .sp-actions{ justify-content:flex-start; }
  .dt-toolbar{ flex-direction:column; align-items:stretch; }
  .dt-toolbar-right{ justify-content:flex-start; }
  .dt-search input.dt-input{ width:100%; min-width:0; }
  .jejak-row{ flex-direction:column; align-items:flex-start; }
}
</style>

<script>
$(function () {

  function formatNIK(nik) {
    if (!nik) return '';
    return String(nik).replace(/(\d{4})(?=\d)/g, '$1 ');
  }

  // format NIK di tabel
  document.querySelectorAll('.nik-display').forEach(el => {
    const raw = el.getAttribute('data-nik') || el.textContent;
    el.textContent = formatNIK(raw);
  });

  // DataTables Riwayat
  const $tbl = $('#tabel-riwayat');
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
      order: [[0, 'desc']],
      initComplete: function () {
        const $search = $(this.api().table().container()).find('div.dt-search input');
        $search.attr('placeholder', 'Kata kunci pencarian (nama/NIK/hasil)');
      }
    });
  }

  // ===== MODAL Jejak Detail (kode kamu, tetap) =====
  const modal = document.getElementById('jejakModal');
  const modalSummary = document.getElementById('modalSummary');
  const modalHint = document.getElementById('modalHint');
  const modalSearch = document.getElementById('modalSearch');

  const modalListYes = document.getElementById('modalListYes');
  const modalListNo  = document.getElementById('modalListNo');
  const countYesEl   = document.getElementById('countYes');
  const countNoEl    = document.getElementById('countNo');

  function esc(s){
    return String(s ?? '')
      .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
      .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
  }

  let currentYes = [];
  let currentNo  = [];
  let currentTotal = 0;

  function codeSort(a, b){
    const ax = String(a[0]).toUpperCase();
    const bx = String(b[0]).toUpperCase();
    const an = parseInt(ax.replace(/\D/g,''), 10);
    const bn = parseInt(bx.replace(/\D/g,''), 10);
    if (!isNaN(an) && !isNaN(bn) && an !== bn) return an - bn;
    return ax.localeCompare(bx);
  }

  function renderList(entries, container){
    container.innerHTML = entries.map(([k, v]) => {
      const yes = !!v;
      return `
        <div class="sp-mi">
          <div class="k">${esc(k)}</div>
          <div class="v ${yes ? 'yes' : 'no'}">
            <span class="dot"></span>${yes ? 'Ya' : 'Tidak'}
          </div>
        </div>
      `;
    }).join('');
  }

  function applySearch(){
    const q = (modalSearch.value || '').trim().toUpperCase();
    const fy = q ? currentYes.filter(([k]) => String(k).toUpperCase().includes(q)) : currentYes;
    const fn = q ? currentNo.filter(([k]) => String(k).toUpperCase().includes(q)) : currentNo;

    renderList(fy, modalListYes);
    renderList(fn, modalListNo);

    countYesEl.textContent = fy.length;
    countNoEl.textContent  = fn.length;

    modalHint.textContent = q
      ? `Filter: "${q}" • tampil ${fy.length + fn.length} dari ${currentTotal}`
      : `${currentTotal} item`;
  }

  function openModal(payload) {
    const { tanggal, nama, nik, hasil, jejak } = payload;

    modalSummary.innerHTML = `
      <div class="row">
        <div class="item">Tanggal: <span class="val">${esc(tanggal || '-')}</span></div>
        <div class="item">Penerima: <span class="val">${esc(nama || '-')}</span></div>
        <div class="item">NIK: <span class="val">${esc(nik || '-')}</span></div>
        <div class="item">Hasil: <span class="val">${esc(hasil || '-')}</span></div>
      </div>
    `;

    const entries = Object.entries(jejak || {}).sort(codeSort);
    currentYes = entries.filter(([_, v]) => !!v);
    currentNo  = entries.filter(([_, v]) => !v);
    currentTotal = entries.length;

    modalSearch.value = '';
    applySearch();

    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    setTimeout(() => modalSearch.focus(), 50);
  }

  function closeModal() {
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  modalSearch?.addEventListener('input', applySearch);

  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.sp-link-detail');
    if (btn) {
      openModal({
        tanggal: btn.dataset.tanggal,
        nama: btn.dataset.nama,
        nik: btn.dataset.nik,
        hasil: btn.dataset.hasil,
        jejak: JSON.parse(btn.dataset.jejak || '{}')
      });
      return;
    }
    if (e.target && e.target.dataset && e.target.dataset.close) closeModal();
  });

  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
  });

});
</script>
@endsection
