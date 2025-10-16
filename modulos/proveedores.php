<?php
include('./db/conexion.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores</title>
    <!-- Incluí Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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

        .badge-activo {
            background-color: #28a745;
            color: white;
        }

        .badge-inactivo {
            background-color: #dc3545;
            color: white;
        }

        .mensaje-flotante {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1055;
            /* más alto que modal para que se vea */
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
            /* verde Bootstrap */
        }

        .mensaje-flotante.alert-danger {
            background-color: #dc3545;
            /* rojo Bootstrap */
        }

        .mensaje-flotante.d-none {
            display: none;
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


        /* DISEÑO DEL MODAL AGREGAR */
        
        #modalAgregarProveedor .modal-content {
            background-color: #ffffff;
            border-radius: 16px;
            border: none;
            box-shadow: 0 6px 18px rgba(31, 44, 76, 0.25);
        }

        #modalAgregarProveedor .modal-header {
            background: linear-gradient(135deg, #1f2c4c 0%, #3b5680 100%);
            color: white;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }

        #modalAgregarProveedor .form-control,
        #modalAgregarProveedor textarea,
        #modalAgregarProveedor .form-select {
            border-radius: 12px;
        }

        #modalAgregarProveedor .form-control:focus,
        #modalAgregarProveedor textarea:focus,
        #modalAgregarProveedor .form-select:focus {
            border-color: #3b5680;
            box-shadow: 0 0 0 0.2rem rgba(59, 86, 128, 0.25);
        }

        #modalAgregarProveedor .btn-success {
            background-color: #3b5680;
            border: none;
            border-radius: 12px;
        }

        #modalAgregarProveedor .btn-success:hover {
            background-color: #2c3f5d;
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-0">
        <h2 class="modern-title mb-4">
            <i class="bi bi-truck"></i> Gestión de Proveedores
        </h2>




        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal"
            data-bs-target="#modalAgregarProveedor"
            style="background: linear-gradient(135deg, #1f2c4c 0%, #3b5680 100%); border: none; color: white;">
            <i class="bi bi-truck"></i> Agregar Proveedor
        </button>


        <div id="mensajeProveedor" class="mensaje-flotante d-none" role="alert"></div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Ubicación</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $consulta = "SELECT * FROM proveedores ORDER BY nombre_proveedores";
                    $resultado = $conexion->query($consulta);

                    if (!$resultado) {
                        echo "<tr><td colspan='8'>Error en la consulta: " . $conexion->error . "</td></tr>";
                    } else {
                        while ($fila = $resultado->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?= $fila['id_proveedores'] ?></td>
                                <td><?= htmlspecialchars($fila['nombre_proveedores'] ?? '') ?></td>
                                <td><?= htmlspecialchars($fila['nombre_contacto_proveedores'] ?? '') ?></td>
                                <td><?= htmlspecialchars($fila['email_proveedores'] ?? '') ?></td>
                                <td><?= htmlspecialchars($fila['telefono_proveedores'] ?? '') ?></td>
                                <td><?= htmlspecialchars($fila['ubicacion_proveedores'] ?? '') ?></td>
                                <td>
                                    <?php if ($fila['estado_proveedores'] == 1): ?>
                                        <span class="badge badge-activo">Activo</span>
                                    <?php else: ?>
                                        <span class="badge badge-inactivo">No Activo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info btn-ver" data-bs-toggle="modal"
                                        data-bs-target="#modalVerProveedor" data-id="<?= $fila['id_proveedores'] ?>"
                                        data-nombre="<?= htmlspecialchars($fila['nombre_proveedores'] ?? '') ?>"
                                        data-contacto="<?= htmlspecialchars($fila['nombre_contacto_proveedores'] ?? '') ?>"
                                        data-email="<?= htmlspecialchars($fila['email_proveedores'] ?? '') ?>"
                                        data-telefono="<?= htmlspecialchars($fila['telefono_proveedores'] ?? '') ?>"
                                        data-direccion="<?= htmlspecialchars($fila['direccion_proveedores'] ?? '') ?>"
                                        data-ubicacion="<?= htmlspecialchars($fila['ubicacion_proveedores'] ?? '') ?>"
                                        data-codigopostal="<?= htmlspecialchars($fila['codigo_postal_proveedores'] ?? '') ?>"
                                        data-sitioweb="<?= htmlspecialchars($fila['sitio_web_proveedores'] ?? '') ?>"
                                        data-horario="<?= htmlspecialchars($fila['horario_atencion_proveedores'] ?? '') ?>"
                                        data-observaciones="<?= htmlspecialchars($fila['observacion_proveedores'] ?? '') ?>"
                                        data-estado="<?= $fila['estado_proveedores'] ?>" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <button class="btn btn-sm btn-warning btn-editar" data-bs-toggle="modal"
                                        data-bs-target="#modalEditarProveedor" data-id="<?= $fila['id_proveedores'] ?>"
                                        data-nombre="<?= htmlspecialchars($fila['nombre_proveedores'] ?? '') ?>"
                                        data-contacto="<?= htmlspecialchars($fila['nombre_contacto_proveedores'] ?? '') ?>"
                                        data-email="<?= htmlspecialchars($fila['email_proveedores'] ?? '') ?>"
                                        data-telefono="<?= htmlspecialchars($fila['telefono_proveedores'] ?? '') ?>"
                                        data-direccion="<?= htmlspecialchars($fila['direccion_proveedores'] ?? '') ?>"
                                        data-ubicacion="<?= htmlspecialchars($fila['ubicacion_proveedores'] ?? '') ?>"
                                        data-codigopostal="<?= htmlspecialchars($fila['codigo_postal_proveedores'] ?? '') ?>"
                                        data-sitioweb="<?= htmlspecialchars($fila['sitio_web_proveedores'] ?? '') ?>"
                                        data-horario="<?= htmlspecialchars($fila['horario_atencion_proveedores'] ?? '') ?>"
                                        data-observaciones="<?= htmlspecialchars($fila['observacion_proveedores'] ?? '') ?>"
                                        data-estado="<?= $fila['estado_proveedores'] ?>" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger btn-eliminar" data-bs-toggle="modal"
                                        data-bs-target="#modalEliminarProveedor" data-id="<?= $fila['id_proveedores'] ?>"
                                        data-nombre="<?= htmlspecialchars($fila['nombre_proveedores'] ?? '') ?>"
                                        title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php
                        endwhile;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Ver Proveedor -->
    <div class="modal fade" id="modalVerProveedor" tabindex="-1" aria-labelledby="modalVerLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalVerLabel">Detalles del Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong> <span id="verNombre">-</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Contacto:</strong> <span id="verContacto">-</span></p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>Email:</strong>
                                <span id="verEmail">-</span>
                                <button type="button" class="btn btn-sm btn-outline-primary ms-2"
                                    onclick="copiarTexto('verEmail', this)" title="Copiar email">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>Teléfono:</strong>
                                <span id="verTelefono"></span>
                                <a id="btnWhatsapp" class="btn btn-sm btn-success ms-2" target="_blank"
                                    style="display:none;">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                            </p>

                        </div>
                        <div class="col-12">
                            <p><strong>Dirección:</strong> <span id="verDireccion">-</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Ubicación:</strong> <span id="verUbicacion">-</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Código Postal:</strong> <span id="verCodigoPostal">-</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Sitio Web:</strong> <span id="verSitioWeb">-</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Estado:</strong> <span id="verEstado">-</span></p>
                        </div>
                        <div class="col-12">
                            <p><strong>Horario de Atención:</strong> <span id="verHorario">-</span></p>
                        </div>
                        <div class="col-12">
                            <p><strong>Observaciones:</strong> <span id="verObservaciones">-</span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Proveedor -->
    <div class="modal fade" id="modalEditarProveedor" tabindex="-1" aria-labelledby="modalEditarLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form id="formEditarProveedor" class="modal-content" method="POST"
                action="modulos/controllers/editar_proveedor.php">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalEditarLabel">Editar Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_proveedores" id="editarId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="editarNombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" id="editarNombre" name="nombre_proveedores"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="editarContacto" class="form-label">Contacto</label>
                            <input type="text" class="form-control" id="editarContacto"
                                name="nombre_contacto_proveedores">
                        </div>
                        <div class="col-md-6">
                            <label for="editarEmail" class="form-label">Email</label>
                            <input type="text" class="form-control" id="editarEmail" name="email_proveedores">
                        </div>
                        <div class="col-md-6">
                            <label for="editarTelefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="editarTelefono" name="telefono_proveedores">
                        </div>
                        <div class="col-12">
                            <label for="editarDireccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="editarDireccion" name="direccion_proveedores">
                        </div>
                        <div class="col-md-6">
                            <label for="editarUbicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="editarUbicacion" name="ubicacion_proveedores">
                        </div>
                        <div class="col-md-6">
                            <label for="editarCodigoPostal" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" id="editarCodigoPostal"
                                name="codigo_postal_proveedores">
                        </div>
                        <div class="col-md-6">
                            <label for="editarSitioWeb" class="form-label">Sitio Web</label>
                            <input type="text" class="form-control" id="editarSitioWeb" name="sitio_web_proveedores">
                        </div>
                        <div class="col-md-6">
                            <label for="editarEstado" class="form-label">Estado *</label>
                            <select class="form-select" id="editarEstado" name="estado_proveedores" required>
                                <option value="">Seleccione una opción</option>
                                <option value="1">Activo</option>
                                <option value="0">No Activo</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="editarHorario" class="form-label">Horario de Atención</label>
                            <input type="text" class="form-control" id="editarHorario"
                                name="horario_atencion_proveedores">
                        </div>
                        <div class="col-12">
                            <label for="editarObservaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="editarObservaciones" name="observacion_proveedores"
                                rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div id="mensajeProveedor" class="alert d-none" role="alert"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Eliminar Proveedor -->
    <div class="modal fade" id="modalEliminarProveedor" tabindex="-1" aria-labelledby="modalEliminarLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formEliminarProveedor" class="modal-content" method="POST"
                action="modulos/controllers/eliminar_proveedor.php">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalEliminarLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <!-- ASEGURAR QUE EL NAME SEA CORRECTO -->
                    <input type="hidden" name="id_proveedores" id="eliminarId" value="">
                    <p>¿Estás seguro que querés eliminar este proveedor?</p>
                    <p><strong><span id="eliminarNombreProveedor">-</span></strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Agregar Proveedor -->
    <div class="modal fade" id="modalAgregarProveedor" tabindex="-1" aria-labelledby="modalAgregarLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form id="formAgregarProveedor" class="modal-content" method="POST"
                action="modulos/controllers/agregar_proveedor.php">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalAgregarLabel">
                        <i class="bi bi-building-add me-2"></i>Agregar Proveedor
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="agregarNombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" id="agregarNombre" name="nombre_proveedores"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="agregarContacto" class="form-label">Contacto</label>
                            <input type="text" class="form-control" id="agregarContacto"
                                name="nombre_contacto_proveedores">
                        </div>
                        <div class="col-md-6">
                            <label for="agregarEmail" class="form-label">Email</label>
                            <input type="text" class="form-control" id="agregarEmail" name="email_proveedores">
                        </div>
                        <div class="col-md-6">
                            <label for="agregarTelefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="agregarTelefono" name="telefono_proveedores">
                        </div>
                        <div class="col-12">
                            <label for="agregarDireccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="agregarDireccion" name="direccion_proveedores">
                        </div>
                        <div class="col-md-6">
                            <label for="agregarUbicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="agregarUbicacion" name="ubicacion_proveedores">
                        </div>
                        <div class="col-md-6">
                            <label for="agregarCodigoPostal" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" id="agregarCodigoPostal"
                                name="codigo_postal_proveedores">
                        </div>
                        <div class="col-md-6">
                            <label for="agregarSitioWeb" class="form-label">Sitio Web</label>
                            <input type="text" class="form-control" id="agregarSitioWeb" name="sitio_web_proveedores">
                        </div>
                        <div class="col-md-6">
                            <label for="agregarEstado" class="form-label">Estado *</label>
                            <select class="form-select" id="agregarEstado" name="estado_proveedores" required>
                                <option value="">Seleccione una opción</option>
                                <option value="1" selected>Activo</option>
                                <option value="0">No Activo</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="agregarHorario" class="form-label">Horario de Atención</label>
                            <input type="text" class="form-control" id="agregarHorario"
                                name="horario_atencion_proveedores" placeholder="Ej: Lunes a Viernes 9:00 - 18:00">
                        </div>
                        <div class="col-12">
                            <label for="agregarObservaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="agregarObservaciones" name="observacion_proveedores"
                                rows="3"></textarea>
                        </div>
                    </div>
                    <div id="mensajeAgregarExito" class="alert alert-success mt-3 mb-0 d-none" role="alert">
                        Proveedor agregado con éxito.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i>Agregar Proveedor
                    </button>
                </div>
            </form>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
