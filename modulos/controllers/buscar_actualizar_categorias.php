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
    $codigo_categ = trim($_POST['codigo_categ'] ?? '');
    $nombre_categ = trim($_POST['nombre_categ'] ?? '');
    $descripcion_categ = trim($_POST['descripcion_categ'] ?? '');

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
        exit;
    }
    if (empty($codigo_categ)) {
        echo json_encode(['success' => false, 'message' => 'El código es obligatorio']);
        exit;
    }
    if (empty($nombre_categ)) {
        echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio']);
        exit;
    }

    $sqlCheck = "SELECT id_categ FROM categorias WHERE id_categ = ?";
    $stmtCheck = $conexion->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    if (!$resultCheck->fetch_assoc()) {
        echo json_encode(['success' => false, 'message' => 'Categoría no encontrada']);
        exit;
    }

    $sqlDuplicate = "SELECT id_categ FROM categorias WHERE codigo_categ = ? AND id_categ != ?";
    $stmtDuplicate = $conexion->prepare($sqlDuplicate);
    $stmtDuplicate->bind_param("si", $codigo_categ, $id);
    $stmtDuplicate->execute();
    $resultDuplicate = $stmtDuplicate->get_result();
    if ($resultDuplicate->fetch_assoc()) {
        echo json_encode(['success' => false, 'message' => 'Ya existe otra categoría con ese código']);
        exit;
    }

    $sql = "UPDATE categorias SET 
            codigo_categ = ?, 
            nombre_categ = ?, 
            descripcion_categ = ?
            WHERE id_categ = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssi", 
        $codigo_categ, 
        $nombre_categ, 
        $descripcion_categ, 
        $id
    );

    $resultado = $stmt->execute();
    
    if ($resultado && $stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true, 
            'message' => 'Categoría actualizada correctamente'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se realizaron cambios en la categoría']);
    }
    
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la actualización: ' . $e->getMessage()]);
}
?>
