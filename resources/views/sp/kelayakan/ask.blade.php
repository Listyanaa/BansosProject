@extends('layouts.app')

@section('title', 'Pertanyaan Kelayakan')

@section('content')
<div class="sp-container">
    {{-- Header --}}
    <div class="sp-toolbar">
        <div class="sp-title-group">
            <h3 class="sp-title">Pertanyaan</h3>
            <p class="sp-subtitle">
                Jawab setiap pertanyaan sesuai kondisi penerima untuk menentukan kelayakan.
            </p>
        </div>

        <a href="{{ route('sp.kelayakan.index') }}" class="sp-btn-back">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    {{-- Card Pertanyaan --}}
    <div class="sp-card-form">
        {{-- Info penerima --}}
        <div class="sp-info-row">
            <span class="sp-info-label">Penerima</span>
            <div class="sp-info-pill">
                {{ $penerima->nama }} — {{ $penerima->nik }}
            </div>
        </div>

        {{-- Pertanyaan --}}
        <div class="sp-question-box">
            <span class="sp-question-code">{{ $kode }}</span>
            <span class="sp-question-text">{{ $teks }}</span>
        </div>

       
        {{-- Form jawaban --}}
        @php
            // fallback kalau $syarat null
            $popupType    = $syarat->popup_type ?? 'none';
            $popupTrigger = $syarat->popup_trigger ?? 'none';
            $popupLabel   = $syarat->popup_label ?? null;
            $popupPlaceholder = $syarat->popup_placeholder ?? null;
            $popupOptions = $syarat->popup_options ?? null;
        @endphp

        <form method="post"
            action="{{ route('sp.kelayakan.answer') }}"
            class="sp-answer-form"
            id="sp-answer-form"
            data-popup-type="{{ $popupType }}"
            data-popup-trigger="{{ $popupTrigger }}"
            data-popup-label="{{ e($popupLabel) }}"
            data-popup-placeholder="{{ e($popupPlaceholder) }}"
            data-popup-options='{{ $popupOptions ?? '[]' }}'>
            @csrf
            <input type="hidden" name="facts" value='@json($facts)'>
            <input type="hidden" name="kode"  value="{{ $kode }}">
            <input type="hidden" name="penerima_id" value="{{ $penerima->id }}">

            <input type="hidden" name="jawaban" id="sp-jawaban">

            <button type="button" class="sp-btn-yes" data-answer="ya">
                <i class="fa-solid fa-check"></i>
                <span>Ya</span>
            </button>

            <button type="button" class="sp-btn-no" data-answer="tidak">
                <i class="fa-solid fa-xmark"></i>
                <span>Tidak</span>
            </button>

            <div id="sp-popup-backdrop" class="sp-popup-backdrop" style="display:none;">
                <div class="sp-popup-modal" role="dialog" aria-modal="true" aria-labelledby="sp-popup-title">
                    <h4 id="sp-popup-title" class="sp-popup-title">Detail Jawaban</h4>
                    <p class="sp-popup-desc" id="sp-popup-desc"></p>

                    <div id="sp-popup-field-container"></div>

                    <div class="sp-popup-actions">
                        <button type="button" class="sp-popup-btn sp-popup-btn-secondary" id="sp-popup-cancel">
                            Batal
                        </button>
                        <button type="button" class="sp-popup-btn sp-popup-btn-primary" id="sp-popup-ok">
                            Simpan &amp; Lanjut
                        </button>
                    </div>
                </div>
            </div>
         
        </form>


        {{-- Fakta sementara --}}
        @if(!empty($facts))
            <div class="sp-facts-box">
                <p class="sp-facts-title">Fakta sementara:</p>
                <ul class="sp-facts-list">
                    @foreach($facts as $k=>$v)
                        <li>{{ $k }} = {{ $v ? 'Ya' : 'Tidak' }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

<style>
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
.sp-info-row{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:14px;
}
.sp-info-label{
    font-size:0.9rem;
    font-weight:600;
    color:#4b5563;
}
.sp-info-pill{
    padding:6px 10px;
    border-radius:999px;
    background:#eff6ff;
    color:#1d4ed8;
    font-weight:600;
    font-size:0.9rem;
}
.sp-question-box{
    display:flex;
    align-items:flex-start;
    gap:10px;
    padding:12px 12px;
    border-radius:10px;
    background:#f9fafb;
    border:1px solid #e5e7eb;
    margin-bottom:16px;
}
.sp-question-code{
    padding:4px 8px;
    border-radius:999px;
    background:#e0ecff;
    color:#1d4ed8;
    font-weight:700;
    font-size:0.8rem;
    min-width:50px;
    text-align:center;
}
.sp-question-text{
    flex:1;
    font-size:0.95rem;
    color:#111827;
}
.sp-answer-form{
    display:flex;
    gap:10px;
    margin-bottom:12px;
}
.sp-btn-yes,
.sp-btn-no{
    flex:1;
    border:none;
    border-radius:10px;
    padding:10px 14px;
    font-weight:700;
    font-size:0.95rem;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    cursor:pointer;
    transition:transform .2s ease, filter .2s ease;
}
.sp-btn-yes{
    background:#16a34a;
    color:#fff;
}
.sp-btn-no{
    background:#dc2626;
    color:#fff;
}
.sp-btn-yes:hover,
.sp-btn-no:hover{
    transform:translateY(-2px);
    filter:brightness(.95);
}
.sp-facts-box{
    margin-top:4px;
    padding-top:10px;
    border-top:1px dashed #e5e7eb;
}
.sp-facts-title{
    font-size:0.9rem;
    font-weight:600;
    color:#374151;
    margin-bottom:4px;
}
.sp-facts-list{
    margin:0;
    padding-left:18px;
    font-size:0.9rem;
    color:#111827;
}
@media (max-width:768px){
    .sp-toolbar{ flex-direction:column; align-items:flex-start; }
    .sp-answer-form{ flex-direction:column; }
}

/* ===== Modal Pop Up Sistem Pakar ===== */
.sp-popup-backdrop{
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,.45);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    padding: 16px;
}
.sp-popup-modal{
    width: min(420px, 100%);
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 22px 45px rgba(15,23,42,.22);
    padding: 16px 16px 14px;
}
.sp-popup-title{
    margin: 0 0 6px 0;
    font-size: 1.05rem;
    font-weight: 700;
    color: #0b1324;
}
.sp-popup-desc{
    margin: 0 0 10px 0;
    font-size: 0.9rem;
    color: #6b7280;
}
.sp-popup-actions{
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 14px;
}
.sp-popup-btn{
    border: none;
    border-radius: 10px;
    padding: 8px 12px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: transform .2s ease, filter .2s ease;
}
.sp-popup-btn-primary{
    background: #2563eb;
    color: #fff;
}
.sp-popup-btn-secondary{
    background: #e5e7eb;
    color: #111827;
}
.sp-popup-btn:hover{
    transform: translateY(-1px);
    filter: brightness(.96);
}
.sp-popup-field-label{
    display:block;
    margin-bottom:6px;
    font-size:0.9rem;
    font-weight:600;
    color:#111827;
}
.sp-popup-input{
    width:100%;
    padding:9px 10px;
    border-radius:8px;
    border:1px solid #d1d5db;
    font-size:0.95rem;
    outline:none;
}
.sp-popup-input:focus{
    border-color:rgba(37,99,235,.7);
    box-shadow:0 0 0 3px rgba(37,99,235,.15);
}
.sp-popup-checkbox-group{
    display:flex;
    flex-direction:column;
    gap:4px;
    max-height:200px;
    overflow-y:auto;
    padding:4px;
    border-radius:8px;
    border:1px solid #e5e7eb;
    background:#f9fafb;
    margin-top:4px;
}
.sp-popup-checkbox-item{
    display:flex;
    align-items:center;
    gap:6px;
    font-size:0.9rem;
    color:#111827;
}
.sp-popup-checkbox-item input{
    width:16px;
    height:16px;
}
@media (max-width:768px){
    .sp-popup-modal{
        width:100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('sp-answer-form');
    if (!form) return;

    const btnYes = form.querySelector('.sp-btn-yes');
    const btnNo  = form.querySelector('.sp-btn-no');
    const hiddenJawaban = document.getElementById('sp-jawaban');

    const popupBackdrop = document.getElementById('sp-popup-backdrop');
    const popupTitle    = document.getElementById('sp-popup-title');
    const popupDesc     = document.getElementById('sp-popup-desc');
    const popupFieldContainer = document.getElementById('sp-popup-field-container');
    const popupCancel   = document.getElementById('sp-popup-cancel');
    const popupOk       = document.getElementById('sp-popup-ok');

    let pendingAnswer = null; // "ya" atau "tidak"

    const popupType    = (form.dataset.popupType || 'none').toLowerCase();
    const popupTrigger = (form.dataset.popupTrigger || 'none').toLowerCase();
    const popupLabel   = form.dataset.popupLabel || '';
    const popupPlaceholder = form.dataset.popupPlaceholder || '';
    let popupOptions   = [];

    try {
        popupOptions = JSON.parse(form.dataset.popupOptions || '[]');
        if (!Array.isArray(popupOptions)) popupOptions = [];
    } catch (e) {
        popupOptions = [];
    }

    function escapeHtml(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function shouldOpenPopup(answer) {
        if (popupType === 'none') return false;
        if (popupTrigger === 'any') return true;
        if (popupTrigger === 'yes' && answer === 'ya') return true;
        if (popupTrigger === 'no'  && answer === 'tidak') return true;
        return false;
    }

    function openPopup(answer) {
        pendingAnswer = answer;

        const questionText = document.querySelector('.sp-question-text')?.textContent || 'Detail jawaban';
        popupTitle.textContent = 'Detail Jawaban';
        popupDesc.textContent  = popupLabel || questionText;

       
        let html = '';
        if (popupType === 'number') {
            html += `
                <label class="sp-popup-field-label">${escapeHtml(popupLabel || 'Masukkan nilai')}</label>
                <input type="number" name="popup_value" class="sp-popup-input" placeholder="${escapeHtml(popupPlaceholder || '')}">
            `;
        } else if (popupType === 'text') {
            html += `
                <label class="sp-popup-field-label">${escapeHtml(popupLabel || 'Masukkan keterangan')}</label>
                <textarea name="popup_value" class="sp-popup-input" rows="3" placeholder="${escapeHtml(popupPlaceholder || '')}"></textarea>
            `;
        } else if (popupType === 'checkbox') {
            html += `<label class="sp-popup-field-label">${escapeHtml(popupLabel || 'Pilih yang sesuai')}</label>`;
            html += `<div class="sp-popup-checkbox-group">`;
            if (popupOptions.length === 0) {
                html += `<div style="font-size:0.85rem;color:#6b7280;">Belum ada opsi pada konfigurasi syarat.</div>`;
            } else {
                popupOptions.forEach(function(opt, idx) {
                    html += `
                        <label class="sp-popup-checkbox-item">
                            <input type="checkbox" name="popup_options[]" value="${escapeHtml(opt)}">
                            <span>${escapeHtml(opt)}</span>
                        </label>
                    `;
                });
            }
            html += `</div>`;
        } else if (popupType === 'select') {
            html += `<label class="sp-popup-field-label">${escapeHtml(popupLabel || 'Pilih salah satu')}</label>`;
            html += `<select name="popup_value" class="sp-popup-input">`;
            html += `<option value="">-- Pilih --</option>`;
            popupOptions.forEach(function(opt) {
                html += `<option value="${escapeHtml(opt)}">${escapeHtml(opt)}</option>`;
            });
            html += `</select>`;
        } else {
          
            pendingAnswer = null;
            return submitForm(answer);
        }

        popupFieldContainer.innerHTML = html;
        popupBackdrop.style.display = 'flex';
    }

    function closePopup() {
        popupBackdrop.style.display = 'none';
        popupFieldContainer.innerHTML = '';
        pendingAnswer = null;
    }

    function submitForm(answer) {
        hiddenJawaban.value = answer;
        form.submit();
    }

    if (btnYes) {
        btnYes.addEventListener('click', function(e) {
            e.preventDefault();
            const answer = 'ya';
            if (shouldOpenPopup(answer)) {
                openPopup(answer);
            } else {
                submitForm(answer);
            }
        });
    }

    if (btnNo) {
        btnNo.addEventListener('click', function(e) {
            e.preventDefault();
            const answer = 'tidak';
            if (shouldOpenPopup(answer)) {
                openPopup(answer);
            } else {
                submitForm(answer);
            }
        });
    }

    if (popupCancel) {
        popupCancel.addEventListener('click', function() {
            closePopup();
        });
    }

    if (popupOk) {
        popupOk.addEventListener('click', function() {
            if (!pendingAnswer) {
                closePopup();
                return;
            }
           
            submitForm(pendingAnswer);
        });
    }

    popupBackdrop?.addEventListener('click', function(e) {
        if (e.target === popupBackdrop) {
            closePopup();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePopup();
        }
    });
});
</script>

@endsection
