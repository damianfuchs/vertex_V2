<?php
// Incluir la conexión a la base de datos
include('../db/conexion.php');

// Verificar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    // Obtener ID del producto
    $id_prod = $_POST['id_prod'] ?? '';

    // Validar que se proporcionó el ID
    if (empty($id_prod) || !is_numeric($id_prod)) {
        throw new Exception('ID de producto no válido');
    }

    // Verificar que el producto existe y obtener la imagen
    $consulta_producto = "SELECT imagen_prod FROM productos WHERE id_prod = ?";
    $stmt_producto = $conexion->prepare($consulta_producto);
    
    if (!$stmt_producto) {
        throw new Exception('Error al preparar consulta: ' . $conexion->error);
    }
    
    $stmt_producto->bind_param("i", $id_prod);
    $stmt_producto->execute();
    $resultado_producto = $stmt_producto->get_result();
    
    if ($resultado_producto->num_rows === 0) {
        throw new Exception('El producto no existe');
    }
    
    $producto = $resultado_producto->fetch_assoc();
    $imagen_prod = $producto['imagen_prod'];

    // Eliminar el producto de la base de datos
    $sql = "DELETE FROM productos WHERE id_prod = ?";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Error al preparar consulta de eliminación: ' . $conexion->error);
    }
    
    $stmt->bind_param("i", $id_prod);

    if (!$stmt->execute()) {
        throw new Exception('Error al ejecutar la consulta: ' . $stmt->error);
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception('No se pudo eliminar el producto');
    }

    // Si se eliminó correctamente, eliminar también la imagen del servidor
    if ($imagen_prod && !empty(trim($imagen_prod))) {
        $directorio_img = dirname(__DIR__) . '../../img/';
        $ruta_imagen = $directorio_img . $imagen_prod;
        
        if (file_exists($ruta_imagen)) {
            if (unlink($ruta_imagen)) {
                $mensaje_imagen = ' e imagen eliminada';
            } else {
                $mensaje_imagen = ' (imagen no se pudo eliminar)';
            }
        } else {
            $mensaje_imagen = ' (imagen no encontrada)';
        }
    } else {
        $mensaje_imagen = '';
    }
    
    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'message' => 'Producto eliminado correctamente' . $mensaje_imagen,
        'id' => $id_prod
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    // Cerrar conexiones
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($stmt_producto)) {
        $stmt_producto->close();
    }
    if (isset($conexion)) {
        $conexion->close();
    }
}
?>
