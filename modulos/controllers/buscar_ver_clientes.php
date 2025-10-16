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
    $sql = "SELECT * FROM clientes WHERE id_clientes = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
    
    if (!$cliente) {
        echo '<div class="alert alert-danger">Cliente no encontrado.</div>';
        exit;
    }
    
    echo '<div class="row">';
    echo '<div class="col-md-6">';
    echo '<h5>Información del Cliente</h5>';
    echo '<table class="table" style="border: 1px solid black;">';
    echo '<tr><td><strong>ID:</strong></td><td>' . htmlspecialchars($cliente['id_clientes']) . '</td></tr>';
    echo '<tr><td><strong>Nombre:</strong></td><td>' . htmlspecialchars($cliente['nombre_clientes']) . '</td></tr>';
    echo '<tr><td><strong>DNI / CUIT:</strong></td><td>' . htmlspecialchars($cliente['dni_cuit_clientes']) . '</td></tr>';
    echo '<tr><td><strong>Email:</strong></td><td>' . htmlspecialchars($cliente['email_clientes']) . '</td></tr>';
    echo '<tr><td><strong>Teléfono:</strong></td><td>' . htmlspecialchars($cliente['telefono_clientes']) . '</td></tr>';
    echo '</table>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<h5>Detalles Adicionales</h5>';
    echo '<table class="table" style="border: 1px solid black;">';
    echo '<tr><td><strong>Dirección:</strong></td><td>' . htmlspecialchars($cliente['direccion_clientes']) . '</td></tr>';
    echo '<tr><td><strong>Localidad:</strong></td><td>' . htmlspecialchars($cliente['localidad_clientes']) . '</td></tr>';
    echo '<tr><td><strong>Tipo de Cliente:</strong></td><td>' . htmlspecialchars($cliente['tipo_cliente_clientes']) . '</td></tr>';
    echo '</table>';
    echo '<h5>Observaciones</h5>';
    echo '<p>' . nl2br(htmlspecialchars($cliente['observaciones_clientes'])) . '</p>';
    echo '</div>';
    echo '</div>';
    
} catch(Exception $e) {
    echo '<div class="alert alert-danger">Error al cargar el cliente: ' . $e->getMessage() . '</div>';
}
?>
