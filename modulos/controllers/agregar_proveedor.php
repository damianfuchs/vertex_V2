<?php
include('../db/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Recibir y limpiar los datos del formulario
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
        if (empty($nombre_proveedores)) {
            throw new Exception("El nombre del proveedor es obligatorio");
        }

        // Preparar la consulta SQL
        $sql = "INSERT INTO proveedores (
                    nombre_proveedores, 
                    nombre_contacto_proveedores, 
                    telefono_proveedores, 
                    email_proveedores, 
                    direccion_proveedores, 
                    ubicacion_proveedores, 
                    codigo_postal_proveedores, 
                    sitio_web_proveedores, 
                    horario_atencion_proveedores, 
                    observacion_proveedores, 
                    estado_proveedores,
                    fecha_creacion_proveedores
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $conexion->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
        }

        $stmt->bind_param(
            "ssssssssssi",
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
            $estado_proveedores
        );

        if ($stmt->execute()) {
            $stmt->close();
            echo json_encode([
                'success' => true,
                'message' => 'Proveedor agregado exitosamente'
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
