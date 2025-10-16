<?php
include('../db/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Recibir el ID del proveedor
        $id_proveedores = isset($_POST['id_proveedores']) ? (int)$_POST['id_proveedores'] : 0;

        // Validación básica - cambié empty() por verificar si es mayor que 0
        if ($id_proveedores <= 0) {
            throw new Exception("ID del proveedor no válido");
        }

        // Verificar que el proveedor existe
        $check_sql = "SELECT id_proveedores, nombre_proveedores FROM proveedores WHERE id_proveedores = ?";
        $check_stmt = $conexion->prepare($check_sql);
        $check_stmt->bind_param("i", $id_proveedores);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("El proveedor no existe");
        }
        
        $proveedor = $result->fetch_assoc();
        $check_stmt->close();

        // Verificar si el proveedor tiene productos asociados (opcional)
        // Descomenta estas líneas si tienes una tabla de productos con proveedor_id
        /*
        $productos_sql = "SELECT COUNT(*) as total FROM productos WHERE proveedor_id = ?";
        $productos_stmt = $conexion->prepare($productos_sql);
        $productos_stmt->bind_param("i", $id_proveedores);
        $productos_stmt->execute();
        $productos_result = $productos_stmt->get_result();
        $productos_count = $productos_result->fetch_assoc()['total'];
        $productos_stmt->close();

        if ($productos_count > 0) {
            throw new Exception("No se puede eliminar el proveedor porque tiene productos asociados");
        }
        */

        // Preparar la consulta SQL de eliminación
        $sql = "DELETE FROM proveedores WHERE id_proveedores = ?";
        $stmt = $conexion->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
        }

        $stmt->bind_param("i", $id_proveedores);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $stmt->close();
                echo json_encode([
                    'success' => true,
                    'message' => 'Proveedor "' . $proveedor['nombre_proveedores'] . '" eliminado exitosamente'
                ]);
            } else {
                throw new Exception("No se pudo eliminar el proveedor");
            }
        } else {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    // Redirigir si no es POST
    header("Location: ../../proveedores.php");
    exit();
}

$conexion->close();
?>
