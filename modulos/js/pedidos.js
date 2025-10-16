// Solo ejecutar si estamos en la página de pedidos
(function () {
  'use strict'

  console.log("🚀 Módulo pedidos.js cargado")

  // Verificar que estamos en la página correcta
  function esPedidos() {
    return document.querySelector("#modalVerPedido") !== null
  }

  if (!esPedidos()) {
    console.log("⚠️ No estamos en la página de pedidos")
    return
  }

  console.log("✅ Página de pedidos detectada, inicializando...")

  // Función para manejar clicks específicos de pedidos
  function manejarClickPedidos(event) {
    const button = event.target.closest("button")
    if (!button) return

    // === PEDIDO: VER ===
    if (button.classList.contains("btn-info") && button.getAttribute("data-bs-target") === "#modalVerPedido") {
      console.log("👁️ === MODAL VER PEDIDO ===")

      const datos = {
        id: button.getAttribute("data-id"),
        nombre_cliente: button.getAttribute("data-nombre-cliente"),
        fecha: button.getAttribute("data-fecha"),
        precio: button.getAttribute("data-precio"),
        estado: button.getAttribute("data-estado"),
        observaciones: button.getAttribute("data-observaciones"),
      }

      // Asignar valores
      const asignar = (id, valor) => {
        const elemento = document.getElementById(id)
        if (elemento) {
          elemento.textContent = valor || "-"
        }
      }

      asignar("verNombreClientePedido", datos.nombre_cliente)
      asignar("verFechaPedido", datos.fecha)
      asignar("verPrecioPedido", datos.precio)
      asignar("verEstadoPedido", datos.estado)
      asignar("verObservacionesPedido", datos.observaciones)

      return
    }

    // === PEDIDO: EDITAR ===
    if (button.classList.contains("btn-warning") && button.getAttribute("data-bs-target") === "#modalEditarPedido") {
      console.log("✏️ === MODAL EDITAR PEDIDO ===")

      const datos = {
        id: button.getAttribute("data-id"),
        nombre_cliente: button.getAttribute("data-nombre-cliente"),
        fecha: button.getAttribute("data-fecha"),
        precio: button.getAttribute("data-precio"),
        estado: button.getAttribute("data-estado"),
        observaciones: button.getAttribute("data-observaciones"),
      }

      // Asignar valores a los campos del formulario
      const asignarCampo = (id, valor) => {
        const elemento = document.getElementById(id)
        if (elemento) {
          elemento.value = valor || ""
        }
      }

      asignarCampo("editarIdPedido", datos.id)
      asignarCampo("editarNombreClientePedido", datos.nombre_cliente)
      const fechaValida = new Date(datos.fecha).toISOString().slice(0, 16)
      asignarCampo("editarFechaPedido", fechaValida)
      asignarCampo("editarPrecioPedido", datos.precio)
      asignarCampo("editarEstadoPedido", datos.estado)
      asignarCampo("editarObservacionesPedido", datos.observaciones)

      return
    }

    // === PEDIDO: ELIMINAR ===
    if (button.classList.contains("btn-danger") && button.getAttribute("data-bs-target") === "#modalEliminarPedido") {
      const id = button.getAttribute("data-id")
      const eliminarIdInput = document.getElementById("eliminarIdPedido")
      if (eliminarIdInput) {
        eliminarIdInput.value = id || ""
      }
      return
    }
  }

  // Función para mostrar mensaje
  function mostrarMensaje(texto, tipo = "success") {
    const contenedor = document.getElementById("mensajePedido")
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
            mostrarMensaje("✅ Pedido agregado con éxito", "success");
            setTimeout(() => location.reload(), 1500);
            break;
          case "editar":
            mostrarMensaje("✅ Pedido editado con éxito", "success");
            setTimeout(() => location.reload(), 1500);
            break;
          case "eliminar":
            mostrarMensaje("✅ Pedido eliminado con éxito", "success");
            setTimeout(() => location.reload(), 1500);
            break;
          default:
            mostrarMensaje("✅ Operación exitosa", "success");
            setTimeout(() => location.reload(), 1500);
        }
      })
      .catch(error => {
        console.error("❌ Error:", error)
        mostrarMensaje(`Error al ${tipo.toLowerCase()} el pedido`, "danger")
      })
  }

  // Configurar event listeners
  function configurarEventListeners() {
    // Clicks en botones
    document.addEventListener("click", manejarClickPedidos)

    // Formularios
    const formAgregar = document.getElementById("formAgregarPedido")
    const formEditar = document.getElementById("formEditarPedido")
    const formEliminar = document.getElementById("formEliminarPedido")

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
    const modalAgregar = document.getElementById("modalAgregarPedido")
    if (modalAgregar) {
      modalAgregar.addEventListener("hidden.bs.modal", () => {
        const form = document.getElementById("formAgregarPedido")
        if (form) form.reset()
      })
    }
  }

  // Inicializar inmediatamente
  configurarEventListeners()

  console.log("🎉 Módulo pedidos configurado correctamente")

  




})()



document.addEventListener('click', function(event) {
  const btn = event.target.closest('.btn-marcar-entregado');
  if (!btn) return;

  const idPedido = btn.getAttribute('data-id');
  if (!idPedido) return;

  // Deshabilitar botón para evitar múltiples clicks
  btn.disabled = true;

  fetch('./modulos/controllers/marcar_entregado.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `id_pedido=${encodeURIComponent(idPedido)}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      // Cambiar el estado en la fila
      const fila = btn.closest('tr');

      // Cambiar badge Estado
      const tdEstado = fila.querySelector('td:nth-child(4)');
      if (tdEstado) {
        tdEstado.innerHTML = `<span class="badge bg-success">Entregado</span>`;
      }

      // Cambiar contenido de la columna "Marcar"
      const tdMarcar = btn.parentElement;
      if (tdMarcar) {
        tdMarcar.innerHTML = `<span class="text-muted">✓</span>`;
      }

      mostrarMensaje('Pedido marcado como Entregado', 'success');
    } else {
      alert('Error: ' + (data.error || 'No se pudo actualizar el estado'));
      btn.disabled = false;
    }
  })
  .catch(err => {
    alert('Error de conexión o en el servidor');
    btn.disabled = false;
  });
});

// Función para mostrar mensaje flotante (puedes usar la misma que tienes)
function mostrarMensaje(texto, tipo = "success") {
  const contenedor = document.getElementById("mensajePedido");
  if (!contenedor) return;

  contenedor.textContent = texto;
  contenedor.className = `mensaje-flotante alert-${tipo}`;
  contenedor.classList.remove("d-none");

  setTimeout(() => {
    contenedor.classList.add("d-none");
  }, 3000);
}
