console.log("🚀 Módulo productos.js cargado - VERSIÓN CORREGIDA v2.5 (Con confirmación de eliminación)")

// Prevenir múltiples inicializaciones
if (window.productosManagerLoaded) {
  console.log("⚠️ ProductosManager ya está cargado, evitando duplicación")
} else {
  window.productosManagerLoaded = true

  // Funcionalidad específica para la página de productos
  class ProductosManager {
    constructor() {
      this.isInitialized = false
      this.init()
    }

    init() {
      if (this.isInitialized) {
        console.log("⚠️ ProductosManager ya inicializado")
        return
      }

      console.log("✅ ProductosManager inicializando...")
      this.setupEventListeners()
      this.setupConfirmacionEliminar()
      this.isInitialized = true
      console.log("✅ ProductosManager inicializado correctamente")
    }

    setupEventListeners() {
      console.log("🔧 Configurando event listeners...")

      // Usar delegación de eventos para evitar problemas de timing
      document.body.addEventListener("click", (e) => {
        // Modal Ver Producto
        if (e.target.closest(".btn-ver")) {
          const btn = e.target.closest(".btn-ver")
          console.log("👁️ Abriendo modal ver producto")
          this.mostrarDetallesProducto(btn)
        }

        // Modal Editar Producto
        if (e.target.closest(".btn-editar")) {
          const btn = e.target.closest(".btn-editar")
          console.log("✏️ Abriendo modal editar producto")
          this.cargarDatosEdicion(btn)
        }

        // Modal Eliminar Producto
        if (e.target.closest(".btn-eliminar")) {
          const btn = e.target.closest(".btn-eliminar")
          console.log("🗑️ Abriendo modal eliminar producto")
          this.prepararEliminacion(btn)
        }
      })

      // Formularios con delegación de eventos
      document.body.addEventListener("submit", (e) => {
        if (e.target.id === "formAgregarProducto") {
          e.preventDefault()
          this.enviarFormulario(e, "agregar")
        } else if (e.target.id === "formEditarProducto") {
          e.preventDefault()
          this.enviarFormulario(e, "editar")
        } else if (e.target.id === "formEliminarProducto") {
          e.preventDefault()
          this.enviarFormulario(e, "eliminar")
        }
      })

      console.log("✅ Event listeners configurados con delegación")
    }

    mostrarDetallesProducto(btn) {
      const datos = btn.dataset
      console.log("📋 Mostrando detalles del producto:", datos.nombre)

      // Llenar campos del modal VER
      this.setElementText("verCodigo", datos.codigo)
      this.setElementText("verNombre", datos.nombre)
      this.setElementText("verDescripcion", datos.descripcion)
      this.setElementText("verCategoria", datos.categoria)
      this.setElementText("verMateria", datos.materia)
      this.setElementText("verPeso", datos.peso)
      this.setElementText("verStock", datos.stock)
      this.setElementText("verUbicacion", datos.ubicacion)

      // Manejar imagen
      const imgElement = document.getElementById("verImagen")
      const sinImagenElement = document.getElementById("sinImagen")

      if (datos.imagen && datos.imagen.trim() !== "") {
        imgElement.src = `./img/${datos.imagen}?v=${Date.now()}`
        imgElement.style.display = "block"
        sinImagenElement.style.display = "none"
      } else {
        imgElement.style.display = "none"
        sinImagenElement.style.display = "block"
      }

      console.log("✅ Detalles del producto cargados")
    }

    cargarDatosEdicion(btn) {
      const datos = btn.dataset
      console.log("📝 Cargando datos para edición:", datos.nombre)

      // Llenar campos del modal EDITAR
      this.setElementValue("editarId", datos.id)
      this.setElementValue("editarCodigo", datos.codigo)
      this.setElementValue("editarNombre", datos.nombre)
      this.setElementValue("editarDescripcion", datos.descripcion)
      this.setElementValue("editarMateria", datos.materia)
      this.setElementValue("editarPeso", datos.peso)
      this.setElementValue("editarStock", datos.stock)
      this.setElementValue("editarUbicacion", datos.ubicacion)
      this.setElementValue("editarCategoria", datos.categoriaId)

      // Limpiar el input de imagen
      const inputImagen = document.getElementById("editarImagen")
      if (inputImagen) {
        inputImagen.value = ""
      }

      // Mostrar nombre de imagen actual
      this.setElementText("nombreImagenActual", datos.imagen || "Sin imagen")

      console.log("✅ Datos de edición cargados")
    }

    prepararEliminacion(btn) {
      const datos = btn.dataset
      console.log("🗑️ Preparando eliminación:", datos.nombre)

      this.setElementValue("eliminarId", datos.id)
      this.setElementText("eliminarNombreProducto", datos.nombre)

      const confirmInput = document.getElementById("confirmacionEliminar")
      const btnEliminar = document.getElementById("btnEliminarProducto")

      if (confirmInput) {
        confirmInput.value = ""
      }

      if (btnEliminar) {
        btnEliminar.disabled = true
      }

      console.log("✅ Datos de eliminación preparados")
    }

    // Convertir enviarFormulario a una función de flecha para mantener el contexto 'this'
    enviarFormulario = async (e, tipo) => {
      // <--- CAMBIO CLAVE AQUÍ
      const form = e.target
      const formData = new FormData(form)
      const submitBtn = form.querySelector('button[type="submit"]')
      const originalBtnText = submitBtn.innerHTML

      try {
        // Deshabilitar botón y mostrar loading
        submitBtn.disabled = true
        submitBtn.innerHTML = `
          <span class="spinner-border spinner-border-sm me-2" role="status"></span>
          ${tipo === "agregar" ? "Agregando..." : tipo === "editar" ? "Guardando..." : "Eliminando..."}
        `

        console.log(`📤 Enviando formulario ${tipo}...`)
        console.log("📋 Datos del formulario:", Object.fromEntries(formData.entries()))

        const response = await fetch(form.action, {
          method: "POST",
          body: formData,
        })

        console.log(`📡 Respuesta del servidor:`, response.status, response.statusText)

        if (!response.ok) {
          throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`)
        }

        const responseText = await response.text()
        console.log(`📄 Respuesta cruda:`, responseText)

        let result
        try {
          result = JSON.parse(responseText)
        } catch (parseError) {
          console.error("❌ Error al parsear JSON:", parseError)
          console.log("📄 Respuesta no JSON:", responseText)
          throw new Error("Respuesta del servidor no válida")
        }

        console.log(`✅ Respuesta parseada:`, result)

        if (result.success) {
          this.mostrarMensaje(result.message, "success")
          this.cerrarModal(form) // Esto ahora debería funcionar correctamente

          if (tipo === "agregar") {
            form.reset()
            this.agregarFilaProducto(result.product)
          } else if (tipo === "editar") {
            this.actualizarFilaProducto(result.product)
          } else if (tipo === "eliminar") {
            this.eliminarFilaProducto(result.id)
          }
        } else {
          throw new Error(result.message || "Error desconocido")
        }
      } catch (error) {
        console.error(`❌ Error en ${tipo}:`, error)
        this.mostrarMensaje(`Error al ${tipo} el producto: ${error.message}`, "danger")
      } finally {
        // Restaurar botón
        submitBtn.disabled = false
        submitBtn.innerHTML = originalBtnText
      }
    }

    cerrarModal(form) {
      const modal = window.bootstrap.Modal.getInstance(form.closest(".modal"))
      if (modal) {
        modal.hide()
      }
    }

    // Helper to generate a table row HTML
    generateProductRowHtml(product) {
      const stockClass = product.stock_prod === "0" || product.stock_prod === 0 ? "fila-stock-cero" : ""
      const imageUrl = product.imagen_prod
        ? `./img/${product.imagen_prod}?v=${Date.now()}`
        : "/placeholder.svg?height=50&width=50"
      const imageHtml = product.imagen_prod
        ? `<img src="${imageUrl}" alt="Imagen" style="max-width: 50px;">`
        : "Sin imagen"
      const categoryName = product.nombre_categ || "Sin categoría" // Use nombre_categ from PHP response

      return `
        <tr data-producto-id="${product.id_prod}" class="${stockClass}">
          <td>${product.codigo_prod}</td>
          <td>${product.nombre_prod}</td>
          <td>${product.descripcion_prod}</td>
          <td>${categoryName}</td>
          <td>${product.materia_prod}</td>
          <td>${product.peso_prod} kg</td>
          <td>${product.stock_prod}</td>
          <td>${product.ubicacion_prod}</td>
          <td>${imageHtml}</td>
          <td>
            <button class="btn btn-sm btn-info btn-ver" data-bs-toggle="modal"
                data-bs-target="#modalVerProducto" 
                data-id="${product.id_prod}"
                data-codigo="${product.codigo_prod}"
                data-nombre="${product.nombre_prod}"
                data-descripcion="${product.descripcion_prod}"
                data-materia="${product.materia_prod}"
                data-peso="${product.peso_prod}"
                data-stock="${product.stock_prod}"
                data-ubicacion="${product.ubicacion_prod}"
                data-imagen="${product.imagen_prod}"
                data-categoria="${categoryName}"
                data-categoria-id="${product.categoria_id}">
                <i class="bi bi-eye"></i>
            </button>

            <button class="btn btn-sm btn-warning btn-editar" data-bs-toggle="modal"
                data-bs-target="#modalEditarProducto" 
                data-id="${product.id_prod}"
                data-codigo="${product.codigo_prod}"
                data-nombre="${product.nombre_prod}"
                data-descripcion="${product.descripcion_prod}"
                data-materia="${product.materia_prod}"
                data-peso="${product.peso_prod}"
                data-stock="${product.stock_prod}"
                data-ubicacion="${product.ubicacion_prod}"
                data-imagen="${product.imagen_prod}"
                data-categoria-id="${product.categoria_id}"
                data-categoria="${categoryName}">
                <i class="bi bi-pencil-square"></i>
            </button>

            <button class="btn btn-sm btn-danger btn-eliminar" data-bs-toggle="modal"
                data-bs-target="#modalEliminarProducto" 
                data-id="${product.id_prod}"
                data-nombre="${product.nombre_prod}">
                <i class="bi bi-trash"></i>
            </button>
          </td>
        </tr>
      `
    }

    agregarFilaProducto(newProduct) {
      const tableBody = document.querySelector("table tbody")
      if (!tableBody) {
        console.error("❌ No se encontró el tbody de la tabla de productos.")
        return
      }

      // Remove "No hay productos registrados" row if it exists
      const noDataRow = tableBody.querySelector("tr td[colspan='11']")
      if (noDataRow) {
        noDataRow.closest("tr").remove()
      }

      const newRowHtml = this.generateProductRowHtml(newProduct)
      // Prepend the new row to the beginning of the table
      tableBody.insertAdjacentHTML("afterbegin", newRowHtml)
      console.log("✅ Fila de producto agregada dinámicamente.")
    }

    actualizarFilaProducto(updatedProduct) {
      const existingRow = document.querySelector(`tr[data-producto-id="${updatedProduct.id_prod}"]`)
      if (existingRow) {
        const updatedRowHtml = this.generateProductRowHtml(updatedProduct)
        existingRow.outerHTML = updatedRowHtml // Replace the entire row
        console.log("✅ Fila de producto actualizada dinámicamente.")
      } else {
        console.warn(`⚠️ No se encontró la fila para el producto ID ${updatedProduct.id_prod}. Recargando página.`)
        // Fallback if row not found (e.g., if it was filtered out)
        window.location.reload()
      }
    }

    eliminarFilaProducto(productId) {
      const rowToDelete = document.querySelector(`tr[data-producto-id="${productId}"]`)
      if (rowToDelete) {
        rowToDelete.remove()
        console.log(`✅ Fila de producto ID ${productId} eliminada dinámicamente.`)

        // Check if table is now empty
        const tableBody = document.querySelector("table tbody")
        if (tableBody && !tableBody.querySelector("tr")) {
          tableBody.innerHTML = `<tr><td colspan='11' class='text-center'>No hay productos registrados</td></tr>`
        }
      } else {
        console.warn(`⚠️ No se encontró la fila para el producto ID ${productId}. Recargando página.`)
        // Fallback if row not found
        window.location.reload()
      }
    }

    // The `actualizarTabla` function is no longer needed for add/edit/delete
    // as we are doing direct DOM manipulation.
    /*
    async actualizarTabla() {
      // ... (original actualizarTabla logic)
    }
    */

    mostrarMensaje(mensaje, tipo = "info") {
      // Remover alertas anteriores
      document.querySelectorAll(".alert-flotante").forEach((el) => el.remove())

      const alert = document.createElement("div")
      alert.className = `alert alert-${tipo} alert-dismissible fade show position-fixed alert-flotante`
      alert.style.cssText = `
        top: 20px; 
        right: 20px; 
        z-index: 9999; 
        min-width: 350px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border: none;
      `

      const iconos = {
        success: '<i class="bi bi-check-circle-fill me-2"></i>',
        danger: '<i class="bi bi-exclamation-triangle-fill me-2"></i>',
        warning: '<i class="bi bi-exclamation-circle-fill me-2"></i>',
        info: '<i class="bi bi-info-circle-fill me-2"></i>',
      }

      alert.innerHTML = `
        <div class="d-flex align-items-center">
          ${iconos[tipo] || iconos.info}
          <div class="flex-grow-1">${mensaje}</div>
          <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"></button>
        </div>
      `

      document.body.appendChild(alert)

      // Auto-remover
      setTimeout(() => {
        if (alert.parentNode) {
          alert.classList.remove("show")
          setTimeout(() => alert.remove(), 150)
        }
      }, 5000)
    }

    // Métodos auxiliares
    setElementText(id, value) {
      const element = document.getElementById(id)
      if (element) element.textContent = value || "-"
    }

    setElementValue(id, value) {
      const element = document.getElementById(id)
      if (element) element.value = value || ""
    }

    setupConfirmacionEliminar() {
      const confirmInput = document.getElementById("confirmacionEliminar")
      const btnEliminar = document.getElementById("btnEliminarProducto")
      const modalEliminar = document.getElementById("modalEliminarProducto")

      if (!confirmInput || !btnEliminar) {
        console.warn("⚠️ No se encontraron elementos de confirmación de eliminación")
        return
      }

      // Validate input on every keystroke
      confirmInput.addEventListener("input", function () {
        const value = this.value.trim().toLowerCase()
        console.log("[v0] Input value:", value)

        if (value === "si") {
          btnEliminar.disabled = false
          btnEliminar.classList.remove("btn-secondary")
          btnEliminar.classList.add("btn-danger")
          console.log("[v0] Button enabled")
        } else {
          btnEliminar.disabled = true
          btnEliminar.classList.remove("btn-danger")
          btnEliminar.classList.add("btn-secondary")
          console.log("[v0] Button disabled")
        }
      })

      // Reset when modal is closed
      if (modalEliminar) {
        modalEliminar.addEventListener("hidden.bs.modal", () => {
          confirmInput.value = ""
          btnEliminar.disabled = true
          btnEliminar.classList.remove("btn-danger")
          btnEliminar.classList.add("btn-secondary")
          console.log("[v0] Modal closed, input reset")
        })
      }

      console.log("✅ Confirmación de eliminación configurada")
    }
  }

  // Inicializar cuando el DOM esté listo
  function inicializarProductos() {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", () => {
        window.productosManager = new ProductosManager()
      })
    } else {
      window.productosManager = new ProductosManager()
    }
  }

  inicializarProductos()
}
