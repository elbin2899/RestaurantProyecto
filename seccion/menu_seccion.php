
<?php include('db.php'); ?>
<!-- Inicio Menú -->
<section id="menu">
<div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h5 class="section-title ff-secondary text-center text-primary fw-normal">Carta</h5>
                <h1 class="mb-5">Nuestros platos más populares</h1>
            </div>
            <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">
                <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                    <li class="nav-item">
                        <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3 active" data-bs-toggle="pill" href="#tab-1">
                            <i class="fa fa-utensils fa-2x text-primary"></i>
                            <div class="ps-3">
                                <small class="text-body">Exclusivo</small>
                                <h6 class="mt-n1 mb-0">Entrantes</h6>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex align-items-center text-start mx-3 pb-3" data-bs-toggle="pill" href="#tab-2">
                            <i class="fa fa-hamburger fa-2x text-primary"></i>
                            <div class="ps-3">
                                <small class="text-body">Especial</small>
                                <h6 class="mt-n1 mb-0">Plato principal</h6>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex align-items-center text-start mx-3 me-0 pb-3" data-bs-toggle="pill" href="#tab-3">
                            <i class="fa fa-coffee fa-2x text-primary"></i>
                            <div class="ps-3">
                                <small class="text-body">Popular</small>
                                <h6 class="mt-n1 mb-0">Postres</h6>
                            </div>
                        </a>
                    </li>
                </ul>
            <div class="tab-content">
                <?php
                // Definir las categorías del menú
                $categorias = [
                    1 => 'Entrantes',
                    2 => 'Plato principal',
                    3 => 'Postres'
                ];
                
                foreach($categorias as $id_categoria => $nombre_categoria) {
                    $active = $id_categoria == 1 ? 'active' : '';
                    echo '<div id="tab-'.$id_categoria.'" class="tab-pane fade show p-0 '.$active.'">';
                    echo '<div class="row g-4">';
                    
                    // Consulta SQL 
                    $query = "SELECT * FROM item_menu WHERE id_categoria = ? AND activo = 1";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, "i", $id_categoria);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    
                    while($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="col-lg-6">';
                        echo '<div class="d-flex align-items-center">';
                        
                        // Imagen del plato
                        $ruta_imagenes = 'img/';
                        $imagen = (!empty($row['imagen'])) ? $ruta_imagenes . $row['imagen'] : 'img/default.png';
                        echo '<img class="flex-shrink-0 img-fluid rounded" src="'.htmlspecialchars($imagen).'" alt="'.htmlspecialchars($row['nombre']).'" style="width: 80px;">';
                        
                        // Contenido textual
                        echo '<div class="w-100 d-flex flex-column text-start ps-4">';
                        echo '<h5 class="d-flex justify-content-between border-bottom pb-2">';
                        echo '<span>' . htmlspecialchars($row['nombre']) . '</span>';
                        echo '<span class="text-primary">€' . number_format($row['precio'], 2, ',', '') . '</span>';
                        echo '</h5>';
                        echo '<small class="fst-italic">' . htmlspecialchars($row['descripcion']) . '</small>';
                        echo '</div>'; // cierre div texto

                        echo '</div>'; // cierre d-flex
                        echo '</div>'; // cierre col
                    }

                    mysqli_stmt_close($stmt);
                    echo '</div></div>';
                }
                ?>
            </div>
        </div>
    </div>
</section>
<!-- Fin Menú -->
