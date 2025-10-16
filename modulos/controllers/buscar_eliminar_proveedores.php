<?php
header('Content-Type: application/json');
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include('../db/conexion.php');
include('../config.php');

$id = $_POST['id'] ?? '';

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
    exit;
}

try {
    $sqlCheck = "SELECT nombre_proveedores FROM proveedores WHERE id_proveedores = ?";
    $stmtCheck = $conexion->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $proveedor = $resultCheck->fetch_assoc();
    
    if (!$proveedor) {
        echo json_encode(['success' => false, 'message' => 'Proveedor no encontrado']);
        exit;
    }
    
    $sql = "DELETE FROM proveedores WHERE id_proveedores = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $resultado = $stmt->execute();
    
    if ($resultado && $stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true, 
            'message' => 'Proveedor "' . $proveedor['nombre_proveedores'] . '" eliminado correctamente'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el proveedor']);
    }
    
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()]);
}
?>
