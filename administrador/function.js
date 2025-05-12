// Función para cargar datos de reserva en el formulario de edición
function cargarDatosReserva(id, nombre, email, telefono, fecha, hora, personas, estado) {
    document.getElementById('id_reserva').value = id;
    document.querySelector('input[name="nombre"]').value = nombre;
    document.querySelector('input[name="email"]').value = email;
    document.querySelector('input[name="telefono"]').value = telefono;
    document.querySelector('input[name="fecha"]').value = fecha;
    document.querySelector('select[name="hora"]').value = hora;
    document.querySelector('input[name="personas"]').value = personas;
}

// Configuración inicial cuando el DOM está cargado
document.addEventListener('DOMContentLoaded', function() {
    // Establecer fecha mínima como hoy
    const fechaInput = document.getElementById('fecha');
    if (fechaInput) {
        fechaInput.min = new Date().toISOString().split('T')[0];
    }

    // Generar opciones de horario
    const horaSelect = document.getElementById('hora');
    if (horaSelect) {
        const crearOpcionesHorario = (inicio, fin, label) => {
            const grupo = document.createElement('optgroup');
            grupo.label = label;

            for (let h = inicio; h <= fin; h++) {
                const minutos = (h === fin) ? [0] : [0, 30];
                minutos.forEach(m => {
                    const hora = `${h.toString().padStart(2, '0')}:${m === 0 ? '00' : '30'}`;
                    const opcion = document.createElement('option');
                    opcion.value = hora;
                    opcion.textContent = hora;
                    grupo.appendChild(opcion);
                });
            }
            return grupo;
        };

        horaSelect.appendChild(crearOpcionesHorario(12, 16, 'Almuerzo'));
        horaSelect.appendChild(crearOpcionesHorario(19, 23, 'Cena'));
    }

    // Validación del formulario
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const nombreInput = document.getElementById('nombre');
            const nombreVal = nombreInput.value.trim();
            const nombreRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{1,30}$/;

            if (!nombreRegex.test(nombreVal)) {
                alert('El nombre solo puede contener letras y espacios (máx. 30 caracteres).');
                nombreInput.focus();
                e.preventDefault();
                return false;
            }

            return true;
        });
    }
});