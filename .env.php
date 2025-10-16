<?php
/**
 * Configuración para Hostinger (Producción)
 * 
 * Renombrá este archivo a .env.php cuando subas a Hostinger
 */

// Configuración de Base de Datos HOSTINGER
define('DB_HOST', 'localhost');
define('DB_USER', 'u792283708_admin');
define('DB_PASS', '1+lGbFAKk');
define('DB_NAME', 'u792283708_vertex_inv');

// Configuración de Entorno
define('ENVIRONMENT', 'production');

// URL Base de tu sitio en Hostinger
define('BASE_URL', 'https://floralwhite-cobra-818672.hostingersite.com');

// Zona Horaria (Argentina)
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Ocultar errores en producción y guardar en log
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php-errors.log');
?>
