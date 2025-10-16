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
    // Obtener datos del producto
    $sql = "SELECT * FROM productos WHERE id_prod = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();
    
    if (!$producto) {
        echo '<div class="alert alert-danger">Producto no encontrado.</div>';
        exit;
    }
    
    // Obtener categorías para el select
    $sqlCategorias = "SELECT id_categ, nombre_categ FROM categorias ORDER BY nombre_categ";
    $stmtCategorias = $conexion->prepare($sqlCategorias);
    $stmtCategorias->execute();
    $resultCategorias = $stmtCategorias->get_result();
    
    echo '<div class="row">';

    // Campo oculto para el ID del producto
    echo '<input type="hidden" id="editId" name="id" value="' . htmlspecialchars($producto['id_prod']) . '">';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editCodigo" class="form-label">Código *</label>';
    echo '<input type="text" class="form-control" id="editCodigo" name="codigo_prod" value="' . htmlspecialchars($producto['codigo_prod']) . '" required>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editNombre" class="form-label">Nombre *</label>';
    echo '<input type="text" class="form-control" id="editNombre" name="nombre_prod" value="' . htmlspecialchars($producto['nombre_prod']) . '" required>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editMateria" class="form-label">Materia</label>';
    echo '<input type="text" class="form-control" id="editMateria" name="materia_prod" value="' . htmlspecialchars($producto['materia_prod']) . '">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editStock" class="form-label">Stock *</label>';
    echo '<input type="number" class="form-control" id="editStock" name="stock_prod" value="' . $producto['stock_prod'] . '" required>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editUbicacion" class="form-label">Ubicación</label>';
    echo '<input type="text" class="form-control" id="editUbicacion" name="ubicacion_prod" value="' . htmlspecialchars($producto['ubicacion_prod']) . '">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-6">';
    echo '<div class="mb-3">';
    echo '<label for="editPeso" class="form-label">Peso</label>';
    echo '<input type="text" class="form-control" id="editPeso" name="peso_prod" value="' . htmlspecialchars($producto['peso_prod']) . '">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-12">';
    echo '<div class="mb-3">';
    echo '<label for="editCategoria" class="form-label">Categoría</label>';
    echo '<select class="form-control" id="editCategoria" name="categoria_id">';
    echo '<option value="">Seleccionar categoría</option>';
    while ($categoria = $resultCategorias->fetch_assoc()) {
        $selected = ($categoria['id_categ'] == $producto['categoria_id']) ? 'selected' : '';
        echo '<option value="' . $categoria['id_categ'] . '" ' . $selected . '>' . htmlspecialchars($categoria['nombre_categ']) . '</option>';
    }
    echo '</select>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-12">';
    echo '<div class="mb-3">';
    echo '<label for="editDescripcion" class="form-label">Descripción</label>';
    echo '<textarea class="form-control" id="editDescripcion" name="descripcion_prod" rows="4">' . htmlspecialchars($producto['descripcion_prod']) . '</textarea>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-12">';
    echo '<div class="mb-3">';
    echo '<label for="editImagen" class="form-label">Imagen Actual</label>';
    if (!empty($producto['imagen_prod'])) {
        // RUTA CORREGIDA: USANDO BASE_URL
        $imageSrc = BASE_URL . '/img/' . htmlspecialchars($producto['imagen_prod']);
        // Ruta absoluta en el servidor para file_exists (esta sigue siendo relativa al PHP)
        $imageServerPath = realpath(__DIR__ . '/../../img/' . $producto['imagen_prod']);

        if ($imageServerPath && file_exists($imageServerPath)) {
            echo '<p><img src="' . $imageSrc . '" alt="Imagen actual" style="max-height:100px; border-radius:6px; box-shadow: 0 0 5px rgba(0,0,0,0.1);"></p>';
            echo '<small class="form-text text-muted">Nombre del archivo actual: ' . htmlspecialchars($producto['imagen_prod']) . '</small>';
            echo '<script>console.log("Ruta de imagen en modal EDITAR (navegador): ' . $imageSrc . '");</script>';
            echo '<script>console.log("Imagen encontrada en servidor (EDITAR): ' . $imageServerPath . '");</script>';
        } else {
            echo '<p class="text-muted">Imagen no encontrada en el servidor o ruta incorrecta.</p>';
            echo '<script>console.log("Ruta de imagen en modal EDITAR (navegador): ' . $imageSrc . '");</script>';
            echo '<script>console.log("Imagen NO encontrada en servidor (EDITAR). Ruta intentada: ' . ($imageServerPath ? $imageServerPath : 'NULL/FALSE') . '");</script>';
        }
    } else {
        echo '<p class="text-muted">No hay imagen actual.</p>';
        echo '<script>console.log("No hay imagen definida para este producto en modal EDITAR.");</script>';
    }
    echo '<label for="uploadImagen" class="form-label mt-2">Subir Nueva Imagen (opcional)</label>';
    echo '<input type="file" class="form-control" id="uploadImagen" name="nueva_imagen" accept="image/*">';
    echo '<small class="form-text text-muted">Deja este campo vacío para mantener la imagen actual.</small>';
    echo '</div>';
    echo '</div>';
    
    echo '</div>';
    
} catch(Exception $e) {
    echo '<div class="alert alert-danger">Error al cargar el formulario: ' . $e->getMessage() . '</div>';
}
?>
