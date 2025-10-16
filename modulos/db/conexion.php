<?php
// Configuración para entorno de producción (Hostinger)
if (file_exists(__DIR__ . '/../../.env.php')) {
    include_once __DIR__ . '/../../.env.php';
}

// Valores por defecto para desarrollo local
$host = getenv('DB_HOST') ?: (defined('DB_HOST') ? DB_HOST : 'localhost');
$usuario = getenv('DB_USER') ?: (defined('DB_USER') ? DB_USER : 'root');
$contrasenia = getenv('DB_PASS') ?: (defined('DB_PASS') ? DB_PASS : '');
$basedatos = getenv('DB_NAME') ?: (defined('DB_NAME') ? DB_NAME : 'vertex_inv');

// Crear conexión con manejo de errores mejorado
try {
    $conexion = new mysqli($host, $usuario, $contrasenia, $basedatos);
    
    // Configurar charset para evitar problemas con caracteres especiales
    $conexion->set_charset("utf8mb4");
    
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión: " . $conexion->connect_error);
    }
} catch (Exception $e) {
    // En producción, no mostrar detalles del error
    if (getenv('ENVIRONMENT') === 'production' || (defined('ENVIRONMENT') && ENVIRONMENT === 'production')) {
        die("Error al conectar con la base de datos. Por favor, contacte al administrador.");
    } else {
        die("Conexión fallida: " . $e->getMessage());
    }
}
?>
