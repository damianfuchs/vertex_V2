<?php
header('Content-Type: application/json');
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../db/conexion.php');
include('../config.php');

try {
    $id = $_POST['id'] ?? '';
    $fecha_venta = trim($_POST['fecha_venta'] ?? '');
    $total_venta = $_POST['total_venta'] ?? '';
    $cliente_id = $_POST['cliente_id'] ?? null; // Can be null
    $estado_venta = trim($_POST['estado_venta'] ?? '');
    $observaciones_venta = trim($_POST['observaciones_venta'] ?? '');

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
        exit;
    }
    if (empty($fecha_venta)) {
        echo json_encode(['success' => false, 'message' => 'La fecha es obligatoria']);
        exit;
    }
    if (!is_numeric($total_venta) || $total_venta < 0) {
        echo json_encode(['success' => false, 'message' => 'El total debe ser un número válido mayor o igual a 0']);
        exit;
    }

    $sqlCheck = "SELECT id_venta FROM ventas WHERE id_venta = ?";
    $stmtCheck = $conexion->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    if (!$resultCheck->fetch_assoc()) {
        echo json_encode(['success' => false, 'message' => 'Venta no encontrada']);
        exit;
    }

    if (empty($cliente_id)) {
        $sql = "UPDATE ventas SET 
                fecha_venta = ?, 
                total_venta = ?, 
                cliente_id = NULL, 
                estado_venta = ?, 
                observaciones_venta = ?
                WHERE id_venta = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sdssi", 
            $fecha_venta, 
            $total_venta, 
            $estado_venta, 
            $observaciones_venta,
            $id
        );
    } else {
        $sql = "UPDATE ventas SET 
                fecha_venta = ?, 
                total_venta = ?, 
                cliente_id = ?, 
                estado_venta = ?, 
                observaciones_venta = ?
                WHERE id_venta = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sdisssi", 
            $fecha_venta, 
            $total_venta, 
            $cliente_id, 
            $estado_venta, 
            $observaciones_venta,
            $id
        );
    }

    $resultado = $stmt->execute();
    
    if ($resultado && $stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true, 
            'message' => 'Venta actualizada correctamente'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se realizaron cambios en la venta']);
    }
    
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la actualización: ' . $e->getMessage()]);
}
?>
