<?php
include('./db/conexion.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans&display=swap" rel="stylesheet">

    <style>
        .table img {
            max-width: 50px;
            max-height: 50px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        tr:hover {
            cursor: pointer;
            background-color: #f5f5f5;
        }

        .alert-flotante {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .modern-title {
            font-family: 'Nunito Sans', sans-serif;
            font-weight: 600;
            /* un poco más grueso para destacar */
            font-size: 1.6rem;
            color: #2c3e50;
            /* un azul oscuro moderno */
            display: flex;
            align-items: center;
            gap: 0.75rem;
            /* espacio entre icono y texto */
            text-transform: uppercase;
            letter-spacing: 1.5px;
            /* espacio entre letras */
            border-left: 4px solid #3498db;
            /* barra lateral color azul */
            padding-left: 12px;
            transition: color 0.3s ease;
        }

        .modern-title i {
            font-size: 1.8rem;
            color: #3498db;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        /* Hover */
        .modern-title:hover {
            color: #2980b9;
        }

        .modern-title:hover i {
            color: #2980b9;
            transform: scale(1.1) rotate(10deg);
        }

        tr.fila-stock-cero td {
            background-color: rgba(185, 103, 109, 1) !important;
            /* fondo rojo claro */
            color: #fffefeff !important;
            /* texto rojo oscuro */

        }


        /* DISEÑO DEL MODAL AGREGAR */

        #modalAgregarProducto .modal-content {
            background-color: #ffffff;
            border-radius: 16px;
            border: none;
            box-shadow: 0 6px 18px rgba(31, 44, 76, 0.25);
        }

        #modalAgregarProducto .modal-header {
            background: linear-gradient(135deg, #1f2c4c 0%, #3b5680 100%);
            color: white;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }

        #modalAgregarProducto .form-control,
        #modalAgregarProducto .form-select,
        #modalAgregarProducto textarea {
            border-radius: 12px;
        }

        #modalAgregarProducto .form-control:focus,
        #modalAgregarProducto .form-select:focus,
        #modalAgregarProducto textarea:focus {
            border-color: #3b5680;
            box-shadow: 0 0 0 0.2rem rgba(59, 86, 128, 0.25);
        }

        #modalAgregarProducto .btn-success {
            background-color: #3b5680;
            border: none;
            border-radius: 12px;
        }

        #modalAgregarProducto .btn-success:hover {
            background-color: #2c3f5d;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h2 class="modern-title mb-4">
            <i class="bi bi-box"></i>Gestión de Inventario
        </h2>

        <button type="button" class="btn mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto"
            style="background: linear-gradient(135deg, #1f2c4c 0%, #3b5680 100%); border: none; color: white;">
            <i class="bi bi-box-seam"></i> Agregar Producto
        </button>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Material</th>
                        <th>Peso</th>
                        <th>Stock</th>
                        <th>Ubicación</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // JOIN con categorias para obtener nombre_categ
                    $consulta = "
                    SELECT p.*, c.nombre_categ 
                    FROM productos p
                    LEFT JOIN categorias c ON p.categoria_id = c.id_categ 
                    ORDER BY p.id_prod DESC
                    ";
                    $resultado = $conexion->query($consulta);

                    if ($resultado && $resultado->num_rows > 0) {
                        while ($fila = $resultado->fetch_assoc()):
                            ?>
                            <tr data-producto-id="<?= $fila['id_prod'] ?>"
                                class="<?= ($fila['stock_prod'] === '0' || $fila['stock_prod'] === 0) ? 'fila-stock-cero' : '' ?>">
                                <td><?= htmlspecialchars($fila['codigo_prod']) ?></td>
                                <td><?= htmlspecialchars($fila['nombre_prod']) ?></td>
                                <td><?= htmlspecialchars($fila['descripcion_prod']) ?></td>
                                <td><?= htmlspecialchars($fila['nombre_categ'] ?? 'Sin categoría') ?></td>
                                <td><?= htmlspecialchars($fila['materia_prod']) ?></td>
                                <td><?= htmlspecialchars($fila['peso_prod']) ?> kg</td>
                                <td><?= htmlspecialchars($fila['stock_prod']) ?></td>
                                <td><?= htmlspecialchars($fila['ubicacion_prod']) ?></td>
                                <td>
                                    <?php if (!empty($fila['imagen_prod'])): ?>
                                        <img src="./img/<?= htmlspecialchars($fila['imagen_prod']) ?>" alt="Imagen"
                                            style="max-width: 50px;">
                                    <?php else: ?>
                                        Sin imagen
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info btn-ver" data-bs-toggle="modal"
                                        data-bs-target="#modalVerProducto" data-id="<?= $fila['id_prod'] ?>"
                                        data-codigo="<?= htmlspecialchars($fila['codigo_prod']) ?>"
                                        data-nombre="<?= htmlspecialchars($fila['nombre_prod']) ?>"
                                        data-descripcion="<?= htmlspecialchars($fila['descripcion_prod']) ?>"
                                        data-materia="<?= htmlspecialchars($fila['materia_prod']) ?>"
                                        data-peso="<?= htmlspecialchars($fila['peso_prod']) ?>"
                                        data-stock="<?= htmlspecialchars($fila['stock_prod']) ?>"
                                        data-ubicacion="<?= htmlspecialchars($fila['ubicacion_prod']) ?>"
                                        data-imagen="<?= htmlspecialchars($fila['imagen_prod']) ?>"
                                        data-categoria="<?= htmlspecialchars($fila['nombre_categ'] ?? 'Sin categoría') ?>"
                                        data-categoria-id="<?= $fila['categoria_id'] ?>">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <button class="btn btn-sm btn-warning btn-editar" data-bs-toggle="modal"
                                        data-bs-target="#modalEditarProducto" data-id="<?= $fila['id_prod'] ?>"
                                        data-codigo="<?= htmlspecialchars($fila['codigo_prod']) ?>"
                                        data-nombre="<?= htmlspecialchars($fila['nombre_prod']) ?>"
                                        data-descripcion="<?= htmlspecialchars($fila['descripcion_prod']) ?>"
                                        data-materia="<?= htmlspecialchars($fila['materia_prod']) ?>"
                                        data-peso="<?= htmlspecialchars($fila['peso_prod']) ?>"
                                        data-stock="<?= htmlspecialchars($fila['stock_prod']) ?>"
                                        data-ubicacion="<?= htmlspecialchars($fila['ubicacion_prod']) ?>"
                                        data-imagen="<?= htmlspecialchars($fila['imagen_prod']) ?>"
                                        data-categoria-id="<?= $fila['categoria_id'] ?>"
                                        data-categoria="<?= htmlspecialchars($fila['nombre_categ'] ?? 'Sin categoría') ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger btn-eliminar" data-bs-toggle="modal"
                                        data-bs-target="#modalEliminarProducto" data-id="<?= $fila['id_prod'] ?>"
                                        data-nombre="<?= htmlspecialchars($fila['nombre_prod']) ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php
                        endwhile;
                    } else {
                        echo "<tr><td colspan='11' class='text-center'>No hay productos registrados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Ver -->
    <div class="modal fade" id="modalVerProducto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content shadow-lg border-0 rounded-4"
                style="background-color: #fdfdfd; font-family: 'Nunito Sans', sans-serif;">
                <div class="modal-header bg-info text-white rounded-top-4">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-eye me-2"></i>Ver Producto
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="mb-3"><strong>Código:</strong> <span id="verCodigo" class="text-muted ms-2">-</span>
                    </div>
                    <div class="mb-3"><strong>Nombre:</strong> <span id="verNombre" class="text-muted ms-2">-</span>
                    </div>
                    <div class="mb-3"><strong>Descripción:</strong> <span id="verDescripcion"
                            class="text-muted ms-2">-</span></div>
                    <div class="mb-3"><strong>Categoría:</strong> <span id="verCategoria"
                            class="text-muted ms-2">-</span></div>
                    <div class="mb-3"><strong>Material:</strong> <span id="verMateria" class="text-muted ms-2">-</span>
                    </div>
                    <div class="mb-3"><strong>Peso:</strong> <span id="verPeso" class="text-muted ms-2">-</span> kg
                    </div>
                    <div class="mb-3"><strong>Stock:</strong> <span id="verStock" class="text-muted ms-2">-</span></div>
                    <div class="mb-4"><strong>Ubicación:</strong> <span id="verUbicacion"
                            class="text-muted ms-2">-</span></div>
                    <div class="text-center">
                        <img id="verImagen" src="/placeholder.svg" alt="Imagen del producto"
                            class="img-fluid rounded-3 border mt-2" style="max-width: 200px; display: none;">
                        <div id="sinImagen" class="text-muted mt-2">Sin imagen</div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="formEditarProducto" class="modal-content" method="post"
                action="modulos/controllers/editar_producto.php" enctype="multipart/form-data">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_prod" id="editarId">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Código:</label>
                            <input type="text" name="codigo_prod" id="editarCodigo" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre:</label>
                            <input type="text" name="nombre_prod" id="editarNombre" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción:</label>
                            <textarea name="descripcion_prod" id="editarDescripcion" class="form-control"
                                rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Categoría:</label>
                            <select name="categoria_id" id="editarCategoria" class="form-select" required>
                                <option value="">Seleccione una categoría</option>
                                <?php
                                $categorias = $conexion->query("SELECT id_categ, nombre_categ FROM categorias ORDER BY nombre_categ");
                                if ($categorias) {
                                    while ($cat = $categorias->fetch_assoc()):
                                        ?>
                                        <option value="<?= $cat['id_categ'] ?>"><?= htmlspecialchars($cat['nombre_categ']) ?>
                                        </option>
                                        <?php
                                    endwhile;
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Material:</label>
                            <input type="text" name="materia_prod" id="editarMateria" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Peso (kg):</label>
                            <input type="number" step="0.01" name="peso_prod" id="editarPeso" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stock:</label>
                            <input type="number" name="stock_prod" id="editarStock" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ubicación:</label>
                            <input type="text" name="ubicacion_prod" id="editarUbicacion" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Imagen:</label>
                            <input type="file" name="imagen_prod" id="editarImagen" class="form-control"
                                accept="image/*">
                            <small class="text-muted">Imagen actual: <span id="nombreImagenActual">-</span></small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Eliminar -->
    <div class="modal fade" id="modalEliminarProducto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <form id="formEliminarProducto" class="modal-content shadow rounded-4 border-0" method="POST"
                action="modulos/controllers/eliminar_producto.php"
                style="font-family: 'Nunito Sans', sans-serif; background-color: #f8f9fa;">
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title"><i class="bi bi-trash3-fill me-2"></i>Eliminar Producto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="mb-2">¿Estás seguro de que querés eliminar este producto?</p>
                    <p class="mb-3"><strong><span id="eliminarNombreProducto">-</span></strong></p>
                    <div class="alert alert-warning py-2 mb-3" role="alert">
                        <small><i class="bi bi-exclamation-triangle me-1"></i>Esta acción no se puede deshacer</small>
                    </div>
                    <div class="mb-3">
                        <label for="confirmacionEliminar" class="form-label small">
                            Escribí <strong>"si"</strong> para confirmar:
                        </label>
                        <input 
                            type="text" 
                            id="confirmacionEliminar" 
                            class="form-control form-control-sm text-center" 
                            placeholder="Escribí: si"
                            autocomplete="off"
                        >
                    </div>
                    <input type="hidden" name="id_prod" id="eliminarId">
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnEliminarProducto" class="btn btn-danger" disabled>
                        <i class="bi bi-trash me-1"></i>Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Agregar Producto -->
    <div class="modal fade" id="modalAgregarProducto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form id="formAgregarProducto" class="modal-content shadow rounded-4 border-0" method="post"
                action="modulos/controllers/agregar_producto.php" enctype="multipart/form-data"
                style="background-color: #f8f9fa; font-family: 'Nunito Sans', sans-serif;">
                <div class="modal-header bg-success text-white rounded-top-4">
                    <h5 class="modal-title"><i class="bi bi-box-seam me-2"></i>Agregar Producto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Código:</label>
                            <input type="text" name="codigo_prod" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre:</label>
                            <input type="text" name="nombre_prod" class="form-control rounded-3" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción:</label>
                            <textarea name="descripcion_prod" class="form-control rounded-3" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Categoría:</label>
                            <select name="categoria_id" class="form-select rounded-3" required>
                                <option value="">Seleccione una categoría</option>
                                <?php
                                $categorias = $conexion->query("SELECT id_categ, nombre_categ FROM categorias ORDER BY nombre_categ");
                                if ($categorias) {
                                    while ($cat = $categorias->fetch_assoc()):
                                        ?>
                                        <option value="<?= $cat['id_categ'] ?>"><?= htmlspecialchars($cat['nombre_categ']) ?>
                                        </option>
                                        <?php
                                    endwhile;
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Material:</label>
                            <input type="text" name="materia_prod" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Peso (kg):</label>
                            <input type="number" step="0.01" name="peso_prod" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stock:</label>
                            <input type="number" name="stock_prod" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ubicación:</label>
                            <input type="text" name="ubicacion_prod" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Imagen:</label>
                            <input type="file" name="imagen_prod" class="form-control rounded-3" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i>Agregar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts al final del body para evitar problemas de timing -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de productos con timestamp para evitar caché -->
    <script>
        // Eliminar cualquier instancia anterior del ProductosManager
        if (window.productosManager) {
            delete window.productosManager;
        }

        console.log("🔄 Cargando productos.js...");
    </script>
    <script src="./modulos/js/productos.js?v=<?= time() ?>" defer></script>

</body>

</html>
