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
    $sql = "SELECT * FROM ventas WHERE id_venta = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $venta = $result->fetch_assoc();
    
    if (!$venta) {
        echo '<div class="alert alert-danger">Venta no encontrada.</div>';
        exit;
    }

    // Fetch clients for dropdown
    $sqlClientes = "SELECT id_clientes, nombre_clientes FROM clientes ORDER BY nombre_clientes";
    $stmtClientes = $conexion->prepare($sqlClientes);
    $stmtClientes->execute();
    $resultClientes = $stmtClientes->get_result();
    
    echo '<div class="row">';
    echo '<input type="hidden" id="editId" name="id" value="' . htmlspecialchars($venta['id_venta']) . '">';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editFecha" class="form-label">Fecha</label>';
    echo '<input type="date" class="form-control" id="editFecha" name="fecha_venta" value="' . htmlspecialchars($venta['fecha_venta']) . '">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editTotal" class="form-label">Total</label>';
    echo '<input type="number" step="0.01" class="form-control" id="editTotal" name="total_venta" value="' . htmlspecialchars($venta['total_venta']) . '">';
    echo '</div>';
    echo '</div>';

    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editCliente" class="form-label">Cliente</label>';
    echo '<select class="form-control" id="editCliente" name="cliente_id">';
    echo '<option value="">Seleccionar cliente</option>';
    while ($cliente = $resultClientes->fetch_assoc()) {
        $selected = ($cliente['id_clientes'] == $venta['cliente_id']) ? 'selected' : '';
        echo '<option value="' . $cliente['id_clientes'] . '" ' . $selected . '>' . htmlspecialchars($cliente['nombre_clientes']) . '</option>';
    }
    echo '</select>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editEstado" class="form-label">Estado</label>';
    echo '<input type="text" class="form-control" id="editEstado" name="estado_venta" value="' . htmlspecialchars($venta['estado_venta']) . '">';
    echo '</div>';
    echo '</div>';

    echo '<div class="col-12">';
    echo '<div class="mb-3">';
    echo '<label for="editObservaciones" class="form-label">Observaciones</label>';
    echo '<textarea class="form-control" id="editObservaciones" name="observaciones_venta" rows="4">' . htmlspecialchars($venta['observaciones_venta'] ?? '') . '</textarea>';
    echo '</div>';
    echo '</div>';
    
    echo '</div>';
    
} catch(Exception $e) {
    echo '<div class="alert alert-danger">Error al cargar el formulario: ' . $e->getMessage() . '</div>';
}
?>
