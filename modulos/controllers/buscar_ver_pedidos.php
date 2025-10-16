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
    $sql = "SELECT * FROM pedidos WHERE id_pedido = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pedido = $result->fetch_assoc();
    
    if (!$pedido) {
        echo '<div class="alert alert-danger">Pedido no encontrado.</div>';
        exit;
    }
    
    echo '<div class="row">';
    echo '<div class="col-md-6">';
    echo '<h5>Información del Pedido</h5>';
    echo '<table class="table" style="border: 1px solid black;">';
    echo '<tr><td><strong>ID:</strong></td><td>' . htmlspecialchars($pedido['id_pedido']) . '</td></tr>';
    echo '<tr><td><strong>Cliente:</strong></td><td>' . htmlspecialchars($pedido['nombre_cliente_pedido']) . '</td></tr>';
    echo '<tr><td><strong>Fecha:</strong></td><td>' . htmlspecialchars($pedido['fecha_pedido']) . '</td></tr>';
    echo '<tr><td><strong>Precio:</strong></td><td>' . htmlspecialchars($pedido['precio_pedido']) . '</td></tr>';
    echo '<tr><td><strong>Estado:</strong></td><td>' . htmlspecialchars($pedido['estado_pedido']) . '</td></tr>';
    echo '</table>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<h5>Observaciones</h5>';
    echo '<p>' . nl2br(htmlspecialchars($pedido['observaciones_pedidos'])) . '</p>';
    echo '</div>';
    echo '</div>';
    
} catch(Exception $e) {
    echo '<div class="alert alert-danger">Error al cargar el pedido: ' . $e->getMessage() . '</div>';
}
?>
