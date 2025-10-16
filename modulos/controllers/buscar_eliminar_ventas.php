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
    $sqlCheck = "SELECT id_venta FROM ventas WHERE id_venta = ?";
    $stmtCheck = $conexion->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $venta = $resultCheck->fetch_assoc();
    
    if (!$venta) {
        echo json_encode(['success' => false, 'message' => 'Venta no encontrada']);
        exit;
    }
    
    $sql = "DELETE FROM ventas WHERE id_venta = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $resultado = $stmt->execute();
    
    if ($resultado && $stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true, 
            'message' => 'Venta eliminada correctamente'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la venta']);
    }
    
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()]);
}
?>
