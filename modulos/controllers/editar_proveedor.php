<?php
include('../db/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Recibir y limpiar los datos del formulario
        $id_proveedores = (int)$_POST['id_proveedores'];
        $nombre_proveedores = trim($_POST['nombre_proveedores']);
        $nombre_contacto_proveedores = trim($_POST['nombre_contacto_proveedores'] ?? '');
        $telefono_proveedores = trim($_POST['telefono_proveedores'] ?? '');
        $email_proveedores = trim($_POST['email_proveedores'] ?? '');
        $direccion_proveedores = trim($_POST['direccion_proveedores'] ?? '');
        $ubicacion_proveedores = trim($_POST['ubicacion_proveedores'] ?? '');
        $codigo_postal_proveedores = trim($_POST['codigo_postal_proveedores'] ?? '');
        $sitio_web_proveedores = trim($_POST['sitio_web_proveedores'] ?? '');
        $horario_atencion_proveedores = trim($_POST['horario_atencion_proveedores'] ?? '');
        $observacion_proveedores = trim($_POST['observacion_proveedores'] ?? '');
        $estado_proveedores = isset($_POST['estado_proveedores']) ? (int)$_POST['estado_proveedores'] : 1;

        // Validación básica - SOLO el nombre es obligatorio
        if (empty($id_proveedores)) {
            throw new Exception("ID del proveedor no válido");
        }

        if (empty($nombre_proveedores)) {
            throw new Exception("El nombre del proveedor es obligatorio");
        }

        // Verificar que el proveedor existe
        $check_sql = "SELECT id_proveedores FROM proveedores WHERE id_proveedores = ?";
        $check_stmt = $conexion->prepare($check_sql);
        $check_stmt->bind_param("i", $id_proveedores);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("El proveedor no existe");
        }
        $check_stmt->close();

        // Preparar la consulta SQL de actualización
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
        
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
        }

        $stmt->bind_param(
            "ssssssssssii",
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
            $id_proveedores
        );

        if ($stmt->execute()) {
            $stmt->close();
            echo json_encode([
                'success' => true,
                'message' => 'Proveedor actualizado exitosamente'
            ]);
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
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}

$conexion->close();
?>
