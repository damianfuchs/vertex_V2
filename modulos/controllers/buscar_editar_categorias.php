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
    $sql = "SELECT * FROM categorias WHERE id_categ = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $categoria = $result->fetch_assoc();
    
    if (!$categoria) {
        echo '<div class="alert alert-danger">Categoría no encontrada.</div>';
        exit;
    }
    
    echo '<div class="row">';
    echo '<input type="hidden" id="editId" name="id" value="' . htmlspecialchars($categoria['id_categ']) . '">';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editCodigo" class="form-label">Código *</label>';
    echo '<input type="text" class="form-control" id="editCodigo" name="codigo_categ" value="' . htmlspecialchars($categoria['codigo_categ']) . '" required>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editNombre" class="form-label">Nombre *</label>';
    echo '<input type="text" class="form-control" id="editNombre" name="nombre_categ" value="' . htmlspecialchars($categoria['nombre_categ']) . '" required>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-12">';
    echo '<div class="mb-3">';
    echo '<label for="editDescripcion" class="form-label">Descripción</label>';
    echo '<textarea class="form-control" id="editDescripcion" name="descripcion_categ" rows="4">' . htmlspecialchars($categoria['descripcion_categ']) . '</textarea>';
    echo '</div>';
    echo '</div>';
    
    echo '</div>';
    
} catch(Exception $e) {
    echo '<div class="alert alert-danger">Error al cargar el formulario: ' . $e->getMessage() . '</div>';
}
?>
