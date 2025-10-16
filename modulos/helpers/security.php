<?php

/**
 * Sanitiza un string para prevenir XSS
 */
function sanitizar_texto($texto) {
    return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
}

/**
 * Valida y sanitiza un número entero
 */
function sanitizar_numero($numero) {
    return filter_var($numero, FILTER_VALIDATE_INT) !== false ? (int)$numero : 0;
}

/**
 * Valida y sanitiza un número decimal
 */
function sanitizar_decimal($numero) {
    return filter_var($numero, FILTER_VALIDATE_FLOAT) !== false ? (float)$numero : 0.0;
}

/**
 * Valida un email
 */
function validar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Genera un token CSRF
 */
function generar_csrf_token() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifica un token CSRF
 */
function verificar_csrf_token($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Previene inyección SQL usando prepared statements
 * Retorna un statement preparado
 */
function preparar_consulta($conexion, $sql) {
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        error_log("Error preparando consulta: " . $conexion->error);
        return false;
    }
    return $stmt;
}
?>
