@extends('layouts.app')

@section('title', 'Hasil Kelayakan')

@section('content')
<div class="sp-container">
    {{-- Header --}}
    <div class="sp-toolbar">
        <div class="sp-title-group">
            <h3 class="sp-title">Hasil Kelayakan</h3>
            <p class="sp-subtitle">Ringkasan hasil tanya–jawab untuk penerima terpilih.</p>
        </div>

        <a href="{{ route('sp.kelayakan.index') }}" class="sp-btn-back">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Cek Penerima Lain</span>
        </a>
    </div>

    {{-- Card Hasil --}}
    <div class="sp-card-form">
        {{-- Status hasil --}}
        <div class="sp-result-box {{ $hasil === 'Layak' ? 'sp-result-ok' : 'sp-result-no' }}">
            <div class="sp-result-main">
                <span class="sp-result-label">Hasil</span>
                <span class="sp-result-pill">{{ $hasil }}</span>
            </div>
            @if(isset($rule) && $rule)
                <div class="sp-result-rule">
                    Aturan terpicu: <em>{{ $rule }}</em>
                </div>
            @endif
        </div>

        {{-- Penerima --}}
        <div class="sp-info-row" style="margin-top:14px;">
            <span class="sp-info-label">Penerima</span>
            <div class="sp-info-pill">
                {{ $penerima->nama }} — {{ $penerima->nik }}
            </div>
        </div>

        {{-- Fakta akhir --}}
        @if(!empty($facts))
            <div class="sp-facts-box" style="margin-top:12px;">
                <p class="sp-facts-title">Fakta akhir:</p>
                <ul class="sp-facts-list">
                    @foreach($facts as $k=>$v)
                        <li>{{ $k }} = {{ $v ? 'Ya' : 'Tidak' }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="sp-form-buttons" style="margin-top:18px;">
            <a href="{{ route('sp.kelayakan.index') }}" class="sp-btn-secondary">
                <i class="fa-solid fa-user-check"></i>
                <span>Cek Penerima Lain</span>
            </a>
            <a href="{{ route('sp.kelayakan.history', ['penerima_id' => $penerima->id]) }}" class="sp-btn-primary">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Lihat Riwayat Penerima Ini</span>
            </a>
        </div>
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

/* Result box */
.sp-result-box{
    border-radius:12px;
    padding:12px 14px;
    display:flex;
    flex-direction:column;
    gap:4px;
}
.sp-result-ok{
    background:#dcfce7;
    border:1px solid #bbf7d0;
}
.sp-result-no{
    background:#fee2e2;
    border:1px solid #fecaca;
}
.sp-result-main{
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
}
.sp-result-label{
    font-size:0.9rem;
    font-weight:600;
    color:#065f46;
}
.sp-result-no .sp-result-label{ color:#991b1b; }
.sp-result-pill{
    padding:6px 12px;
    border-radius:999px;
    font-weight:800;
    font-size:0.9rem;
    background:#fff;
    color:#111827;
}
.sp-result-rule{
    font-size:0.85rem;
    color:#374151;
}

/* Info penerima & fakta (reuse dari ask) */
.sp-info-row{
    display:flex;
    align-items:center;
    gap:10px;
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
.sp-facts-box{
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
    text-decoration:none;
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
    .sp-form-buttons{ flex-direction:column; align-items:stretch; }
}
</style>
@endsection
