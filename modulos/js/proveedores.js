// Solo ejecutar si estamos en la página de proveedores
(function () {
    'use strict'

    console.log("🚀 Módulo proveedores.js cargado")

    // Verificar que estamos en la página correcta
    function esProveedores() {
        return document.querySelector("#modalVerProveedor") !== null
    }

    if (!esProveedores()) {
        console.log("⚠️ No estamos en la página de proveedores")
        return
    }

    console.log("✅ Página de proveedores detectada, inicializando...")

    // Función para copiar texto
    function copiarTexto(elementId, button) {
        const elemento = document.getElementById(elementId)
        const texto = elemento.textContent.trim()

        if (!texto || texto === "-") {
            alert("No hay texto para copiar")
            return
        }

        navigator.clipboard
            .writeText(texto)
            .then(() => {
                const originalText = button.innerHTML
                button.innerHTML = '<i class="bi bi-check"></i> Copiado'
                button.classList.remove("btn-outline-primary", "btn-outline-success")
                button.classList.add("btn-success")

                setTimeout(() => {
                    button.innerHTML = originalText
                    button.classList.remove("btn-success")
                    if (elementId === "verEmail") {
                        button.classList.add("btn-outline-primary")
                    } else {
                        button.classList.add("btn-outline-success")
                    }
                }, 2000)
            })
            .catch((err) => {
                console.error("Error al copiar: ", err)
                alert("Error al copiar el texto")
            })
    }

    // Función para manejar clicks específicos de proveedores
    function manejarClickProveedores(event) {
        const button = event.target.closest("button")
        if (!button) return

        // === PROVEEDOR: VER ===
        if (button.classList.contains("btn-ver") && button.getAttribute("data-bs-target") === "#modalVerProveedor") {
            console.log("👁️ === MODAL VER PROVEEDOR ===")

            const datos = {
                id: button.getAttribute("data-id"),
                nombre: button.getAttribute("data-nombre"),
                contacto: button.getAttribute("data-contacto"),
                email: button.getAttribute("data-email"),
                telefono: button.getAttribute("data-telefono"),
                direccion: button.getAttribute("data-direccion"),
                ubicacion: button.getAttribute("data-ubicacion"),
                codigoPostal: button.getAttribute("data-codigopostal"),
                sitioWeb: button.getAttribute("data-sitioweb"),
                horario: button.getAttribute("data-horario"),
                observaciones: button.getAttribute("data-observaciones"),
                estado: button.getAttribute("data-estado"),
            }

            // Asignar valores
            const asignar = (id, valor) => {
                const elemento = document.getElementById(id)
                if (elemento) {
                    elemento.textContent = valor || "-"
                }
            }

            asignar("verNombre", datos.nombre)
            asignar("verContacto", datos.contacto)
            asignar("verEmail", datos.email)
            asignar("verTelefono", datos.telefono)
            asignar("verDireccion", datos.direccion)
            asignar("verUbicacion", datos.ubicacion)
            asignar("verCodigoPostal", datos.codigoPostal)
            asignar("verSitioWeb", datos.sitioWeb)
            asignar("verHorario", datos.horario)
            asignar("verObservaciones", datos.observaciones)
            asignar("verEstado", datos.estado == "1" ? "Activo" : "No Activo")

            // Agregar link WhatsApp dinámico
            const telefono = datos.telefono || "";
            const btnWhatsapp = document.getElementById("btnWhatsapp");

            if (telefono.trim() !== "") {
                const numeroLimpio = telefono.replace(/\D/g, "");
                btnWhatsapp.href = `https://wa.me/${numeroLimpio}`;
                btnWhatsapp.style.display = "inline-block";
            } else {
                btnWhatsapp.style.display = "none";
            }

            return
        }

        // === PROVEEDOR: EDITAR ===
        if (button.classList.contains("btn-editar") && button.getAttribute("data-bs-target") === "#modalEditarProveedor") {
            console.log("✏️ === MODAL EDITAR PROVEEDOR ===")

            const datos = {
                id: button.getAttribute("data-id"),
                nombre: button.getAttribute("data-nombre"),
                contacto: button.getAttribute("data-contacto"),
                email: button.getAttribute("data-email"),
                telefono: button.getAttribute("data-telefono"),
                direccion: button.getAttribute("data-direccion"),
                ubicacion: button.getAttribute("data-ubicacion"),
                codigoPostal: button.getAttribute("data-codigopostal"),
                sitioWeb: button.getAttribute("data-sitioweb"),
                horario: button.getAttribute("data-horario"),
                observaciones: button.getAttribute("data-observaciones"),
                estado: button.getAttribute("data-estado"),
            }

            // Asignar valores a los campos del formulario
            const asignarCampo = (id, valor) => {
                const elemento = document.getElementById(id)
                if (elemento) {
                    elemento.value = valor || ""
                }
            }

            asignarCampo("editarId", datos.id)
            asignarCampo("editarNombre", datos.nombre)
            asignarCampo("editarContacto", datos.contacto)
            asignarCampo("editarEmail", datos.email)
            asignarCampo("editarTelefono", datos.telefono)
            asignarCampo("editarDireccion", datos.direccion)
            asignarCampo("editarUbicacion", datos.ubicacion)
            asignarCampo("editarCodigoPostal", datos.codigoPostal)
            asignarCampo("editarSitioWeb", datos.sitioWeb)
            asignarCampo("editarHorario", datos.horario)
            asignarCampo("editarObservaciones", datos.observaciones)
            asignarCampo("editarEstado", datos.estado || "1")
            return
        }

        // === PROVEEDOR: ELIMINAR ===
        if (button.classList.contains("btn-eliminar") && button.getAttribute("data-bs-target") === "#modalEliminarProveedor") {

            const id = button.getAttribute("data-id")
            const nombre = button.getAttribute("data-nombre")

            const eliminarIdInput = document.getElementById("eliminarId")
            const eliminarNombreSpan = document.getElementById("eliminarNombreProveedor")

            if (eliminarIdInput) {
                eliminarIdInput.value = id || ""
            }

            if (eliminarNombreSpan) {
                eliminarNombreSpan.textContent = nombre || "Sin nombre"
            }
            return
        }
    }


    // Función para enviar formularios
    function mostrarMensaje(texto, tipo = "success") {
        const contenedor = document.getElementById("mensajeProveedor");
        if (!contenedor) return;

        contenedor.textContent = texto;
        contenedor.className = `mensaje-flotante alert-${tipo}`;
        contenedor.classList.remove("d-none");

        setTimeout(() => {
            contenedor.classList.add("d-none");
        }, 3000);
    }



    function enviarFormulario(event, tipo) {
        event.preventDefault();
        console.log(`📝 Enviando formulario de ${tipo}...`);

        const formData = new FormData(event.target);

        fetch(event.target.action, {
            method: "POST",
            body: formData,
        })
            .then(response => response.text())
            .then(data => {
                try {
                    const jsonResponse = JSON.parse(data);
                    if (jsonResponse.success) {
                        mostrarMensaje("✅ " + jsonResponse.message, "success");
                        setTimeout(() => location.reload(), 1500); // Recarga la página luego de mostrar mensaje
                    } else {
                        mostrarMensaje("❌ Error: " + jsonResponse.message, "danger");
                    }
                } catch (e) {
                    mostrarMensaje(`✅ ${tipo} realizado con éxito`, "success");
                    setTimeout(() => location.reload(), 1500);
                }
            })
            .catch(error => {
                console.error("❌ Error:", error);
                mostrarMensaje(`Error al ${tipo.toLowerCase()} el proveedor`, "danger");
            });
    }

    // Configurar event listeners
    function configurarEventListeners() {
        // Clicks en botones
        document.addEventListener("click", manejarClickProveedores)

        // Formularios
        const formAgregar = document.getElementById("formAgregarProveedor")
        const formEditar = document.getElementById("formEditarProveedor")
        const formEliminar = document.getElementById("formEliminarProveedor")

        if (formAgregar) {
            formAgregar.addEventListener("submit", (e) => enviarFormulario(e, "agregar"))
        }

        if (formEditar) {
            formEditar.addEventListener("submit", (e) => enviarFormulario(e, "editar"))
        }

        if (formEliminar) {
            formEliminar.addEventListener("submit", (e) => enviarFormulario(e, "eliminar"))
        }

        // Limpiar formularios al cerrar modales
        const modalAgregar = document.getElementById("modalAgregarProveedor")
        if (modalAgregar) {
            modalAgregar.addEventListener("hidden.bs.modal", () => {
                const form = document.getElementById("formAgregarProveedor")
                const mensaje = document.getElementById("mensajeAgregarExito")
                if (form) form.reset()
                if (mensaje) mensaje.classList.add("d-none")
            })
        }
    }

    // Inicializar inmediatamente
    configurarEventListeners()

    // Hacer funciones globales
    window.copiarTexto = copiarTexto

    console.log("🎉 Módulo proveedores configurado correctamente")
})()
