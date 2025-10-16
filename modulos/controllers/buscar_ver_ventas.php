<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include('../db/conexion.php');
include('../config.php');

$id = $_GET['id'] ?? '';

if (empty($id)) {
    echo '<div class="alert alert-danger">ID no proporcionado.</div>';
    exit;
}

try {
    // Assuming 'ventas' table has 'cliente_id' and 'clientes' table has 'id_clientes' and 'nombre_clientes'
    $sql = "SELECT v.*, c.nombre_clientes AS nombre_cliente 
            FROM ventas v 
            LEFT JOIN clientes c ON v.cliente_id = c.id_clientes 
            WHERE v.id_venta = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $venta = $result->fetch_assoc();
    
    if (!$venta) {
        echo '<div class="alert alert-danger">Venta no encontrada.</div>';
        exit;
    }
    
    echo '<div class="row">';
    echo '<div class="col-md-6">';
    echo '<h5>Información de la Venta</h5>';
    echo '<table class="table table-borderless" style="border: 1px solid black;">';
    echo '<tr><td><strong>ID:</strong></td><td>' . htmlspecialchars($venta['id_venta']) . '</td></tr>';
    echo '<tr><td><strong>Fecha:</strong></td><td>' . htmlspecialchars($venta['fecha_venta']) . '</td></tr>';
    echo '<tr><td><strong>Total:</strong></td><td>' . htmlspecialchars($venta['total_venta']) . '</td></tr>';
    echo '<tr><td><strong>Cliente:</strong></td><td>' . htmlspecialchars($venta['nombre_cliente'] ?? 'N/A') . '</td></tr>';
    echo '<tr><td><strong>Estado:</strong></td><td>' . htmlspecialchars($venta['estado_venta']) . '</td></tr>';
    echo '</table>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<h5>Observaciones</h5>';
    echo '<p>' . nl2br(htmlspecialchars($venta['observaciones_venta'] ?? '')) . '</p>';
    echo '</div>';
    echo '</div>';
    
} catch(Exception $e) {
    echo '<div class="alert alert-danger">Error al cargar la venta: ' . $e->getMessage() . '</div>';
}
?>
