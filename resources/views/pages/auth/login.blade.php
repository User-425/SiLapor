{{-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiLapor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Login SiLapor</h4>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
                                <input id="nama_pengguna" type="text" class="form-control @error('nama_pengguna') is-invalid @enderror"
                                    name="nama_pengguna" value="{{ old('nama_pengguna') }}" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="kata_sandi" class="form-label">Kata Sandi</label>
                                <input id="kata_sandi" type="password" class="form-control @error('kata_sandi') is-invalid @enderror"
                                    name="kata_sandi" required>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Ingat Saya</label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> --}}
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="style.css" />
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
                      <img class="g" src="img/g-1026.png" />
                      <img class="path" src="img/path1240-5-7-9.svg" />
                      <img class="img" src="img/path1240-5-2-24-6.svg" />
                      <img class="path-2" src="img/path1230-4-0-9.svg" />
                      <img class="path-3" src="img/path1232-8-6-33.svg" />
                    </div>
                    <img class="rect" src="img/rect-4529.svg" />
                  </div>
                </div>
              </div>
              <div class="frame-2">
                <div class="text-wrapper">Welcome</div>
                <p class="lorem-ipsum-dolor">
                  Lorem ipsum dolor sit amet consectetur. Aenean <br />consectetur leo dolor a netus eu.
                </p>
              </div>
              <div class="form">
                <div class="inputs">
                  <input class="input" placeholder="Email" type="email" id="input-1" />
                  <div class="placeholder-wrapper"><label class="placeholder" for="input-1">Password</label></div>
                </div>
                <div class="text-wrapper-2">Forgot your password?</div>
                <div class="actions">
                  <button class="button"><button class="button-2">Sign in</button></button>
                  <button class="button-wrapper"><button class="button-3">Create new account</button></button>
                </div>
              </div>
            </div>
          </div>
          <div class="overlap-group-wrapper">
            <div class="overlap-2">
              <div class="overlap-3">
                <img class="background-complete" src="img/background-complete.png" />
                <img class="chart" src="img/chart-3.png" />
                <img class="chart-2" src="img/chart-2.png" />
                <img class="chart-3" src="img/chart-1.png" />
                <img class="character" src="img/character.png" />
                <p class="sistem-manajemen">Sistem Manajemen Pelaporan <br />dan Perbaikan Fasilitas Kampus</p>
              </div>
              <img class="plant" src="img/plant.png" />
              <div class="text-wrapper-3">SiLapor</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
