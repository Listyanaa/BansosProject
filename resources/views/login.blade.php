<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Bansos Kota Parepare</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display:flex; justify-content:center; align-items:center; min-height:100vh; background:linear-gradient(135deg,#3b82f6,#1e40af); padding:20px; }
        .container { display:flex; width:900px; max-width:95%; box-shadow:0 15px 30px rgba(0,0,0,.2); border-radius:20px; overflow:hidden; background:#fff; transition:transform .3s ease; }
        .container:hover { transform:translateY(-5px); }
        .left { flex:1; padding:50px 40px; background:#fff; display:flex; flex-direction:column; justify-content:center; }
        .logo-container { display:flex; align-items:center; margin-bottom:25px; }
        .logo-placeholder { width:70px; height:70px; background:#eef2ff; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-right:15px; box-shadow:0 4px 10px rgba(59,130,246,.2); }
        .logo-placeholder i { font-size:30px; color:#3b82f6; }
        .title { font-size:22px; color:#1e293b; line-height:1.3; font-weight:700; }
        .subtitle { font-size:14px; color:#64748b; margin-top:5px; }
        .input-box { margin-top:35px; }
        .input-group { position:relative; margin-bottom:25px; }
        .input-group input { width:100%; padding:15px 15px 15px 45px; border:2px solid #e2e8f0; border-radius:12px; font-size:16px; transition:all .3s ease; background-color:#f8fafc; }
        .input-group input:focus { border-color:#3b82f6; background:#fff; box-shadow:0 0 0 3px rgba(59,130,246,.2); outline:none; }
        .input-icon { position:absolute; left:15px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:18px; }
        .btn { width:100%; padding:15px; background:linear-gradient(to right,#3b82f6,#1d4ed8); color:#fff; border:none; border-radius:12px; cursor:pointer; font-size:16px; font-weight:600; transition:all .3s ease; box-shadow:0 4px 15px rgba(59,130,246,.4); }
        .btn:hover { background:linear-gradient(to right,#2563eb,#1e40af); transform:translateY(-2px); box-shadow:0 6px 20px rgba(59,130,246,.5); }
        .btn:active { transform:translateY(0); }
        .right { flex:1; background:linear-gradient(135deg,#3b82f6,#1e40af); color:#fff; display:flex; flex-direction:column; justify-content:center; align-items:center; padding:40px; text-align:center; position:relative; overflow:hidden; }
        .right::before { content:''; position:absolute; width:200px; height:200px; background:rgba(255,255,255,.1); border-radius:50%; top:-50px; right:-50px; }
        .right::after { content:''; position:absolute; width:150px; height:150px; background:rgba(255,255,255,.1); border-radius:50%; bottom:-50px; left:-50px; }
        .welcome-title { font-size:32px; margin-bottom:15px; font-weight:700; position:relative; z-index:1; }
        .welcome-text { font-size:18px; opacity:.9; position:relative; z-index:1; }
        .feature-icon { font-size:60px; margin-bottom:25px; opacity:.9; position:relative; z-index:1; }
        .alert { background:#fee2e2; color:#7f1d1d; border:1px solid #fecaca; padding:10px 12px; border-radius:10px; margin-bottom:12px; }
        .alert-success { background:#dcfce7; color:#14532d; border:1px solid #bbf7d0; }
        @media (max-width:768px){ .container{flex-direction:column; width:100%;} .right{padding:30px 20px;} .left{padding:40px 30px;} }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container">
    <div class="left">
        <div class="logo-container">
            <div class="logo-placeholder"><i class="fas fa-map-marked-alt"></i></div>
            <div>
                <h1 class="title">Sistem Informasi Titik Koordinat</h1>
                <p class="subtitle">Penerima Bantuan Sosial di Kota Parepare</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        @if(session('error'))
            <div class="alert">{{ session('error') }}</div>
        @endif

        <form id="loginForm" method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="input-box">
                <div class="input-group">
                    <i class="fas fa-user input-icon"></i>
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
            </div>

            <button type="submit" class="btn" id="btnLogin">
                <span id="btnText">Login</span>
                <i id="btnSpinner" class="fas fa-spinner fa-spin" style="display:none; margin-left:10px;"></i>
            </button>
        </form>

        <script>
            (function () {
            const form = document.getElementById('loginForm');
            const btn  = document.getElementById('btnLogin');
            const spn  = document.getElementById('btnSpinner');

            let submitted = false;

            form.addEventListener('submit', function (e) {
                if (submitted) {
                e.preventDefault();
                return;
                }
                submitted = true;

                btn.disabled = true;
                btn.style.opacity = '0.85';
                btn.style.cursor  = 'not-allowed';

                // tampilkan loading icon
                spn.style.display = 'inline-block';
            });
        })();
        </script>


    </div>

    <!-- Right -->
    <div class="right">
        <i class="fas fa-map-marker-alt feature-icon"></i>
        <h2 class="welcome-title">Welcome Page!</h2>
        <p class="welcome-text">Sign In To Your Account</p>
    </div>
</div>
</body>
</html>
