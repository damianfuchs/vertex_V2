<?php
include('../db/conexion.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoria_id = $_POST['categoria_id'] ?? null;
    $codigo_prod = $_POST['codigo_prod'] ?? '';
    $nombre_prod = $_POST['nombre_prod'] ?? '';
    $descripcion_prod = $_POST['descripcion_prod'] ?? '';
    $materia_prod = $_POST['materia_prod'] ?? '';
    $peso_prod = $_POST['peso_prod'] ?? 0;
    $stock_prod = $_POST['stock_prod'] ?? 0;
    $ubicacion_prod = $_POST['ubicacion_prod'] ?? '';

    $imagen_prod = "";  // inicializo como string vacía

    if (isset($_FILES['imagen_prod']) && $_FILES['imagen_prod']['error'] === UPLOAD_ERR_OK) {
        $nombreTmp = $_FILES['imagen_prod']['tmp_name'];
        $nombreArchivo = basename($_FILES['imagen_prod']['name']);
        $ext = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $nombreArchivoNuevo = uniqid('img_') . "." . $ext;
        $rutaDestino = "../../img/" . $nombreArchivoNuevo;

        if (!file_exists('../../img/')) {
            mkdir('../../img/', 0777, true);
        }

        if (move_uploaded_file($nombreTmp, $rutaDestino)) {
            $imagen_prod = $nombreArchivoNuevo;
        }
    }

    $sql = "INSERT INTO productos (categoria_id, codigo_prod, nombre_prod, descripcion_prod, materia_prod, peso_prod, stock_prod, ubicacion_prod, imagen_prod) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al preparar la consulta: ' . $conexion->error
        ]);
        exit;
    }

    $stmt->bind_param("issssdiss", $categoria_id, $codigo_prod, $nombre_prod, $descripcion_prod, $materia_prod, $peso_prod, $stock_prod, $ubicacion_prod, $imagen_prod);
    
    header('Content-Type: application/json');

    if ($stmt->execute()) {
        $new_product_id = $conexion->insert_id;


        $sql_fetch = "SELECT p.*, c.nombre_categ 
                      FROM productos p
                      LEFT JOIN categorias c ON p.categoria_id = c.id_categ 
                      WHERE p.id_prod = ?";
        $stmt_fetch = $conexion->prepare($sql_fetch);
        $stmt_fetch->bind_param("i", $new_product_id);
        $stmt_fetch->execute();
        $result_fetch = $stmt_fetch->get_result();
        $new_product = $result_fetch->fetch_assoc();
        $stmt_fetch->close();

        echo json_encode([
            'success' => true,
            'message' => 'Producto agregado correctamente',
            'product' => $new_product 
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se pudo agregar el producto: ' . $stmt->error
        ]);
    }
    
    $stmt->close();
    $conexion->close();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
