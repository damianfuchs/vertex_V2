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
    $nombre_clientes = trim($_POST['nombre_clientes'] ?? '');
    $dni_cuit_clientes = trim($_POST['dni_cuit_clientes'] ?? '');
    $email_clientes = trim($_POST['email_clientes'] ?? '');
    $telefono_clientes = trim($_POST['telefono_clientes'] ?? '');
    $direccion_clientes = trim($_POST['direccion_clientes'] ?? '');
    $localidad_clientes = trim($_POST['localidad_clientes'] ?? '');
    $tipo_cliente_clientes = trim($_POST['tipo_cliente_clientes'] ?? '');
    $observaciones_clientes = trim($_POST['observaciones_clientes'] ?? '');

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
        exit;
    }
    if (empty($nombre_clientes)) {
        echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio']);
        exit;
    }

    $sqlCheck = "SELECT id_clientes FROM clientes WHERE id_clientes = ?";
    $stmtCheck = $conexion->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    if (!$resultCheck->fetch_assoc()) {
        echo json_encode(['success' => false, 'message' => 'Cliente no encontrado']);
        exit;
    }

    $sql = "UPDATE clientes SET 
            nombre_clientes = ?, 
            dni_cuit_clientes = ?, 
            email_clientes = ?, 
            telefono_clientes = ?, 
            direccion_clientes = ?, 
            localidad_clientes = ?, 
            tipo_cliente_clientes = ?, 
            observaciones_clientes = ?
            WHERE id_clientes = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssssssi", 
        $nombre_clientes, 
        $dni_cuit_clientes, 
        $email_clientes, 
        $telefono_clientes, 
        $direccion_clientes, 
        $localidad_clientes, 
        $tipo_cliente_clientes, 
        $observaciones_clientes,
        $id
    );

    $resultado = $stmt->execute();
    
    if ($resultado && $stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true, 
            'message' => 'Cliente actualizado correctamente'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se realizaron cambios en el cliente']);
    }
    
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la actualización: ' . $e->getMessage()]);
}
?>
