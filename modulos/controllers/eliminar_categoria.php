<?php
include('../db/conexion.php'); // Ajusta la ruta si es necesario

header('Content-Type: application/json'); // Indicar que la respuesta es JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_categ'];

    $sql = "DELETE FROM categorias WHERE id_categ = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Categoría eliminada exitosamente",
            "id_categ" => $id
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Error al eliminar categoría: " . $conexion->error
        ]);
    }
    
    $stmt->close();
    $conexion->close();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Método de solicitud no permitido."
    ]);
}
?>
