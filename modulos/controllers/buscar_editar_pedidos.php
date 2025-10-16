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
    echo '<input type="hidden" id="editId" name="id" value="' . htmlspecialchars($pedido['id_pedido']) . '">';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editNombreCliente" class="form-label">Nombre Cliente</label>';
    echo '<input type="text" class="form-control" id="editNombreCliente" name="nombre_cliente_pedido" value="' . htmlspecialchars($pedido['nombre_cliente_pedido']) . '">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editFecha" class="form-label">Fecha</label>';
    echo '<input type="date" class="form-control" id="editFecha" name="fecha_pedido" value="' . htmlspecialchars($pedido['fecha_pedido']) . '">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editPrecio" class="form-label">Precio</label>';
    echo '<input type="number" step="0.01" class="form-control" id="editPrecio" name="precio_pedido" value="' . htmlspecialchars($pedido['precio_pedido']) . '">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editEstado" class="form-label">Estado</label>';
    echo '<select class="form-select" id="editEstado" name="estado_pedido">';
    $estados = ['Pendiente', 'Entregado'];
    foreach ($estados as $estado) {
        $selected = ($pedido['estado_pedido'] == $estado) ? 'selected' : '';
        echo '<option value="' . htmlspecialchars($estado) . '" ' . $selected . '>' . htmlspecialchars($estado) . '</option>';
    }
    echo '</select>';
    echo '</div>';
    echo '</div>';

    echo '<div class="col-12">';
    echo '<div class="mb-3">';
    echo '<label for="editObservaciones" class="form-label">Observaciones</label>';
    echo '<textarea class="form-control" id="editObservaciones" name="observaciones_pedidos" rows="4">' . htmlspecialchars($pedido['observaciones_pedidos']) . '</textarea>';
    echo '</div>';
    echo '</div>';
    
    echo '</div>';
    
} catch(Exception $e) {
    echo '<div class="alert alert-danger">Error al cargar el formulario: ' . $e->getMessage() . '</div>';
}
?>
