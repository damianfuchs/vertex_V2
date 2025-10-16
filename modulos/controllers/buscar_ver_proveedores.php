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
    $sql = "SELECT * FROM proveedores WHERE id_proveedores = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $proveedor = $result->fetch_assoc();
    
    if (!$proveedor) {
        echo '<div class="alert alert-danger">Proveedor no encontrado.</div>';
        exit;
    }
    
    echo '<div class="row">';
    echo '<div class="col-md-6">';
    echo '<h5>Información del Proveedor</h5>';
    echo '<table class="table" style="border: 1px solid black;">';
    echo '<tr><td><strong>ID:</strong></td><td>' . htmlspecialchars($proveedor['id_proveedores']) . '</td></tr>';
    echo '<tr><td><strong>Nombre:</strong></td><td>' . htmlspecialchars($proveedor['nombre_proveedores']) . '</td></tr>';
    echo '<tr><td><strong>Contacto:</strong></td><td>' . htmlspecialchars($proveedor['nombre_contacto_proveedores']) . '</td></tr>';
    echo '<tr><td><strong>Teléfono:</strong></td><td>' . htmlspecialchars($proveedor['telefono_proveedores']) . '</td></tr>';
    echo '<tr><td><strong>Email:</strong></td><td>' . htmlspecialchars($proveedor['email_proveedores']) . '</td></tr>';
    echo '<tr><td><strong>Dirección:</strong></td><td>' . htmlspecialchars($proveedor['direccion_proveedores']) . '</td></tr>';
    echo '<tr><td><strong>Ubicación:</strong></td><td>' . htmlspecialchars($proveedor['ubicacion_proveedores']) . '</td></tr>';
    echo '<tr><td><strong>Código Postal:</strong></td><td>' . htmlspecialchars($proveedor['codigo_postal_proveedores']) . '</td></tr>';
    echo '</table>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<h5>Detalles Adicionales</h5>';
    echo '<table class="table" style="border: 1px solid black;">';
    echo '<tr><td><strong>Sitio Web:</strong></td><td>' . htmlspecialchars($proveedor['sitio_web_proveedores']) . '</td></tr>';
    echo '<tr><td><strong>Horario Atención:</strong></td><td>' . htmlspecialchars($proveedor['horario_atencion_proveedores']) . '</td></tr>';
    echo '<tr><td><strong>Estado:</strong></td><td>' . htmlspecialchars($proveedor['estado_proveedores']) . '</td></tr>';
    echo '<tr><td><strong>Fecha Registro:</strong></td><td>' . htmlspecialchars($proveedor['fecha_creacion_proveedores']) . '</td></tr>';
    echo '</table>';
    echo '<h5>Observaciones</h5>';
    echo '<p>' . nl2br(htmlspecialchars($proveedor['observacion_proveedores'])) . '</p>';
    echo '</div>';
    echo '</div>';
    
} catch(Exception $e) {
    echo '<div class="alert alert-danger">Error al cargar el proveedor: ' . $e->getMessage() . '</div>';
}
?>
