<?php
include('../db/conexion.php'); // Ajusta la ruta si es necesario

header('Content-Type: application/json'); // Indicar que la respuesta es JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_categ'];
    $nombre = $_POST['nombre_categ'];
    $codigo = $_POST['codigo_categ'];
    $descripcion = $_POST['descripcion_categ'];

    $sql = "UPDATE categorias SET 
            nombre_categ = ?, 
            codigo_categ = ?,
            descripcion_categ = ? 
            WHERE id_categ = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $codigo, $descripcion, $id);
    
    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Categoría editada exitosamente",
            "category" => [
                "id_categ" => $id,
                "nombre_categ" => $nombre,
                "codigo_categ" => $codigo,
                "descripcion_categ" => $descripcion
            ]
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Error al editar categoría: " . $conexion->error
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
