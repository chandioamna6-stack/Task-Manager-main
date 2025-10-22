<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') | Task Manager</title>
  <link rel="shortcut icon" href="{{ asset('assets/img/logo-circle.png') }}" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@800&display=swap" rel="stylesheet">

  <style>
    body {
      display: flex;
      height: 100vh;
      margin: 0;
      overflow: hidden;
      background-color: rgb(241 245 249);
      font-family: "Noto Sans", sans-serif !important;
    }

    /* Sidebar styling */
    .sidebar {
      width: 250px;
      background: linear-gradient(180deg, #1f1c2c, #928DAB);
      background-size: 200% 200%;
      animation: gradientShift 8s ease infinite;
      color: white;
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      box-shadow: 4px 0 15px rgba(0, 0, 0, 0.15);
    }

    @keyframes gradientShift {
      0% {
        background-position: 0% 0%;
      }

      50% {
        background-position: 100% 100%;
      }

      100% {
        background-position: 0% 0%;
      }
    }

    .sidebar .nav-link {
      color: rgba(255, 255, 255, 0.9);
      display: flex;
      align-items: center;
      padding: 12px 14px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      transition: all 0.3s ease-in-out;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background: rgba(255, 255, 255, 0.15);
      border-radius: 0.25rem;
      transform: translateX(6px);
      box-shadow: inset 0 0 8px rgba(255, 255, 255, 0.15);
    }

    .sidebar .nav-link .bi {
      margin-right: 10px;
      font-size: 1.2rem;
      transition: transform 0.3s ease, color 0.3s ease;
    }

    .sidebar .nav-link:hover .bi {
      color: #FFD700;
      transform: scale(1.2) rotate(8deg);
    }

    .content {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      overflow-y: auto;
      background-color: #f8fafc;
    }

    .topnav {
      flex-shrink: 0;
      background-color: #ffffff;
      box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
    }

    footer {
      background-color: #ffffff;
      box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
      flex-shrink: 0;
    }

    main {
      flex-grow: 1;
    }

    /* 🌟 Neon Purple-Pink Outline Welcome Screen 🌟 */
    #welcomeScreen {
      position: fixed;
      inset: 0;
      background: #000;
      z-index: 9999;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      font-family: 'Poppins', sans-serif;
      animation: fadeOut 1s ease forwards;
      animation-delay: 5s;
    }

    #welcomeScreen h1 {
      font-size: 4rem;
      color: transparent;
      letter-spacing: 6px;
      text-transform: uppercase;
      background: linear-gradient(90deg, #ff00ff, #a020f0);
      -webkit-background-clip: text;
      -webkit-text-stroke: 1.5px #fff;
      text-shadow:
        0 0 10px rgba(255, 0, 255, 0.6),
        0 0 20px rgba(160, 32, 240, 0.5),
        0 0 30px rgba(255, 0, 255, 0.4);
      animation: glowPulse 2s ease-in-out infinite, smokeFade 5s ease forwards;
    }

    @keyframes glowPulse {
      0%, 100% {
        text-shadow:
          0 0 8px rgba(255, 0, 255, 0.7),
          0 0 16px rgba(160, 32, 240, 0.6),
          0 0 24px rgba(255, 0, 255, 0.5);
      }

      50% {
        text-shadow:
          0 0 12px rgba(255, 0, 255, 1),
          0 0 24px rgba(160, 32, 240, 0.9),
          0 0 36px rgba(255, 0, 255, 0.8);
      }
    }

    @keyframes smokeFade {
      0% {
        opacity: 1;
        filter: blur(0);
        transform: translateY(0);
      }

      80% {
        opacity: 0.9;
        filter: blur(1px);
        transform: translateY(-10px);
      }

      100% {
        opacity: 0;
        filter: blur(15px);
        transform: translateY(-50px);
      }
    }

    @keyframes fadeOut {
      to {
        opacity: 0;
        visibility: hidden;
      }
    }
  </style>
</head>

<body>
  <!-- 🌟 Welcome Screen -->
  @if(session('show_welcome'))
    <div id="welcomeScreen">
      <h1>WELCOME&nbsp;&nbsp;{{ strtoupper(Auth::user()->name) }}</h1>
    </div>
  @endif

  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column p-3">
    <h4 class="mb-4 text-center">
      <a href="{{ route('dashboard') }}">
        <img style="filter: invert(100%) brightness(200%);" src="{{ asset('assets/img/logo-circle-horizontal.png') }}" class="img-fluid" width="100%" alt="task manager">
      </a>
    </h4>

    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i> Home</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->is('projects*') ? 'active' : '' }}" href="{{ route('projects.index') }}"><i class="bi bi-folder"></i> Projects</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->is('tasks*') ? 'active' : '' }}" href="{{ route('tasks.index') }}"><i class="bi bi-check2-square"></i> Tasks</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->is('routines*') ? 'active' : '' }}" href="{{ route('routines.index') }}"><i class="bi bi-calendar-check"></i> Routines</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->is('notes*') ? 'active' : '' }}" href="{{ route('notes.index') }}"><i class="bi bi-sticky"></i> Notes</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->is('reminders*') ? 'active' : '' }}" href="{{ route('reminders.index') }}"><i class="bi bi-bell"></i> Reminders</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->is('files*') ? 'active' : '' }}" href="{{ route('files.index') }}"><i class="bi bi-file-earmark"></i> Files</a></li>
    </ul>
  </div>

  <div class="content d-flex flex-column">
    <header class="topnav mb-4">
      <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
          <a class="navbar-brand" href="{{ route('dashboard') }}">
            <span class="fw-normal" id="currentDateTime"></span>
          </a>
          <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false">{{ Auth::user()->name }}</a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                  <li>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                      @csrf
                      <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>

    <main>@yield('content')</main>

    <footer class="mt-auto py-3 text-center bg-light border-top">
      <div class="container">
        <span class="text-muted">&copy; {{ date('Y') }} Task Manager | Developed by <strong>Amna Chandio</strong></span>
      </div>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function updateDateTime() {
      const now = new Date();
      const dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
      const day = dayNames[now.getDay()];
      const date = now.toLocaleDateString(['en-US'], { day: 'numeric', month: 'long', year: 'numeric' });
      const time = now.toLocaleTimeString();
      document.getElementById('currentDateTime').innerText = `${day}, ${date}  ${time}`;
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);
  </script>

  @if(session('show_welcome'))
  <script>
    // Clear the welcome flag after animation ends
    setTimeout(() => {
      fetch("{{ url('/clear-welcome-flag') }}");
    }, 5000);
  </script>
  @endif
</body>
</html>
