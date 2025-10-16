<?php
include('../db/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_clientes'];

    $sql = "DELETE FROM clientes WHERE id_clientes = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "Cliente eliminado exitosamente";
    } else {
        echo "Error al eliminar cliente: " . $conexion->error;
    }
    
    $stmt->close();
    $conexion->close();
}
?>
