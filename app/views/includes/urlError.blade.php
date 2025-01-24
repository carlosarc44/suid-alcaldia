<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>SUID en mantenimiento</title>
  
  <!-- Favicons -->
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    
    <!-- Mobile -->
  <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
    
  <!-- CSS start here -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap/bootstrap.min.css')}}" media="screen">
  <link rel="stylesheet" type="text/css" href="{{ asset('offline/css/styles-offline.css')}}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('offline/css/animate.css')}}" />
  <style>
    
    body {
    font-family: 'Open Sans', sans-serif;
    color: #8a8b8b;
    font-size: 18px;
    line-height: 22px;
    background-color: rgba(53, 10, 4, .5);
    background: url('img/bg44.jpg');
    background-size: cover;
    background-repeat:repeat;
    font-weight: normal;
}
</style>
<!-- CSS end here -->  
</head>
<body class="ux" id="error">
  <div class="bg-overlay"></div>
  <!-- Preloader start here -->
  <div id="preloader">
    <div id="status"></div>
  </div>
  <!-- Preloader end here -->

  <!-- Main container start here -->
  <section class="container main-wrapper">
    <!-- Logo start here -->
    <section id="logo" class="fade-down">
      <a href="javascript: void(0)" title="SUID">
        <img src="{{ asset('img/logoSuidWeb.png')}}">
      </a>
    </section>
    <!-- Logo end here -->

    <!-- Slogan start here -->
    <section class="slogan fade-down">
      <h1>Error 404. Esta ruta es desconocida.</h1>
      <h1>Verifica la url e inténtalo de nuevo.</h1><br><br>   
      <h2>Volver atrás y <a href="javascript: void(0)" onClick="history.go(-1)">seguir trabajando</a></h2>
    </section>
    <!-- Slogan end here -->
        
    <!-- Footer start here -->
    <footer class="fade-down">        
      <p class="footer-text">&copy; SUID <?php echo date("Y"); ?>, Oficina de Control Disciplinario | Alcaldía de Manizales.</p>
    </footer>
    <!-- Footer end here -->
  </section>

    <!-- Main container start here -->
    <!-- Javascript framework and plugins start here -->
    <script type="text/javascript" src="{{ asset('offline/js/jquery.js')}}"></script> 
    <script src="{{ asset('offline/js/modernizr.js')}}"></script> 
    <script type="text/javascript" src="{{ asset('offline/js/appear.js')}}"></script>     
    <script src="{{ asset('offline/js/jquery.ccountdown.js')}}"></script>
    <script src="{{ asset('offline/js/general.js')}}"></script>
<!-- Javascript framework and plugins end here -->
</body>
</html>