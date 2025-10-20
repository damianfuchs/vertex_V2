;(() => {
  const btnBuscar = document.getElementById("btnBuscar")
  const resultadosDiv = document.getElementById("resultadosBusqueda")

  if (!btnBuscar || !resultadosDiv) {
    console.log("⚠️ Elementos de búsqueda no encontrados, abortando buscar.js")
    return
  }

  const bootstrap = window.bootstrap // Declare the bootstrap variable
  const modalVer = new bootstrap.Modal(document.getElementById("modalVer"))
  const modalEditar = new bootstrap.Modal(document.getElementById("modalEditar"))
  const modalEliminar = new bootstrap.Modal(document.getElementById("modalEliminar"))
  const modalVerBody = document.getElementById("modalVerBody")
  const modalEditarBody = document.getElementById("modalEditarBody")
  const itemAEliminarNombre = document.getElementById("itemAEliminarNombre")
  const confirmarEliminarBtn = document.getElementById("confirmarEliminarBtn")
  const formEditar = document.getElementById("formEditar")

  let editarId = null
  let idAEliminar = null
  let moduloAEliminar = null

  btnBuscar.addEventListener("click", () => {
    const modulo = document.getElementById("selectModulo").value
    const termino = document.getElementById("inputBusqueda").value.trim()

    if (!termino) {
      resultadosDiv.innerHTML = '<div class="alert alert-warning">Por favor, escribí un término para buscar.</div>'
      return
    }

    resultadosDiv.innerHTML = '<div class="text-center">Buscando...</div>'

    fetch(
      `modulos/controllers/busqueda_global.php?modulo=${modulo}&termino=${encodeURIComponent(termino)}&_=${Date.now()}`,
    )
      .then((response) => {
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`)
        return response.text()
      })
      .then((html) => {
        resultadosDiv.innerHTML = html
      })
      .catch((error) => {
        resultadosDiv.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`
        console.error("Error en fetch:", error)
      })
  })

  resultadosDiv.addEventListener("click", (e) => {
    const btn = e.target.closest("button")
    if (!btn) return

    const id = btn.getAttribute("data-id")
    if (!id) return

    const modulo = document.getElementById("selectModulo").value

    if (btn.classList.contains("btn-ver")) {
      modalVerBody.innerHTML = "Cargando..."
      modalVer.show()

      fetch(`modulos/controllers/buscar_ver_${modulo}.php?id=${id}`)
        .then((res) => (res.ok ? res.text() : Promise.reject("Error al cargar detalle")))
        .then((html) => {
          modalVerBody.innerHTML = html
        })
        .catch((err) => {
          modalVerBody.innerHTML = `<div class="alert alert-danger">${err}</div>`
        })
    } else if (btn.classList.contains("btn-editar")) {
      editarId = id
      modalEditarBody.innerHTML = "Cargando formulario..."
      modalEditar.show()

      fetch(`modulos/controllers/buscar_editar_${modulo}.php?id=${id}`)
        .then((res) => (res.ok ? res.text() : Promise.reject("Error al cargar formulario")))
        .then((html) => {
          modalEditarBody.innerHTML = html
        })
        .catch((err) => {
          modalEditarBody.innerHTML = `<div class="alert alert-danger">${err}</div>`
        })
    } else if (btn.classList.contains("btn-eliminar")) {
      idAEliminar = id
      moduloAEliminar = modulo
      const nombreItem = btn.getAttribute("data-nombre") || "este registro"
      itemAEliminarNombre.textContent = nombreItem

      const inputConfirmacion = document.getElementById("confirmacionEliminarBusqueda")
      if (inputConfirmacion) {
        inputConfirmacion.value = ""
      }
      confirmarEliminarBtn.disabled = true

      modalEliminar.show()
    }
  })

  const inputConfirmacion = document.getElementById("confirmacionEliminarBusqueda")
  if (inputConfirmacion) {
    inputConfirmacion.addEventListener("input", (e) => {
      const valor = e.target.value.trim().toLowerCase()
      confirmarEliminarBtn.disabled = valor !== "si"
    })
  }

  formEditar.addEventListener("submit", (e) => {
    e.preventDefault()
    if (!editarId) return

    const modulo = document.getElementById("selectModulo").value
    const formData = new FormData(formEditar)
    formData.append("id", editarId)

    fetch(`modulos/controllers/buscar_actualizar_${modulo}.php`, {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          mostrarMensajeFlotante("success", "Registro actualizado correctamente.")
          modalEditar.hide()
          btnBuscar.click()
        } else {
          mostrarMensajeFlotante("danger", "Error al actualizar: " + (data.message || "Ocurrió un problema"))
        }
      })
      .catch(() => mostrarMensajeFlotante("danger", "Error en la actualización."))
  })

  confirmarEliminarBtn.addEventListener("click", () => {
    if (!idAEliminar || !moduloAEliminar) return

    fetch(`modulos/controllers/buscar_eliminar_${moduloAEliminar}.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `id=${encodeURIComponent(idAEliminar)}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          mostrarMensajeFlotante("success", data.message || "Eliminado correctamente")
          document.getElementById("btnBuscar").click()
        } else {
          mostrarMensajeFlotante("danger", data.message || "Error al eliminar")
        }
        modalEliminar.hide()
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error)
        mostrarMensajeFlotante("danger", "Error en la solicitud")
        modalEliminar.hide()
      })
  })

  function mostrarMensajeFlotante(tipo, mensaje) {
    const alert = document.createElement("div")
    alert.className = `alert alert-${tipo} position-fixed top-0 start-50 translate-middle-x mt-3`
    alert.style.zIndex = 9999
    alert.textContent = mensaje

    document.body.appendChild(alert)

    setTimeout(() => {
      alert.remove()
    }, 3000)
  }
})()
