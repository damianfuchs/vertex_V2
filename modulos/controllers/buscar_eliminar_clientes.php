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
    $sqlCheck = "SELECT nombre_clientes FROM clientes WHERE id_clientes = ?";
    $stmtCheck = $conexion->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $cliente = $resultCheck->fetch_assoc();
    
    if (!$cliente) {
        echo json_encode(['success' => false, 'message' => 'Cliente no encontrado']);
        exit;
    }
    
    $sql = "DELETE FROM clientes WHERE id_clientes = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $resultado = $stmt->execute();
    
    if ($resultado && $stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true, 
            'message' => 'Cliente "' . $cliente['nombre_clientes'] . '" eliminado correctamente'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el cliente']);
    }
    
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()]);
}
?>
