<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Sistema de Licencia</title>
  <link rel="icon" href="{{ asset('img/logo_sinfondo.png') }}" type="image/x-icon">
  <!-- Bootstrap CSS (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FontAwesome (CDN) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">


  <style>
    .brand-title {
      line-height: 1;
    }

    .brand-sub {
      font-size: 0.9rem;
      opacity: 0.85;
      margin-top: 0.05rem;
    }

    header .navbar {
      padding-top: .5rem;
      padding-bottom: .5rem;
    }
  </style>
</head>

<body>



  <!-- HEADER -->
  <header class="bg-white border-bottom">
    <nav class="container navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand d-flex align-items-center" href="/main">
        <img src="{{ asset('img/logo_sinfondo.png') }}" alt="Logo" class="me-2" style="height:50px;">

        <div class="d-flex flex-column">
          <span class="h5 mb-0 fw-bold brand-title">Sistema de Licencias de Funcionamiento</span>
          <small class="text-muted brand-sub">Municipalidad Distrital</small>
        </div>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
        aria-controls="mainNav" aria-expanded="false" aria-label="Alternar navegación">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menú -->
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item">
            <a class="nav-link" href="/main"><i class="fa-solid fa-house me-1"></i> Inicio</a>
          </li>

          @if(Auth::check() && in_array(Auth::user()->role, ['ADMIN', 'DEFENSA_CIVIL']))
          <li class="nav-item">
            <a class="nav-link" href="/defensa_civil">
              <i class="fa-solid fa-shield-halved"></i> Defensa Civil
            </a>
          </li>
          @endif




          @if(Auth::check() && in_array(Auth::user()->role, ['ADMIN', 'ADMINISTRADOR']))
          <li class="nav-item">
            <a class="nav-link" href="/admin">
              <i class="fa-solid fa-file-lines me-1"></i> Administración
            </a>
          </li>
          @endif

          @if(Auth::check() && in_array(Auth::user()->role, ['ADMIN']))
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="mantenedoresDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa-solid fa-gears"></i> Mantenedores
            </a>
            <ul class="dropdown-menu" aria-labelledby="mantenedoresDropdown">
              <li><a class="dropdown-item" href="{{ route('mantenedores.index') }}">Usuarios</a></li>
              <li><a class="dropdown-item" href="{{ route('sectores.indexSector') }}">Sectores</a></li>
              <li><a class="dropdown-item" href="{{ route('direcciones.indexDirecciones') }}">Direcciones</a></li>
              <li><a class="dropdown-item" href="{{ route('tiporiesgo.indexTipoRiesgo') }}">Tipo riesgo</a></li>
            </ul>
          </li>
          @endif



          <!-- Usuario -->
          <li class="nav-item dropdown ms-2">
            <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              <i class="fa-solid fa-user-tie"></i> {{ Auth::user()->name }}

            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
              <li>
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="dropdown-item">Cerrar sesión</button>
                </form>
              </li>
            </ul>

          </li>
        </ul>
      </div>
    </nav>
  </header>


  <main class="container my-5">




    @yield('content')

  </main>


  <footer class="footer">
    <div class="footer-content">
      <p>© Gestión de Licencias de Funcionamiento de la Municipalidad Distrital de Pacasmayo - 2025</p>
      <p>
      <p> Desarrollado por <strong>MEQH</strong> - Todos los derechos conservados </p>
    </div>
  </footer>

  <style>
    .footer {
      background: linear-gradient(135deg, #2f3a49ff, #48556fff);
      color: #e5e7eb;
      text-align: center;
      padding: 25px 10px;
      font-family: 'Segoe UI', Roboto, Arial, sans-serif;
      border-top: 1px solid #2563eb;
      box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.3);
    }

    .footer-content {
      max-width: 900px;
      margin: 0 auto;
      line-height: 1.6;
    }

    .footer p {
      margin: 5px 0;
      font-size: 15px;
      letter-spacing: 0.5px;
    }

    .footer strong {
      color: #3b82f6;
      font-weight: 600;
    }

    .footer-link {
      color: #93c5fd;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .footer-link:hover {
      color: #60a5fa;
      text-decoration: underline;
    }
  </style>


  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>