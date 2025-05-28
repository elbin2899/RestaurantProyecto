<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $fecha_reserva = $_POST['fecha_reserva'];
    $comentario = $_POST['comentario'];

    // Validar que la reserva exista con ese nombre y fecha, y que sea anterior a hoy
    $stmt = $conn->prepare("SELECT r.id_reserva 
                            FROM reserva r
                            JOIN cliente c ON r.id_cliente = c.id_cliente
                            WHERE c.nombre = ? AND r.fecha = ? AND r.fecha < CURDATE()");
    $stmt->bind_param("ss", $nombre, $fecha_reserva);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $id_reserva = $row['id_reserva'];

        $insert = $conn->prepare("INSERT INTO testimonio (id_reserva, comentario) VALUES (?, ?)");
        $insert->bind_param("is", $id_reserva, $comentario);
        if ($insert->execute()) {
            echo '<div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                    Gracias por tu testimonio. Será revisado antes de publicarse.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        } else {
            echo '<div class="alert alert-danger mt-4">Error al guardar el testimonio.</div>';
        }
    } else {
        echo '<div class="alert alert-danger mt-4">No encontramos una reserva con ese nombre y fecha, o aún no ha ocurrido.</div>';
    }
}
?>

<!-- Sección de Testimonios -->
<section id="testimonial" class="container py-5">
    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h5 class="section-title ff-secondary text-center text-primary fw-normal">Testimonios</h5>
        <h1 class="mb-5">Lo que dicen nuestros clientes</h1>
    </div>
    
    <div class="row g-4">
        <?php
        $sql = "SELECT t.comentario, c.nombre, t.fecha_comentario
                FROM testimonio t
                JOIN reserva r ON t.id_reserva = r.id_reserva
                JOIN cliente c ON r.id_cliente = c.id_cliente
                WHERE t.aprobado = 1
                ORDER BY t.fecha_comentario DESC"; // Eliminé el LIMIT 3

        $resultado = $conn->query($sql);
        
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                echo '<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="testimonial-item bg-light rounded p-4">
                            <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                            <p class="mb-4">' . htmlspecialchars($row['comentario']) . '</p>
                            <div class="d-flex align-items-center">
                                <div class="ps-3">
                                    <h6 class="mb-1">@' . strtolower(str_replace(' ', '_', $row['nombre'])) . '</h6>
                                    <small class="text-uppercase">Cliente verificado</small>
                                </div>
                            </div>
                        </div>
                      </div>';
            }
        } else {
            echo '<div class="col-12 text-center">
                    <p class="text-muted">Aún no hay testimonios disponibles.</p>
                  </div>';
        }
        // Al final de la sección de testimonios
if ($resultado->num_rows > 6) { // Mostrar paginación si hay más de 6 testimonios
    echo '<div class="col-12 text-center mt-4">
            <nav aria-label="Page navigation">
              <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                  <a class="page-link" href="#" tabindex="-1">Anterior</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                  <a class="page-link" href="#">Siguiente</a>
                </li>
              </ul>
            </nav>
          </div>';
}
        ?>
    </div>
</section>

<!-- Formulario para Testimonios -->
<section class="container py-5" id="testimonial-form ">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
            <div class="text-center">
                <h5 class="section-title ff-secondary text-center text-primary fw-normal">Comparte tu experiencia</h5>
                <h2 class="mb-5">¿Tuviste una reserva?</h2>
            </div>
            
            <form method="POST" class="bg-light p-4 rounded">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                            <label for="nombre">Tu nombre (como en la reserva)</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="fecha_reserva" name="fecha_reserva" required>
                            <label for="fecha_reserva">Fecha de reserva</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Deja tu testimonio" id="comentario" name="comentario" style="height: 100px" required></textarea>
                            <label for="comentario">Tu testimonio</label>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <section id ="testimonial">
                        <button class="btn btn-primary py-3 px-5" type="submit">Enviar Testimonio</button>
                        </section>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>