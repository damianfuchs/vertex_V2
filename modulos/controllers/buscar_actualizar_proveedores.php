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
    $nombre_proveedores = trim($_POST['nombre_proveedores'] ?? '');
    $nombre_contacto_proveedores = trim($_POST['nombre_contacto_proveedores'] ?? '');
    $telefono_proveedores = trim($_POST['telefono_proveedores'] ?? '');
    $email_proveedores = trim($_POST['email_proveedores'] ?? '');
    $direccion_proveedores = trim($_POST['direccion_proveedores'] ?? '');
    $ubicacion_proveedores = trim($_POST['ubicacion_proveedores'] ?? '');
    $codigo_postal_proveedores = trim($_POST['codigo_postal_proveedores'] ?? '');
    $sitio_web_proveedores = trim($_POST['sitio_web_proveedores'] ?? '');
    $horario_atencion_proveedores = trim($_POST['horario_atencion_proveedores'] ?? '');
    $observacion_proveedores = trim($_POST['observacion_proveedores'] ?? '');
    $estado_proveedores = trim($_POST['estado_proveedores'] ?? '');

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
        exit;
    }
    if (empty($nombre_proveedores)) {
        echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio']);
        exit;
    }

    $sqlCheck = "SELECT id_proveedores FROM proveedores WHERE id_proveedores = ?";
    $stmtCheck = $conexion->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    if (!$resultCheck->fetch_assoc()) {
        echo json_encode(['success' => false, 'message' => 'Proveedor no encontrado']);
        exit;
    }

    $sql = "UPDATE proveedores SET 
            nombre_proveedores = ?, 
            nombre_contacto_proveedores = ?, 
            telefono_proveedores = ?, 
            email_proveedores = ?, 
            direccion_proveedores = ?, 
            ubicacion_proveedores = ?, 
            codigo_postal_proveedores = ?, 
            sitio_web_proveedores = ?, 
            horario_atencion_proveedores = ?, 
            observacion_proveedores = ?, 
            estado_proveedores = ?
            WHERE id_proveedores = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssssssssi", 
        $nombre_proveedores, 
        $nombre_contacto_proveedores, 
        $telefono_proveedores, 
        $email_proveedores, 
        $direccion_proveedores, 
        $ubicacion_proveedores, 
        $codigo_postal_proveedores, 
        $sitio_web_proveedores, 
        $horario_atencion_proveedores, 
        $observacion_proveedores, 
        $estado_proveedores,
        $id
    );

    $resultado = $stmt->execute();
    
    if ($resultado && $stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true, 
            'message' => 'Proveedor actualizado correctamente'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se realizaron cambios en el proveedor']);
    }
    
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la actualización: ' . $e->getMessage()]);
}
?>
