<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Helpdesk IT PDAM Tirta Mulia</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background-color: #f3f4f6; height: 100vh; overflow: hidden; }
        .login-wrapper { width: 100vw; height: 100vh; display: flex; }
        
        /* Kolom Kiri: Banner Biru & Maskot */
        .login-banner { 
            display: none; 
            width: 50%; 
            height: 100%; 
            background: linear-gradient(135deg, #1e3a8a, #1e40af, #312e81); 
            padding: 48px; 
            flex-direction: column; 
            justify-content: space-between; 
            color: white; 
            position: relative;
            overflow: hidden;
        }
        .login-banner::before {
            content: '';
            position: absolute;
            top: -50px;
            left: -50px;
            width: 300px;
            height: 300px;
            background: rgba(59, 130, 246, 0.2);
            border-radius: 50%;
            filter: blur(60px);
            pointer-events: none;
        }
        @media (min-width: 1024px) { 
            .login-banner { display: flex; } 
        }
        
        /* Kolom Kanan: Form Login */
        .login-form-side { 
            width: 100%; 
            height: 100%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 24px; 
            overflow-y: auto; 
        }
        @media (min-width: 1024px) { 
            .login-form-side { width: 50%; } 
        }
        
        .form-card { 
            max-width: 420px; 
            width: 100%; 
            background: white; 
            padding: 40px; 
            border-radius: 24px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05); 
            border: 1px solid #e5e7eb; 
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Kolom Kiri: Identitas, Maskot, & Banner -->
        <div class="login-banner">
            <div style="display: flex; align-items: center; gap: 12px; z-index: 10;">
                <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 900; border: 1px solid rgba(255,255,255,0.2);">IT</div>
                <span style="font-weight: bold; font-size: 13px; letter-spacing: 1.5px; text-transform: uppercase;">Helpdesk PDAM Tirta Mulia</span>
            </div>

            <div style="max-width: 450px; display: flex; flex-direction: column; gap: 20px; z-index: 10;">
                <!-- MASKOT DARI FOLDER PUBLIC -->
                <div>
                    <img src="{{ asset('maskot.png') }}" alt="Maskot PDAM" style="height: 150px; object-fit: contain; filter: drop-shadow(0 12px 20px rgba(0,0,0,0.3));">
                </div>

                <div>
                    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(59, 130, 246, 0.25); border: 1px solid rgba(96, 165, 250, 0.3); padding: 6px 14px; border-radius: 20px; margin-bottom: 12px;">
                        <span style="width: 8px; height: 8px; background: #60a5fa; border-radius: 50%; display: inline-block;"></span>
                        <span style="color: #93c5fd; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">Perumda Air Minum Kab. Pemalang</span>
                    </div>
                    <h1 style="font-size: 36px; font-weight: 900; line-height: 1.15; letter-spacing: -0.5px; margin-bottom: 12px;">Solusi Cepat Kendala IT & Infrastruktur Jaringan.</h1>
                    <p style="color: #bfdbfe; font-size: 14px; line-height: 1.6;">Platform terpusat untuk melaporkan masalah perangkat komputer, printer, dan jaringan secara real-time dengan penanganan profesional.</p>
                </div>
            </div>

            <div style="font-size: 12px; color: #93c5fd; z-index: 10;">
                &copy; {{ date('Y') }} Perumda Air Minum Tirta Mulia Kabupaten Pemalang.
            </div>
        </div>

        <!-- Kolom Kanan: Kotak Form -->
        <div class="login-form-side">
            <div class="form-card">
                <div style="text-align: center; margin-bottom: 32px;">
                    <h2 style="font-size: 24px; font-weight: 900; color: #111827; margin-bottom: 8px;">Selamat Datang! 👋</h2>
                    <p style="font-size: 13px; color: #6b7280;">Silakan masuk menggunakan akun resmi Anda.</p>
                </div>

                @if (session('status'))
                    <div style="margin-bottom: 16px; font-size: 13px; color: #16a34a; text-align: center;">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 11px; font-weight: bold; color: #374151; text-transform: uppercase; margin-bottom: 8px;">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@tirtamulia.test" style="width: 100%; background: #f9fafb; border: 1px solid #d1d5db; padding: 12px 16px; border-radius: 12px; font-size: 14px; outline: none;">
                        @error('email')
                            <span style="font-size: 12px; color: #dc2626; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password dengan Tombol Hide/Show -->
                    <div style="margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <label style="font-size: 11px; font-weight: bold; color: #374151; text-transform: uppercase;">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" style="font-size: 12px; font-weight: bold; color: #2563eb; text-decoration: none;">Lupa sandi?</a>
                            @endif
                        </div>
                        <div style="position: relative;">
                            <input type="password" name="password" id="password" required placeholder="••••••••" style="width: 100%; background: #f9fafb; border: 1px solid #d1d5db; padding: 12px 45px 12px 16px; border-radius: 12px; font-size: 14px; outline: none;">
                            <button type="button" onclick="togglePassword()" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6b7280; display: flex; align-items: center;" title="Tampilkan/Sembunyikan Sandi">
                                <svg id="eye-icon" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span style="font-size: 12px; color: #dc2626; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div style="display: flex; align-items: center; margin-bottom: 24px;">
                        <input type="checkbox" name="remember" id="remember_me" style="width: 16px; height: 16px; accent-color: #2563eb;">
                        <label for="remember_me" style="margin-left: 8px; font-size: 12px; font-weight: bold; color: #4b5563;">Ingat perangkat ini</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" style="width: 100%; background: #2563eb; color: white; font-weight: bold; padding: 14px; border-radius: 12px; border: none; cursor: pointer; font-size: 14px; box-shadow: 0 4px 12px rgba(37,99,235,0.3);">
                        Masuk ke Sistem
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Script JavaScript untuk Toggle Show/Hide Password -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.07 10.07 0 014.213-5.184m3.11-1.375C10.5 5.174 11.238 5 12 5c4.478 0 8.268 2.943 9.542 7a10.078 10.078 0 01-1.413 2.766M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18"/>`;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }
        }
    </script>
</body>
</html>