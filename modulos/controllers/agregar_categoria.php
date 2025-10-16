<?php
include('../db/conexion.php'); // Ajusta la ruta si es necesario

header('Content-Type: application/json'); // Indicar que la respuesta es JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre_categ'];
    $codigo = $_POST['codigo_categ'];
    $descripcion = $_POST['descripcion_categ'];

    $sql = "INSERT INTO categorias (nombre_categ, codigo_categ, descripcion_categ) 
            VALUES (?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $nombre, $codigo, $descripcion);
    
    if ($stmt->execute()) {
        $new_id = $stmt->insert_id;
        echo json_encode([
            "success" => true,
            "message" => "Categoría agregada exitosamente",
            "category" => [
                "id_categ" => $new_id,
                "nombre_categ" => $nombre,
                "codigo_categ" => $codigo,
                "descripcion_categ" => $descripcion
            ]
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Error al agregar categoría: " . $conexion->error
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
