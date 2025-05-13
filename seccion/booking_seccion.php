<head>
    <!-- Primero jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Luego datetimepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<!-- Finalmente tu script -->
<script src="functionBooking.js"></script>
</head>
<body>
<section id="reserva">
<!-- Reservation Start -->
<div class="container-xxl py-5 px-0 wow fadeInUp" data-wow-delay="0.1s">
    <div class="row g-0">
        <div class="col-md-6">
            <div class="video">
                <button type="button" class="btn-play" data-bs-toggle="modal"
                    data-src="https://www.youtube.com/embed/DWRcNpR6Kdc" data-bs-target="#videoModal">
                    <span></span>
                </button>
            </div>
        </div>
        <div class="col-md-6 bg-dark d-flex align-items-center">
            <div class="p-5 wow fadeInUp" data-wow-delay="0.2s">
                <h5 class="section-title ff-secondary text-start text-primary fw-normal">Reservas</h5>
                <h1 class="text-white mb-4">Reserva tu mesa online</h1>
                <form>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" placeholder="Tu nombre">
                                <label for="name">Tu nombre</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" placeholder="Tu correo electrónico">
                                <label for="email">Tu correo electrónico</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating date" id="date3" data-target-input="nearest">
                                <input type="date" class="form-control datetimepicker-input" id="datetime"
                                    placeholder="Fecha y hora" data-target="#date3"
                                    data-toggle="datetimepicker" />
                                <label for="datetime">Fecha</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="hora">
                                    <!-- Las opciones de hora se generarán con JavaScript -->
                                </select>
                                <label for="hora">Hora</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" id="telefono" placeholder="Teléfono"
                                    pattern="^(\+?\d{1,3})?\d{9}$"
                                    title="Debe contener 9 dígitos, opcionalmente con prefijo (+34, etc.)"
                                    maxlength="13">
                                <label for="telefono">Teléfono</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="select1">
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
                                <textarea class="form-control" placeholder="Solicitud especial" id="message"
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Obtener elementos del DOM
    const fechaInput = document.getElementById('datetime');
    const horaSelect = document.getElementById('hora');
    const formReserva = document.querySelector('#reserva form');
    
    // Configurar fecha mínima (hoy)
    const hoy = new Date();
    const fechaHoy = hoy.toISOString().split('T')[0];
    if (fechaInput) {
        fechaInput.min = fechaHoy;
        fechaInput.value = fechaHoy; // Establecer hoy como valor por defecto
    }

    // Generar horarios disponibles inicialmente
    generarHorariosDisponibles(hoy);

    // Actualizar horarios cuando cambia la fecha
    if (fechaInput) {
        fechaInput.addEventListener('change', function() {
            const esHoy = this.value === fechaHoy;
            generarHorariosDisponibles(esHoy ? hoy : null);
        });
    }

    // Validar antes de enviar el formulario
    if (formReserva) {
        formReserva.addEventListener('submit', function(e) {
            if (!validarFechaHora()) {
                e.preventDefault();
                alert('No puedes reservar para una hora que ya ha pasado. Por favor, selecciona una hora válida.');
                return false;
            }
            return true;
        });
    }

    // Función para generar horarios disponibles (modificada desde tu original)
    function generarHorariosDisponibles(fechaReferencia) {
        // Limpiar select de horas
        horaSelect.innerHTML = '';

        // Función interna para crear grupos de horarios (similar a tu original)
        const crearOpcionesHorario = (inicio, fin, label) => {
            const grupo = document.createElement('optgroup');
            grupo.label = label;

            for (let h = inicio; h <= fin; h++) {
                const minutos = (h === fin) ? [0] : [0, 30];
                minutos.forEach(m => {
                    // Si es para hoy, verificar si la hora ya pasó
                    if (fechaReferencia) {
                        const horaActual = fechaReferencia.getHours();
                        const minutoActual = fechaReferencia.getMinutes();
                        
                        // Si la hora es anterior a la actual, saltar
                        if (h < horaActual || (h === horaActual && m < minutoActual)) {
                            return;
                        }
                    }

                    const hora = `${h.toString().padStart(2, '0')}:${m === 0 ? '00' : '30'}`;
                    const opcion = document.createElement('option');
                    opcion.value = hora;
                    opcion.textContent = hora;
                    grupo.appendChild(opcion);
                });
            }
            return grupo;
        };

        // Crear horarios (manteniendo tu estructura original)
        const grupoAlmuerzo = crearOpcionesHorario(12, 16, 'Almuerzo');
        const grupoCena = crearOpcionesHorario(19, 23, 'Cena');

        // Añadir solo si tienen opciones disponibles
        if (grupoAlmuerzo.children.length > 0) horaSelect.appendChild(grupoAlmuerzo);
        if (grupoCena.children.length > 0) horaSelect.appendChild(grupoCena);

        // Si no hay horarios disponibles (ej. tarde noche)
        if (horaSelect.options.length === 0) {
            const opcion = document.createElement('option');
            opcion.value = '';
            opcion.textContent = 'No hay horarios disponibles';
            opcion.disabled = true;
            opcion.selected = true;
            horaSelect.appendChild(opcion);
        }
    }

    // Función para validar fecha y hora seleccionada
    function validarFechaHora() {
        const fechaSeleccionada = fechaInput.value;
        const horaSeleccionada = horaSelect.value;
        
        // Si es para hoy, validar que la hora no haya pasado
        if (fechaSeleccionada === fechaHoy) {
            const ahora = new Date();
            const [horas, minutos] = horaSeleccionada.split(':').map(Number);
            const horaReserva = new Date();
            horaReserva.setHours(horas, minutos, 0, 0);

            return horaReserva > ahora;
        }
        return true;
    }
});
</script>
</body>