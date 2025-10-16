<?php
header('Content-Type: application/json');
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include('../db/conexion.php'); // Adjust path as necessary
include('../config.php'); // Adjust path as necessary

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $nombre_cliente_pedido = $_POST['nombre_cliente_pedido'] ?? '';
    $fecha_pedido = $_POST['fecha_pedido'] ?? '';
    $precio_pedido = $_POST['precio_pedido'] ?? '';
    $estado_pedido = $_POST['estado_pedido'] ?? '';
    $observaciones_pedidos = $_POST['observaciones_pedidos'] ?? '';

    if (empty($id)) {
        $response['message'] = 'ID de pedido no proporcionado.';
        echo json_encode($response);
        exit;
    }

    try {
        $sql = "UPDATE pedidos SET 
                    nombre_cliente_pedido = ?, 
                    fecha_pedido = ?, 
                    precio_pedido = ?, 
                    estado_pedido = ?, 
                    observaciones_pedidos = ? 
                WHERE id_pedido = ?";
        
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $conexion->error);
        }

        // Ensure correct types for binding
        // s: string, d: double (for decimal), i: integer
        $stmt->bind_param(
            "ssdssi", 
            $nombre_cliente_pedido, 
            $fecha_pedido, 
            $precio_pedido, 
            $estado_pedido, 
            $observaciones_pedidos, 
            $id
        );

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $response['success'] = true;
                $response['message'] = 'Pedido actualizado correctamente.';
            } else {
                $response['message'] = 'No se realizaron cambios en el pedido o el pedido no existe.';
            }
        } else {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        $stmt->close();

    } catch (Exception $e) {
        $response['message'] = 'Error en la actualización: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Método de solicitud no permitido.';
}

echo json_encode($response);
?>
