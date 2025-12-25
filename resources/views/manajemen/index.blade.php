@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="sp-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="header-title">Manajemen Pengguna</h1>
            <p class="header-subtitle">Kelola akun dan hak akses pengguna</p>
        </div>
    </div>

    <div class="sp-actions">
        <button class="btn-add" type="button" onclick="openTambahModal()">
            <i class="fa-solid fa-user-plus"></i> Tambah Pengguna
        </button>
    </div>

    @if($users->isEmpty())
        <div class="empty-state">— Tidak ada data pengguna —</div>
    @else
        <div class="table-container">
            <table id="tabel-users" class="display nowrap data-table" style="width:100%">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th style="width:120px;">Password</th>
                        <th style="width:140px;">Role</th>
                        <th style="width:210px;" class="no-sort">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td data-order="{{ $user->nama }}" data-search="{{ $user->nama }}">
                                <div class="cell-name">
                                    <div class="avatar">{{ strtoupper(substr($user->nama,0,2)) }}</div>
                                    <span class="name">{{ $user->nama }}</span>
                                </div>
                            </td>

                            <td data-order="{{ $user->email }}" data-search="{{ $user->email }}" class="cell-email">
                                {{ $user->email }}
                            </td>

                            <td data-order="0">●●●●●●</td>

                            <td data-order="{{ strtolower($user->role) }}" data-search="{{ $user->role }}">
                                <span class="badge {{ strtolower($user->role) }}">{{ $user->role }}</span>
                            </td>

                            <td style="white-space:nowrap;">
                                <div class="row-actions">
                                   
                                    <button class="btn-edit" type="button"
                                        onclick='openEditModal(@json($user->id), @json($user->nama), @json($user->email), @json($user->role))'>
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>

                                    {{-- Tombol Reset --}}
                                    <form action="{{ route('manajemen.reset', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-reset">
                                            <i class="fa-solid fa-key"></i> Reset
                                        </button>
                                    </form>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('manajemen.destroy', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">
                                            <i class="fa-solid fa-trash"></i> Hapus
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

{{-- Modal Tambah/Edit Pengguna --}}
<div id="modalForm" class="modal">
    <div class="modal-content">
        <h3 id="modalTitle"><i class="fa-solid fa-user-plus"></i> Tambah Pengguna</h3>

        <form id="formUser" method="POST" action="{{ route('manajemen.store') }}">
            @csrf
            <input type="hidden" id="_method" name="_method" value="POST">

            <label>Nama</label>
            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required>
            @error('nama')
                <small class="text-error">{{ $message }}</small>
            @enderror

            <label>Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            @error('email', 'store')
                <small class="text-error">{{ $message }}</small>
            @enderror
            @error('email', 'update')
                <small class="text-error">{{ $message }}</small>
            @enderror

            <div id="passwordField">
                <label>Password</label>
                <input type="password" name="password" id="password">
                @error('password', 'store')
                    <small class="text-error">{{ $message }}</small>
                @enderror

                <label style="margin-top:8px;">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation">
                @error('password_confirmation', 'store')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </div>

            <label>Role</label>
            <select name="role" id="role" required>
                <option value="" {{ old('role') == '' ? 'selected' : '' }}>-- Pilih Role --</option>
                <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Administrator</option>
                <option value="Staf" {{ old('role') == 'Staf' ? 'selected' : '' }}>Staf</option>
                <option value="Pimpinan" {{ old('role') == 'Pimpinan' ? 'selected' : '' }}>Pimpinan</option>
            </select>
            @error('role')
                <small class="text-error">{{ $message }}</small>
            @enderror

            <div class="modal-buttons">
                <button type="button" onclick="closeModal()" class="btn-cancel">Batal</button>
                <button type="submit" class="btn-save">
                    <span class="btnText">Simpan</span>
                    <i class="btnSpinner fas fa-spinner fa-spin" style="display:none; margin-left:10px;"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>

<script>
function openTambahModal(fromError = false) {
    const modal = document.getElementById('modalForm');
    const form = document.getElementById('formUser');
    const title = document.getElementById('modalTitle');
    const passwordField = document.getElementById('passwordField');
    const method = document.getElementById('_method');

    form.action = "{{ route('manajemen.store') }}";
    method.value = "POST";
    title.innerHTML = '<i class="fa-solid fa-user-plus"></i> Tambah Pengguna';
    passwordField.style.display = 'block';

    if (!fromError) {
        form.reset();
        document.querySelectorAll('.text-error').forEach(el => el.textContent = '');
    }

    document.getElementById('nama').value = '';
    document.getElementById('email').value = '';
    document.getElementById('role').value = '';
    document.getElementById('password').value = '';
    document.getElementById('password_confirmation').value = '';

    document.getElementById('password').required = true;
    document.getElementById('password_confirmation').required = true;

    modal.style.display = 'flex';
}

function openEditModal(id, nama, email, role) {
    const modal = document.getElementById('modalForm');
    const form = document.getElementById('formUser');
    const title = document.getElementById('modalTitle');
    const passwordField = document.getElementById('passwordField');
    const method = document.getElementById('_method');

    form.action = `/manajemen-pengguna/${id}`;
    method.value = "PUT";
    title.innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Edit Pengguna';
    passwordField.style.display = 'none';

    document.getElementById('nama').value = nama;
    document.getElementById('email').value = email;
    document.getElementById('role').value = role;

    document.getElementById('password').required = false;
    document.getElementById('password_confirmation').required = false;

    modal.style.display = 'flex';
}

function closeModal() {
    document.getElementById('modalForm').style.display = 'none';
    document.querySelectorAll('.text-error').forEach(el => el.textContent = '');
}
</script>

@if (session('modal') === 'add' && $errors->store->any())
<script>
window.addEventListener('load', function () {
    openTambahModal(true);
});
</script>
@endif

@if (session('modal') === 'edit' && $errors->update->any())
<script>
window.addEventListener('load', function () {
    openEditModal(
        @json(session('edit_id')),
        @json(old('nama')),
        @json(old('email')),
        @json(old('role'))
    );
});
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const $tbl = $('#tabel-users');
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
            order: [[0, 'asc']],
            initComplete: function () {
                const $search = $(this.api().table().container()).find('div.dt-search input');
                $search.attr('placeholder', 'Kata kunci (nama/email/role)');
            }
        });

        const initialSearch = @json(request('search'));
        if (initialSearch) dt.search(initialSearch).draw();
    }
});
</script>

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
    margin: 10px 0 10px;
}

/* Tombol Tambah  */
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
    box-shadow: 0 8px 16px rgba(37,99,235,.20);
    transition: transform .2s ease, filter .2s ease;
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

/* ===== Toolbar DataTables ===== */
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

.cell-name{ display:flex; align-items:center; gap:10px; }
.avatar{
    width:28px; height:28px; border-radius:50%;
    background:linear-gradient(135deg,#1E3A8A,#2563EB);
    color:#fff; font-weight:800; font-size:.8rem;
    display:grid; place-items:center;
}
.cell-email{ color:#334155; }

/* Role badge */
.badge{
    padding:6px 10px;
    border-radius:999px;
    font-weight:800;
    font-size:.8rem;
    display:inline-block;
    border:1px solid transparent;
}
.badge.admin{ background:#dbeafe; color:#1e3a8a; border-color:#bfdbfe; }
.badge.staf{ background:#dcfce7; color:#065f46; border-color:#86efac; }
.badge.pimpinan{ background:#fee2e2; color:#991b1b; border-color:#fecaca; }

/* Aksi */
.row-actions{ display:flex; gap:8px; align-items:center; justify-content:center; }
.btn-edit, .btn-reset, .btn-delete{
    display:inline-flex;
    align-items:center;
    gap:8px;
    border:none;
    cursor:pointer;
    padding:8px 12px;
    border-radius:10px;
    font-weight:800;
    color:#fff;
    transition: transform .2s ease, filter .2s ease;
    white-space:nowrap;
}
.btn-edit{ background:#f59e0b; }
.btn-reset{ background:#1d4ed8; }
.btn-delete{ background:#e11d48; }
.btn-edit:hover, .btn-reset:hover, .btn-delete:hover{
    transform: translateY(-2px);
    filter: brightness(.95);
}

/* ===== Modal ===== */
.modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(2,6,23,.45);
    backdrop-filter: blur(2px);
    -webkit-backdrop-filter: blur(2px);
    justify-content:center;
    align-items:center;
    z-index:1000;
    padding:16px;
}
.modal-content{
    background:#fff;
    width:min(420px, 100%);
    border-radius:12px;
    border:1px solid #e5e7eb;
    box-shadow: 0 24px 48px rgba(2,6,23,.18);
    padding:18px;
}
.modal-content h3{
    margin:0 0 12px 0;
    font-size:1.05rem;
    font-weight:800;
    color:#0b1324;
}
.modal-content label{
    display:block;
    margin:10px 0 6px;
    font-weight:600;
    color:#1f2937;
    font-size:.9rem;
}
.modal-content input, .modal-content select{
    width:100%;
    padding:10px 12px;
    border-radius:8px;
    border:1px solid #d1d5db;
    font-size:.95rem;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.modal-content input:focus, .modal-content select:focus{
    border-color: rgba(37,99,235,.6);
    box-shadow: 0 0 0 3px rgba(37,99,235,.12);
}
.modal-buttons{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    margin-top:14px;
}
.btn-cancel, .btn-save{
    border:none;
    border-radius:10px;
    padding:10px 14px;
    font-weight:800;
    cursor:pointer;
}
.btn-cancel{ background:#e5e7eb; color:#111827; }
.btn-save{ background:#2563EB; color:#fff; }
.btn-cancel:hover, .btn-save:hover{ filter: brightness(.95); transform: translateY(-2px); }

/* Error */
.text-error{
    color:#dc2626;
    font-size:0.8rem;
    display:block;
    margin-top:4px;
}

/* Responsive */
@media (max-width:768px){
  .dt-toolbar{ flex-direction:column; align-items:stretch; }
  .dt-toolbar-right{ justify-content:flex-start; }
  .dt-search input.dt-input{ width:100%; min-width:0; }
  .sp-actions{ justify-content:flex-start; }
  .row-actions{ justify-content:flex-start; flex-wrap:wrap; }
}
</style>
@endsection
