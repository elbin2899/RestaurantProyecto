<?php session_start(); ?> 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Restaurante</title>
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet, para service -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <!-- Customized Bootstrap Stylesheet importante -->
    <link href="css/bootstrap.min.css" rel="stylesheet"> 

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
   
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <?php include('includes/header.php'); ?>

        <?php include('seccion/service_seccion.php'); ?>

        <?php include('seccion/about_seccion.php'); ?>

        <?php include('seccion/menu_seccion.php'); ?>

        <?php include('seccion/booking_seccion.php'); ?>

        <?php include('seccion/team_seccion.php'); ?>

        <?php include('includes/footer.php'); ?>

    </div>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const usuario = document.getElementById('usuario').value.trim();
            const contrasena = document.getElementById('contrasena').value.trim();
        
            if (usuario.length > 15 || contrasena.length > 15) {
                alert("El usuario y la contraseña no deben superar los 20 caracteres.");
                e.preventDefault(); // Evita que se envíe el formulario
            }
        });
    </script>
        
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <!--Our Clients say-->
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    
     <!-- Template Javascript importante -->
     <script src="js/main.js"></script>

    <!-- Bootstrap JS (necesario para que funcione el modal de reserva) -->
    <?php if (isset($_SESSION['reserva_error_modal'])): ?>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            var modal = new bootstrap.Modal(document.getElementById('errorReservaModal'));
            modal.show();
        });
    </script>
    <?php
    // Solo eliminar la sesión DESPUÉS de que JS la ha usado
    unset($_SESSION['reserva_error_modal']);
    endif;
    ?>

</body>
</html>