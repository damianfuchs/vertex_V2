console.log("🚀 Menu.js cargado")

// Manejo del sidebar/menu
const menu = document.getElementById("menu")
const sidebar = document.getElementById("sidebar")
const main = document.getElementById("main")

if (menu && sidebar && main) {
  menu.addEventListener("click", (e) => {
    e.stopPropagation()
    sidebar.classList.toggle("menu-toggle")
    menu.classList.toggle("menu-toggle")
    main.classList.toggle("menu-toggle")
  })

  document.addEventListener("click", (e) => {
    const isClickInsideSidebar = sidebar.contains(e.target)
    const isClickOnMenu = menu.contains(e.target)

    if (!isClickInsideSidebar && !isClickOnMenu) {
      sidebar.classList.remove("menu-toggle")
      menu.classList.remove("menu-toggle")
      main.classList.remove("menu-toggle")
    }
  })

  console.log("✅ Menu configurado correctamente")
} else {
  console.error("❌ No se encontraron elementos del menu")
}
