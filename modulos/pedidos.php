<?php
include('./db/conexion.php');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestión de Pedidos</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        tr:hover {
            cursor: pointer;
            background-color: #f5f5f5;
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

        .modern-title:hover {
            color: #2980b9;
        }

        .modern-title:hover i {
            color: #2980b9;
            transform: scale(1.1) rotate(10deg);
        }

        .mensaje-flotante {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1055;
            min-width: 250px;
            padding: 1rem 1.5rem;
            border-radius: 0.3rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            font-weight: 500;
            color: white;
            opacity: 0.95;
            transition: opacity 0.3s ease;
        }

        .mensaje-flotante.alert-success {
            background-color: #198754;
        }

        .mensaje-flotante.alert-danger {
            background-color: #dc3545;
        }

        .mensaje-flotante.d-none {
            display: none;
        }


        
        /* DISEÑO DEL MODAL AGREGAR */

        #modalAgregarPedido .modal-content {
            background-color: #ffffff;
            border-radius: 16px;
            border: none;
            box-shadow: 0 6px 18px rgba(31, 44, 76, 0.25);
        }

        #modalAgregarPedido .modal-header {
            background: linear-gradient(135deg, #1f2c4c 0%, #3b5680 100%);
            color: white;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }

        #modalAgregarPedido .form-control,
        #modalAgregarPedido .form-select,
        #modalAgregarPedido textarea {
            border-radius: 12px;
        }

        #modalAgregarPedido .form-control:focus,
        #modalAgregarPedido .form-select:focus,
        #modalAgregarPedido textarea:focus {
            border-color: #3b5680;
            box-shadow: 0 0 0 0.2rem rgba(59, 86, 128, 0.25);
        }

        #modalAgregarPedido .btn-success {
            background-color: #3b5680;
            border: none;
            border-radius: 12px;
        }

        #modalAgregarPedido .btn-success:hover {
            background-color: #2c3f5d;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h2 class="modern-title mb-4">
            <i class="bi bi-card-checklist"></i> Gestión de Pedidos
        </h2>

        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarPedido"
            style="background: linear-gradient(135deg, #1f2c4c 0%, #3b5680 100%); border: none; color: white;">
            <i class="bi bi-plus-circle"></i> Agregar Pedido
        </button>

        <div id="mensajePedido" class="alert mensaje-flotante d-none" role="alert"></div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <!-- <th>ID Pedido</th> --> <!-- Lo quitamos -->
                        <th>Nombre Cliente</th>
                        <th>Fecha</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                        <th><i class="bi bi-clipboard-check"></i> Marcar</th> <!-- NUEVA COLUMNA PARA BOTÓN -->
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $consulta = "SELECT * FROM pedidos ORDER BY fecha_pedido DESC";
                    $resultado = $conexion->query($consulta);

                    while ($fila = $resultado->fetch_assoc()):
                        ?>
                        <tr>
                            <!-- <td><?= htmlspecialchars($fila['id_pedido']) ?></td> --> <!-- Lo quitamos -->

                            <td><?= htmlspecialchars($fila['nombre_cliente_pedido']) ?></td>
                            <td><?= htmlspecialchars($fila['fecha_pedido']) ?></td>
                            <td>$<?= number_format($fila['precio_pedido'], 2, ',', '.') ?></td>
                            <td>
                                <?php if ($fila['estado_pedido'] === 'Pendiente'): ?>
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                <?php elseif ($fila['estado_pedido'] === 'Entregado'): ?>
                                    <span class="badge bg-success">Entregado</span>
                                <?php else: ?>
                                    <span><?= htmlspecialchars($fila['estado_pedido']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($fila['observaciones_pedidos']) ?></td>

                            <!-- NUEVA COLUMNA CON BOTÓN "LISTO" -->
                            <td>
                                <?php if ($fila['estado_pedido'] === 'Pendiente'): ?>
                                    <button class="btn btn-sm btn-success btn-marcar-entregado"
                                        data-id="<?= $fila['id_pedido'] ?>">
                                        <i class="bi bi-check-circle"></i> Listo
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted">✓</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalVerPedido"
                                    data-id="<?= $fila['id_pedido'] ?>"
                                    data-nombre-cliente="<?= htmlspecialchars($fila['nombre_cliente_pedido']) ?>"
                                    data-fecha="<?= htmlspecialchars($fila['fecha_pedido']) ?>"
                                    data-precio="<?= htmlspecialchars($fila['precio_pedido']) ?>"
                                    data-estado="<?= htmlspecialchars($fila['estado_pedido']) ?>"
                                    data-observaciones="<?= htmlspecialchars($fila['observaciones_pedidos']) ?>">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modalEditarPedido" data-id="<?= $fila['id_pedido'] ?>"
                                    data-nombre-cliente="<?= htmlspecialchars($fila['nombre_cliente_pedido']) ?>"
                                    data-fecha="<?= htmlspecialchars($fila['fecha_pedido']) ?>"
                                    data-precio="<?= $fila['precio_pedido'] ?>"
                                    data-estado="<?= htmlspecialchars($fila['estado_pedido']) ?>"
                                    data-observaciones="<?= htmlspecialchars($fila['observaciones_pedidos']) ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalEliminarPedido" data-id="<?= $fila['id_pedido'] ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>


        <!-- Modal Ver Pedido -->
        <div class="modal fade" id="modalVerPedido" tabindex="-1" aria-labelledby="modalVerPedidoLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="modalVerPedidoLabel">Detalles del Pedido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nombre Cliente:</strong> <span id="verNombreClientePedido"></span></p>
                        <p><strong>Fecha:</strong> <span id="verFechaPedido"></span></p>
                        <p><strong>Precio:</strong> $<span id="verPrecioPedido"></span></p>
                        <p><strong>Estado:</strong> <span id="verEstadoPedido"></span></p>
                        <p><strong>Observaciones:</strong> <span id="verObservacionesPedido"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Editar Pedido -->
        <div class="modal fade" id="modalEditarPedido" tabindex="-1" aria-labelledby="modalEditarPedidoLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form id="formEditarPedido" class="modal-content" method="POST"
                    action="modulos/controllers/editar_pedido.php" onsubmit="return enviarEditarPedido(event)">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="modalEditarPedidoLabel">Editar Pedido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_pedido" id="editarIdPedido" />

                        <div class="mb-3">
                            <label for="editarNombreCliente" class="form-label">Nombre Cliente</label>
                            <input type="text" class="form-control" id="editarNombreClientePedido"
                                name="nombre_cliente_pedido" required />
                        </div>
                        <div class="mb-3">
                            <label for="editarFechaPedido" class="form-label">Fecha</label>
                            <input type="datetime-local" class="form-control" id="editarFechaPedido" name="fecha_pedido"
                                required />
                        </div>
                        <div class="mb-3">
                            <label for="editarPrecioPedido" class="form-label">Precio</label>
                            <input type="number" step="0.01" class="form-control" id="editarPrecioPedido"
                                name="precio_pedido" required />
                        </div>
                        <div class="mb-3">
                            <label for="editarEstadoPedido" class="form-label">Estado</label>
                            <select class="form-select" id="editarEstadoPedido" name="estado_pedido" required>
                                <option value="">Seleccione estado</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Entregado">Entregado</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editarObservacionesPedido" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="editarObservacionesPedido" name="observaciones_pedidos"
                                rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Eliminar Pedido -->
        <div class="modal fade" id="modalEliminarPedido" tabindex="-1" aria-labelledby="modalEliminarPedidoLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form id="formEliminarPedido" class="modal-content" method="POST"
                    action="modulos/controllers/eliminar_pedido.php" onsubmit="return enviarEliminarPedido(event)">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalEliminarPedidoLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_pedido" id="eliminarIdPedido" />
                        <p>¿Estás seguro que querés eliminar este pedido?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Agregar Pedido -->
        <div class="modal fade" id="modalAgregarPedido" tabindex="-1" aria-labelledby="modalAgregarPedidoLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form id="formAgregarPedido" class="modal-content" method="POST"
                    action="modulos/controllers/agregar_pedido.php" onsubmit="return enviarAgregarPedido(event)">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="modalAgregarPedidoLabel">
                            <i class="bi bi-plus-circle me-2"></i> Agregar Pedido
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="agregarNombreCliente" class="form-label">Nombre Cliente *</label>
                                <input type="text" class="form-control" id="agregarNombreCliente"
                                    name="nombre_cliente_pedido" required />
                            </div>
                            <div class="col-md-6">
                                <label for="agregarPrecioPedido" class="form-label">Precio *</label>
                                <input type="number" step="0.01" class="form-control" id="agregarPrecioPedido"
                                    name="precio_pedido" required />
                            </div>
                            <div class="col-md-6">
                                <label for="agregarEstadoPedido" class="form-label">Estado *</label>
                                <select class="form-select" id="agregarEstadoPedido" name="estado_pedido" required>
                                    <option value="">Seleccione estado</option>
                                    <option value="Pendiente" selected>Pendiente</option>
                                    <option value="Entregado">Entregado</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="agregarFechaPedido" class="form-label">Fecha *</label>
                                <input type="date" class="form-control" id="agregarFechaPedido" name="fecha_pedido"
                                    required />

                            </div>
                            <div class="col-12">
                                <label for="agregarObservacionesPedido" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="agregarObservacionesPedido"
                                    name="observaciones_pedidos" rows="3"></textarea>
                            </div>
                        </div>

                        <div id="mensajeAgregarExito" class="alert alert-success mt-3 mb-0 d-none" role="alert">
                            Pedido agregado con éxito.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i> Agregar Pedido
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS bundle (Popper + Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Tu JS para manejar eventos, validaciones, etc -->
    <script src="./modulos/js/pedidos.js?v=<?= time() ?>" defer></script>
</body>

</html>
