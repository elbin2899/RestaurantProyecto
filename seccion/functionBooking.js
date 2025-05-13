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