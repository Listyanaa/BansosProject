@extends('layouts.app')

@section('title', 'Edit Aturan')

@section('content')
<div class="sp-container">
    {{-- Header --}}
    <div class="sp-toolbar">
        <div class="sp-title-group">
            <h3 class="sp-title">Edit Aturan</h3>
            <p class="sp-subtitle">Perbarui kombinasi syarat dan hasil yang sudah ada.</p>
        </div>

        <a href="{{ route('sp.aturan.index') }}" class="sp-btn-back">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    {{-- Error validasi --}}
    @if($errors->any())
        <div class="sp-alert-error">
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Card Form --}}
    <div class="sp-card-form">
        <form method="post" action="{{ route('sp.aturan.update',$aturan) }}">
            @csrf
            @method('PUT')

            {{-- Kode Aturan --}}
            <label class="sp-label">Kode Aturan</label>
            <input type="text" name="kode_aturan"
                   class="sp-input @error('kode_aturan') sp-input-error @enderror"
                   value="{{ old('kode_aturan', $aturan->kode_aturan) }}"
                   required>
            @error('kode_aturan')
                <small class="sp-text-error">{{ $message }}</small>
            @enderror

            {{-- Pilih Syarat --}}
            <label class="sp-label" style="margin-top:14px;">Pilih Syarat</label>
            <div class="sp-syarat-box">
                @foreach($syarat as $s)
                    <label class="sp-syarat-item">
                        <input type="checkbox"
                               name="kondisi[]"
                               value="{{ $s->kode }}"
                               {{ in_array($s->kode, $selected) ? 'checked' : '' }}>
                        <span class="sp-syarat-kode">{{ $s->kode }}</span>
                        <span class="sp-syarat-teks">— {{ $s->teks }}</span>
                    </label>
                @endforeach
            </div>

            {{-- Hasil --}}
            <label class="sp-label" style="margin-top:14px;">Hasil</label>
            <div class="sp-radio-row">
                <label class="sp-radio">
                    <input type="radio" name="hasil" value="Layak"
                           {{ $aturan->hasil === 'Layak' ? 'checked' : '' }}>
                    <span>Layak</span>
                </label>
                <label class="sp-radio">
                    <input type="radio" name="hasil" value="Tidak Layak"
                           {{ $aturan->hasil === 'Tidak Layak' ? 'checked' : '' }}>
                    <span>Tidak Layak</span>
                </label>
            </div>

            <div class="sp-form-buttons">
                <button type="reset" class="sp-btn-secondary">Reset</button>
                <button type="submit" class="sp-btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Update</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Pakai style yang sama dengan create untuk konsistensi */
.sp-container{
    background:#fff;
    padding:22px;
    border-radius:14px;
    box-shadow:0 6px 14px rgba(0,0,0,.08);
}
.sp-toolbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    margin-bottom:16px;
}
.sp-title-group{
    display:flex;
    flex-direction:column;
    gap:4px;
}
.sp-title{
    margin:0;
    font-size:1.25rem;
    font-weight:800;
    color:#0b1324;
}
.sp-subtitle{
    margin:0;
    font-size:0.9rem;
    color:#6b7280;
}
.sp-btn-back{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:8px 12px;
    border-radius:10px;
    border:1px solid #e5e7eb;
    background:#f9fafb;
    color:#111827;
    font-weight:600;
    text-decoration:none;
    cursor:pointer;
    transition:background .2s ease, transform .2s ease;
}
.sp-btn-back:hover{
    background:#e5e7eb;
    transform:translateY(-1px);
}

.sp-card-form{
    border-radius:12px;
    border:1px solid #e5e7eb;
    box-shadow:0 6px 14px rgba(15,23,42,.04);
    padding:18px 18px 20px;
}
.sp-label{
    display:block;
    margin-bottom:6px;
    margin-top:10px;
    font-weight:600;
    font-size:0.9rem;
    color:#111827;
}
.sp-input{
    width:100%;
    padding:10px 12px;
    border-radius:8px;
    border:1px solid #d1d5db;
    font-size:0.95rem;
    outline:none;
    transition:border-color .2s ease, box-shadow .2s ease;
}
.sp-input:focus{
    border-color:rgba(37,99,235,.7);
    box-shadow:0 0 0 3px rgba(37,99,235,.15);
}
.sp-input-error{
    border-color:#dc2626;
}

/* Daftar syarat */
.sp-syarat-box{
    border:1px solid #e5e7eb;
    border-radius:10px;
    padding:10px 12px;
    max-height:250px;
    overflow-y:auto;
    background:#f9fafb;
}
.sp-syarat-item{
    display:flex;
    align-items:flex-start;
    gap:8px;
    padding:4px 0;
    font-size:0.9rem;
    color:#111827;
}
.sp-syarat-item input{
    margin-top:2px;
}
.sp-syarat-kode{
    display:inline-block;
    min-width:48px;
    padding:4px 8px;
    border-radius:999px;
    background:#e0ecff;
    color:#1d4ed8;
    font-weight:700;
    font-size:0.78rem;
    text-align:center;
}
.sp-syarat-teks{
    flex:1;
}

/* Radio hasil */
.sp-radio-row{
    display:flex;
    gap:16px;
    margin-top:4px;
}
.sp-radio{
    display:inline-flex;
    align-items:center;
    gap:6px;
    font-size:0.9rem;
    color:#111827;
}

/* Error */
.sp-alert-error{
    background:#fee2e2;
    color:#991b1b;
    border:1px solid #fecaca;
    padding:10px 12px;
    border-radius:10px;
    margin-bottom:14px;
    font-size:0.9rem;
}
.sp-alert-error ul{ margin:6px 0 0 18px; }
.sp-text-error{
    color:#dc2626;
    font-size:0.8rem;
    display:block;
    margin-top:4px;
}

/* Buttons */
.sp-form-buttons{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    margin-top:18px;
}
.sp-btn-primary,
.sp-btn-secondary{
    border:none;
    border-radius:10px;
    padding:9px 14px;
    font-weight:700;
    font-size:0.9rem;
    display:inline-flex;
    align-items:center;
    gap:6px;
    cursor:pointer;
    transition:transform .2s ease, filter .2s ease;
}
.sp-btn-primary{
    background:#2563eb;
    color:#fff;
}
.sp-btn-secondary{
    background:#e5e7eb;
    color:#111827;
}
.sp-btn-primary:hover,
.sp-btn-secondary:hover{
    transform:translateY(-2px);
    filter:brightness(.95);
}

@media (max-width:768px){
    .sp-toolbar{ flex-direction:column; align-items:flex-start; }
}
</style>
@endsection
