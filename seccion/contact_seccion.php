<!-- Inicio Contacto -->
<section id="contact">
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Contáctanos</h5>
            <h1 class="mb-5">Consulta cualquier duda</h1>
        </div>
        
       <?php if (isset($_GET['success'])): ?>
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
               <div class="toast-header bg-primary text-white">
                    <strong class="me-auto"><i class="fas fa-check-circle me-2"></i>Confirmación</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body bg-light">
                    <p>Tu mensaje ha sido enviado correctamente. Te contactaremos pronto.</p>
                    <div class="progress mt-2" style="height: 3px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        // Cierra automáticamente después de 5 segundos
        setTimeout(() => {
            $('.toast').toast('hide');
        }, 5000);
        </script>
        <?php endif; ?>
        
        <div class="row g-4">
            <div class="col-12">
                <div class="row gy-4">
                    <div class="col-md-4">
                        <h5 class="section-title ff-secondary fw-normal text-start text-primary">Reservas</h5>
                        <p><i class="fa fa-envelope-open text-primary me-2"></i>reservas@cuisine.com</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="section-title ff-secondary fw-normal text-start text-primary">Información general</h5>
                        <p><i class="fa fa-envelope-open text-primary me-2"></i>info@cuisine.com</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="section-title ff-secondary fw-normal text-start text-primary">Soporte técnico</h5>
                        <p><i class="fa fa-envelope-open text-primary me-2"></i>tecnico@cuisine.com</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 wow fadeIn" data-wow-delay="0.1s">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3037.1803985567317!2d-3.707972524042738!3d40.427004155014046!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd422862367391f5%3A0xac51c6a5beeda8b1!2sC.%20de%20San%20Bernardo%2C%2070%2C%20Centro%2C%2028015%20Madrid!5e0!3m2!1ses!2ses!4v1748459048454!5m2!1ses!2ses" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-md-6">
                <div class="wow fadeInUp" data-wow-delay="0.2s">
                    <form method="POST" action="seccion/enviar_contacto.php">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Tu nombre" required>
                                    <label for="name">Tu nombre</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Tu correo electrónico" required>
                                    <label for="email">Tu correo electrónico</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Asunto" required>
                                    <label for="subject">Asunto</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Escribe tu mensaje aquí" id="message" name="message" style="height: 150px" required></textarea>
                                    <label for="message">Mensaje</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Enviar mensaje</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<!-- Fin Contacto -->