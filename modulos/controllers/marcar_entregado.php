<?php
include('../db/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_pedido'] ?? null;

    if ($id) {
        // Cambiar estado a 'Entregado'
        $stmt = $conexion->prepare("UPDATE pedidos SET estado_pedido = 'Entregado' WHERE id_pedido = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al actualizar estado']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'ID no recibido']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>
