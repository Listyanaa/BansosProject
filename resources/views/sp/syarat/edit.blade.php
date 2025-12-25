@extends('layouts.app')

@section('title', 'Edit Syarat')

@section('content')
@php
  $popupTypeOld    = old('popup_type', $item->popup_type ?? 'none');
  $popupTriggerOld = old('popup_trigger', $item->popup_trigger ?? 'yes');

  $popupOptionsRaw = old('popup_options_raw');
  if ($popupOptionsRaw === null && !empty($item->popup_options)) {
      $decoded = json_decode($item->popup_options, true) ?: [];
      $popupOptionsRaw = implode("\n", $decoded);
  }
@endphp

<div class="sp-page">
  <div class="sp-header">
    <div>
      <h3 class="sp-title">Edit Syarat</h3>
      <p class="sp-subtitle">Perbarui data syarat yang sudah tersimpan.</p>
    </div>

    <a href="{{ route('sp.syarat.index') }}" class="sp-btn sp-btn-ghost">
      <i class="fa-solid fa-arrow-left"></i>
      <span>Kembali</span>
    </a>
  </div>

  @if ($errors->any())
    <div class="sp-alert">
      <strong>Data belum valid.</strong>
      <ul>
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="sp-card">
    <form method="post" action="{{ route('sp.syarat.update',$item) }}" id="syaratEditForm">
      @csrf
      @method('PUT')

      {{-- Row 1 --}}
      <div class="sp-grid-2">
        <div class="sp-field">
          <label class="sp-label">Kode</label>
          <input type="text" name="kode"
                 class="sp-input @error('kode') sp-input-error @enderror"
                 value="{{ old('kode', $item->kode) }}"
                 required>
          @error('kode') <small class="sp-error">{{ $message }}</small> @enderror
        </div>

        <div class="sp-field">
          <label class="sp-label">Status</label>
          <label class="sp-switch">
            <input type="checkbox" name="aktif" {{ old('aktif', $item->aktif) ? 'checked' : '' }}>
            <span class="sp-slider"></span>
            <span class="sp-switch-text">Aktif</span>
          </label>
        </div>
      </div>

      {{-- Pertanyaan --}}
      <div class="sp-field">
        <label class="sp-label">Teks Pertanyaan</label>
        <input type="text" name="teks"
               class="sp-input @error('teks') sp-input-error @enderror"
               value="{{ old('teks', $item->teks) }}"
               required>
        @error('teks') <small class="sp-error">{{ $message }}</small> @enderror
      </div>

      {{-- Pop Up (opsional) --}}
      <details class="sp-details" id="popupDetails" {{ $popupTypeOld !== 'none' ? 'open' : '' }}>
        <summary class="sp-summary">
          <span>Pengaturan Pop Up</span>
          <i class="fa-solid fa-chevron-down sp-chev"></i>
        </summary>

        <div class="sp-details-body">
          <div class="sp-grid-2">
            <div class="sp-field">
              <label class="sp-label">Tipe</label>
              <select name="popup_type" class="sp-input" id="popupType">
                <option value="none"   {{ $popupTypeOld=='none' ? 'selected':'' }}>Tidak ada</option>
                <option value="number" {{ $popupTypeOld=='number' ? 'selected':'' }}>Angka</option>
                <option value="text"   {{ $popupTypeOld=='text' ? 'selected':'' }}>Teks</option>
                <option value="checkbox" {{ $popupTypeOld=='checkbox' ? 'selected':'' }}>Checkbox</option>
                <option value="select" {{ $popupTypeOld=='select' ? 'selected':'' }}>Dropdown</option>
              </select>
            </div>

            <div class="sp-field" id="popupTriggerWrap">
              <label class="sp-label">Muncul saat</label>
              <select name="popup_trigger" class="sp-input" id="popupTrigger">
                <option value="yes" {{ $popupTriggerOld=='yes' ? 'selected':'' }}>Jawaban “Ya”</option>
                <option value="no"  {{ $popupTriggerOld=='no' ? 'selected':'' }}>Jawaban “Tidak”</option>
                <option value="any" {{ $popupTriggerOld=='any' ? 'selected':'' }}>Ya / Tidak</option>
              </select>
            </div>
          </div>

          <div class="sp-grid-2" id="popupMetaWrap">
            <div class="sp-field">
              <label class="sp-label">Label</label>
              <input type="text" name="popup_label" class="sp-input"
                     value="{{ old('popup_label', $item->popup_label) }}">
            </div>

            <div class="sp-field">
              <label class="sp-label">Placeholder</label>
              <input type="text" name="popup_placeholder" class="sp-input"
                     value="{{ old('popup_placeholder', $item->popup_placeholder) }}">
            </div>
          </div>

          <div class="sp-field" id="popupOptionsWrap">
            <label class="sp-label">Opsi (1 baris = 1 opsi)</label>
            <textarea name="popup_options_raw" class="sp-input" rows="4">{{ $popupOptionsRaw }}</textarea>
          </div>
        </div>
      </details>

      <div class="sp-actions">
        <button type="reset" class="sp-btn sp-btn-ghost">Reset</button>
        <button type="submit" class="sp-btn sp-btn-primary">
          <i class="fa-solid fa-floppy-disk"></i>
          <span>Update</span>
        </button>
      </div>
    </form>
  </div>
</div>

<style>
/* ===== Typography (nyaman & konsisten) ===== */
.sp-page{
  font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue",
               Arial, "Noto Sans", "Liberation Sans", sans-serif;
  font-size: 15px;
  line-height: 1.55;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-rendering: optimizeLegibility;

  max-width: 920px;
  margin:0 auto;
  padding:18px;
}

/* Header */
.sp-header{
  display:flex;
  justify-content:space-between;
  align-items:flex-start;
  gap:12px;
  margin-bottom:12px;
}
.sp-title{
  margin:0;
  font-size:1.18rem;
  font-weight:700;
  letter-spacing:-0.2px;
  color:#0f172a;
}
.sp-subtitle{
  margin:6px 0 0;
  font-size:.92rem;
  font-weight:400;
  color:#6b7280;
}

/* Card */
.sp-card{
  background:#fff;
  border:1px solid #e5e7eb;
  border-radius:12px;
  box-shadow:0 8px 18px rgba(15,23,42,.06);
  padding:16px;
}

/* Alert */
.sp-alert{
  background:#fff7ed;
  border:1px solid #fed7aa;
  color:#9a3412;
  border-radius:10px;
  padding:10px 12px;
  margin-bottom:12px;
  font-size:.92rem;
}
.sp-alert ul{ margin:6px 0 0 18px; }

/* Form */
.sp-grid-2{ display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.sp-field{ margin-top:10px; }

.sp-label{
  display:block;
  margin-bottom:6px;
  font-weight:600;
  font-size:.88rem;
  color:#111827;
}

.sp-input{
  width:100%;
  padding:10px 12px;
  border-radius:10px;
  border:1px solid #d1d5db;
  font-size:.95rem;
  line-height:1.35;
  outline:none;
  background:#fff;
  color:#0f172a;
  transition:border-color .15s ease, box-shadow .15s ease;
}
.sp-input::placeholder{ color:#9ca3af; }
.sp-input:focus{ border-color:rgba(37,99,235,.7); box-shadow:0 0 0 3px rgba(37,99,235,.12); }
.sp-input-error{ border-color:#dc2626; }
.sp-error{
  display:block;
  margin-top:6px;
  font-size:.82rem;
  color:#dc2626;
  font-weight:600;
}

/* Switch */
.sp-switch{ display:flex; align-items:center; gap:10px; user-select:none; }
.sp-switch input{ display:none; }
.sp-slider{
  width:42px; height:22px; border-radius:999px;
  background:#e5e7eb; position:relative; transition:.2s;
}
.sp-slider:before{
  content:""; width:16px; height:16px; border-radius:999px; background:#fff;
  position:absolute; top:3px; left:3px;
  box-shadow:0 3px 8px rgba(0,0,0,.12);
  transition:.2s;
}
.sp-switch input:checked + .sp-slider{ background:#2563eb; }
.sp-switch input:checked + .sp-slider:before{ transform:translateX(20px); }
.sp-switch-text{ font-weight:700; color:#0f172a; }

/* Details */
.sp-details{ margin-top:14px; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; }
.sp-summary{
  cursor:pointer;
  padding:10px 12px;
  background:#f9fafb;
  display:flex;
  align-items:center;
  justify-content:space-between;
  font-weight:700;
  font-size:.92rem;
  color:#0f172a;
}
.sp-summary::-webkit-details-marker{ display:none; }
.sp-details-body{ padding:12px; background:#fff; }
.sp-chev{ color:#6b7280; transition:transform .2s ease; }
details[open] .sp-chev{ transform:rotate(180deg); }

/* Buttons */
.sp-actions{ display:flex; justify-content:flex-end; gap:10px; margin-top:14px; }
.sp-btn{
  border:none;
  border-radius:10px;
  padding:10px 14px;
  font-weight:700;
  font-size:.9rem;
  display:inline-flex;
  align-items:center;
  gap:8px;
  cursor:pointer;
  text-decoration:none;
  transition:transform .15s ease, filter .15s ease;
}
.sp-btn-primary{ background:#2563eb; color:#fff; }
.sp-btn-ghost{ background:#f9fafb; color:#111827; border:1px solid #e5e7eb; }
.sp-btn:hover{ transform:translateY(-1px); filter:brightness(.98); }

@media (max-width:768px){
  .sp-grid-2{ grid-template-columns:1fr; }
  .sp-header{ flex-direction:column; align-items:flex-start; }
}
</style>

<script>
(function(){
  const popupType = document.getElementById('popupType');
  const details   = document.getElementById('popupDetails');

  const triggerWrap = document.getElementById('popupTriggerWrap');
  const metaWrap    = document.getElementById('popupMetaWrap');
  const optWrap     = document.getElementById('popupOptionsWrap');

  function applyPopupUI(){
    const type = popupType.value;

    if(type === 'none'){
      triggerWrap.style.display = 'none';
      metaWrap.style.display    = 'none';
      optWrap.style.display     = 'none';
      // tidak memaksa tutup, biar user tetap bisa lihat pengaturan jika mau
      return;
    }

    triggerWrap.style.display = '';
    metaWrap.style.display    = '';
    optWrap.style.display     = (type === 'checkbox' || type === 'select') ? '' : 'none';

    // bila user memilih selain none, buka otomatis agar jelas
    if(!details.open) details.open = true;
  }

  applyPopupUI();
  popupType.addEventListener('change', applyPopupUI);
})();
</script>
@endsection
