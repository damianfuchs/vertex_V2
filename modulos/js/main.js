console.log("🚀 Sistema principal iniciado")

// Usar IIFE para evitar conflictos de variables globales
;(() => {
  // Variables locales
  const mainElement = document.getElementById("main")
  const sidebarElement = document.getElementById("sidebar")
  const menuElement = document.getElementById("menu")

  // Verificar elementos esenciales
  if (!mainElement) {
    console.error("❌ Elemento 'main' no encontrado")
    return
  }

  console.log("✅ Elementos encontrados:", {
    main: !!mainElement,
    sidebar: !!sidebarElement,
    menu: !!menuElement,
  })

  // === MANEJO DEL MENU RESPONSIVE ===
  if (menuElement && sidebarElement && mainElement) {
    // Click en el menú hamburguesa
    menuElement.addEventListener("click", (e) => {
      e.preventDefault()
      e.stopPropagation()

      console.log("🍔 Click en menú hamburguesa")

      // Toggle de las clases
      sidebarElement.classList.toggle("menu-toggle")
      menuElement.classList.toggle("menu-toggle")
      mainElement.classList.toggle("menu-toggle")

      // Log del estado actual
      const isOpen = sidebarElement.classList.contains("menu-toggle")
      console.log(`📱 Sidebar ${isOpen ? "abierto" : "cerrado"}`)
    })

    // Click fuera del sidebar para cerrarlo
    document.addEventListener("click", (e) => {
      // Solo cerrar si el sidebar está abierto
      if (!sidebarElement.classList.contains("menu-toggle")) {
        return
      }

      const isClickInsideSidebar = sidebarElement.contains(e.target)
      const isClickOnMenu = menuElement.contains(e.target)

      // Si el click no fue en el sidebar ni en el menú, cerrar
      if (!isClickInsideSidebar && !isClickOnMenu) {
        console.log("🖱️ Click fuera del sidebar - cerrando")
        sidebarElement.classList.remove("menu-toggle")
        menuElement.classList.remove("menu-toggle")
        mainElement.classList.remove("menu-toggle")
      }
    })

    // Cerrar sidebar al hacer click en un enlace (solo en móvil)
    sidebarElement.addEventListener("click", (e) => {
      const link = e.target.closest("a")
      if (link && window.innerWidth <= 768) {
        console.log("📱 Click en enlace - cerrando sidebar en móvil")
        setTimeout(() => {
          sidebarElement.classList.remove("menu-toggle")
          menuElement.classList.remove("menu-toggle")
          mainElement.classList.remove("menu-toggle")
        }, 100)
      }
    })

    console.log("✅ Menu responsive configurado")
  }

  // === NAVEGACIÓN ===
  function cargarPagina(page) {
    console.log(`📄 Cargando: ${page}`)

    // Mostrar loading
    mainElement.innerHTML = `
      <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
        <span class="ms-3">Cargando ${page}...</span>
      </div>
    `

    // Añadir parámetro de cache-busting a la URL del archivo PHP
    fetch(`modulos/${page}.php?v=${Date.now()}`)
      .then((response) => {
        console.log(`📡 Respuesta ${page}:`, response.status)
        // Log del Content-Type de la respuesta
        console.log(`📡 Content-Type de ${page}:`, response.headers.get('Content-Type'));
        if (!response.ok) {
          throw new Error(`Error ${response.status}`)
        }
        return response.text()
      })
      .then((html) => {
        mainElement.innerHTML = html
        cargarScriptEspecifico(page)
        console.log(`✅ ${page} cargado`)
      })
      .catch((error) => {
        console.error(`❌ Error cargando ${page}:`, error)
        mainElement.innerHTML = `
          <div class="alert alert-danger m-4">
            <h4>Error al cargar ${page}</h4>
            <p>${error.message}</p>
            <button class="btn btn-outline-danger" onclick="location.reload()">
              Recargar
            </button>
          </div>
        `
      })
  }

  // Exponer cargarPagina globalmente para que otros módulos puedan usarla
  window.app = window.app || {};
  window.app.cargarPagina = cargarPagina;

  // Función para cargar scripts específicos
  function cargarScriptEspecifico(page) {
    // Remover script anterior si existe
    const scriptAnterior = document.querySelector("script[data-modulo]")
    if (scriptAnterior) {
      scriptAnterior.remove()
    }

    // Lista de páginas que tienen scripts específicos
    const paginasConScript = ["proveedores", "categorias", "productos", "clientes", "pedidos", "ventas", "buscar"]


    if (paginasConScript.includes(page)) {
      const script = document.createElement("script")
      script.src = `modulos/js/${page}.js?v=${Date.now()}`
      script.setAttribute("data-modulo", page)
      script.onload = () => console.log(`✅ Script ${page}.js cargado`)
      script.onerror = () => console.log(`⚠️ No se pudo cargar ${page}.js`)
      document.head.appendChild(script)
    }
  }

  // === INICIALIZACIÓN ===
  function inicializar() {
    console.log("🎯 Inicializando sistema...")

    // Cargar página inicial
    cargarPagina("inicio")

    // Configurar enlaces del sidebar
    const sidebarLinks = document.querySelectorAll(".sidebar a")
    console.log(`🔗 Enlaces encontrados: ${sidebarLinks.length}`)

    sidebarLinks.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault()

        // Actualizar selección visual
        sidebarLinks.forEach((item) => item.classList.remove("selected"))
        this.classList.add("selected")

        // Cargar página
        const page = this.getAttribute("data-page")
        cargarPagina(page)
      })
    })

    // Configurar clicks en tarjetas del dashboard
    mainElement.addEventListener("click", (e) => {
      const card = e.target.closest(".card-inventario")
      if (card && card.dataset.page) {
        const page = card.dataset.page
        console.log(`🎴 Click en tarjeta: ${page}`)

        // Actualizar sidebar
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

    // Manejar cambios de tamaño de ventana
    window.addEventListener("resize", () => {
      // Si cambiamos a desktop, asegurar que el sidebar esté visible
      if (window.innerWidth > 768) {
        sidebarElement.classList.remove("menu-toggle")
        menuElement.classList.remove("menu-toggle")
        mainElement.classList.remove("menu-toggle")
      }
    })

    console.log("✅ Sistema inicializado")
  }

  // Inicializar cuando el DOM esté listo
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", inicializar)
  } else {
    inicializar()
  }
})()
