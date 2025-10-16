// Solo ejecutar si estamos en la página de categorías
(function () {
  'use strict'

  // Verificar que estamos en la página correcta
  function esCategorias() {
    return document.querySelector("#modalVerCategoria") !== null
  }

  if (!esCategorias()) {
    return
  }

  const categoriasTableBody = document.getElementById("categoriasTableBody");

  // Helper para crear una fila de tabla de categoría
  function createCategoryRow(category) {
    const row = document.createElement('tr');
    row.setAttribute('data-id', category.id_categ);
    row.innerHTML = `
      <td>${category.codigo_categ || '-'}</td>
      <td>${category.nombre_categ || '-'}</td>
      <td>${category.descripcion_categ || '-'}</td>
      <td>
        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalVerCategoria"
            data-id="${category.id_categ}"
            data-codigo="${category.codigo_categ || ''}"
            data-nombre="${category.nombre_categ || ''}"
            data-descripcion="${category.descripcion_categ || ''}">
            <i class="bi bi-eye"></i>
        </button>
        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
            data-bs-target="#modalEditarCategoria" data-id="${category.id_categ}"
            data-codigo="${category.codigo_categ || ''}"
            data-nombre="${category.nombre_categ || ''}"
            data-descripcion="${category.descripcion_categ || ''}">
            <i class="bi bi-pencil-square"></i>
        </button>
        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
            data-bs-target="#modalEliminarCategoria" data-id="${category.id_categ}">
            <i class="bi bi-trash"></i>
        </button>
      </td>
    `;
    return row;
  }

  // Función para manejar clicks específicos de categorías
  function manejarClickCategorias(event) {
    const button = event.target.closest("button")
    if (!button) return

    // === CATEGORÍA: VER ===
    if (button.classList.contains("btn-info") && button.getAttribute("data-bs-target") === "#modalVerCategoria") {
      console.log("👁️ === MODAL VER CATEGORÍA ===")

      const datos = {
        id: button.getAttribute("data-id"),
        codigo: button.getAttribute("data-codigo"),
        nombre: button.getAttribute("data-nombre"),
        descripcion: button.getAttribute("data-descripcion"),
      }

      // Asignar valores
      const asignar = (id, valor) => {
        const elemento = document.getElementById(id)
        if (elemento) {
          elemento.textContent = valor || "-"
        }
      }

      asignar("verCodigo", datos.codigo)
      asignar("verNombre", datos.nombre)
      asignar("verDescripcion", datos.descripcion)
      return
    }

    // === CATEGORÍA: EDITAR ===
    if (button.classList.contains("btn-warning") && button.getAttribute("data-bs-target") === "#modalEditarCategoria") {
      console.log("✏️ === MODAL EDITAR CATEGORÍA ===")

      const datos = {
        id: button.getAttribute("data-id"),
        codigo: button.getAttribute("data-codigo"),
        nombre: button.getAttribute("data-nombre"),
        descripcion: button.getAttribute("data-descripcion"),
      }

      // Asignar valores a los campos del formulario
      const asignarCampo = (id, valor) => {
        const elemento = document.getElementById(id)
        if (elemento) {
          elemento.value = valor || ""
        }
      }

      asignarCampo("editarId", datos.id)
      asignarCampo("editarCodigo", datos.codigo)
      asignarCampo("editarNombre", datos.nombre)
      asignarCampo("editarDescripcion", datos.descripcion)
      return
    }

    // === CATEGORÍA: ELIMINAR ===
    if (button.classList.contains("btn-danger") && button.getAttribute("data-bs-target") === "#modalEliminarCategoria") {
      console.log("🗑️ === MODAL ELIMINAR CATEGORÍA ===")
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
    const contenedor = document.getElementById("mensajeCategoria")
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
    const modalId = event.target.closest('.modal').id;
    const modalInstance = bootstrap.Modal.getInstance(document.getElementById(modalId));


    fetch(event.target.action, {
      method: "POST",
      body: formData,
    })
      .then(response => response.json()) // Esperamos una respuesta JSON
      .then(data => {
        if (data.success) {
          mostrarMensaje(`✅ ${data.message}`, "success");
          if (modalInstance) {
            modalInstance.hide(); // Cerrar el modal
          }

          // Actualizar la tabla dinámicamente
          if (tipo === "agregar" && data.category) {
            const newRow = createCategoryRow(data.category);
            categoriasTableBody.appendChild(newRow);
          } else if (tipo === "editar" && data.category) {
            const existingRow = categoriasTableBody.querySelector(`tr[data-id="${data.category.id_categ}"]`);
            if (existingRow) {
              const updatedRow = createCategoryRow(data.category);
              existingRow.replaceWith(updatedRow);
            }
          } else if (tipo === "eliminar" && data.id_categ) {
            const rowToRemove = categoriasTableBody.querySelector(`tr[data-id="${data.id_categ}"]`);
            if (rowToRemove) {
              rowToRemove.remove();
            }
          }
        } else {
          mostrarMensaje(`❌ ${data.message}`, "danger");
        }
      })
      .catch(error => {
        console.error("❌ Error:", error)
        mostrarMensaje(`Error al ${tipo.toLowerCase()} la categoría`, "danger")
      })

  }

  // Configurar event listeners
  function configurarEventListeners() {
    // Clicks en botones
    document.addEventListener("click", manejarClickCategorias)

    // Formularios
    const formAgregar = document.getElementById("formAgregarCategoria")
    const formEditar = document.getElementById("formEditarCategoria")
    const formEliminar = document.getElementById("formEliminarCategoria")

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
    const modalAgregar = document.getElementById("modalAgregarCategoria")
    if (modalAgregar) {
      modalAgregar.addEventListener("hidden.bs.modal", () => {
        const form = document.getElementById("formAgregarCategoria")
        const mensaje = document.getElementById("mensajeAgregarExito")
        if (form) form.reset()
        if (mensaje) mensaje.classList.add("d-none")
      })
    }
  }

  // Inicializar inmediatamente
  configurarEventListeners()

  console.log("🎉 Módulo categorías configurado correctamente")
})()
