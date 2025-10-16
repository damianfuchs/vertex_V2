<?php
// Cargar variables de entorno si existen
if (file_exists(__DIR__ . '/../.env.php')) {
    include_once __DIR__ . '/../.env.php';
}

// Define la URL base de tu aplicación
if (!defined('BASE_URL')) {
    // Para desarrollo local (XAMPP/WAMP/MAMP)
    if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
        define('BASE_URL', '/VERTEX-INV');
    } else {
        // Para producción (Hostinger)
        // Si subiste el CONTENIDO directamente a public_html
        define('BASE_URL', '');
        
        // Si subiste la carpeta VERTEX-INV completa, descomentá la siguiente línea:
        // define('BASE_URL', '/VERTEX-INV');
    }
}

// Configuración de zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Configuración de errores según el entorno
if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') {
    // En producción, no mostrar errores en pantalla
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../logs/php-errors.log');
} else {
    // En desarrollo, mostrar todos los errores
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
?>
