;(() => {
  console.log("🚀 Sistema de navegación iniciado")

  // Variables locales para evitar conflictos
  const mainElement = document.getElementById("main")
  const sidebarElement = document.getElementById("sidebar")
  const menuElement = document.getElementById("menu")

  // Verificar elementos esenciales
  if (!mainElement) {
    console.error("❌ Elemento 'main' no encontrado")
    return
  } else {
    console.log("✅ Elemento 'main' encontrado")
  }

  // Función para cargar páginas
  function cargarPagina(page) {
    console.log(`📄 Intentando cargar página: ${page}`)

    if (!mainElement) {
      console.error("❌ No se puede cargar página - elemento main no existe")
      return
    }

    // Mostrar loading
    mainElement.innerHTML = `
      <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
        <span class="ms-3">Cargando ${page}...</span>
      </div>
    `

    // Cargar contenido
    fetch(`modulos/${page}.php`)
      .then((response) => {
        console.log(`📡 Respuesta para ${page}:`, response.status, response.statusText)
        if (!response.ok) {
          throw new Error(`Error ${response.status}: ${response.statusText}`)
        }
        return response.text()
      })
      .then((html) => {
        console.log(`📄 HTML recibido para ${page}:`, html.substring(0, 100) + "...")
        mainElement.innerHTML = html

        // Cargar script específico si existe
        cargarScriptEspecifico(page)

        console.log(`✅ Página ${page} cargada correctamente`)
      })
      .catch((error) => {
        console.error(`❌ Error cargando ${page}:`, error)
        mainElement.innerHTML = `
          <div class="alert alert-danger m-4" role="alert">
            <h4 class="alert-heading">Error al cargar</h4>
            <p>No se pudo cargar la sección: <strong>${page}</strong></p>
            <hr>
            <p class="mb-0">Error: ${error.message}</p>
            <p class="mt-2">
              <small>Verifica que el archivo <code>modulos/${page}.php</code> existe.</small>
            </p>
            <button class="btn btn-outline-danger mt-2" onclick="location.reload()">
              Recargar página
            </button>
          </div>
        `
      })
  }

  // Función para cargar scripts específicos
  function cargarScriptEspecifico(page) {
    // Remover script anterior
    const scriptAnterior = document.querySelector("script[data-modulo]")
    if (scriptAnterior) {
      scriptAnterior.remove()
      console.log("🧹 Script anterior removido")
    }

    // Lista de páginas que tienen scripts específicos
    const paginasConScript = ["proveedores", "categorias", "productos", "clientes"]

    if (paginasConScript.includes(page)) {
      console.log(`📜 Cargando script específico para ${page}`)
      const script = document.createElement("script")
      script.src = `modulos/js/${page}.js?v=${Date.now()}`
      script.setAttribute("data-modulo", page)

      script.onload = () => {
        console.log(`✅ Script ${page}.js cargado`)
      }

      script.onerror = () => {
        console.log(`⚠️ No se pudo cargar ${page}.js`)
      }

      document.head.appendChild(script)
    } else {
      console.log(`⚠️ No hay script específico para ${page}`)
    }
  }

  // Inicializar navegación
  function inicializarNavegacion() {
    const sidebarLinks = document.querySelectorAll(".sidebar a")
    console.log(`🔍 Enlaces encontrados en sidebar: ${sidebarLinks.length}`)

    if (sidebarLinks.length === 0) {
      console.error("❌ No se encontraron enlaces del sidebar")
      return
    }

    // Configurar clicks en sidebar
    sidebarLinks.forEach((link, index) => {
      const page = link.getAttribute("data-page")
      console.log(`🔗 Configurando enlace ${index + 1}: ${page}`)

      link.addEventListener("click", function (e) {
        e.preventDefault()
        console.log(`🖱️ Click en enlace: ${page}`)

        // Actualizar selección visual
        sidebarLinks.forEach((item) => item.classList.remove("selected"))
        this.classList.add("selected")

        // Cargar página
        cargarPagina(page)

        // Cerrar sidebar en móvil
        if (sidebarElement && menuElement && mainElement) {
          sidebarElement.classList.remove("menu-toggle")
          menuElement.classList.remove("menu-toggle")
          mainElement.classList.remove("menu-toggle")
        }
      })
    })

    console.log(`✅ ${sidebarLinks.length} enlaces del sidebar configurados`)
  }

  // Configurar clicks en tarjetas del dashboard
  function configurarTarjetas() {
    if (!mainElement) {
      console.error("❌ No se puede configurar tarjetas - elemento main no existe")
      return
    }

    mainElement.addEventListener("click", (e) => {
      const card = e.target.closest(".card-inventario")
      if (card && card.dataset.page) {
        const page = card.dataset.page
        console.log(`🎴 Click en tarjeta: ${page}`)

        // Actualizar sidebar
        const sidebarLinks = document.querySelectorAll(".sidebar a")
        sidebarLinks.forEach((link) => {
          if (link.getAttribute("data-page") === page) {
            link.classList.add("selected")
          } else {
            link.classList.remove("selected")
          }
        })

        // Cargar página
        cargarPagina(page)
      }
    })

    console.log("✅ Configuración de tarjetas completada")
  }

  // Cargar página inicial
  function cargarPaginaInicial() {
    console.log("🎯 Iniciando carga de página inicial...")

    // Marcar inicio como seleccionado
    const sidebarLinks = document.querySelectorAll(".sidebar a")
    sidebarLinks.forEach((link) => {
      if (link.getAttribute("data-page") === "inicio") {
        link.classList.add("selected")
      } else {
        link.classList.remove("selected")
      }
    })

    // Cargar página inicio
    cargarPagina("inicio")
  }

  // Inicializar cuando el DOM esté listo
  document.addEventListener("DOMContentLoaded", () => {
    console.log("🎯 DOM cargado, inicializando navegación...")

    // Verificar elementos esenciales
    if (!mainElement) {
      console.error("❌ Elemento 'main' no encontrado - navegación no funcionará")
      return
    }

    if (!sidebarElement) {
      console.error("❌ Elemento 'sidebar' no encontrado")
    }

    if (!menuElement) {
      console.error("❌ Elemento 'menu' no encontrado")
    }

    // Inicializar componentes
    inicializarNavegacion()
    configurarTarjetas()

    // Cargar página inicial después de un pequeño delay
    setTimeout(() => {
      cargarPaginaInicial()
    }, 500)

    console.log("🎉 Sistema de navegación inicializado")
  })

  console.log("🌐 Sistema de navegación configurado")
})()
