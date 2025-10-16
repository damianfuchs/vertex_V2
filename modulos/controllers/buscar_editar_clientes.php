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
    echo '<input type="hidden" id="editId" name="id" value="' . htmlspecialchars($cliente['id_clientes']) . '">';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editNombre" class="form-label">Nombre *</label>';
    echo '<input type="text" class="form-control" id="editNombre" name="nombre_clientes" value="' . htmlspecialchars($cliente['nombre_clientes']) . '" required>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editDniCuit" class="form-label">DNI / CUIT</label>';
    echo '<input type="text" class="form-control" id="editDniCuit" name="dni_cuit_clientes" value="' . htmlspecialchars($cliente['dni_cuit_clientes']) . '">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editEmail" class="form-label">Email</label>';
    echo '<input type="text" class="form-control" id="editEmail" name="email_clientes" value="' . htmlspecialchars($cliente['email_clientes']) . '">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editTelefono" class="form-label">Teléfono</label>';
    echo '<input type="text" class="form-control" id="editTelefono" name="telefono_clientes" value="' . htmlspecialchars($cliente['telefono_clientes']) . '">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editDireccion" class="form-label">Dirección</label>';
    echo '<input type="text" class="form-control" id="editDireccion" name="direccion_clientes" value="' . htmlspecialchars($cliente['direccion_clientes']) . '">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editLocalidad" class="form-label">Localidad</label>';
    echo '<input type="text" class="form-control" id="editLocalidad" name="localidad_clientes" value="' . htmlspecialchars($cliente['localidad_clientes']) . '">';
    echo '</div>';
    echo '</div>';

    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editTipoCliente" class="form-label">Tipo de Cliente</label>';
    echo '<input type="text" class="form-control" id="editTipoCliente" name="tipo_cliente_clientes" value="' . htmlspecialchars($cliente['tipo_cliente_clientes']) . '">';
    echo '</div>';
    echo '</div>';

    echo '<div class="col-12">';
    echo '<div class="mb-3">';
    echo '<label for="editObservaciones" class="form-label">Observaciones</label>';
    echo '<textarea class="form-control" id="editObservaciones" name="observaciones_clientes" rows="4">' . htmlspecialchars($cliente['observaciones_clientes']) . '</textarea>';
    echo '</div>';
    echo '</div>';
    
    echo '</div>';
    
} catch(Exception $e) {
    echo '<div class="alert alert-danger">Error al cargar el formulario: ' . $e->getMessage() . '</div>';
}
?>
