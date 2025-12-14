<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Licencias</title>
    <link rel="icon" href="{{ asset('img/logo.jpeg') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!--CSS -->
     <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

     <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

</head>
<body class="body-login">

    <div class="container text-center">
        <div class="mb-4 d-flex align-items-center justify-content-center">
            <img src="{{ asset('img/logo_sinfondo.PNG') }}" alt="Logo" class="logo-login me-3">
            <div class="text-start">
                <h3 class="fw-bold mb-0 login-title">Sistema de Licencias de Funcionamiento</h3>
                <small class="login-subtitle">Municipalidad Distrital</small>
            </div>
        </div>

       
        <div class="login-container mx-auto">
            <div class="login-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="form-group">
                        <i class="fa-solid fa-envelope input-icon"></i>
                        <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                    </div>

                    <div class="form-group">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                    </div>

                    <button type="submit" class="btn btn-login">Ingresar</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
