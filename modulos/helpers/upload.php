<?php

/**
 * Procesa y guarda una imagen subida
 * @param array $archivo - $_FILES['nombre_campo']
 * @param string $carpeta_destino - Carpeta donde guardar (ej: '../img/')
 * @param int $max_size - Tamaño máximo en bytes (default: 5MB)
 * @return array - ['success' => bool, 'filename' => string, 'error' => string]
 */
function procesar_imagen($archivo, $carpeta_destino = '../img/', $max_size = 5242880) {
    $resultado = [
        'success' => false,
        'filename' => '',
        'error' => ''
    ];
    
    // Verificar que se subió un archivo
    if (!isset($archivo) || $archivo['error'] === UPLOAD_ERR_NO_FILE) {
        $resultado['error'] = 'No se subió ningún archivo';
        return $resultado;
    }
    
    // Verificar errores de upload
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        $resultado['error'] = 'Error al subir el archivo';
        return $resultado;
    }
    
    // Verificar tamaño
    if ($archivo['size'] > $max_size) {
        $resultado['error'] = 'El archivo es demasiado grande (máximo 5MB)';
        return $resultado;
    }
    
    // Verificar tipo de archivo
    $tipos_permitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $archivo['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, $tipos_permitidos)) {
        $resultado['error'] = 'Tipo de archivo no permitido. Solo se permiten imágenes (JPG, PNG, GIF, WEBP)';
        return $resultado;
    }
    
    // Generar nombre único
    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombre_archivo = 'producto_' . uniqid() . '_' . time() . '.' . $extension;
    $ruta_completa = $carpeta_destino . $nombre_archivo;
    
    // Crear carpeta si no existe
    if (!file_exists($carpeta_destino)) {
        mkdir($carpeta_destino, 0755, true);
    }
    
    // Mover archivo
    if (move_uploaded_file($archivo['tmp_name'], $ruta_completa)) {
        // Optimizar imagen (reducir tamaño si es muy grande)
        optimizar_imagen($ruta_completa, $mime_type);
        
        $resultado['success'] = true;
        $resultado['filename'] = $nombre_archivo;
    } else {
        $resultado['error'] = 'Error al guardar el archivo';
    }
    
    return $resultado;
}

/**
 * Optimiza una imagen reduciendo su tamaño si es necesario
 */
function optimizar_imagen($ruta, $mime_type, $max_width = 1200, $max_height = 1200, $quality = 85) {
    // Obtener dimensiones originales
    list($width, $height) = getimagesize($ruta);
    
    // Si la imagen es pequeña, no hacer nada
    if ($width <= $max_width && $height <= $max_height) {
        return true;
    }
    
    // Calcular nuevas dimensiones manteniendo proporción
    $ratio = min($max_width / $width, $max_height / $height);
    $new_width = round($width * $ratio);
    $new_height = round($height * $ratio);
    
    // Crear imagen según tipo
    switch ($mime_type) {
        case 'image/jpeg':
        case 'image/jpg':
            $source = imagecreatefromjpeg($ruta);
            break;
        case 'image/png':
            $source = imagecreatefrompng($ruta);
            break;
        case 'image/gif':
            $source = imagecreatefromgif($ruta);
            break;
        case 'image/webp':
            $source = imagecreatefromwebp($ruta);
            break;
        default:
            return false;
    }
    
    if (!$source) {
        return false;
    }
    
    // Crear nueva imagen redimensionada
    $thumb = imagecreatetruecolor($new_width, $new_height);
    
    // Preservar transparencia para PNG y GIF
    if ($mime_type === 'image/png' || $mime_type === 'image/gif') {
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
    }
    
    // Redimensionar
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
    // Guardar según tipo
    switch ($mime_type) {
        case 'image/jpeg':
        case 'image/jpg':
            imagejpeg($thumb, $ruta, $quality);
            break;
        case 'image/png':
            imagepng($thumb, $ruta, 9);
            break;
        case 'image/gif':
            imagegif($thumb, $ruta);
            break;
        case 'image/webp':
            imagewebp($thumb, $ruta, $quality);
            break;
    }
    
    // Liberar memoria
    imagedestroy($source);
    imagedestroy($thumb);
    
    return true;
}

/**
 * Elimina una imagen del servidor
 */
function eliminar_imagen($nombre_archivo, $carpeta = '../img/') {
    if (empty($nombre_archivo)) {
        return false;
    }
    
    $ruta = $carpeta . $nombre_archivo;
    
    if (file_exists($ruta)) {
        return unlink($ruta);
    }
    
    return false;
}
?>
