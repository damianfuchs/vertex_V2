<?php
// Incluir la conexión a la base de datos
include('../db/conexion.php'); // Adjusted path for controller

// Verificar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Método no permitido');
}

try {
    // Obtener datos del formulario
    $id_prod = $_POST['id_prod'] ?? '';
    $codigo_prod = $_POST['codigo_prod'] ?? '';
    $nombre_prod = $_POST['nombre_prod'] ?? '';
    $descripcion_prod = $_POST['descripcion_prod'] ?? '';
    $categoria_id = $_POST['categoria_id'] ?? null;
    $materia_prod = $_POST['materia_prod'] ?? '';
    $peso_prod = $_POST['peso_prod'] ?? null;
    $stock_prod = $_POST['stock_prod'] ?? 0;
    $ubicacion_prod = $_POST['ubicacion_prod'] ?? '';

    // Validar campos obligatorios
    if (empty($id_prod) || empty($codigo_prod) || empty($nombre_prod)) {
        throw new Exception('Faltan campos obligatorios');
    }

    // Obtener la imagen actual del producto
    $consulta_imagen_actual = "SELECT imagen_prod FROM productos WHERE id_prod = ?";
    $stmt_imagen_actual = $conexion->prepare($consulta_imagen_actual);
    $stmt_imagen_actual->bind_param("i", $id_prod);
    $stmt_imagen_actual->execute();
    $resultado_imagen_actual = $stmt_imagen_actual->get_result();
    $imagen_actual = '';
    
    if ($fila_imagen = $resultado_imagen_actual->fetch_assoc()) {
        $imagen_actual = $fila_imagen['imagen_prod'];
    }
    $stmt_imagen_actual->close(); // Close this statement

    // Manejar la imagen si se subió una nueva
    $imagen_prod = $imagen_actual; // Por defecto, mantener la imagen actual
    
    if (isset($_FILES['imagen_prod']) && $_FILES['imagen_prod']['error'] === UPLOAD_ERR_OK) {
        $archivo = $_FILES['imagen_prod'];
        $nombre_archivo = $archivo['name'];
        $tmp_name = $archivo['tmp_name'];
        $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
        
        // Validar extensión
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($extension, $extensiones_permitidas)) {
            throw new Exception('Formato de imagen no permitido');
        }
        
        // Validar tamaño (máximo 5MB)
        if ($archivo['size'] > 5 * 1024 * 1024) {
            throw new Exception('La imagen es demasiado grande. Máximo 5MB');
        }
        
        // Generar nombre único
        $nuevo_nombre = 'producto_' . $id_prod . '_' . time() . '.' . $extension;
        $ruta_destino = '../../img/' . $nuevo_nombre; // Correct path for saving

        // Crear directorio si no existe
        if (!file_exists('../../img/')) {
            mkdir('../../img/', 0777, true);
        }
        
        // Mover archivo
        if (move_uploaded_file($tmp_name, $ruta_destino)) {
            // Eliminar imagen anterior si existe y es diferente
            // Note: The path for unlink should be relative to the script or absolute
            if ($imagen_actual && $imagen_actual !== $nuevo_nombre && file_exists('../../img/' . $imagen_actual)) {
                unlink('../../img/' . $imagen_actual);
            }
            $imagen_prod = $nuevo_nombre;
        } else {
            throw new Exception('Error al subir la imagen');
        }
    }

    // Preparar la consulta SQL - SIEMPRE actualizar la imagen (aunque sea la misma)
    $sql = "UPDATE productos SET 
            codigo_prod = ?, 
            nombre_prod = ?, 
            descripcion_prod = ?, 
            categoria_id = ?, 
            materia_prod = ?, 
            peso_prod = ?, 
            stock_prod = ?, 
            ubicacion_prod = ?, 
            imagen_prod = ?
            WHERE id_prod = ?";
    
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Error al preparar la consulta: ' . $conexion->error);
    }

    $stmt->bind_param("sssississi", 
        $codigo_prod, 
        $nombre_prod, 
        $descripcion_prod, 
        $categoria_id, 
        $materia_prod, 
        $peso_prod, 
        $stock_prod, 
        $ubicacion_prod, 
        $imagen_prod, 
        $id_prod
    );

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Obtener los datos actualizados del producto, incluyendo el nombre de la categoría
        $consulta_actualizada = "SELECT p.*, c.nombre_categ 
                                FROM productos p
                                LEFT JOIN categorias c ON p.categoria_id = c.id_categ 
                                WHERE p.id_prod = ?";
        $stmt_actualizada = $conexion->prepare($consulta_actualizada);
        $stmt_actualizada->bind_param("i", $id_prod);
        $stmt_actualizada->execute();
        $resultado_actualizada = $stmt_actualizada->get_result();
        $producto_actualizado = $resultado_actualizada->fetch_assoc();
        $stmt_actualizada->close();

        echo json_encode([
            'success' => true,
            'message' => 'Producto editado correctamente',
            'id' => $id_prod,
            'product' => $producto_actualizado // Ensure it's 'product' for consistency
        ]);
    } else {
        throw new Exception('Error al ejecutar la consulta: ' . $stmt->error);
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    // Cerrar conexiones
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conexion)) {
        $conexion->close();
    }
}
?>
