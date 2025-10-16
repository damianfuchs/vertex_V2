// Solo ejecutar si estamos en la página de clientes
(function () {
  'use strict'

 

  // Verificar que estamos en la página correcta
  function esClientes() {
    return document.querySelector("#modalVerCliente") !== null
  }

  if (!esClientes()) {

    return
  }



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

  // Función para manejar clicks específicos de clientes
  function manejarClickClientes(event) {
    const button = event.target.closest("button")
    if (!button) return

    // === CLIENTE: VER ===
    if (button.classList.contains("btn-info") && button.getAttribute("data-bs-target") === "#modalVerCliente") {
      console.log("👁️ === MODAL VER CLIENTE ===")

      const datos = {
        id: button.getAttribute("data-id"),
        nombre: button.getAttribute("data-nombre"),
        dni: button.getAttribute("data-dni"),
        email: button.getAttribute("data-email"),
        telefono: button.getAttribute("data-telefono"),
        direccion: button.getAttribute("data-direccion"),
        localidad: button.getAttribute("data-localidad"),
        tipo: button.getAttribute("data-tipo"),
        observaciones: button.getAttribute("data-observaciones"),
      }

      // Asignar valores
      const asignar = (id, valor) => {
        const elemento = document.getElementById(id)
        if (elemento) {
          elemento.textContent = valor || "-"
        }
      }

      asignar("verNombre", datos.nombre)
      asignar("verDni", datos.dni)
      asignar("verEmail", datos.email)
      asignar("verTelefono", datos.telefono)
      asignar("verDireccion", datos.direccion)
      asignar("verLocalidad", datos.localidad)
      asignar("verTipo", datos.tipo)
      asignar("verObservaciones", datos.observaciones)

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

    // === CLIENTE: EDITAR ===
    if (button.classList.contains("btn-warning") && button.getAttribute("data-bs-target") === "#modalEditarCliente") {
      console.log("✏️ === MODAL EDITAR CLIENTE ===")

      const datos = {
        id: button.getAttribute("data-id"),
        nombre: button.getAttribute("data-nombre"),
        dni: button.getAttribute("data-dni"),
        email: button.getAttribute("data-email"),
        telefono: button.getAttribute("data-telefono"),
        direccion: button.getAttribute("data-direccion"),
        localidad: button.getAttribute("data-localidad"),
        tipo: button.getAttribute("data-tipo"),
        observaciones: button.getAttribute("data-observaciones"),
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
      asignarCampo("editarDni", datos.dni)
      asignarCampo("editarEmail", datos.email)
      asignarCampo("editarTelefono", datos.telefono)
      asignarCampo("editarDireccion", datos.direccion)
      asignarCampo("editarLocalidad", datos.localidad)
      asignarCampo("editarTipo", datos.tipo)
      asignarCampo("editarObservaciones", datos.observaciones)
      return
    }

    // === CLIENTE: ELIMINAR ===
    if (button.classList.contains("btn-danger") && button.getAttribute("data-bs-target") === "#modalEliminarCliente") {

      const id = button.getAttribute("data-id")

      const eliminarIdInput = document.getElementById("eliminarId")

      if (eliminarIdInput) {
        eliminarIdInput.value = id || ""
      }
      return
    }
  }



  // Función para mostrar mensaje
  function mostrarMensaje(texto, tipo = "success") {
    const contenedor = document.getElementById("mensajeCliente")
    if (!contenedor) return

    contenedor.textContent = texto
    contenedor.className = `mensaje-flotante alert-${tipo}`
    contenedor.classList.remove("d-none")

    setTimeout(() => {
      contenedor.classList.add("d-none")
    }, 3000)
  }

  // Función para enviar formularios (agregar, editar, eliminar)
  function enviarFormulario(event, tipo) {
    event.preventDefault()
    const formData = new FormData(event.target)

    fetch(event.target.action, {
      method: "POST",
      body: formData,
    })
      .then(response => response.text())
      .then(data => {
        switch (tipo) {
          case "agregar":
            mostrarMensaje("✅ Cliente agregado con éxito", "success");
            setTimeout(() => location.reload(), 1500);
            break;
          case "editar":
            mostrarMensaje("✅ Cliente editado con éxito", "success");
            setTimeout(() => location.reload(), 1500);
            break;
          case "eliminar":
            mostrarMensaje("✅ Cliente eliminado con éxito", "success");
            setTimeout(() => location.reload(), 1500);
            break;
          default:
            mostrarMensaje("✅ Operación exitosa", "success");
            setTimeout(() => location.reload(), 1500);
        }
      })
      .catch(error => {
        console.error("❌ Error:", error)
        mostrarMensaje(`Error al ${tipo.toLowerCase()} el cliente`, "danger")
      })

  }

  // Configurar event listeners
  function configurarEventListeners() {
    // Clicks en botones
    document.addEventListener("click", manejarClickClientes)

    // Formularios
    const formAgregar = document.getElementById("formAgregarCliente")
    const formEditar = document.getElementById("formEditarCliente")
    const formEliminar = document.getElementById("formEliminarCliente")

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
    const modalAgregar = document.getElementById("modalAgregarCliente")
    if (modalAgregar) {
      modalAgregar.addEventListener("hidden.bs.modal", () => {
        const form = document.getElementById("formAgregarCliente")
        const mensaje = document.getElementById("mensajeAgregarExito")
        if (form) form.reset()
        if (mensaje) mensaje.classList.add("d-none")
      })
    }
  }

  // Inicializar inmediatamente
  configurarEventListeners()

  // Hacer funciones globales para poder usarlas en HTML
  window.copiarTexto = copiarTexto

  console.log("🎉 Módulo clientes configurado correctamente")
})()
