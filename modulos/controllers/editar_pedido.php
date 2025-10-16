<?php
include('../db/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_pedido'] ?? null;
    $nombre = $_POST['nombre_cliente_pedido'] ?? '';
    $precio = $_POST['precio_pedido'] ?? 0;
    $estado = $_POST['estado_pedido'] ?? 'Pendiente';
    $observaciones = $_POST['observaciones_pedidos'] ?? '';

    $nombre = trim($nombre);
    $observaciones = trim($observaciones);

    if ($id !== null && $nombre !== '') {
        $stmt = $conexion->prepare("UPDATE pedidos SET nombre_cliente_pedido = ?, precio_pedido = ?, estado_pedido = ?, observaciones_pedidos = ? WHERE id_pedido = ?");
        $stmt->bind_param("sdssi", $nombre, $precio, $estado, $observaciones, $id);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Pedido actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
    }
}
?>
