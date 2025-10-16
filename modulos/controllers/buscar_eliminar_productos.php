<?php
header('Content-Type: application/json');
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include('../db/conexion.php');

$id = $_POST['id'] ?? '';

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
    exit;
}

try {
    // Verificar si el producto existe
    $sqlCheck = "SELECT nombre_prod FROM productos WHERE id_prod = ?";
    $stmtCheck = $conexion->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $producto = $resultCheck->fetch_assoc();
    
    if (!$producto) {
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
        exit;
    }
    
    // Eliminar el producto
    $sql = "DELETE FROM productos WHERE id_prod = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $resultado = $stmt->execute();
    
    if ($resultado && $stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true, 
            'message' => 'Producto "' . $producto['nombre_prod'] . '" eliminado correctamente'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el producto']);
    }
    
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()]);
}
?>
