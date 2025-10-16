<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include('../db/conexion.php');
include('../config.php'); // ¡¡¡NUEVA LÍNEA!!! Asegúrate de que la ruta sea correcta

$id = $_GET['id'] ?? '';

if (empty($id)) {
    echo '<div class="alert alert-danger">ID no proporcionado.</div>';
    exit;
}

try {
    $sql = "SELECT p.*, c.nombre_categ AS categoria_nombre 
            FROM productos p 
            LEFT JOIN categorias c ON p.categoria_id = c.id_categ 
            WHERE p.id_prod = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();
    
    if (!$producto) {
        echo '<div class="alert alert-danger">Producto no encontrado.</div>';
        exit;
    }
    
    echo '<div class="row">';
    echo '<div class="col-md-6">';
    echo '<h5>Información del Producto</h5>';
    echo '<table class="table" style="border: 1px solid rgba(0, 1, 2, 1);">';
    echo '<tr><td><strong>ID:</strong></td><td>' . htmlspecialchars($producto['id_prod']) . '</td></tr>';
    echo '<tr><td><strong>Código:</strong></td><td>' . htmlspecialchars($producto['codigo_prod']) . '</td></tr>';
    echo '<tr><td><strong>Nombre:</strong></td><td>' . htmlspecialchars($producto['nombre_prod']) . '</td></tr>';
    echo '<tr><td><strong>Materia:</strong></td><td>' . htmlspecialchars($producto['materia_prod']) . '</td></tr>';
    echo '<tr><td><strong>Stock:</strong></td><td>' . htmlspecialchars($producto['stock_prod']) . '</td></tr>';
    echo '<tr><td><strong>Ubicación:</strong></td><td>' . htmlspecialchars($producto['ubicacion_prod']) . '</td></tr>';
    echo '<tr><td><strong>Peso:</strong></td><td>' . htmlspecialchars($producto['peso_prod']) . '</td></tr>';
    echo '<tr><td><strong>Categoría:</strong></td><td>' . htmlspecialchars($producto['categoria_nombre'] ?? 'Sin categoría') . '</td></tr>';
    echo '</table>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    if (!empty($producto['imagen_prod'])) {
        // RUTA CORREGIDA: USANDO BASE_URL
        $imageSrc = BASE_URL . '/img/' . htmlspecialchars($producto['imagen_prod']);
        // Ruta absoluta en el servidor para file_exists (esta sigue siendo relativa al PHP)
        $imageServerPath = realpath(__DIR__ . '/../../img/' . $producto['imagen_prod']);

        echo '<div class="mb-3">';
        echo '<h5>Imagen</h5>';
        
        if ($imageServerPath && file_exists($imageServerPath)) {
            echo '<img src="' . $imageSrc . '" alt="Imagen del producto" class="img-fluid" style="max-height: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
            echo '<script>console.log("Ruta de imagen en modal VER (navegador): ' . $imageSrc . '");</script>';
            echo '<script>console.log("Imagen encontrada en servidor (VER): ' . $imageServerPath . '");</script>';
        } else {
            echo '<p class="text-muted">Imagen no encontrada en el servidor o ruta incorrecta.</p>';
            echo '<script>console.log("Ruta de imagen en modal VER (navegador): ' . $imageSrc . '");</script>';
            echo '<script>console.log("Imagen NO encontrada en servidor (VER). Ruta intentada: ' . ($imageServerPath ? $imageServerPath : 'NULL/FALSE') . '");</script>';
        }
        echo '</div>';
    } else {
        echo '<div class="mb-3">';
        echo '<h5>Imagen</h5>';
        echo '<p class="text-muted">No hay imagen definida para este producto.</p>';
        echo '<script>console.log("No hay imagen definida para este producto en modal VER.");</script>';
        echo '</div>';
    }
    
    echo '<h5>Descripción</h5>';
    echo '<p>' . nl2br(htmlspecialchars($producto['descripcion_prod'])) . '</p>';
    echo '</div>';
    echo '</div>';
    
} catch(Exception $e) {
    echo '<div class="alert alert-danger">Error al cargar el producto: ' . $e->getMessage() . '</div>';
}
?>
