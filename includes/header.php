
<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Navbar Start -->
<div class="container-xxl position-relative p-0">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0">
        <a href="index.php" class="navbar-brand p-0">
            <h1 class="text-primary m-0"><i class="fa fa-utensils me-3"></i>Cuisine X</h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0 pe-4">
                <a href="index.php" class="nav-item nav-link <?php if($currentPage == 'index.php') echo 'active'; ?>">Inicio</a>
                <a href="about.php" class="nav-item nav-link <?php if($currentPage == 'about.php') echo 'active'; ?>">Sobre Nosotros</a>
                <a href="service.php" class="nav-item nav-link <?php if($currentPage == 'service.php') echo 'active'; ?>">Servicios</a>
                <a href="menu.php" class="nav-item nav-link <?php if($currentPage == 'menu.php') echo 'active'; ?>">Menú</a>

            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Páginas</a>
                <div class="dropdown-menu m-0">
                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="fas fa-sign-in-alt"></i>
                    </a>
                    <a href="team.php" class="dropdown-item">Nuestro Equipo</a>
                    <a href="testimonial.php" class="dropdown-item">Testimonios</a>
                </div>
            </div>
                <a href="contact.php" class="nav-item nav-link <?php if($currentPage == 'contact.php') echo 'active'; ?>">Contacto</a>
            </div>
            <a href="booking.php" class="btn btn-primary py-2 px-4">Reservar</a>
        </div>
    </nav>
</div>
<!-- Navbar End -->

<!-- Hero Start -->
<div class="container-xxl py-5 bg-dark hero-header mb-5">
    <div class="container my-5 py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 text-center text-lg-start">
                <h1 class="display-3 text-white animated slideInLeft">Disfruta Nuestra<br>Deliciosa Comida</h1>
                <p class="text-white animated slideInLeft mb-4 pb-2">Tempor erat elitr rebum at clita. Diam dolor diam
                    ipsum sit. Aliqu diam amet diam et eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita
                    duo justo magna dolore erat amet</p>
                <a href="#reserva" class="btn btn-primary py-sm-3 px-sm-5 me-3 animated slideInLeft">Reservar</a>
            </div>
            <div class="col-lg-6 text-center text-lg-end overflow-hidden">
                <img class="img-fluid" src="img/parrillaVariad_8.png" alt="parrillada">
            </div>
        </div>
    </div>
</div>
<!-- Hero End -->

<!-- Modal Login -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-primary text-white">
                <!--color todo modal-->
                <h5 class="modal-title"><i class="fas fa-user-circle me-2"></i>Acceso Administrador</h5>
                <!--icono usuario-->
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
                <!--para cerrar modal-->
            </div>
            <form id="loginForm" action="login.php" method="POST">
                <div class="modal-body bg-light">
                    <!--azulejo de la seccion-->
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="usuario" name="usuario"
                                placeholder="Ingresa tu usuario" required maxlength="20">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="contrasena" name="contrasena"
                                placeholder="Ingresa tu contraseña" required maxlength="20">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light d-flex justify-content-between">
                    <!--azulejo-->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <!--btn para estilo del boton-->
                    <button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt me-1"></i>Entrar</button>
                </div>
            </form>
        </div>
    </div>
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