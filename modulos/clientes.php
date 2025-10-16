<?php
include('./db/conexion.php'); ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Incluí Bootstrap (si no lo tenés aún en tu layout principal) -->

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


        /* DISEÑO DEL MODAL AGREGAR */

        #modalAgregarCliente .modal-content {
            background-color: #ffffff;
            border-radius: 16px;
            border: none;
            box-shadow: 0 6px 18px rgba(31, 44, 76, 0.25);
        }

        #modalAgregarCliente .modal-header {
            background: linear-gradient(135deg, #1f2c4c 0%, #3b5680 100%);
            color: white;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }

        #modalAgregarCliente .form-control,
        #modalAgregarCliente textarea,
        #modalAgregarCliente .form-select {
            border-radius: 12px;
        }

        #modalAgregarCliente .form-control:focus,
        #modalAgregarCliente textarea:focus,
        #modalAgregarCliente .form-select:focus {
            border-color: #3b5680;
            box-shadow: 0 0 0 0.2rem rgba(59, 86, 128, 0.25);
        }

        #modalAgregarCliente .btn-success {
            background-color: #3b5680;
            border: none;
            border-radius: 12px;
        }

        #modalAgregarCliente .btn-success:hover {
            background-color: #2c3f5d;
        }
    </style>

</head>

<body>
    <div class="container-fluid ">
        <h2 class="modern-title mb-4">
            <i class="bi bi-person"></i> Gestión de Clientes
        </h2>


        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarCliente"
            style="background: linear-gradient(135deg, #1f2c4c 0%, #3b5680 100%); border: none; color: white;">
            <i class="bi bi-person-fill-add"></i> Agregar Cliente
        </button>


        <div id="mensajeCliente" class="alert mensaje-flotante d-none" role="alert"></div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>

                        <th>Nombre</th>
                        <th>DNI / CUIT</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Localidad</th>
                        <th>Tipo Cliente</th>
                        <th>Observaciones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $consulta = "SELECT * FROM clientes";
                    $resultado = $conexion->query($consulta);

                    while ($fila = $resultado->fetch_assoc()):
                        ?>
                        <tr>

                            <td><?= htmlspecialchars($fila['nombre_clientes']) ?></td>
                            <td><?= htmlspecialchars($fila['dni_cuit_clientes']) ?></td>
                            <td><?= htmlspecialchars($fila['email_clientes']) ?></td>
                            <td><?= htmlspecialchars($fila['telefono_clientes']) ?></td>
                            <td><?= htmlspecialchars($fila['direccion_clientes']) ?></td>
                            <td><?= htmlspecialchars($fila['localidad_clientes']) ?></td>
                            <td><?= htmlspecialchars($fila['tipo_cliente_clientes']) ?></td>
                            <td><?= htmlspecialchars($fila['observaciones_clientes']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalVerCliente"
                                    data-id="<?= $fila['id_clientes'] ?>"
                                    data-nombre="<?= htmlspecialchars($fila['nombre_clientes']) ?>"
                                    data-dni="<?= htmlspecialchars($fila['dni_cuit_clientes']) ?>"
                                    data-email="<?= htmlspecialchars($fila['email_clientes']) ?>"
                                    data-telefono="<?= htmlspecialchars($fila['telefono_clientes']) ?>"
                                    data-direccion="<?= htmlspecialchars($fila['direccion_clientes']) ?>"
                                    data-localidad="<?= htmlspecialchars($fila['localidad_clientes']) ?>"
                                    data-tipo="<?= htmlspecialchars($fila['tipo_cliente_clientes']) ?>"
                                    data-observaciones="<?= htmlspecialchars($fila['observaciones_clientes']) ?>">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modalEditarCliente" data-id="<?= $fila['id_clientes'] ?>"
                                    data-nombre="<?= htmlspecialchars($fila['nombre_clientes']) ?>"
                                    data-dni="<?= htmlspecialchars($fila['dni_cuit_clientes']) ?>"
                                    data-email="<?= htmlspecialchars($fila['email_clientes']) ?>"
                                    data-telefono="<?= htmlspecialchars($fila['telefono_clientes']) ?>"
                                    data-direccion="<?= htmlspecialchars($fila['direccion_clientes']) ?>"
                                    data-localidad="<?= htmlspecialchars($fila['localidad_clientes']) ?>"
                                    data-tipo="<?= htmlspecialchars($fila['tipo_cliente_clientes']) ?>"
                                    data-observaciones="<?= htmlspecialchars($fila['observaciones_clientes']) ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalEliminarCliente" data-id="<?= $fila['id_clientes'] ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>



        <!-- Modal Ver Cliente -->
        <div class="modal fade" id="modalVerCliente" tabindex="-1" aria-labelledby="modalVerLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="modalVerLabel">Detalles del Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nombre:</strong> <span id="verNombre"></span></p>
                        <p><strong>DNI / CUIT:</strong> <span id="verDni"></span></p>

                        <!-- Email con botón copiar -->
                        <p>
                            <strong>Email:</strong>
                            <span id="verEmail"></span>
                            <button type="button" class="btn btn-sm btn-outline-primary ms-2"
                                onclick="copiarTexto('verEmail', this)" title="Copiar email">
                                <i class="bi bi-clipboard"></i> Copiar
                            </button>
                        </p>

                        <!-- Teléfono con botón copiar -->
                        <p>
                            <strong>Teléfono:</strong>
                            <span id="verTelefono"></span>
                            <a href="#" id="btnWhatsapp" target="_blank" class="btn btn-sm btn-success ms-2"
                                title="Abrir WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        </p>

                        <p><strong>Dirección:</strong> <span id="verDireccion"></span></p>
                        <p><strong>Localidad:</strong> <span id="verLocalidad"></span></p>
                        <p><strong>Tipo Cliente:</strong> <span id="verTipo"></span></p>
                        <p><strong>Observaciones:</strong> <span id="verObservaciones"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal Editar Cliente -->
        <div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-labelledby="modalEditarLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form id="formEditarCliente" class="modal-content" method="POST"
                    action="modulos/controllers/editar_cliente.php" onsubmit="return enviarEditarCliente(event)">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="modalEditarLabel">Editar Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_clientes" id="editarId">

                        <div class="mb-3">
                            <label for="editarNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="editarNombre" name="nombre_clientes" required>
                        </div>
                        <div class="mb-3">
                            <label for="editarDni" class="form-label">DNI / CUIT</label>
                            <input type="text" class="form-control" id="editarDni" name="dni_cuit_clientes">
                        </div>
                        <div class="mb-3">
                            <label for="editarEmail" class="form-label">Email</label>
                            <input type="text" class="form-control" id="editarEmail" name="email_clientes">
                        </div>
                        <div class="mb-3">
                            <label for="editarTelefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="editarTelefono" name="telefono_clientes">
                        </div>
                        <div class="mb-3">
                            <label for="editarDireccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="editarDireccion" name="direccion_clientes">
                        </div>
                        <div class="mb-3">
                            <label for="editarLocalidad" class="form-label">Localidad</label>
                            <input type="text" class="form-control" id="editarLocalidad" name="localidad_clientes">
                        </div>
                        <div class="mb-3">
                            <label for="editarTipo" class="form-label">Tipo Cliente</label>
                            <select class="form-select" id="editarTipo" name="tipo_cliente_clientes" required>
                                <option value="">Seleccione una opción</option>
                                <option value="Minorista">Minorista</option>
                                <option value="Mayorista">Mayorista</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editarObservaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="editarObservaciones" name="observaciones_clientes"
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


        <!-- Modal Eliminar Cliente -->
        <div class="modal fade" id="modalEliminarCliente" tabindex="-1" aria-labelledby="modalEliminarLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form id="formEliminarCliente" class="modal-content" method="POST"
                    action="modulos/controllers/eliminar_cliente.php" onsubmit="return enviarEliminarCliente(event)">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalEliminarLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_clientes" id="eliminarId">
                        <p>¿Estás seguro que querés eliminar este cliente?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Agregar Cliente -->
        <div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-labelledby="modalAgregarLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form id="formAgregarCliente" class="modal-content" method="POST"
                    action="modulos/controllers/agregar_cliente.php" onsubmit="return enviarAgregarCliente(event)">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="modalAgregarLabel">
                            <i class="bi bi-person-fill-add me-2"></i>Agregar Cliente
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="agregarNombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="agregarNombre" name="nombre_clientes"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="agregarDni" class="form-label">DNI / CUIT</label>
                                <input type="text" class="form-control" id="agregarDni" name="dni_cuit_clientes">
                            </div>
                            <div class="col-md-6">
                                <label for="agregarEmail" class="form-label">Email</label>
                                <input type="text" class="form-control" id="agregarEmail" name="email_clientes">
                            </div>
                            <div class="col-md-6">
                                <label for="agregarTelefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="agregarTelefono" name="telefono_clientes">
                            </div>
                            <div class="col-12">
                                <label for="agregarDireccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="agregarDireccion" name="direccion_clientes">
                            </div>
                            <div class="col-md-6">
                                <label for="agregarLocalidad" class="form-label">Localidad</label>
                                <input type="text" class="form-control" id="agregarLocalidad" name="localidad_clientes">
                            </div>
                            <div class="col-md-6">
                                <label for="agregarTipo" class="form-label">Tipo Cliente *</label>
                                <select class="form-select" id="agregarTipo" name="tipo_cliente_clientes" required>
                                    <option value="">Seleccione una opción</option>
                                    <option value="Minorista">Minorista</option>
                                    <option value="Mayorista">Mayorista</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="agregarObservaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="agregarObservaciones" name="observaciones_clientes"
                                    rows="3"></textarea>
                            </div>
                        </div>
                        <div id="mensajeAgregarExito" class="alert alert-success mt-3 mb-0 d-none" role="alert">
                            Cliente agregado con éxito.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i>Agregar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>







    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./modulos/js/clientes.js?v=<?= time() ?>" defer></script>



</body>

</html>
