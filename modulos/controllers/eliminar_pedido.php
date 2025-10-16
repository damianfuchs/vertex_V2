<?php
include('../db/conexion.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_pedido'] ?? null;

    if (!$id) {
        echo json_encode(['success' => false, 'error' => 'ID de pedido no recibido']);
        exit;
    }

    // Preparar y ejecutar la eliminación
    $stmt = $conexion->prepare("DELETE FROM pedidos WHERE id_pedido = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar el pedido']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>
