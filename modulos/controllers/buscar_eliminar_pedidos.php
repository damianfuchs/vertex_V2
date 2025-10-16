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
    $sqlCheck = "SELECT id_pedido FROM pedidos WHERE id_pedido = ?";
    $stmtCheck = $conexion->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $pedido = $resultCheck->fetch_assoc();
    
    if (!$pedido) {
        echo json_encode(['success' => false, 'message' => 'Pedido no encontrado']);
        exit;
    }
    
    $sql = "DELETE FROM pedidos WHERE id_pedido = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $resultado = $stmt->execute();
    
    if ($resultado && $stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true, 
            'message' => 'Pedido eliminado correctamente'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el pedido']);
    }
    
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()]);
}
?>
