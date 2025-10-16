<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Búsqueda Global</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
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


        /* Filas sin stock resaltadas */
        .fila-stock-cero {
            background-color: #f8d7da;
            /* rojo suave */
            color: #842029;
        }

        /* Hover para toda la fila */
        .table-hover tbody tr:hover {
            background-color: #e9ecef;
            /* gris claro */
        }

        /* Imagen con borde redondeado y sombra */
        .table img {
            border-radius: 6px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            max-height: 50px;
        }

        /* Margen entre botones */
        .btn-ver,
        .btn-editar,
        .btn-eliminar {
            margin-right: 0.25rem;
        }

        /* Opcional: iconos centrados y tamaño uniforme */
        .btn i {
            font-size: 1.1rem;
            vertical-align: middle;
        }

        #modalVer .modal-content,
        #modalEditar .modal-content,
        #modalEliminar .modal-content {
            font-family: 'Nunito Sans', sans-serif;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="modern-title mb-4">
                    <i class="bi bi-search"></i> Busqueda Global
                </h2>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card text-white border-0 shadow-lg rounded-4"
                    style="max-width: 500px; background: linear-gradient(135deg, #1f2c4c 0%, #3b5680 100%);">
                    <div class="card-body p-4">
                        <!-- Selector de módulo -->
                        <div class="mb-4">
                            <label for="selectModulo" class="form-label fw-semibold">
                                <i class="bi bi-funnel-fill text-light me-2"></i> Módulo
                            </label>
                            <select class="form-select" id="selectModulo">
                                <option value="productos" selected>Productos</option>
                                <option value="categorias">Categorías</option>
                                <option value="proveedores">Proveedores</option>
                                <option value="clientes">Clientes</option>
                                <option value="ventas">Herramientas</option>
                                <option value="pedidos">Pedidos</option>
                            </select>
                        </div>

                        <div class="mb-0">
                            <label for="inputBusqueda" class="form-label fw-semibold">
                                <i class="bi bi-search text-light me-2"></i> Buscar
                            </label>
                            <div class="input-group">
                                <input type="text" id="inputBusqueda" class="form-control" placeholder="Buscar...">
                                <button class="btn btn-light text-primary px-4" type="button" id="btnBuscar">
                                    <i class="bi bi-arrow-right-circle"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>


    <!-- Modal Ver moderno -->
    <div class="modal fade" id="modalVer" tabindex="-1" aria-labelledby="modalVerLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 rounded-4 shadow-lg"
                style="background: linear-gradient(135deg, #1f2c4c 0%, #3b5680 100%);">

                <!-- Encabezado moderno -->
                <div class="modal-header border-0 px-4 pt-4 text-white">
                    <h4 class="modal-title fw-bold text-white" id="modalVerLabel">
                        <i class="bi bi-info-circle-fill me-2 text-white"></i>Detalle del registro
                    </h4>
                    <button type="button" class="btn btn-light btn-sm rounded-circle shadow-sm" data-bs-dismiss="modal"
                        aria-label="Cerrar">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <!-- Contenido -->
                <div class="modal-body px-4 py-3" id="modalVerBody" style="background-color: #f9f9f9;  ">
                    <div class="text-muted text-center">Cargando contenido...</div>
                </div>

                <!-- Footer limpio -->
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-dark rounded-pill px-4 mt-4" data-bs-dismiss="modal">
                        <i class="bi bi-arrow-left-circle me-1"></i> Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>



    <!-- Modal Editar moderno -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">

                <form id="formEditar">

                    <!-- Encabezado con fondo azul y texto blanco -->
                    <div class="modal-header border-0 px-4 pt-4 text-white"
                        style="background: linear-gradient(135deg, #1f2c4c 0%, #3b5680 100%);">
                        <h4 class="modal-title fw-bold text-white" id="modalEditarLabel">
                            <i class="bi bi-pencil-square me-2"></i>Editar registro
                        </h4>
                        <button type="button" class="btn btn-light btn-sm rounded-circle shadow-sm"
                            data-bs-dismiss="modal" aria-label="Cerrar">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <!-- Cuerpo con fondo claro -->
                    <div class="modal-body px-4 py-3" id="modalEditarBody" style="background-color: #f9f9f9;">
                        <div class="text-muted text-center">Cargando formulario...</div>
                    </div>

                    <!-- Footer moderno -->
                    <div class="modal-footer border-0 px-4 pb-4 pt-0" style="background-color: #f9f9f9;">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-check-circle me-1"></i> Guardar cambios
                        </button>
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <!-- Modal Eliminar moderno -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <form id="formEliminarBusqueda" class="modal-content shadow rounded-4 border-0">
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title"><i class="bi bi-trash3-fill me-2"></i> Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="mb-2">¿Estás seguro de que querés eliminar este registro?</p>
                    <p class="mb-3"><strong><span id="itemAEliminarNombre">-</span></strong></p>
                    <div class="alert alert-warning py-2 mb-3" role="alert">
                        <small><i class="bi bi-exclamation-triangle me-1"></i>Esta acción no se puede deshacer</small>
                    </div>
                    <div class="mb-3">
                        <label for="confirmacionEliminarBusqueda" class="form-label small">
                            Escribí <strong>"si"</strong> para confirmar:
                        </label>
                        <input type="text" id="confirmacionEliminarBusqueda"
                            class="form-control form-control-sm text-center" placeholder="Escribí: si"
                            autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="confirmarEliminarBtn" class="btn btn-danger" disabled>
                        <i class="bi bi-trash me-1"></i>Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>


    <div id="resultadosBusqueda" class="m-3"></div>

    <script src="./modulos/js/buscar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>