<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiLapor</title>
    @vite('resources/css/app.css')
    <style>
        .macbook-air {
            display: flex;
            min-height: 100vh;
        }
        .overlap-wrapper {
            width: 100%;
        }
        .overlap {
            display: flex;
            width: 100%;
            height: 100vh;
        }
        .frame {
            width: 50%;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .div {
            max-width: 450px;
            width: 100%;
        }
        .group {
            margin-bottom: 30px;
        }
        .frame-2 {
            margin-bottom: 30px;
        }
        .text-wrapper {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .lorem-ipsum-dolor {
            color: #666;
            line-height: 1.5;
        }
        .form {
            width: 100%;
        }
        .inputs {
            margin-bottom: 20px;
        }
        .input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .text-wrapper-2 {
            text-align: right;
            color: #666;
            margin-bottom: 24px;
            cursor: pointer;
        }
        .actions {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .button, .button-wrapper {
            width: 100%;
        }
        .button-2 {
            width: 100%;
            padding: 12px;
            background-color: #3B82F6;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }
        .button-3 {
            width: 100%;
            padding: 12px;
            background-color: white;
            color: #3B82F6;
            border: 1px solid #3B82F6;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }
        .overlap-group-wrapper {
            width: 50%;
            background-color: #3B82F6;
            position: relative;
            overflow: hidden;
        }
        .overlap-2 {
            height: 100%;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 40px;
        }
        .text-wrapper-3 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 24px;
        }
        .sistem-manajemen {
            font-size: 18px;
            text-align: center;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="macbook-air">
        <div class="overlap-wrapper">
            <div class="overlap">
                <div class="frame">
                    <div class="div">
                        <div class="group">
                            <div class="overlap-group">
                                <div class="ellipse"></div>
                                <div class="layer">
                                    <div class="overlap-group-2">
                                        <!-- Image placeholders - add these to your project -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="frame-2">
                            <div class="text-wrapper">Selamat Datang</div>
                            <p class="lorem-ipsum-dolor">
                                Silahkan masuk ke akun Anda untuk mengakses sistem pelaporan dan perbaikan fasilitas kampus.
                            </p>
                        </div>
                        
                        <div class="form">
                            @if($errors->any())
                                <div style="color: #EF4444; margin-bottom: 16px; padding: 12px; background-color: #FEF2F2; border-radius: 8px;">
                                    <ul style="margin: 0; padding-left: 20px;">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="inputs">
                                    <input class="input" 
                                        placeholder="Nama Pengguna" 
                                        type="text" 
                                        id="nama_pengguna" 
                                        name="nama_pengguna" 
                                        value="{{ old('nama_pengguna') }}" 
                                        required 
                                        autofocus>
                                    
                                    <input class="input" 
                                        placeholder="Kata Sandi" 
                                        type="password" 
                                        id="kata_sandi" 
                                        name="kata_sandi" 
                                        required>
                                </div>
                                
                                <div style="display: flex; justify-content: space-between; margin-bottom: 24px;">
                                    <div>
                                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label for="remember">Ingat Saya</label>
                                    </div>
                                    <div class="text-wrapper-2">Lupa kata sandi?</div>
                                </div>
                                
                                <div class="actions">
                                    <button type="submit" class="button-2">Masuk</button>
                                    <button type="button" class="button-3">Buat Akun Baru</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="overlap-group-wrapper">
                    <div class="overlap-2">
                        <div class="text-wrapper-3">SiLapor</div>
                        <p class="sistem-manajemen">Sistem Manajemen Pelaporan <br>dan Perbaikan Fasilitas Kampus</p>
                        <!-- Image placeholders - add these images to your project -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>