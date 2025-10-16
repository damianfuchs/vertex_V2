<?php
include('../db/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_clientes'];
    $nombre = $_POST['nombre_clientes'];
    $dni_cuit = $_POST['dni_cuit_clientes'];
    $email = $_POST['email_clientes'];
    $telefono = $_POST['telefono_clientes'];
    $direccion = $_POST['direccion_clientes'];
    $localidad = $_POST['localidad_clientes'];
    $tipo_cliente = $_POST['tipo_cliente_clientes'];
    $observaciones = $_POST['observaciones_clientes'];

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
    $stmt->bind_param("ssssssssi", $nombre, $dni_cuit, $email, $telefono, $direccion, $localidad, $tipo_cliente, $observaciones, $id);
    
    if ($stmt->execute()) {
        echo "Cliente editado exitosamente";
    } else {
        echo "Error al editar cliente: " . $conexion->error;
    }
    
    $stmt->close();
    $conexion->close();
}
?>
