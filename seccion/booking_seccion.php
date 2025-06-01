<body>
<section id="reserva">
<!-- Reservation Start -->
<div class="container-xxl py-5 px-0 wow fadeInUp" data-wow-delay="0.1s">
    <div class="row g-0">
        <div class="col-md-6">
            <div class="video">
                <button type="button" class="btn-play" data-bs-toggle="modal"
                    data-src="https://www.youtube.com/embed/ZHmx4fsv-iM" data-bs-target="#videoModal">
                    <span></span>
                </button>
            </div>
        </div>
        <div class="col-md-6 bg-dark d-flex align-items-center">
            <div class="p-5 wow fadeInUp" data-wow-delay="0.2s">
                <h5 class="section-title ff-secondary text-start text-primary fw-normal">Reservas</h5>
                <h1 class="text-white mb-4">Reserva tu mesa online</h1>
                <form action="seccion/booking/guardar_reserva.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="nombre" placeholder="Tu nombre" required>
                                <label for="name">Tu nombre</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Tu correo electrónico" required>
                                <label for="email">Tu correo electrónico</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating date" id="date3" data-target-input="nearest">
                                <input type="date" class="form-control datetimepicker-input" id="datetime" name="fecha"
                                    placeholder="Fecha" data-target="#date3" data-toggle="datetimepicker" required>
                                <label for="datetime">Fecha</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="hora" name="hora" required>
                                    <!-- Asegúrate de que este campo se rellene con opciones válidas -->
                                </select>
                                <label for="hora">Hora</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Teléfono"
                                    pattern="^(\+?\d{1,3})?\d{9}$"
                                    title="Debe contener 9 dígitos, opcionalmente con prefijo (+34, etc.)"
                                    maxlength="13">
                                <label for="telefono">Teléfono</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="select1" name="numero_personas" required>
                                    <option value="1">1 persona</option>
                                    <option value="2">2 personas</option>
                                    <option value="3">3 personas</option>
                                    <option value="4">4 personas</option>
                                    <option value="5">5 personas</option>
                                    <option value="6">6 personas</option>
                                    <option value="7">7 personas</option>
                                    <option value="8">8 personas</option>
                                </select>
                                <label for="select1">Número de personas</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Solicitud especial" id="message" name="solicitud_especial"
                                    style="height: 100px"></textarea>
                                <label for="message">Solicitud especial</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 py-3" type="submit">Reservar ahora</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Modal siempre presente -->
    <div class="modal fade" id="errorReservaModal" tabindex="-1" aria-labelledby="errorReservaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-center">
        <div class="modal-header">
            <h5 class="modal-title text-danger" id="errorReservaLabel">
            <i class="fas fa-calendar-times me-2"></i> Reserva no disponible
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
            <div class="error-icon mb-3" style="font-size: 48px; color: #dc3545;">
            <i class="fas fa-calendar-times"></i>
            </div>
            <?php
            if (isset($_SESSION['reserva_error_modal'])) {
                echo $_SESSION['reserva_error_modal'];
            }
            ?>
        </div>
        <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
        </div>
    </div>
    </div>
</section>
<!-- Modal de Video -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Video de presentación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- 16:9 aspect ratio -->
                <div class="ratio ratio-16x9">
                    <iframe class="embed-responsive-item" src="" id="video" allowfullscreen
                        allowscriptaccess="always" allow="autoplay"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Reservation End -->
<script src="seccion/booking/functionBooking.js"></script>
</body>
