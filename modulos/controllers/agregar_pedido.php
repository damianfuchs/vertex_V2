<?php
include('../db/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_cliente_pedido'] ?? '';
    $precio = $_POST['precio_pedido'] ?? 0;
    $estado = $_POST['estado_pedido'] ?? 'Pendiente';
    $observaciones = $_POST['observaciones_pedidos'] ?? '';

    $nombre = trim($nombre);
    $observaciones = trim($observaciones);

    if ($nombre !== '') {
        $stmt = $conexion->prepare("INSERT INTO pedidos (nombre_cliente_pedido, fecha_pedido, precio_pedido, estado_pedido, observaciones_pedidos) VALUES (?, NOW(), ?, ?, ?)");
        $stmt->bind_param("sdss", $nombre, $precio, $estado, $observaciones);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Pedido agregado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'El nombre del cliente es obligatorio']);
    }
}
?>
