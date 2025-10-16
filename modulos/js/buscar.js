(() => {
    // ---> 1. OBTENER NUEVOS ELEMENTOS DEL MODAL
    const btnBuscar = document.getElementById("btnBuscar");
    const resultadosDiv = document.getElementById("resultadosBusqueda");
    
    // Validar elementos de búsqueda
    if (!btnBuscar || !resultadosDiv) {
        console.log("⚠️ Elementos de búsqueda no encontrados, abortando buscar.js");
        return;
    }

    // Instancias de Modals
    const modalVer = new bootstrap.Modal(document.getElementById('modalVer'));
    const modalEditar = new bootstrap.Modal(document.getElementById('modalEditar'));
    const modalEliminar = new bootstrap.Modal(document.getElementById('modalEliminar'));

    // Elementos de los Modals
    const modalVerBody = document.getElementById('modalVerBody');
    const modalEditarBody = document.getElementById('modalEditarBody');
    const itemAEliminarNombre = document.getElementById('itemAEliminarNombre');
    const formEditar = document.getElementById('formEditar');
    const formEliminar = document.getElementById('formEliminarBusqueda'); // Formulario de eliminación
    const confirmarEliminarBtn = document.getElementById('confirmarEliminarBtn'); // Botón de submit
    const confirmacionInput = document.getElementById('confirmacionEliminarBusqueda'); // Input de texto

    // Variables de estado
    let editarId = null;
    let idAEliminar = null;
    let moduloAEliminar = null;

    // Función de Búsqueda (sin cambios)
    const ejecutarBusqueda = () => {
        const modulo = document.getElementById("selectModulo").value;
        const termino = document.getElementById("inputBusqueda").value.trim();

        if (!termino) {
            resultadosDiv.innerHTML = '<div class="alert alert-warning">Por favor, escribí un término para buscar.</div>';
            return;
        }

        resultadosDiv.innerHTML = '<div class="text-center">Buscando...</div>';
        fetch(`modulos/controllers/busqueda_global.php?modulo=${modulo}&termino=${encodeURIComponent(termino)}&_=${Date.now()}`)
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.text();
            })
            .then(html => {
                resultadosDiv.innerHTML = html;
            })
            .catch(error => {
                resultadosDiv.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
                console.error('Error en fetch:', error);
            });
    };
    btnBuscar.addEventListener("click", ejecutarBusqueda);


    // Delegación de eventos en los resultados (con modificación en btn-eliminar)
    resultadosDiv.addEventListener("click", (e) => {
        const btn = e.target.closest("button");
        if (!btn) return;
        const id = btn.getAttribute("data-id");
        if (!id) return;
        const modulo = document.getElementById("selectModulo").value;

        if (btn.classList.contains("btn-ver")) {
            // ... (sin cambios aquí)
        } else if (btn.classList.contains("btn-editar")) {
            // ... (sin cambios aquí)
        } else if (btn.classList.contains("btn-eliminar")) {
            // ---> 2. MODIFICADO: PREPARAR MODAL DE ELIMINACIÓN (RESETEAR ESTADO)
            idAEliminar = id;
            moduloAEliminar = modulo;
            const nombreItem = btn.getAttribute("data-nombre") || "este registro";
            itemAEliminarNombre.textContent = nombreItem;
            
            // Reseteamos el estado del formulario de confirmación
            confirmacionInput.value = "";
            confirmarEliminarBtn.disabled = true;
            
            modalEliminar.show();
        }
    });

    // Manejo del formulario de editar (sin cambios)
    formEditar.addEventListener("submit", (e) => {
        // ... (sin cambios aquí)
    });

    // ---> 3. REEMPLAZADO: MANEJO DEL FORMULARIO DE ELIMINACIÓN
    formEliminar.addEventListener("submit", (e) => {
        e.preventDefault(); // Prevenir envío normal
        if (!idAEliminar || !moduloAEliminar) return;

        // Aquí va la lógica de fetch que antes estaba en el 'click' del botón
        fetch(`modulos/controllers/buscar_eliminar_${moduloAEliminar}.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${encodeURIComponent(idAEliminar)}`
        })
        .then(response => response.json())
        .then(data => {
            modalEliminar.hide();
            if (data.success) {
                mostrarMensajeFlotante('success', data.message || 'Eliminado correctamente');
                btnBuscar.click(); // Recarga la búsqueda
            } else {
                mostrarMensajeFlotante('danger', data.message || 'Error al eliminar');
            }
        })
        .catch(error => {
            modalEliminar.hide();
            console.error('Error en la solicitud:', error);
            mostrarMensajeFlotante('danger', 'Error en la solicitud');
        });
    });

    // ---> 4. NUEVA FUNCIÓN: LÓGICA PARA HABILITAR EL BOTÓN AL ESCRIBIR "si"
    const setupConfirmacionEliminar = () => {
        if (!confirmacionInput || !confirmarEliminarBtn) return;

        confirmacionInput.addEventListener("input", function () {
            const esValido = this.value.trim().toLowerCase() === "si";
            confirmarEliminarBtn.disabled = !esValido;
        });
    };

    // Función para mostrar mensajes flotantes (sin cambios)
    function mostrarMensajeFlotante(tipo, mensaje) {
        // ... (sin cambios aquí)
    }

    // ---> 5. LLAMAR A LA NUEVA FUNCIÓN DE CONFIGURACIÓN
    setupConfirmacionEliminar();

})();