<div id="popupForm" class="popup-form" style="display:none;">
    <div class="popup-content">
        <span class="close-btn" id="closePopup">&times;</span>
        <h2>
            @if(isset($editItem))
                Edit Data Penerima Bantuan
            @else
                Tambah Data Penerima Bantuan
            @endif
        </h2>

        <form action="{{ isset($editItem) ? url('/data-bansos/' . $editItem->id) : url('/data-bansos') }}" method="POST">
            @csrf
            @if(isset($editItem))
                @method('PUT')
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" style="background:#fee2e2; color:#b91c1c; padding:10px; border-radius:6px; margin-bottom:15px;">
                    <strong>⚠️ Terjadi kesalahan:</strong>
                    <ul style="margin:8px 0 0 15px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid-2">
                <!-- NIK -->
                <div class="form-group">
                    <label for="nik">NIK</label>
                    <input type="text" id="nik" name="nik"
                           value="{{ old('nik', $editItem->nik ?? '') }}"
                           maxlength="20" required>
                </div>

                <!-- Nama Lengkap -->
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama"
                           value="{{ old('nama', $editItem->nama ?? '') }}"
                           required>
                </div>

                <!-- Tempat / Tanggal Lahir -->
                <div class="form-group">
                    <label for="tempat_tgl_lahir">Tempat / Tanggal Lahir</label>
                    <input
                        type="text"
                        id="tempat_tgl_lahir"
                        name="tempat_tgl_lahir"
                        value="{{ old('tempat_tgl_lahir', $editItem->tempat_tgl_lahir ?? '') }}"
                        placeholder="Contoh: Parepare, 01-01-2000">
                </div>

                <!-- Jenis Kelamin -->
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <div class="radio-row">
                        <label>
                            <input type="radio" name="jenis_kelamin" value="Laki-laki"
                                   {{ old('jenis_kelamin', $editItem->jenis_kelamin ?? '')=='Laki-laki' ? 'checked' : '' }}
                                   required> Laki-laki
                        </label>
                        <label>
                            <input type="radio" name="jenis_kelamin" value="Perempuan"
                                   {{ old('jenis_kelamin', $editItem->jenis_kelamin ?? '')=='Perempuan' ? 'checked' : '' }}
                                   required> Perempuan
                        </label>
                    </div>
                </div>

                <!-- Agama -->
                <div class="form-group">
                    <label for="agama">Agama</label>
                    <select id="agama" name="agama">
                        <option value="">-- Pilih --</option>
                        <option value="Islam"     {{ old('agama', $editItem->agama ?? '')=='Islam' ? 'selected' : '' }}>Islam</option>
                        <option value="Kristen"   {{ old('agama', $editItem->agama ?? '')=='Kristen' ? 'selected' : '' }}>Kristen</option>
                        <option value="Katolik"   {{ old('agama', $editItem->agama ?? '')=='Katolik' ? 'selected' : '' }}>Katolik</option>
                        <option value="Hindu"     {{ old('agama', $editItem->agama ?? '')=='Hindu' ? 'selected' : '' }}>Hindu</option>
                        <option value="Buddha"    {{ old('agama', $editItem->agama ?? '')=='Buddha' ? 'selected' : '' }}>Buddha</option>
                        <option value="Konghucu"  {{ old('agama', $editItem->agama ?? '')=='Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        <option value="Lainnya"   {{ old('agama', $editItem->agama ?? '')=='Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <!-- Status Pernikahan -->
                <div class="form-group">
                    <label for="status_pernikahan">Status Pernikahan</label>
                    <select id="status_pernikahan" name="status_pernikahan">
                        <option value="">-- Pilih --</option>
                        <option value="Belum Menikah" {{ old('status_pernikahan', $editItem->status_pernikahan ?? '')=='Belum Menikah' ? 'selected':'' }}>Belum Menikah</option>
                        <option value="Menikah" {{ old('status_pernikahan', $editItem->status_pernikahan ?? '')=='Menikah' ? 'selected':'' }}>Menikah</option>
                        <option value="Cerai Hidup" {{ old('status_pernikahan', $editItem->status_pernikahan ?? '')=='Cerai Hidup' ? 'selected':'' }}>Cerai Hidup</option>
                        <option value="Cerai Mati" {{ old('status_pernikahan', $editItem->status_pernikahan ?? '')=='Cerai Mati' ? 'selected':'' }}>Cerai Mati</option>
                    </select>
                </div>

                <!-- Status Kepala Keluarga -->
                <div class="form-group">
                    <label for="status_kepala_keluarga">Status Kepala Keluarga</label>
                    <select id="status_kepala_keluarga" name="status_kepala_keluarga">
                        <option value="">-- Pilih --</option>
                        <option value="Ya" {{ old('status_kepala_keluarga', $editItem->status_kepala_keluarga ?? '')=='Ya' ? 'selected':'' }}>Ya</option>
                        <option value="Tidak" {{ old('status_kepala_keluarga', $editItem->status_kepala_keluarga ?? '')=='Tidak' ? 'selected':'' }}>Tidak</option>
                    </select>
                </div>

                <!-- Pekerjaan -->
                <div class="form-group">
                    <label for="pekerjaan">Pekerjaan</label>
                    <input type="text" id="pekerjaan" name="pekerjaan"
                           value="{{ old('pekerjaan', $editItem->pekerjaan ?? '') }}">
                </div>

                <!-- Kecamatan -->
                <div class="form-group">
                    <label for="kecamatan">Kecamatan</label>
                    <select id="kecamatan" name="kecamatan" required>
                        <option value="">-- Pilih Kecamatan --</option>
                        <option value="Bacukiki" {{ old('kecamatan', $editItem->kecamatan ?? '')=='Bacukiki' ? 'selected':'' }}>Bacukiki</option>
                        <option value="Bacukiki Barat" {{ old('kecamatan', $editItem->kecamatan ?? '')=='Bacukiki Barat' ? 'selected':'' }}>Bacukiki Barat</option>
                        <option value="Soreang" {{ old('kecamatan', $editItem->kecamatan ?? '')=='Soreang' ? 'selected':'' }}>Soreang</option>
                        <option value="Ujung" {{ old('kecamatan', $editItem->kecamatan ?? '')=='Ujung' ? 'selected':'' }}>Ujung</option>
                    </select>
                </div>

                <!-- Kelurahan -->
                <div class="form-group">
                    <label for="kelurahan">Kelurahan</label>
                    <select id="kelurahan" name="kelurahan" required>
                        <option value="">-- Pilih Kelurahan --</option>
                        {{-- nilai lama saat edit --}}
                        @if(old('kelurahan', $editItem->kelurahan ?? false))
                            <option selected>{{ old('kelurahan', $editItem->kelurahan ?? '') }}</option>
                        @endif
                    </select>
                </div>

                {{-- SCRIPT UNTUK OTOMATIS MENGUBAH KELURAHAN --}}
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const kecamatanSelect = document.getElementById('kecamatan');
                    const kelurahanSelect = document.getElementById('kelurahan');

                    const dataKelurahan = {
                        "Bacukiki": ["Galung Maloang", "Lemoe", "Lompoe", "Watang Bacukiki"],
                        "Bacukiki Barat": ["Cappa Galung", "Kampung Baru", "Lumpue", "Sumpang Minangae", "Tiro Sompe", "Bumi Harapan"],
                        "Soreang": ["Bukit Harapan", "Bukit Indah", "Kampung Pisang", "Lakessi", "Ujung Baru", "Ujung Lare", "Watang Soreang"],
                        "Ujung": ["Labukkang", "Lapadde", "Mallusetasi", "Ujung Bulu", "Ujung Sabbang"]
                    };

                    function updateKelurahan(selectedKecamatan) {
                        kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';

                        if (dataKelurahan[selectedKecamatan]) {
                            dataKelurahan[selectedKecamatan].forEach(function(kel) {
                                const option = document.createElement('option');
                                option.value = kel;
                                option.textContent = kel;
                                kelurahanSelect.appendChild(option);
                            });
                        }
                    }

                    kecamatanSelect.addEventListener('change', function() {
                        updateKelurahan(this.value);
                    });

                    const oldKecamatan = "{{ old('kecamatan') }}";
                    const oldKelurahan = "{{ old('kelurahan') }}";
                    if (oldKecamatan) {
                        updateKelurahan(oldKecamatan);
                        if (oldKelurahan) kelurahanSelect.value = oldKelurahan;
                    }
                });
                </script>

                <!-- Alamat Lengkap -->
                <div class="form-group grid-1" style="grid-column:1 / -1;">
                    <label for="alamat_lengkap">Alamat Lengkap</label>
                    <textarea id="alamat_lengkap" name="alamat_lengkap" rows="3" required>{{ old('alamat_lengkap', $editItem->alamat_lengkap ?? '') }}</textarea>
                </div>

                <!-- Latitude -->
                <div class="form-group">
                    <label for="latitude">Latitude</label>
                    <input type="number" step="0.0000001" id="latitude" name="latitude"
                        min="-90" max="0"
                        value="{{ old('latitude', $editItem->latitude ?? '') }}" required>

                    <small id="latHint" style="display:none; margin-top:6px; color:#b91c1c; font-size:12px; font-weight:600;"></small>
                </div>

                <!-- Longitude -->
                <div class="form-group">
                    <label for="longitude">Longitude</label>
                    <input type="number" step="0.0000001" id="longitude" name="longitude"
                           value="{{ old('longitude', $editItem->longitude ?? '') }}" required>
                </div>

                <!-- PETA KOORDINAT -->
                <div class="form-group grid-1" style="grid-column:1 / -1;">
                    <label>Pilih Lokasi di Peta</label>
                    <div id="mapPicker"></div>
                    <small style="color:#6b7280; font-size:12px;">
                        Klik pada peta untuk mengisi Latitude & Longitude, atau ubah angka untuk memindahkan marker.
                    </small>
                </div>

                <!-- Tanggal Menerima Layanan -->
                <div class="form-group">
                    <label for="tanggal_menerima_layanan">Tanggal Menerima Layanan</label>
                    <input type="date" id="tanggal_menerima_layanan" name="tanggal_menerima_layanan"
                           value="{{ old('tanggal_menerima_layanan', $editItem->tanggal_menerima_layanan ?? '') }}"
                           required>
                </div>

                <!-- Tanggal Meninggal -->
                <div class="form-group">
                    <label for="tanggal_meninggal">Tanggal Meninggal</label>
                    <input type="date" id="tanggal_meninggal" name="tanggal_meninggal"
                           value="{{ old('tanggal_meninggal', $editItem->tanggal_meninggal ?? '') }}">
                </div>
            </div>

            <div style="display: flex; justify-content: center; gap: 15px; margin-top: 25px;">
                <button type="submit" class="btn btn-primary">
                    <span class="btnText">Simpan</span>
                    <i class="btnSpinner fas fa-spinner fa-spin" style="display:none; margin-left:10px;"></i>
                </button>


                <a href="{{ url('/data-bansos') }}"
                   class="btn-cancel"
                   style="background-color:#6b7280; color:white; padding:10px 25px; border-radius:8px; font-weight:600; text-decoration:none; text-align:center; width:120px; transition:0.2s;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const kelurahanSelect = document.getElementById('kelurahan');
    const kecamatanInput  = document.getElementById('kecamatan');
    if (kelurahanSelect && kecamatanInput) {
        const kecamatanMap = {
            'Ujung Bulu': 'Ujung',
            'Ujung Lare': 'Ujung',
            'Sumpang Minangae': 'Bacukiki Barat',
            'Cappa Galung': 'Bacukiki Barat',
            'Watang Bacukiki': 'Bacukiki',
            'Lemoe': 'Bacukiki',
            'Lapadde': 'Soreang',
            'Bukit Harapan': 'Soreang'
        };
        kelurahanSelect.addEventListener('change', function() {
            kecamatanInput.value = kecamatanMap[this.value] || '';
        });
    }

    const latInput     = document.getElementById('latitude');
    const lngInput     = document.getElementById('longitude');
    const mapContainer = document.getElementById('mapPicker');

    const formEl = document.querySelector('#popupForm form');
    const latHint = document.getElementById('latHint');

    function validateLatitude(showMessage = false) {
        if (!latInput) return true;

        const v = parseFloat(latInput.value);
        let msg = '';

        if (isNaN(v)) {
            msg = 'Latitude wajib diisi.';
        } else if (v >= 0) {
            msg = 'Latitude harus bernilai negatif (wajib pakai tanda -).';
        } else if (v < -90 || v > 90) {
            msg = 'Nilai latitude harus di antara -90 dan 90.';
        }

        latInput.setCustomValidity(msg);

        if (latHint) {
            latHint.textContent = msg;
            latHint.style.display = msg ? 'block' : 'none';
        }

        if (showMessage && msg) latInput.reportValidity();
        return !msg;
    }

    latInput?.addEventListener('input', () => validateLatitude(false));
    latInput?.addEventListener('blur',  () => validateLatitude(true));

    formEl?.addEventListener('submit', function (e) {
        if (!validateLatitude(true)) {
            e.preventDefault(); 
        }
    });

    if (latInput && lngInput && mapContainer && typeof L !== 'undefined') {
        let lat = parseFloat(latInput.value);
        let lng = parseFloat(lngInput.value);

        if (isNaN(lat) || isNaN(lng)) {
            lat = -4.016000;
            lng = 119.633000;
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
        }

        const map = L.map('mapPicker').setView([lat, lng], 14);
        window.mapPickerInstance = map;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        map.on('click', function(e) {
            const clickedLat = e.latlng.lat;
            const clickedLng = e.latlng.lng;

            latInput.value = clickedLat.toFixed(6);
            lngInput.value = clickedLng.toFixed(6);

            marker.setLatLng(e.latlng);
        });

        marker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            latInput.value = pos.lat.toFixed(6);
            lngInput.value = pos.lng.toFixed(6);
        });

        function updateMarkerFromInputs() {
            const latVal = parseFloat(latInput.value);
            const lngVal = parseFloat(lngInput.value);

            if (!isNaN(latVal) && !isNaN(lngVal)) {
                const newLatLng = L.latLng(latVal, lngVal);
                marker.setLatLng(newLatLng);
                map.panTo(newLatLng);
            }
        }

        latInput.addEventListener('input',  updateMarkerFromInputs);
        lngInput.addEventListener('input',  updateMarkerFromInputs);
        latInput.addEventListener('change', updateMarkerFromInputs);
        lngInput.addEventListener('change', updateMarkerFromInputs);
    }
});
</script>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popup = document.getElementById('popupForm');
        popup.style.display = 'block';

        if (window.mapPickerInstance) {
            setTimeout(function() {
                window.mapPickerInstance.invalidateSize();
            }, 200);
        }
    });
</script>
@endif

<style>
    .popup-form {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }
    .popup-content {
        background-color: #fff;
        margin: 50px auto;
        padding: 30px;
        border-radius: 10px;
        width: 90%;
        max-width: 900px;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
        max-height: 85vh;
        overflow-y: auto;
    }
    .close-btn {
        float: right;
        font-size: 24px;
        cursor: pointer;
    }
    .form-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    .form-group label {
        font-weight: bold;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }
    .grid-1 { display: block; }

    .form-group { display:flex; flex-direction:column; margin-bottom:12px; }
    .form-group label { font-weight:600; margin-bottom:6px; color:#1f2937; font-size:14px; }
    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group input[type="date"],
    .form-group select,
    .form-group textarea {
        padding: 9px 10px;
        border-radius:8px;
        border:1px solid #d1d5db;
        font-size:14px;
    }

    .radio-row { display:flex; gap:12px; align-items:center; }
    .checkbox-row { display:flex; gap:8px; flex-wrap:wrap; }

    .btn-submit {
        width:100%;
        padding:12px;
        border-radius:8px;
        border:none;
        background:#16a34a;
        color:white;
        font-weight:700;
    }

    .form-actions {
        text-align: right;
        margin-top: 20px;
    }

    #mapPicker {
        width: 100%;
        height: 260px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        margin-top: 4px;
        overflow: hidden;
    }
</style>
