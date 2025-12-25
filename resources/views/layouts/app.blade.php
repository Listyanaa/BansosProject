<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Sistem Bansos')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <!-- Leaflet CSS -->
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>

    <!-- Leaflet JS -->
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin="">
    </script>

    <style>
        :root{
            --sidebar-w: 240px;
            --bg: #f5f8ff;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e5e7eb;
            --primary: #2563EB;
            --success: #10b981;
            --danger: #ef4444;
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; }

        body {
            margin: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, Roboto, Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            overflow: hidden; 
        }

        /* Kontainer utama */
        .main-container{
            margin-left: var(--sidebar-w);
            display: flex;
            flex-direction: column;
            width: calc(100% - var(--sidebar-w));
            height: 100vh;
        }

        /* Header */
        .header{
            flex-shrink: 0;
            background: var(--card);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 16px 24px;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .header h1{
            font-size: 1.125rem; 
            margin: 0;
            font-weight: 600;
            color: #0b1324;
        }

        .user-info{
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 6px 10px 6px 6px;
            cursor: pointer;
            transition: box-shadow .2s ease, transform .2s ease;
        }
        .user-info:hover{
            box-shadow: 0 4px 14px rgba(2,6,23,.08);
            transform: translateY(-1px);
        }
        .user-avatar{
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1E3A8A, #2563EB);
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
            display: grid;
            place-items: center;
        }
        .user-name{
            color: var(--muted);
            font-weight: 600;
            font-size: 0.95rem;
            max-width: 40vw;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .content{
            flex: 1 1 auto;
            padding: 24px;
            overflow: auto;
        }

        .modal-backdrop{
            display:none;
            position:fixed;
            inset:0;
            background:rgba(2,6,23,0.45);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            justify-content:center;
            align-items:center;
            z-index:1000;
            padding: 16px;
        }
        .modal-card{
            width: min(420px, 100%);
            background: var(--card);
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 24px 48px rgba(2,6,23,.18);
            padding: 18px 18px 14px;
        }
        .modal-title{
            margin: 0 0 10px 0;
            font-size: 1.05rem;
            font-weight: 700;
            color: #0b1324;
        }
        .form-group{
            display: grid;
            gap: 6px;
            margin-bottom: 12px;
        }
        .form-group label{
            font-size: .875rem;
            color: var(--muted);
            font-weight: 600;
        }
        .form-control{
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--text);
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .form-control:focus{
            border-color: rgba(37,99,235,.6);
            box-shadow: 0 0 0 3px rgba(37,99,235,.15);
        }
        .divider{
            height: 1px;
            background: var(--border);
            margin: 12px 0 10px;
            border: 0;
        }
        .modal-actions{
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 8px;
        }
        .btn{
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: .95rem;
        }
        .btn-secondary{ background:#e5e7eb; color:#111827; }
        .btn-secondary:hover{ background:#d1d5db; }
        .btn-primary{ background: var(--primary); color:#fff; }
        .btn-primary:hover{ filter: brightness(0.95); }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to   { transform: translateX(0);   opacity: 1; }
        }

        @media (max-width: 768px){
            .main-container{
                margin-left: 0;
                width: 100%;
            }
            .header{
                padding: 14px 16px;
            }
            .content{
                padding: 16px;
            }
            .user-name{ max-width: 50vw; }
        }

        .form-control:focus{
            border-color: rgba(37,99,235,.6);
            box-shadow: 0 0 0 3px rgba(37,99,235,.15);
        }

        .form-control.is-invalid{
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(239,68,68,.15);
        }
        .invalid-feedback{
            font-size: 0.8rem;
            color: var(--danger);
        }

        .btn .btnSpinner { display: none; margin-left: 10px; }

        .btn.is-loading .btnSpinner { display: inline-block; }

        .btn.is-loading { opacity: .85; cursor: not-allowed; }
        
    </style>
</head>
<body>

    @include('layouts.sidebar')

    {{-- Konten utama --}}
    <div class="main-container">
        <div class="header">
            <h1>@yield('title')</h1>

            {{-- Tombol user-info tetap memanggil openProfileModal() --}}
            <div class="user-info" onclick="openProfileModal()">
                <div class="user-avatar">
                    {{ strtoupper(substr(session('nama'), 0, 2)) }}
                </div>
                <span class="user-name">{{ session('nama') }}</span>
            </div>
        </div>

        <div class="content">
            @yield('content')
        </div>
    </div>

    {{-- Modal Profil (fungsi & route tetap sama) --}}
    <div id="profileModal" class="modal-backdrop" aria-hidden="true">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
            <h2 id="modalTitle" class="modal-title">
                <i class="fa-solid fa-id-badge" style="margin-right:8px;color:#334155;"></i>
                Profil {{ session('role') }}
            </h2>

            <form method="POST" action="{{ route('profil.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nama</label>
                    <input class="form-control" type="text" name="nama" value="{{ Auth::user()->nama }}" readonly>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email" value="{{ Auth::user()->email }}" readonly>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <input class="form-control" type="text" name="role" value="{{ Auth::user()->role }}" readonly>
                </div>

                <hr class="divider">

                <div class="form-group">
                    <label>Password Lama</label>
                    <input
                        class="form-control @error('password_lama') is-invalid @enderror"
                        type="password"
                        name="password_lama">
                    @error('password_lama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Password Baru</label>
                    <input
                        class="form-control @error('password_baru') is-invalid @enderror"
                        type="password"
                        name="password_baru">
                    @error('password_baru')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input
                        class="form-control @error('password_konfirmasi') is-invalid @enderror"
                        type="password"
                        name="password_konfirmasi">
                    @error('password_konfirmasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeProfileModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openProfileModal() {
            const el = document.getElementById('profileModal');
            if(!el) return;
            el.style.display = 'flex';
            el.setAttribute('aria-hidden', 'false');
        }
        function closeProfileModal() {
            const el = document.getElementById('profileModal');
            if(!el) return;
            el.style.display = 'none';
            el.setAttribute('aria-hidden', 'true');

            
            document.querySelectorAll('#profileModal .form-control.is-invalid')
                .forEach((input) => input.classList.remove('is-invalid'));

            document.querySelectorAll('#profileModal .invalid-feedback')
                .forEach((msg) => msg.style.display = 'none');

            document.querySelectorAll('#profileModal input[type="password"]')
                .forEach((input) => input.value = '');
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeProfileModal();
        });
        document.getElementById('profileModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'profileModal') closeProfileModal();
        });

        document.addEventListener('DOMContentLoaded', () => {
            @if($errors->has('password_lama') ||
                $errors->has('password_baru') ||
                $errors->has('password_konfirmasi'))
                openProfileModal();
            @endif
        });
    </script>

    <script>
        (function () {
        document.addEventListener('submit', function (e) {
            if (e.defaultPrevented) return;

            const form = e.target;
            if (!(form instanceof HTMLFormElement)) return;

            if (form.dataset.submitted === '1') {
            e.preventDefault();
            return;
            }

            const btn = e.submitter;
            if (!btn || btn.tagName.toLowerCase() !== 'button') return;

            form.dataset.submitted = '1';

            form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach(el => {
            el.disabled = true;
            el.setAttribute('aria-disabled', 'true');
            });

            let textSpan = btn.querySelector('.btnText');
            if (!textSpan) {
           
            const oldText = btn.textContent.trim() || 'Submit';
            btn.innerHTML = ''; 
            textSpan = document.createElement('span');
            textSpan.className = 'btnText';
            textSpan.textContent = oldText;
            btn.appendChild(textSpan);
            }

            let spn = btn.querySelector('.btnSpinner');
            if (!spn) {
            spn = document.createElement('i');
            spn.className = 'btnSpinner fas fa-spinner fa-spin';
            spn.style.display = 'none';
            spn.style.marginLeft = '10px';
            btn.appendChild(spn);
            }

            btn.classList.add('is-loading');
            btn.setAttribute('aria-busy', 'true');
            spn.style.display = 'inline-block';
        });

        window.addEventListener('pageshow', function () {
            document.querySelectorAll('form[data-submitted="1"]').forEach(form => {
            delete form.dataset.submitted;
            form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach(btn => {
                btn.disabled = false;
                btn.removeAttribute('aria-disabled');
                btn.classList.remove('is-loading');
                btn.removeAttribute('aria-busy');
                const spn = btn.querySelector('.btnSpinner');
                if (spn) spn.style.display = 'none';
            });
            });
        });
        })();
        </script>


    {{-- Toast Notification --}}
    @if(session('success') || session('error'))
        <div id="toast"
            style="
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                background: {{ session('success') ? 'var(--success)' : 'var(--danger)' }};
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 8px 20px rgba(2,6,23,.25);
                animation: slideIn .3s ease-out;
                font-weight: 600;
            ">
            {{ session('success') ?? session('error') }}
        </div>

        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast');
                if (toast) toast.remove();
            }, 3000);
        </script>
    @endif

</body>
</html>
