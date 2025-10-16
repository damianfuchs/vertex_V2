<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include('../db/conexion.php');
include('../config.php'); // ¡¡¡NUEVA LÍNEA!!! Asegúrate de que la ruta sea correcta

ini_set('display_errors', 1);
error_reporting(E_ALL);

$modulo = $_GET['modulo'] ?? '';
$termino = $_GET['termino'] ?? '';

if (!$modulo || !$termino) {
    echo '<div class="alert alert-warning">Parámetros inválidos.</div>';
    exit;
}

$termino = "%{$termino}%";

switch ($modulo) {
    case 'productos':
        $sql = "SELECT p.id_prod, p.codigo_prod, p.nombre_prod, p.descripcion_prod, p.materia_prod, p.stock_prod, p.ubicacion_prod, p.peso_prod, p.imagen_prod, c.nombre_categ AS categoria
            FROM productos p
            LEFT JOIN categorias c ON p.categoria_id = c.id_categ
            WHERE p.nombre_prod LIKE ? OR p.codigo_prod LIKE ? OR p.descripcion_prod LIKE ? OR p.materia_prod LIKE ? OR p.ubicacion_prod LIKE ? OR p.peso_prod LIKE ? OR c.nombre_categ LIKE ?";
        $id_columna = 'id_prod';
        $nombre_columna_para_eliminar = 'nombre_prod'; // Specific name column for products
        break;
    case 'categorias':
        $sql = "SELECT * FROM categorias WHERE nombre_categ LIKE ? OR codigo_categ LIKE ? OR descripcion_categ LIKE ?";
        $id_columna = 'id_categ';
        $nombre_columna_para_eliminar = 'nombre_categ'; // Specific name column for categories
        break;
    case 'proveedores':
        $sql = "SELECT * FROM proveedores WHERE nombre_proveedores LIKE ? OR nombre_contacto_proveedores LIKE ? OR email_proveedores LIKE ? OR telefono_proveedores LIKE ? OR direccion_proveedores LIKE ? OR ubicacion_proveedores LIKE ? OR codigo_postal_proveedores LIKE ? OR sitio_web_proveedores LIKE ? OR horario_atencion_proveedores LIKE ? OR observacion_proveedores LIKE ? OR estado_proveedores LIKE ? OR fecha_creacion_proveedores LIKE ?";
        $id_columna = 'id_proveedores';
        $nombre_columna_para_eliminar = 'nombre_proveedores'; // Specific name column for suppliers
        break;
    case 'clientes':
        $sql = "SELECT * FROM clientes WHERE nombre_clientes LIKE ? OR dni_cuit_clientes LIKE ? OR email_clientes LIKE ? OR telefono_clientes LIKE ? OR direccion_clientes LIKE ? OR localidad_clientes LIKE ? OR tipo_cliente_clientes LIKE ? OR observaciones_clientes LIKE ?";
        $id_columna = 'id_clientes';
        $nombre_columna_para_eliminar = 'nombre_clientes'; // Specific name column for clients
        break;
    case 'pedidos':
        $sql = "SELECT * FROM pedidos WHERE nombre_cliente_pedido LIKE ? OR estado_pedido LIKE ? OR observaciones_pedidos LIKE ? OR fecha_pedido LIKE ? OR precio_pedido LIKE ?";
        $id_columna = 'id_pedido';
        $nombre_columna_para_eliminar = 'nombre_cliente_pedido'; // Specific name column for orders
        break;
    case 'ventas':
        $sql = "SELECT v.id_venta, v.fecha_venta, v.total_venta, c.nombre_clientes AS nombre_cliente, v.estado_venta, v.observaciones_venta
                FROM ventas v
                LEFT JOIN clientes c ON v.cliente_id = c.id_clientes
                WHERE v.fecha_venta LIKE ? OR v.estado_venta LIKE ? OR v.observaciones_venta LIKE ? OR c.nombre_clientes LIKE ? OR v.total_venta LIKE ?";
        $id_columna = 'id_venta';
        $nombre_columna_para_eliminar = 'nombre_cliente'; // Specific name column for sales (client name)
        break;
    default:
        echo '<div class="alert alert-danger">Módulo no válido.</div>';
        exit;
}

// Definir nombres amigables para encabezados según módulo
$columnas_amigables = [
    'productos' => [
        'id_prod' => 'ID',
        'codigo_prod' => 'Código',
        'nombre_prod' => 'Nombre',
        'descripcion_prod' => 'Descripción',
        'materia_prod' => 'Material',
        'stock_prod' => 'Stock',
        'ubicacion_prod' => 'Ubicación',
        'peso_prod' => 'Peso',
        'imagen_prod' => 'Imagen',
        'categoria' => 'Categoría',
    ],
    'categorias' => [
        'id_categ' => 'ID',
        'codigo_categ' => 'Código',
        'nombre_categ' => 'Nombre',
        'descripcion_categ' => 'Descripción',
    ],
    'proveedores' => [
        'id_proveedores' => 'ID',
        'nombre_proveedores' => 'Nombre',
        'nombre_contacto_proveedores' => 'Contacto',
        'telefono_proveedores' => 'Teléfono',
        'email_proveedores' => 'Email',
        'direccion_proveedores' => 'Dirección',
        'ubicacion_proveedores' => 'Ubicación',
        'codigo_postal_proveedores' => 'Codigo Postal',
        'sitio_web_proveedores' => 'Sitio Web',
        'horario_atencion_proveedores' => 'Horario de Atención',
        'observacion_proveedores' => 'Observación',
        'estado_proveedores' => 'Estado',
        'fecha_creacion_proveedores' => 'Fecha de Registro',
    ],
    'clientes' => [
        'id_clientes' => 'ID',
        'nombre_clientes' => 'Nombre',
        'dni_cuit_clientes' => 'DNI / CUIT',
        'email_clientes' => 'Email',
        'telefono_clientes' => 'Teléfono',
        'direccion_clientes' => 'Dirección',
        'localidad_clientes' => 'Localidad',
        'tipo_cliente_clientes' => 'Tipo de Cliente',
        'observaciones_clientes' => 'Observación',
    ],
    'pedidos' => [
        'id_pedido' => 'ID',
        'nombre_cliente_pedido' => 'Cliente',
        'fecha_pedido' => 'Fecha',
        'precio_pedido' => 'Precio',
        'estado_pedido' => 'Estado',
        'observaciones_pedidos' => 'Observaciones',
    ],
    'ventas' => [
        'id_venta' => 'ID',
        'fecha_venta' => 'Fecha',
        'total_venta' => 'Total',
        'nombre_cliente' => 'Cliente',
        'estado_venta' => 'Estado',
        'observaciones_venta' => 'Observaciones',
    ],
];

$stmt = $conexion->prepare($sql);
if (!$stmt) {
    echo '<div class="alert alert-danger">Error en la consulta.</div>';
    exit;
}

$paramsCount = substr_count($sql, '?');
$params = array_fill(0, $paramsCount, $termino);
$types = str_repeat('s', $paramsCount);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<div class="alert alert-info">No se encontraron resultados.</div>';
    exit;
}

echo '<div class="table-responsive">';
echo '<table class="table table-bordered table-striped table-hover">';
echo '<thead class="table-dark"><tr>';
foreach ($result->fetch_fields() as $field) {
    if ($field->name === $id_columna) continue; // Saltear columna ID
    $nombre_columna = $columnas_amigables[$modulo][$field->name] ?? $field->name;
    echo '<th>' . htmlspecialchars($nombre_columna) . '</th>';
}
// Nueva columna acciones
echo '<th>Acciones</th>';
echo '</tr></thead><tbody>';

while ($row = $result->fetch_assoc()) {
    $claseFila = (isset($row['stock_prod']) && ($row['stock_prod'] == 0)) ? 'fila-stock-cero' : '';
    echo "<tr class=\"$claseFila\">";
    foreach ($row as $campo => $valor) {
        if ($campo === $id_columna) continue; // Saltear columna ID
        if (strpos($campo, 'imagen') !== false && !empty($valor)) {
            // RUTA CORREGIDA: USANDO BASE_URL
            $imageSrc = BASE_URL . '/img/' . htmlspecialchars($valor);
            // Ruta absoluta en el servidor para file_exists (esta sigue siendo relativa al PHP)
            $imageServerPath = realpath(__DIR__ . '/../../img/' . $valor);

            if ($imageServerPath && file_exists($imageServerPath)) {
                echo '<td><img src="' . $imageSrc . '" alt="Imagen" style="max-height:50px; border-radius:6px; box-shadow: 0 0 5px rgba(0,0,0,0.1);"></td>';
                echo '<script>console.log("Ruta de imagen en tabla (navegador): ' . $imageSrc . '");</script>';
                echo '<script>console.log("Imagen encontrada en servidor (tabla): ' . $imageServerPath . '");</script>';
            } else {
                echo '<td><span class="text-muted">No img</span></td>'; // O un icono de imagen rota
                echo '<script>console.log("Ruta de imagen en tabla (navegador): ' . $imageSrc . '");</script>';
                echo '<script>console.log("Imagen NO encontrada en servidor (tabla). Ruta intentada: ' . ($imageServerPath ? $imageServerPath : 'NULL/FALSE') . '");</script>';
            }
        } else {
            echo '<td>' . htmlspecialchars($valor ?? '') . '</td>';
        }
    }
    // Agregamos la celda de acciones con botones
    $id = $row[$id_columna];
    // Dynamically get the name for the delete confirmation
    $item_name_for_delete = $row[$nombre_columna_para_eliminar] ?? 'este registro';
    echo '<td>
            <button class="btn btn-sm btn-primary btn-ver" data-id="' . $id . '" title="Ver"><i class="bi bi-eye"></i></button>
            <button class="btn btn-sm btn-warning btn-editar" data-id="' . $id . '" title="Editar"><i class="bi bi-pencil-square"></i></button>
            <button class="btn btn-sm btn-danger btn-eliminar" data-id="' . $id . '" data-nombre="' . htmlspecialchars($item_name_for_delete) . '" title="Eliminar"><i class="bi bi-trash"></i></button>
          </td>';
    echo '</tr>';
}
echo '</tbody></table></div>'; // Cerrar tabla y div responsive
?>
