
<?php
include('./db/conexion.php'); // Ajustá el path si es necesario

// Total Productos
$sqlProductos = "SELECT COUNT(*) AS total_productos FROM productos";
$resProductos = mysqli_query($conexion, $sqlProductos);
$dataProductos = mysqli_fetch_assoc($resProductos);

// Clientes Activos (suponiendo que tenés un campo `activo = 1`)
$sqlClientes = "SELECT COUNT(*) AS total_clientes FROM clientes";
$resClientes = mysqli_query($conexion, $sqlClientes);
$dataClientes = mysqli_fetch_assoc($resClientes);

// Total Proveedores
$sqlProveedores = "SELECT COUNT(*) AS total_proveedores FROM proveedores";
$resProveedores = mysqli_query($conexion, $sqlProveedores);
$dataProveedores = mysqli_fetch_assoc($resProveedores);

//ACA VA LA CANTIDAD DE PEDIDOS-->
// Cantidad de pedidos pendientes
$sqlPedidosPendientes = "SELECT COUNT(*) AS total_pedidos_pendientes FROM pedidos WHERE estado_pedido = 'Pendiente'";
$resPedidosPendientes = mysqli_query($conexion, $sqlPedidosPendientes);
$dataPedidosPendientes = mysqli_fetch_assoc($resPedidosPendientes);



?>

<style>
    .card-inventario {
        background: linear-gradient(135deg, #1f2c4c 0%, #3b5680 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
        height: 120px;
    }

    .card-inventario:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .card-inventario i {
        font-size: 2.5rem;
        opacity: 0.9;
    }

    .card-inventario .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }

    .card-inventario small {
        opacity: 0.8;
    }

    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .card-inventario small {
        color: #dce1ecff !important;
        /* Elegí el color que quieras */
    }

    .modern-title {
        font-family: 'Nunito Sans', sans-serif;
        font-weight: 300;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #2c3e50;
        border-left: 4px solid #3498db;
        padding-left: 10px;
        transition: color 0.3s ease;
 
    }

    .modern-title i {
        font-size: 2.4rem;
        color: #3498db;
        transition: color 0.3s ease, transform 0.3s ease;
    }

    .modern-title:hover {
        color: #2980b9;
    }

    .modern-title:hover i {
        color: #2980b9;
        transform: scale(1.1) rotate(10deg);
    }
</style>


<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans&display=swap" rel="stylesheet">

<div class="container-fluid ">
    <div class="row">
        <div class="col-12">
            <h2 class="modern-title mb-4">
                <i class="bi bi-house-door"></i> Panel de Control
            </h2>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <div class="card-inventario" data-page="productos">
                <i class="bi bi-grid"></i>
                <div>
                    <p class="card-title">Inventario</p>
                    <small class="text-muted">Gestionar inventario</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card-inventario" data-page="categorias">
                <i class="bi bi-tags"></i>
                <div>
                    <p class="card-title">Categorías</p>
                    <small class="text-muted">Organizar productos</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card-inventario" data-page="proveedores">
                <i class="bi bi-truck"></i>
                <div>
                    <p class="card-title">Proveedores</p>
                    <small class="text-muted">Gestionar proveedores</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card-inventario" data-page="clientes">
                <i class="bi bi-person"></i>
                <div>
                    <p class="card-title">Clientes</p>
                    <small class="text-muted">Base de clientes</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card-inventario" data-page="ventas">    
                <i class="bi bi-tools"></i>
                <div>
                    <p class="card-title">Herramientas</p>
                    <small class="text-muted">Registro de Herramientas</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card-inventario" data-page="pedidos">
                <i class="bi bi-receipt"></i>
                <div>
                    <p class="card-title">Pedidos</p>
                    <small class="text-muted">Gestionar pedidos</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="modern-title mb-4">
                <i class="bi bi-graph-up"></i> Resumen Rápido
            </h4>

        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary">
                        <i class="bi bi-box"></i>
                    </h5>
                    <h3 class="text-primary"><?= $dataProductos['total_productos'] ?></h3>
                    <p class="card-text">Productos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success">
                        <i class="bi bi-people"></i>
                    </h5>
                    <h3 class="text-success"><?= $dataClientes['total_clientes'] ?></h3>
                    <p class="card-text">Clientes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning">
                        <i class="bi bi-truck"></i>
                    </h5>
                    <h3 class="text-warning"><?= $dataProveedores['total_proveedores'] ?></h3>
                    <p class="card-text">Proveedores</p>
                </div>
            </div>
        </div>
        <!-- ACA VA LA CANTIDAD DE PEDIDOS-->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-info">
                        <i class="bi bi-receipt"></i>
                    </h5>
                    <h3 class="text-info"><?= $dataPedidosPendientes['total_pedidos_pendientes'] ?></h3>
                    <p class="card-text">Pedidos Pendientes</p>
                </div>
            </div>
        </div>


    </div>
</div>
