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
    echo '<input type="hidden" id="editId" name="id" value="' . htmlspecialchars($proveedor['id_proveedores']) . '">';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editNombre" class="form-label">Nombre *</label>';
    echo '<input type="text" class="form-control" id="editNombre" name="nombre_proveedores" value="' . htmlspecialchars($proveedor['nombre_proveedores']) . '" required>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editContacto" class="form-label">Contacto</label>';
    echo '<input type="text" class="form-control" id="editContacto" name="nombre_contacto_proveedores" value="' . htmlspecialchars($proveedor['nombre_contacto_proveedores']) . '">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editTelefono" class="form-label">Teléfono</label>';
    echo '<input type="text" class="form-control" id="editTelefono" name="telefono_proveedores" value="' . htmlspecialchars($proveedor['telefono_proveedores']) . '">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editEmail" class="form-label">Email</label>';
    echo '<input type="text" class="form-control" id="editEmail" name="email_proveedores" value="' . htmlspecialchars($proveedor['email_proveedores']) . '">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editDireccion" class="form-label">Dirección</label>';
    echo '<input type="text" class="form-control" id="editDireccion" name="direccion_proveedores" value="' . htmlspecialchars($proveedor['direccion_proveedores']) . '">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editUbicacion" class="form-label">Ubicación</label>';
    echo '<input type="text" class="form-control" id="editUbicacion" name="ubicacion_proveedores" value="' . htmlspecialchars($proveedor['ubicacion_proveedores']) . '">';
    echo '</div>';
    echo '</div>';

    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editCodigoPostal" class="form-label">Código Postal</label>';
    echo '<input type="text" class="form-control" id="editCodigoPostal" name="codigo_postal_proveedores" value="' . htmlspecialchars($proveedor['codigo_postal_proveedores']) . '">';
    echo '</div>';
    echo '</div>';

    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editSitioWeb" class="form-label">Sitio Web</label>';
    echo '<input type="text" class="form-control" id="editSitioWeb" name="sitio_web_proveedores" value="' . htmlspecialchars($proveedor['sitio_web_proveedores']) . '">';
    echo '</div>';
    echo '</div>';

    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editHorarioAtencion" class="form-label">Horario de Atención</label>';
    echo '<input type="text" class="form-control" id="editHorarioAtencion" name="horario_atencion_proveedores" value="' . htmlspecialchars($proveedor['horario_atencion_proveedores']) . '">';
    echo '</div>';
    echo '</div>';

    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editEstado" class="form-label">Estado</label>';
    echo '<select class="form-select" id="editEstado" name="estado_proveedores">';
    echo '<option value="1"' . ($proveedor['estado_proveedores'] == '1' ? ' selected' : '') . '>Activo</option>';
    echo '<option value="0"' . ($proveedor['estado_proveedores'] == '0' ? ' selected' : '') . '>Inactivo</option>';
    echo '</select>';
    echo '</div>';
    echo '</div>';


    echo '<div class="col-12">';
    echo '<div class="mb-3">';
    echo '<label for="editObservacion" class="form-label">Observación</label>';
    echo '<textarea class="form-control" id="editObservacion" name="observacion_proveedores" rows="4">' . htmlspecialchars($proveedor['observacion_proveedores']) . '</textarea>';
    echo '</div>';
    echo '</div>';
    
    echo '</div>';
    
} catch(Exception $e) {
    echo '<div class="alert alert-danger">Error al cargar el formulario: ' . $e->getMessage() . '</div>';
}
?>
