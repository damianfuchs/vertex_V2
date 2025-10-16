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
    echo '<div class="col-md-12">';
    echo '<h5>Información de la Categoría</h5>';
    echo '<table class="table" style="border: 1px solid black;">';
    echo '<tr><td><strong>ID:</strong></td><td>' . htmlspecialchars($categoria['id_categ']) . '</td></tr>';
    echo '<tr><td><strong>Código:</strong></td><td>' . htmlspecialchars($categoria['codigo_categ']) . '</td></tr>';
    echo '<tr><td><strong>Nombre:</strong></td><td>' . htmlspecialchars($categoria['nombre_categ']) . '</td></tr>';
    echo '</table>';
    echo '</div>';
    
    echo '<div class="col-md-12">';
    echo '<h5>Descripción</h5>';
    echo '<p>' . nl2br(htmlspecialchars($categoria['descripcion_categ'])) . '</p>';
    echo '</div>';
    echo '</div>';
    
} catch(Exception $e) {
    echo '<div class="alert alert-danger">Error al cargar la categoría: ' . $e->getMessage() . '</div>';
}
?>
