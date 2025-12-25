@extends('layouts.app')

@section('title', 'Peta Distribusi')

@section('content')
<div class="container-bansos">
    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="header-title">Peta Distribusi Penerima Bantuan</h1>
            <p class="header-subtitle">Lihat distribusi penerima</p>
        </div>
    </div>

    <div class="search-box">
        <div class="search-input">
            <i class="fa-solid fa-magnifying-glass"></i>

            <input type="text" id="searchNIK" placeholder="Cari NIK / Nama penerima..." />

            <button type="button" class="clear-btn" id="clearSearchBtn" aria-label="Bersihkan">&times;</button>
        </div>

        <button class="btn-primary" type="button" onclick="cariData()">
            <i class="fa-solid fa-magnifying-glass"></i> Cari
        </button>
    </div>

    <div class="grid-wrapper">
        <div class="map-card">
            <div id="map"></div>
        </div>

        <div class="hasil-box">
            <h4>📋 Data Penerima</h4>
            <div id="hasilContainer" class="hasil-container"></div>
        </div>
    </div>
</div>

<div id="detailModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="tutupModal()">&times;</span>
        <h3>Detail Data Penerima</h3>
        <form id="detailForm" class="detail-form">
           
        </form>
        <div style="text-align:right; margin-top:10px;">
            <button class="btn-primary" type="button" onclick="tutupModal()">
                <i class="fa-solid fa-xmark"></i> Tutup
            </button>
        </div>
    </div>
</div>

{{-- Overlay loading upload foto --}}
<div id="uploadOverlay" class="upload-overlay" style="display:none;">
    <div class="upload-overlay-content">
        <div class="spinner"></div>
        <p>Mengunggah foto distribusi...<br>Silakan tunggu, jangan menutup halaman.</p>
    </div>
</div>

{{-- Leaflet CSS & JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

{{-- Styling --}}
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

.btn-primary {
    background:#2563eb;
    color:#fff;
    border:none;
    border-radius:10px;
    padding:8px 14px;
    font-weight:700;
    display:inline-flex;
    align-items:center;
    gap:8px;
    cursor:pointer;
    box-shadow:0 6px 14px rgba(37,99,235,.25);
    transition: transform .2s ease, filter .2s ease;
}
.btn-primary:hover {
    transform: translateY(-2px);
    filter: brightness(.96);
}

/* Kuning (seperti btn-edit) */
.btn-warning {
    background:#f59e0b;
    color:#fff;
    border:none;
    border-radius:10px;
    padding:6px 12px;
    font-weight:700;
    display:inline-flex;
    align-items:center;
    gap:6px;
    cursor:pointer;
    transition: transform .2s ease, filter .2s ease;
}
.btn-warning:hover {
    transform: translateY(-2px);
    filter: brightness(.95);
}

.container-bansos{
    background:#fff;
    padding:22px;
    border-radius:14px;
    box-shadow:0 6px 14px rgba(0,0,0,.08);
}

.peta-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 18px 22px 22px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.peta-card-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #111827;
}

.peta-card-header p {
    margin: 4px 0 0;
    font-size: 13px;
    color: #6b7280;
}

/* MAP CARD supaya peta ikut rounded + shadow */
.map-card {
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    box-shadow: 0 10px 26px rgba(15, 23, 42, 0.06);
}

/* Map di dalam card: tidak perlu border lagi */
#map {
    height: 480px;
    border-radius: 0;
    border: none;
    cursor: grab;
}

h2 {
    text-align: center;
    margin-bottom: 10px;
}

.search-box {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.search-input{
    display:flex;
    align-items:center;
    gap:10px;
    background:#fff;
    border:1px solid #aaa;
    border-radius:8px;
    padding:8px 10px;
    width: 320px; /* boleh kamu kecilkan/ besarkan */
}

.search-input i{
    color:#64748b;
}

.search-input input{
    border:none;
    outline:none;
    flex:1;
    font-size:0.95rem;
    color:#0f172a;
    background:transparent;
}

/* tombol X */
.clear-btn{
    border:none;
    background:transparent;
    cursor:pointer;
    font-size:1.2rem;
    line-height:1;
    padding:0 4px;
    color:#94a3b8;
    display:none; /* default hidden */
}
.clear-btn:hover{
    color:#64748b;
}

.grid-wrapper {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 15px;
    align-items: start;
}

#map {
    height: 500px;
    border-radius: 10px;
    border: 2px solid #ddd;
    cursor: grab;
}

/* agar map bisa discroll/zoom */
.leaflet-container {
    touch-action: auto !important;
    pointer-events: auto !important;
}

/* Box hasil pencarian */
.hasil-box {
    background-color: #fefefe;
    border-radius: 10px;
    padding: 15px;
    border: 1px solid #ddd;
    max-height: 500px;
    overflow-y: auto;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.hasil-box h4 {
    margin-bottom: 10px;
    text-align: center;
    color: #333;
    font-weight: 600;
}

.hasil-container {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.no-data {
    text-align: center;
    color: #777;
    font-style: italic;
}

/* Card tiap penerima (ringkas & profesional) */
.card-item {
    background-color: #ffffff;
    border: 1px solid #e6e6e6;
    border-radius: 10px;
    padding: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    transition: all 0.15s;
}

.card-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 14px rgba(0,0,0,0.08);
}

.card-header {
    display: flex;
    align-items: center;
    gap: 12px;
}

.card-foto {
    width: 72px;
    height: 72px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    background-color: #f5f5f5;
    border: 1px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-foto img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.add-photo {
    width: 72px;
    height: 72px;
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
    font-size:12px;
    color:#666;
    cursor:pointer;
}

.card-info {
    flex: 1;
}

.card-info .nama {
    font-weight: 600;
    color: #222;
}

.card-info .nik, .card-info .lokasi {
    font-size: 0.9em;
    color: #555;
    margin-top: 4px;
}


/* ========== MODAL OVERLAY ========== */
.modal {
    display: none;              /* dibuka dari JS */
    position: fixed;
    z-index: 999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    padding: 24px;              /* jarak dari tepi layar */
    box-sizing: border-box;
    background-color: rgba(15, 23, 42, 0.45); /* overlay gelap */
    backdrop-filter: blur(2px);
    overflow: auto;             /* jaga-jaga untuk layar sangat kecil */
}

/* ========== CARD DI TENGAH (SEPERTI TAMBAH DATA) ========== */
.modal-content {
    background-color: #fff;
    margin: auto;                              /* center horizontal & vertical (karena ada padding di .modal) */
    padding: 22px 26px 18px;
    border-radius: 18px;
    width: 90%;
    max-width: 900px;
    max-height: calc(100vh - 80px);           /* TIDAK sampai full ke bawah */
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
    position: relative;
    display: flex;
    flex-direction: column;
    overflow: hidden;                          /* yang scroll cuma body/form */
}

/* Judul & tombol close */
.modal-content h3 {
    margin: 0 0 14px;
    font-size: 20px;
    font-weight: 600;
    color: #111827;
}

.close {
    position: absolute;
    right: 18px;
    top: 14px;
    font-size: 22px;
    font-weight: 600;
    color: #6b7280;
    cursor: pointer;
}
.close:hover {
    color: #111827;
}

/* ========== FORM DI DALAM MODAL ========== */
.detail-form {
    margin-top: 8px;
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    column-gap: 18px;
    row-gap: 12px;
    padding-right: 6px;          /* space untuk scrollbar */
    
    /* supaya bagian ini yang scroll, tombol "Tutup" tetap di bawah card */
    flex: 1 1 auto;
    min-height: 0;
    overflow-y: auto;
}

/* group field */
.detail-form .form-group {
    display: flex;
    flex-direction: column;
}

.detail-form label {
    font-weight: 600;
    font-size: 0.85rem;
    color: #374151;
    margin-bottom: 4px;
    text-transform: capitalize;  /* "nik" -> "Nik" dsb */
}

.detail-form input,
.detail-form textarea {
    padding: 8px 10px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: #f9fafb;
    font-size: 0.9rem;
}

.detail-form textarea {
    resize: vertical;
    min-height: 80px;
}

/* field full satu baris (kalau dipakai) */
.detail-full {
    grid-column: 1 / -1;
}

/* Saat modal terbuka, sembunyikan semua control Leaflet */
.hide-leaflet-controls .leaflet-control-container {
    display: none !important;
}
.hide-leaflet-controls .leaflet-container .leaflet-control-attribution {
    display: none !important;
}

/* Skeleton placeholder */
.skeleton-card {
    background: #fff;
    border: 1px solid #e6e6e6;
    border-radius: 10px;
    padding: 12px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    animation: fadeIn 0.4s ease-in-out;
}

.skeleton-header {
    display: flex;
    gap: 12px;
}

.skeleton-photo {
    width: 72px;
    height: 72px;
    background: #e3e3e3;
    border-radius: 8px;
    flex-shrink: 0;
    overflow: hidden;
    position: relative;
}

.skeleton-line {
    height: 12px;
    background: #e3e3e3;
    border-radius: 6px;
    margin-top: 8px;
    width: 100%;
    overflow: hidden;
    position: relative;
}

.skeleton-line.short { width: 60%; }
.skeleton-line.mid { width: 80%; }

.skeleton-photo::after,
.skeleton-line::after {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    height: 100%;
    width: 100%;
    background: linear-gradient(90deg, transparent, #f5f5f5, transparent);
    animation: shimmer 1.2s infinite;
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

@keyframes fadeIn {
    from {opacity: 0;}
    to {opacity: 1;}
}


/* Tombol berbahaya (hapus) */
.btn-danger {
    background:#ef4444;
    color:#fff;
    border:none;
    border-radius:10px;
    padding:6px 12px;
    font-weight:700;
    display:inline-flex;
    align-items:center;
    gap:6px;
    cursor:pointer;
    transition: transform .2s ease, filter .2s ease;
}
.btn-danger:hover {
    transform: translateY(-2px);
    filter: brightness(.95);
}

/* Versi kecil untuk tombol di dalam kartu foto */
.btn-xs {
    padding:4px 8px;
    font-size:11px;
    border-radius:8px;
    box-shadow:none;
}

/* Gallery foto di modal detail */
.foto-list {
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-top:6px;
    margin-bottom:10px;
}

.foto-item {
    background:#f9fafb;
    border:1px solid #e5e7eb;
    border-radius:10px;
    padding:6px;
    max-width:160px;
}

.foto-item img {
    width:100%;
    border-radius:8px;
    display:block;
    margin-bottom:6px;
}

.foto-actions {
    display:flex;
    gap:6px;
}

/* Overlay full screen saat upload foto */
.upload-overlay {
    position: fixed;
    z-index: 2000; /* lebih tinggi dari modal */
    inset: 0;
    background: rgba(15, 23, 42, 0.65);
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(2px);
}

/* Konten di tengah overlay */
.upload-overlay-content {
    background: #ffffff;
    padding: 18px 22px;
    border-radius: 14px;
    text-align: center;
    box-shadow: 0 18px 40px rgba(0, 0, 0, 0.25);
    max-width: 320px;
}

/* Spinner sederhana */
.spinner {
    width: 40px;
    height: 40px;
    border-radius: 999px;
    border: 4px solid #e5e7eb;
    border-top-color: #2563eb;
    margin: 0 auto 12px;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.upload-overlay-content p {
    margin: 0;
    font-size: 0.9rem;
    color: #374151;
}
</style>

{{-- Script --}}
<script>
    // Inisialisasi peta (TIDAK diubah)
    const map = L.map('map').setView([-4.009, 119.630], 13);

    // Layer peta OSM (gratis)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let allMarkers = [];

    // Data dari server (foto_bukti SUDAH dinormalisasi ke array)
    const dataPenerima = @json($data);
    const syaratPopup  = @json($syaratPopup ?? []);

    // Tambah marker (TIDAK diubah — popup MARKER tetap persis semula)
    dataPenerima.forEach((item) => {
        // ⬇️ Skip jika tidak layak
        if ((item.kelayakan || '').toLowerCase().trim() === 'tidak layak') return;

        if (item.latitude && item.longitude) {
            let marker = L.marker([item.latitude, item.longitude]).addTo(map);
            marker.bindPopup(`
                <b>Nama:</b> ${item.nama}<br>
                <b>NIK:</b> ${item.nik}<br>
                <b>Alamat:</b> ${item.alamat_lengkap}<br>
                <b>Koordinat:</b> ${item.latitude}, ${item.longitude}
            `);
            allMarkers.push({ marker, data: item });

            // Klik marker → tampilkan kartu penerima (sama)
            marker.on('click', function() {
                tampilkanHasil([item]);
            });
        }
    });

    // Fungsi pencarian nik penerima
    function cariData() {
        const keyword = document.getElementById('searchNIK').value.trim();

        if (!keyword) {
            alert('Masukkan NIK atau Nama untuk mencari.');
            return;
        }

        fetch(`/peta-distribusi/search?nik=${encodeURIComponent(keyword)}`)
            .then(res => res.json())
            .then(result => {
            if (result.length === 0) {
                alert('Data tidak ditemukan.');
                tampilkanHasil([]);
                return;
            }

            tampilkanHasil(result);

            // Fokus ke marker (pakai nik dari hasil pertama)
            const found = allMarkers.find(m => m.data.nik == result[0].nik);
            if (found) {
                map.setView(found.marker.getLatLng(), 15);
                found.marker.openPopup();
            }
            })
            .catch(err => console.error(err));
        }

    // Fungsi tampilkan hasil di card
    function tampilkanHasil(data) {
        const container = document.getElementById('hasilContainer');
        container.innerHTML = '';

        const filtered = (data || []).filter(
            it => (it.kelayakan || '').toLowerCase().trim() !== 'tidak layak'
        );

        if (!filtered.length) {
            container.innerHTML = `<p class="no-data">Data tidak ditemukan</p>`;
            return;
        }

        filtered.forEach((item) => {
            const card = document.createElement('div');
            card.classList.add('card-item');

            const header = document.createElement('div');
            header.classList.add('card-header');

            const fotoWrapper = document.createElement('div');
            fotoWrapper.classList.add('card-foto');

            // Normalisasi foto_bukti di sisi JS (jaga-jaga)
            const fotos = Array.isArray(item.foto_bukti)
                ? item.foto_bukti
                : (item.foto_bukti ? [item.foto_bukti] : []);

            if (fotos.length > 0) {
                const img = document.createElement('img');
                img.src = `/storage/${fotos[0]}`; // thumbnail pakai foto pertama
                img.alt = 'Foto distribusi';
                fotoWrapper.appendChild(img);
            } else {
                const add = document.createElement('div');
                add.classList.add('add-photo');
                add.innerHTML = '📷<br>Tambah Foto';
                add.addEventListener('click', function(e) {
                    e.stopPropagation();
                    uploadFoto(item.nik); // tambah foto baru (boleh multi)
                });
                fotoWrapper.appendChild(add);
            }

            const info = document.createElement('div');
            info.classList.add('card-info');
            info.innerHTML = `
                <div class="nama">${item.nama}</div>
                <div class="nik"><b>NIK:</b> ${item.nik}</div>
                <div class="lokasi">
                    <b>Kec:</b> ${item.kecamatan || '-'} &nbsp;
                    <b>Kel:</b> ${item.kelurahan || '-'}
                </div>
            `;

            header.appendChild(fotoWrapper);
            header.appendChild(info);

            const btn = document.createElement('button');
            btn.classList.add('btn-primary');
            btn.type = 'button';
            btn.textContent = 'Detail';
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                lihatDetail(item);
            });

            // Klik card → fokus ke marker
            card.addEventListener('click', function() {
                const found = allMarkers.find(m => m.data.nik == item.nik);
                if (found) {
                    map.setView(found.marker.getLatLng(), 15);
                    found.marker.openPopup();
                }
            });

            card.appendChild(header);
            card.appendChild(btn);
            container.appendChild(card);
        });
    }

    // Fungsi lihatDetail -> tampilkan modal berisi form detail + gallery foto
    // Fungsi lihatDetail -> tampilkan modal berisi form detail + gallery foto + jawaban syarat
    function lihatDetail(item) {
        const modal = document.getElementById('detailModal');
        const form  = document.getElementById('detailForm');
        form.innerHTML = '';

        document.body.classList.add('hide-leaflet-controls');

        // ========= 1) GALLERY FOTO (tetap) =========
        const fotos = Array.isArray(item.foto_bukti)
            ? item.foto_bukti
            : (item.foto_bukti ? [item.foto_bukti] : []);

        const imgGroup = document.createElement('div');
        imgGroup.classList.add('detail-full');

        const labelFoto = document.createElement('label');
        labelFoto.textContent = 'Foto Distribusi';
        imgGroup.appendChild(labelFoto);

        const list = document.createElement('div');
        list.classList.add('foto-list');

        if (fotos.length > 0) {
            fotos.forEach((path, idx) => {
                const wrapper = document.createElement('div');
                wrapper.classList.add('foto-item');

                const img = document.createElement('img');
                img.src = `/storage/${path}`;
                img.alt = `Foto distribusi ${idx + 1}`;
                wrapper.appendChild(img);

                const actions = document.createElement('div');
                actions.classList.add('foto-actions');

                const btnGanti = document.createElement('button');
                btnGanti.type = 'button';
                btnGanti.classList.add('btn-warning', 'btn-xs');
                btnGanti.textContent = 'Ganti';
                btnGanti.addEventListener('click', function(e) {
                    e.preventDefault();
                    uploadFoto(item.nik, idx);
                });

                const btnHapus = document.createElement('button');
                btnHapus.type = 'button';
                btnHapus.classList.add('btn-danger', 'btn-xs');
                btnHapus.textContent = 'Hapus';
                btnHapus.addEventListener('click', function(e) {
                    e.preventDefault();
                    hapusFoto(item.nik, idx);
                });

                actions.appendChild(btnGanti);
                actions.appendChild(btnHapus);
                wrapper.appendChild(actions);

                list.appendChild(wrapper);
            });
        } else {
            const info = document.createElement('div');
            info.style.fontSize = '0.85rem';
            info.style.color = '#6b7280';
            info.style.margin = '6px 0 10px';
            info.textContent = 'Belum ada foto distribusi.';
            list.appendChild(info);
        }

        imgGroup.appendChild(list);

        const btnTambah = document.createElement('button');
        btnTambah.type = 'button';
        btnTambah.classList.add('btn-primary', 'btn-xs');
        btnTambah.innerHTML = '<i class="fa-solid fa-plus"></i> Tambah Foto';
        btnTambah.addEventListener('click', function(e) {
            e.preventDefault();
            uploadFoto(item.nik);
        });

        imgGroup.appendChild(btnTambah);
        form.appendChild(imgGroup);

        // ========= 2) FIELD DETAIL PENERIMA (SAMA DENGAN LAPORAN) =========
        const fields = [
            'nik',
            'nama',
            'tempat_tgl_lahir',
            'jenis_kelamin',
            'agama',
            'status_pernikahan',
            'status_kepala_keluarga',
            'kecamatan',
            'kelurahan',
            'alamat_lengkap',
            'latitude',
            'longitude',
            'pekerjaan',
            'tanggal_menerima_layanan',
            'tanggal_meninggal',
            'kelayakan'
        ];

        const labelMap = {
            nik: 'NIK',
            nama: 'Nama',
            tempat_tgl_lahir: 'Tempat / Tgl. Lahir',
            jenis_kelamin: 'Jenis Kelamin',
            agama: 'Agama',
            status_pernikahan: 'Status Pernikahan',
            status_kepala_keluarga: 'Status Kepala Keluarga',
            kecamatan: 'Kecamatan',
            kelurahan: 'Kelurahan',
            alamat_lengkap: 'Alamat Lengkap',
            latitude: 'Latitude',
            longitude: 'Longitude',
            pekerjaan: 'Pekerjaan',
            tanggal_menerima_layanan: 'Tanggal Menerima Layanan',
            tanggal_meninggal: 'Tanggal Meninggal',
            kelayakan: 'Kelayakan'
        };

        fields.forEach(key => {
            const group = document.createElement('div');
            group.classList.add('form-group');

            const label = document.createElement('label');
            label.textContent = labelMap[key] || key.replaceAll('_',' ');
            group.appendChild(label);

            let value = item[key] || '-';

            // Format tanggal ke dd-mm-YYYY
            if (key === 'tanggal_menerima_layanan' || key === 'tanggal_meninggal') {
                if (item[key]) {
                    const parts = String(item[key]).split('-'); // [YYYY, MM, DD]
                    if (parts.length === 3) {
                        value = `${parts[2]}-${parts[1]}-${parts[0]}`;
                    }
                }
            }

            let input;
            if (key === 'alamat_lengkap') {
                input = document.createElement('textarea');
                input.value = value;
            } else {
                input = document.createElement('input');
                input.type = 'text';
                input.value = value;
            }
            input.readOnly = true;
            group.appendChild(input);

            form.appendChild(group);
        });

        // ========= 3) JAWABAN SYARAT (SAMA DENGAN KOLOM DI LAPORAN) =========
        if (Array.isArray(syaratPopup) && syaratPopup.length > 0) {
            syaratPopup.forEach(s => {
                const alias = 'sp_' + String(s.kode).toLowerCase(); // contoh: sp_g01

                const group = document.createElement('div');
                group.classList.add('form-group');

                const label = document.createElement('label');
                label.textContent = s.popup_label || s.teks || s.kode;
                group.appendChild(label);

                // Tambah di atas loop syarat (sekali saja):
                const rupiahCodes = ['G01']; // --> hanya kode ini yg pakai "Rp", sesuaikan kalau ada yg lain

                // Di dalam loop syarat, ganti blok formatnya jadi:
                let rawVal = item[alias];
                let value = rawVal;

                if (value === null || value === undefined || value === '') {
                    value = '-';
                } else if (s.popup_type === 'number') {
                    const num = parseFloat(value);
                    if (!isNaN(num)) {
                        const isRupiah = rupiahCodes.includes(String(s.kode).toUpperCase());
                        value = isRupiah
                        ? 'Rp ' + num.toLocaleString('id-ID')   // untuk penghasilan, dst
                        : num.toLocaleString('id-ID');          // untuk tanggungan, dll (tanpa Rp)
                    }
                }

                const input = document.createElement('input');
                input.type = 'text';
                input.value = value;
                input.readOnly = true;

                group.appendChild(input);
                form.appendChild(group);
            });
        }

        modal.style.display = 'block';
    }


    function tutupModal() {
        document.getElementById('detailModal').style.display = 'none';
        document.body.classList.remove('hide-leaflet-controls');
    }

    // Flag global untuk mencegah double upload
    let isUploadingFoto = false;

    function showUploadLoading() {
        isUploadingFoto = true;
        const overlay = document.getElementById('uploadOverlay');
        if (overlay) overlay.style.display = 'flex';
    }

    function hideUploadLoading() {
        isUploadingFoto = false;
        const overlay = document.getElementById('uploadOverlay');
        if (overlay) overlay.style.display = 'none';
    }


    /**
     * Upload foto:
     * - index === null → tambah foto (boleh multi)
     * - index != null  → ganti foto ke-index tersebut (single)
     */
    function uploadFoto(nik, index = null) {
        // Jika masih ada proses upload yang berjalan, abaikan klik berikutnya
        if (isUploadingFoto) {
            return;
        }

        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';

        // Jika tambah foto, boleh multi; jika ganti, 1 file
        if (index === null) {
            input.multiple = true;
        }

        input.onchange = () => {
            if (!input.files.length) return;

            const formData = new FormData();

            if (index === null) {
                // Tambah foto baru (bisa lebih dari satu)
                Array.from(input.files).forEach(file => {
                    formData.append('foto_bukti[]', file);
                });
            } else {
                // Ganti foto tertentu
                formData.append('foto_bukti', input.files[0]);
                formData.append('index', index);
            }

            // TAMPILKAN LOADING & LOCK SCREEN
            showUploadLoading();

            fetch(`/peta-distribusi/upload-foto/${nik}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    // SUKSES: cukup hentikan loading dan refresh tampilan
                    hideUploadLoading();

                    // kalau mau langsung lihat fotonya ter-update,
                    // cara paling simpel: reload halaman tanpa alert
                    location.reload();
                } else {
                    hideUploadLoading();
                    alert(res.message || 'Gagal upload foto');
                }
            })
            .catch(err => {
                console.error(err);
                hideUploadLoading();
                alert('Terjadi kesalahan upload');
            });
        };

        input.click();
    }

    function hapusFoto(nik, index) {
        if (!confirm('Yakin ingin menghapus foto ini?')) return;

        const formData = new FormData();
        formData.append('index', index);

        fetch(`/peta-distribusi/hapus-foto/${nik}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                alert(res.message || 'Foto berhasil dihapus');
                location.reload();
            } else {
                alert(res.message || 'Gagal menghapus foto');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat menghapus foto');
        });
    }

    // Tutup modal saat klik di luar konten
    window.onclick = function(event) {
        const modal = document.getElementById('detailModal');
        if (event.target == modal) {
            modal.style.display = "none";
            document.body.classList.remove('hide-leaflet-controls');
        }
    };

    // ENTER untuk cari
    document.getElementById('searchNIK').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            cariData();
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const input    = document.getElementById('searchNIK');
        const clearBtn = document.getElementById('clearSearchBtn');

        const initialView = { lat: -4.009, lng: 119.630, zoom: 13 };

        function toggleClearBtn() {
            clearBtn.style.display = input.value.trim() ? 'inline-flex' : 'none';
        }

        function resetSearch() {
            input.value = '';
            toggleClearBtn();

            // ✅ Kembalikan panel kanan ke data awal (BUKAN data kosong)
            tampilkanHasil(dataPenerima);

            // kembalikan peta ke posisi awal
            map.closePopup();
            map.setView([initialView.lat, initialView.lng], initialView.zoom);
        }

        clearBtn.addEventListener('click', function (e) {
            e.preventDefault();
            resetSearch();
        });

        input.addEventListener('input', function () {
            toggleClearBtn();

            // (opsional) kalau user hapus manual sampai kosong, otomatis balikkan list
            if (!input.value.trim()) {
                tampilkanHasil(dataPenerima);
                map.closePopup();
                map.setView([initialView.lat, initialView.lng], initialView.zoom);
            }
        });

        toggleClearBtn();
        // ✅ INI YANG MEMBUAT TAMPILAN AWAL LANGSUNG SEPERTI GAMBAR 2
        tampilkanHasil(dataPenerima);
    });
</script>

@endsection
