<?php
include('./db/conexion.php'); // Ajusta la ruta si es necesario
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías</title>

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
            font-size: 1.6rem;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-left: 4px solid #3498db;
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
        #modalAgregarCategoria .modal-content {
            background-color: #ffffff;
            border-radius: 16px;
            border: none;
            box-shadow: 0 6px 18px rgba(31, 44, 76, 0.25);
        }

        #modalAgregarCategoria .modal-header {
            background: linear-gradient(135deg, #1f2c4c 0%, #3b5680 100%);
            color: white;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }

        #modalAgregarCategoria .form-control,
        #modalAgregarCategoria textarea,
        #modalAgregarCategoria .form-select {
            border-radius: 12px;
        }

        #modalAgregarCategoria .form-control:focus,
        #modalAgregarCategoria textarea:focus,
        #modalAgregarCategoria .form-select:focus {
            border-color: #3b5680;
            box-shadow: 0 0 0 0.2rem rgba(59, 86, 128, 0.25);
        }

        #modalAgregarCategoria .btn-success {
            background-color: #3b5680;
            border: none;
            border-radius: 12px;
        }

        #modalAgregarCategoria .btn-success:hover {
            background-color: #2c3f5d;
        }
    </style>

</head>

<body>
    <div class="container-fluid ">
        <h2 class="modern-title mb-4">
            <i class="bi bi-tags"></i> Gestión de Categorías
        </h2>

        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarCategoria"
            style="background: linear-gradient(135deg, #1f2c4c 0%, #3b5680 100%); border: none; color: white;">
            <i class="bi bi-tag"></i> Agregar Categoría
        </button>

        <div id="mensajeCategoria" class="alert mensaje-flotante d-none" role="alert"></div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="categoriasTableBody">
                    <?php
                    $consulta = "SELECT * FROM categorias";
                    $resultado = $conexion->query($consulta);

                    while ($fila = $resultado->fetch_assoc()):
                        ?>
                        <tr data-id="<?= $fila['id_categ'] ?>">
                            <td><?= htmlspecialchars($fila['codigo_categ']) ?></td>
                            <td><?= htmlspecialchars($fila['nombre_categ']) ?></td>
                            <td><?= htmlspecialchars($fila['descripcion_categ']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalVerCategoria"
                                    data-id="<?= $fila['id_categ'] ?>"
                                    data-codigo="<?= htmlspecialchars($fila['codigo_categ']) ?>"
                                    data-nombre="<?= htmlspecialchars($fila['nombre_categ']) ?>"
                                    data-descripcion="<?= htmlspecialchars($fila['descripcion_categ']) ?>">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modalEditarCategoria" data-id="<?= $fila['id_categ'] ?>"
                                    data-codigo="<?= htmlspecialchars($fila['codigo_categ']) ?>"
                                    data-nombre="<?= htmlspecialchars($fila['nombre_categ']) ?>"
                                    data-descripcion="<?= htmlspecialchars($fila['descripcion_categ']) ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalEliminarCategoria" data-id="<?= $fila['id_categ'] ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Ver Categoría -->
        <div class="modal fade" id="modalVerCategoria" tabindex="-1" aria-labelledby="modalVerLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="modalVerLabel">Detalles de la Categoría</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Código:</strong> <span id="verCodigo"></span></p>
                        <p><strong>Nombre:</strong> <span id="verNombre"></span></p>
                        <p><strong>Descripción:</strong> <span id="verDescripcion"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Editar Categoría -->
        <div class="modal fade" id="modalEditarCategoria" tabindex="-1" aria-labelledby="modalEditarLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form id="formEditarCategoria" class="modal-content" method="POST"
                    action="modulos/controllers/editar_categoria.php">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="modalEditarLabel">Editar Categoría</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_categ" id="editarId">

                        <div class="mb-3">
                            <label for="editarCodigo" class="form-label">Código</label>
                            <input type="text" class="form-control" id="editarCodigo" name="codigo_categ">
                        </div>
                        <div class="mb-3">
                            <label for="editarNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="editarNombre" name="nombre_categ" required>
                        </div>
                        <div class="mb-3">
                            <label for="editarDescripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="editarDescripcion" name="descripcion_categ"
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

        <!-- Modal Eliminar Categoría -->
        <div class="modal fade" id="modalEliminarCategoria" tabindex="-1" aria-labelledby="modalEliminarLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form id="formEliminarCategoria" class="modal-content" method="POST"
                    action="modulos/controllers/eliminar_categoria.php">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalEliminarLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_categ" id="eliminarId">
                        <p>¿Estás seguro que querés eliminar esta categoría?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Agregar Categoría -->
        <div class="modal fade" id="modalAgregarCategoria" tabindex="-1" aria-labelledby="modalAgregarLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form id="formAgregarCategoria" class="modal-content" method="POST"
                    action="modulos/controllers/agregar_categoria.php">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="modalAgregarLabel">
                            <i class="bi bi-tag"></i> Agregar Categoría
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="agregarCodigo" class="form-label">Código</label>
                                <input type="text" class="form-control" id="agregarCodigo" name="codigo_categ">
                            </div>
                            <div class="col-12">
                                <label for="agregarNombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="agregarNombre" name="nombre_categ"
                                    required>
                            </div>
                            <div class="col-12">
                                <label for="agregarDescripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="agregarDescripcion" name="descripcion_categ"
                                    rows="3"></textarea>
                            </div>
                        </div>
                        <div id="mensajeAgregarExito" class="alert alert-success mt-3 mb-0 d-none" role="alert">
                            Categoría agregada con éxito.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i>Agregar Categoría
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./modulos/js/categorias.js?v=<?= time() ?>" defer></script>

</body>

</html>
