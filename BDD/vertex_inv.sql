-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-10-2025 a las 17:39:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `vertex_inv`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categ` int(11) NOT NULL,
  `codigo_categ` varchar(50) NOT NULL,
  `nombre_categ` varchar(100) NOT NULL,
  `descripcion_categ` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categ`, `codigo_categ`, `nombre_categ`, `descripcion_categ`) VALUES
(174, 'AAA', 'ad', 'HOLA'),
(175, 'fgr', 'das', 'das'),
(176, 'DD1321', '3', 'bddd'),
(177, 'jhgfdas', 'dd', 'NODDDD'),
(222, 'CAT_123', 'Categoria1', 'Categoria de Prueba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_clientes` int(11) NOT NULL,
  `nombre_clientes` varchar(100) DEFAULT NULL,
  `dni_cuit_clientes` varchar(20) DEFAULT NULL,
  `email_clientes` varchar(100) DEFAULT NULL,
  `telefono_clientes` varchar(20) DEFAULT NULL,
  `direccion_clientes` varchar(150) DEFAULT NULL,
  `localidad_clientes` varchar(100) DEFAULT NULL,
  `tipo_cliente_clientes` varchar(20) DEFAULT NULL,
  `observaciones_clientes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_clientes`, `nombre_clientes`, `dni_cuit_clientes`, `email_clientes`, `telefono_clientes`, `direccion_clientes`, `localidad_clientes`, `tipo_cliente_clientes`, `observaciones_clientes`) VALUES
(1, 'Juan Pérez', '12345678', 'juan.perez@gmail.com', '1234567890', 'Calle Falsa 123', 'Ciudad A', 'Otro', 'Cliente frecuente'),
(2, 'María L', '87654321', 'maria.lopez@mail.com', '0111111', 'Avenida Siempre Viva 742', 'Ciudad B', 'Minorista', 'Compra al por mayor'),
(3, 'Carlos Gómez', '11223344', 'carlos.gomez@mail.com', '1122334455', 'Boulevard Central 45', 'DDDD', 'Otro', 'Comprador 1'),
(4, 'Ana Martínez', '44332211', 'ana.martinez@mail.com', '5544332211', 'Calle Nueva 89', 'Ciudad A', 'Minorista', 'Primer compra'),
(6, 'Damian Emmanuel', '4199999', 'damian@hotmail.com', '3435515310', 'sdasdas', 'dsada', 'Minorista', 'dasdasas'),
(8, 'Damian', 'dda', 'dsadsada@2131', 'dadad', 'asdasdada', 'd', 'Minorista', '1111DDDD'),
(27, 'Jose NIII', '222', 'jose@hotmail.com', '222', '', '', 'Mayorista', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `nombre_cliente_pedido` varchar(100) NOT NULL,
  `fecha_pedido` date DEFAULT NULL,
  `precio_pedido` decimal(10,2) DEFAULT NULL,
  `estado_pedido` enum('Pendiente','Entregado') DEFAULT 'Pendiente',
  `observaciones_pedidos` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `nombre_cliente_pedido`, `fecha_pedido`, `precio_pedido`, `estado_pedido`, `observaciones_pedidos`) VALUES
(2, 'María Gómez', '2025-07-26', 2300.50, 'Entregado', 'Cliente frecuente'),
(6, 'Damian Fuchs', '2025-07-31', 2000.00, 'Entregado', 'dasdasdsa'),
(7, 'Emmanuel Fuchs', '2025-08-01', 200000.00, 'Entregado', 'Trabajos'),
(8, 'Damian Emmanuel', '2025-08-01', 100000.00, 'Pendiente', 'Trabajos en casa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_prod` int(11) NOT NULL,
  `codigo_prod` varchar(100) NOT NULL,
  `nombre_prod` varchar(300) NOT NULL,
  `descripcion_prod` varchar(500) NOT NULL,
  `materia_prod` varchar(300) NOT NULL,
  `stock_prod` int(11) NOT NULL,
  `ubicacion_prod` varchar(500) NOT NULL,
  `peso_prod` double NOT NULL,
  `imagen_prod` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_prod`, `codigo_prod`, `nombre_prod`, `descripcion_prod`, `materia_prod`, `stock_prod`, `ubicacion_prod`, `peso_prod`, `imagen_prod`, `categoria_id`) VALUES
(110, 'dsad', 'asdas', 'DDDD', 'dsad', 0, '0', 231, 'img_68939d750210f.png', 222),
(111, 'PROD123', 'dsad', 'ADA', 'dsad', 31231, 'JKJJDASDASDSA sASDADASDAS dsaD', 231, 'producto_111_1754512885.png', 176),
(117, 'P3322', 'Prueba', 'Producto de Prueba ', 'Aluminio', 0, 'JJJJJJJJJJJJJJJJJJJ IIIIIIIIIIIIIIIII SSSSSSSSSSSSS', 2, 'img_6893c60aa573d.jpg', 222),
(118, '2222', 'dadadad', 'dadada', 'dada', 3132131, 'JAAA dsadas', 231, '', 222),
(119, 'JUJUJU123', 'dsadas', 'dadasda', 'dsadas', 22, 'HOLA', 22, '', 222);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedores` int(11) NOT NULL,
  `nombre_proveedores` varchar(100) NOT NULL,
  `nombre_contacto_proveedores` varchar(100) DEFAULT NULL,
  `telefono_proveedores` varchar(20) DEFAULT NULL,
  `email_proveedores` varchar(100) DEFAULT NULL,
  `direccion_proveedores` varchar(150) DEFAULT NULL,
  `ubicacion_proveedores` varchar(100) DEFAULT NULL,
  `codigo_postal_proveedores` varchar(10) DEFAULT NULL,
  `sitio_web_proveedores` varchar(100) DEFAULT NULL,
  `horario_atencion_proveedores` varchar(100) DEFAULT NULL,
  `observacion_proveedores` text DEFAULT NULL,
  `estado_proveedores` tinyint(4) DEFAULT 1,
  `fecha_creacion_proveedores` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedores`, `nombre_proveedores`, `nombre_contacto_proveedores`, `telefono_proveedores`, `email_proveedores`, `direccion_proveedores`, `ubicacion_proveedores`, `codigo_postal_proveedores`, `sitio_web_proveedores`, `horario_atencion_proveedores`, `observacion_proveedores`, `estado_proveedores`, `fecha_creacion_proveedores`) VALUES
(2, 'TecnoInsumos S.A.', 'Carlos Méndez C', '11-4567-8900', 'ventas@tecnoinsumos.com.ar', 'Calle Falsa 742', 'San Justo, Buenos Aires', '1754', '', 'Lunes a Sábado, 09:00 a 18:00', 'Demoran 48hs en enviar factura electrónica. NUEVO', 0, '2025-07-30 17:58:29'),
(3, 'Café del Sur', 'Luciana Torres', '3435515310', 'contacto@cafedelsur.com', 'Ruta 9 Km 20', 'Yerba Buena, Tucumán', '4107', '', 'Martes a Viernes, 10:00 a 16:00', 'Solo atienden por WhatsApp', 1, '2025-07-30 17:58:29'),
(29, 'dasd', 'adasda', 'dasdasd', 'dasda', 'adasdasd', 'adas', 'dasdasd', 'asdasd', '', '', 1, '2025-07-31 15:34:55'),
(30, 'gdfsf', 'fsfsd', 'dfds', 'sfdsf@dsa.com', '', '', '', '', '', 'DDD', 1, '2025-07-31 18:42:27'),
(31, 'PRUEBA', '', '', '', '', '', '', '', '', '', 0, '2025-08-06 14:50:46'),
(40, 'KIO', '', '', '', '', '', '', '', '', '', 1, '2025-08-06 16:31:15');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categ`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_clientes`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_prod`),
  ADD KEY `fk_categoria` (`categoria_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedores`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categ` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_clientes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_prod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedores` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id_categ`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
