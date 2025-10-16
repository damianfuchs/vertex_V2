<?php
header('Content-Type: application/json');
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Habilitar errores para debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../db/conexion.php');
include('../config.php'); // ¡¡¡NUEVA LÍNEA!!! Asegúrate de que la ruta sea correcta

// Configuración de subida de imágenes
// Esta ruta es para el servidor, no para el navegador. Debe ser relativa al archivo PHP o absoluta en el sistema de archivos.
// __DIR__ es la carpeta actual (modulos/controllers). ../../img/ te lleva a VERTEX-INV/img/
$uploadDir = realpath(__DIR__ . '/../../img/') . DIRECTORY_SEPARATOR; 
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$maxFileSize = 5 * 1024 * 1024; // 5 MB

// Log de debugging - puedes comentar estas líneas después de que funcione
error_log("POST data: " . print_r($_POST, true));

try {
    $id = $_POST['id'] ?? '';
    $codigo_prod = trim($_POST['codigo_prod'] ?? '');
    $nombre_prod = trim($_POST['nombre_prod'] ?? '');
    $descripcion_prod = trim($_POST['descripcion_prod'] ?? '');
    $materia_prod = trim($_POST['materia_prod'] ?? '');
    $stock_prod = $_POST['stock_prod'] ?? '';
    $ubicacion_prod = trim($_POST['ubicacion_prod'] ?? '');
    $peso_prod = trim($_POST['peso_prod'] ?? '');
    $imagen_prod = trim($_POST['imagen_prod'] ?? '');
    $categoria_id = $_POST['categoria_id'] ?? '';

    // Validaciones básicas
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
        exit;
    }

    if (empty($codigo_prod)) {
        echo json_encode(['success' => false, 'message' => 'El código es obligatorio']);
        exit;
    }

    if (empty($nombre_prod)) {
        echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio']);
        exit;
    }

    if (!is_numeric($stock_prod) || $stock_prod < 0) {
        echo json_encode(['success' => false, 'message' => 'El stock debe ser un número válido mayor o igual a 0']);
        exit;
    }

    // Verificar conexión a la base de datos
    if (!$conexion) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
        exit;
    }

    // Verificar si el producto existe
    $sqlCheck = "SELECT id_prod, imagen_prod FROM productos WHERE id_prod = ?";
    $stmtCheck = $conexion->prepare($sqlCheck);
    
    if (!$stmtCheck) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar consulta de verificación: ' . $conexion->error]);
        exit;
    }
    
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $producto_actual = $resultCheck->fetch_assoc();
    
    if (!$producto_actual) {
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
        exit;
    }
    $imagen_prod_actual_db = $producto_actual['imagen_prod'];


    // Verificar duplicados de código
    $sqlDuplicate = "SELECT id_prod FROM productos WHERE codigo_prod = ? AND id_prod != ?";
    $stmtDuplicate = $conexion->prepare($sqlDuplicate);
    
    if (!$stmtDuplicate) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar consulta de duplicados: ' . $conexion->error]);
        exit;
    }
    
    $stmtDuplicate->bind_param("si", $codigo_prod, $id);
    $stmtDuplicate->execute();
    $resultDuplicate = $stmtDuplicate->get_result();
    
    if ($resultDuplicate->fetch_assoc()) {
        echo json_encode(['success' => false, 'message' => 'Ya existe otro producto con ese código']);
        exit;
    }

    $imagen_prod_a_guardar = $imagen_prod_actual_db; // Mantener la imagen existente por defecto

    // Manejo de la subida de nueva imagen
    if (isset($_FILES['nueva_imagen']) && $_FILES['nueva_imagen']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['nueva_imagen']['tmp_name'];
        $fileName = $_FILES['nueva_imagen']['name'];
        $fileSize = $_FILES['nueva_imagen']['size'];
        $fileType = $_FILES['nueva_imagen']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Validaciones de la imagen
        if (!in_array($fileType, $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Tipo de archivo no permitido. Solo JPG, PNG, GIF, WEBP.']);
            exit;
        }
        if ($fileSize > $maxFileSize) {
            echo json_encode(['success' => false, 'message' => 'El archivo es demasiado grande. Máximo ' . ($maxFileSize / (1024 * 1024)) . ' MB.']);
            exit;
        }

        // Generar un nombre único para el archivo
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $destPath = $uploadDir . $newFileName;

        // Crear directorio si no existe (aunque ya debería existir si la ruta es correcta)
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // 0755 es un permiso seguro
        }

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Eliminar imagen anterior si existe y es diferente a la nueva
            if (!empty($imagen_prod_actual_db) && $imagen_prod_actual_db !== $newFileName) {
                $oldImagePath = $uploadDir . $imagen_prod_actual_db;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                    error_log("Imagen antigua eliminada: " . $oldImagePath);
                }
            }
            $imagen_prod_a_guardar = $newFileName; // Actualizar el nombre de la imagen para la DB
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al mover el archivo subido.']);
            exit;
        }
    }

    // Preparar la actualización
    if (empty($categoria_id)) {
        // Si no hay categoría, usar NULL
        $sql = "UPDATE productos SET 
                codigo_prod = ?, 
                nombre_prod = ?, 
                descripcion_prod = ?, 
                materia_prod = ?, 
                stock_prod = ?, 
                ubicacion_prod = ?, 
                peso_prod = ?, 
                imagen_prod = ?, 
                categoria_id = NULL
                WHERE id_prod = ?";
        
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Error al preparar consulta de actualización: ' . $conexion->error]);
            exit;
        }
        
        $stmt->bind_param("ssssisssi", 
            $codigo_prod, 
            $nombre_prod, 
            $descripcion_prod, 
            $materia_prod, 
            $stock_prod, 
            $ubicacion_prod, 
            $peso_prod, 
            $imagen_prod_a_guardar,
            $id
        );
    } else {
        // Con categoría
        $sql = "UPDATE productos SET 
                codigo_prod = ?, 
                nombre_prod = ?, 
                descripcion_prod = ?, 
                materia_prod = ?, 
                stock_prod = ?, 
                ubicacion_prod = ?, 
                peso_prod = ?, 
                imagen_prod = ?, 
                categoria_id = ?
                WHERE id_prod = ?";
        
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Error al preparar consulta de actualización: ' . $conexion->error]);
            exit;
        }
        
        $stmt->bind_param("ssssisssii", 
            $codigo_prod, 
            $nombre_prod, 
            $descripcion_prod, 
            $materia_prod, 
            $stock_prod, 
            $ubicacion_prod, 
            $peso_prod, 
            $imagen_prod_a_guardar,
            $categoria_id, 
            $id
        );
    }

    // Ejecutar la actualización
    $resultado = $stmt->execute();
    
    if (!$resultado) {
        echo json_encode(['success' => false, 'message' => 'Error al ejecutar actualización: ' . $stmt->error]);
        exit;
    }
    
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true, 
            'message' => 'Producto actualizado correctamente'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se realizaron cambios en el producto']);
    }
    
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la actualización: ' . $e->getMessage()]);
} catch(Error $e) {
    echo json_encode(['success' => false, 'message' => 'Error fatal: ' . $e->getMessage()]);
}
?>
