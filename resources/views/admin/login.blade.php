<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - HOTELX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-box">
            <h1>HOTEL<span>X</span></h1>
            <p>Login ke Admin Panel</p>

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="/admin/login" method="POST">
                @csrf
                <div class="form-group">
                    <label>ID Karyawan</label>
                    <input type="text" name="id_karyawan" class="form-control" placeholder="Masukkan ID Karyawan" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
