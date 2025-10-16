<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vertex Inventario</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="shortcut icon" href="./img/logovertex.jpg"/>
</head>
<body>
    <header>
        <div class="left">
            <div class="menu-container">
                <div class="menu" id="menu">
                    <div class=""></div>
                    <div class=""></div>
                    <div class=""></div>
                </div>
            </div>
            <div class="brand">
                <img src="img/logo.jpg" alt="" class="logo" style="width: 70px">
                
            </div>
        </div>
        
    </header>
    <div class="sidebar" id="sidebar">
        <nav>
            <ul>
                <li>
                    <a href="#" data-page="buscar" class="search">
                        <img src="iconos/search.svg" alt="">
                        <span>Buscar</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-page="inicio" class="selected">
                        <img src="iconos/home2.svg" alt="">
                        <span>Pagina de Inicio</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-page="productos">
                        <img src="iconos/grid.svg" alt="">
                        <span>Inventario</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-page="categorias">
                        <img src="iconos/category.svg" alt="">
                        <span>Categorias</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-page="proveedores">
                        <img src="iconos/truck.svg" alt="">
                        <span>Proveedores</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-page="clientes">
                        <img src="iconos/clientes.svg" alt="">
                        <span>Clientes</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-page="ventas">
                        <img src="iconos/tools2.svg" alt="">
                        <span>Herramientas</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-page="pedidos">
                        <img src="iconos/pedidos.svg" alt="">
                        <span>Pedidos</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <main id="main">
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
    
    <!-- Solo cargar main.js, que ahora maneja la navegación y el menú -->
    <script src="modulos/js/main.js?v=<?= time() ?>"></script>

</body>
</html>
