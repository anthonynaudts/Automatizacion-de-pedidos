-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-08-2023 a las 00:13:21
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ventas_php`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos_pedidos`
--

CREATE TABLE `articulos_pedidos` (
  `idPedido` int(11) NOT NULL,
  `idProd` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precioUnitario` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `telefono` varchar(25) NOT NULL,
  `direccion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `telefono`, `direccion`) VALUES
(1, 'Jose', '8294564564', 'Santiago'),
(2, 'Derril Aggs', '6078385652', 'Santiago'),
(3, 'Johannes Giorgeschi', '6033331067', 'Puerto Plata'),
(4, 'Pascale Farnham', '2085901902', 'Santiago'),
(5, 'Pepe Theuss', '7703903918', 'Santiago'),
(6, 'Morry Paulino', '1125495731', 'Santiago'),
(7, 'Conant Bernot', '4795074629', 'Santiago'),
(8, 'Alberto Lodden', '9555080222', 'Santiago'),
(9, 'Fidelity Ludford', '3212793170', 'Santiago'),
(10, 'Dimitry McKeefry', '7329232264', 'Santiago'),
(11, 'Marjory Mercik', '7567561694', 'Santiago'),
(12, 'Cherin Firebrace', '8737397015', 'Santiago'),
(13, 'Phillis Whitwam', '6986077022', 'Santiago'),
(14, 'Andrea De Bruyn', '6388016847', 'Santiago'),
(15, 'Elisabetta Delete', '5821212867', 'Santiago'),
(16, 'Joey Bertelmot', '3257493077', 'Santiago');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `idEstado` int(11) NOT NULL,
  `estado` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`idEstado`, `estado`) VALUES
(1, 'Enviado al suplidor'),
(2, 'Recibido por el suplidor'),
(3, 'Pedido recibido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `idPedido` int(11) NOT NULL,
  `fechaPedido` date NOT NULL,
  `fechaRecepcion` date DEFAULT NULL,
  `montoPedido` decimal(18,2) NOT NULL,
  `idEstado` int(2) NOT NULL,
  `idSuplidor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prioridad_productos`
--

CREATE TABLE `prioridad_productos` (
  `idPrioridad` int(11) NOT NULL,
  `prioridad` varchar(50) NOT NULL,
  `tiempoLlegadaDias` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prioridad_productos`
--

INSERT INTO `prioridad_productos` (`idPrioridad`, `prioridad`, `tiempoLlegadaDias`) VALUES
(1, 'Menor costo', 1),
(2, 'Menor tiempo de entrega', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `compra` decimal(8,2) NOT NULL,
  `venta` decimal(8,2) NOT NULL,
  `existencia` int(11) NOT NULL,
  `cantMin` int(11) NOT NULL,
  `cantFija` int(11) NOT NULL,
  `idPrioridad` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `compra`, `venta`, `existencia`, `cantMin`, `cantFija`, `idPrioridad`) VALUES
(1, '5213658', 'Set de palos de golf Titleist T300', 30000.00, 32500.00, 2, 2, 6, 2),
(2, '369888', 'Guante de béisbol Wilson A2000', 500.00, 700.00, 9, 9, 18, 1),
(3, '1208674', 'Raqueta de tenis Babolat Pure Strike 18x20 3rd Gen', 7000.00, 9996.00, 10, 10, 13, 2),
(4, '9875210', 'Raqueta de tenis Yonex VCORE Pro 97HD 18x20', 5000.00, 8500.00, 7, 5, 15, 2),
(5, '5423681', 'Set de palos de golf Callaway Strata Ultimate', 27900.00, 30400.00, 2, 3, 8, 1),
(6, '62346324', 'Pelota de baloncesto - Tamaño 7 (reglamentario)', 3000.00, 3500.00, 26, 15, 30, 1),
(7, '5467536', 'Pelota de voleibol - Tamaño 5 (estándar)', 750.00, 900.00, 12, 10, 25, 2),
(8, '45375457', 'Red de tenis - Tamaño estándar', 3000.51, 3500.51, 11, 3, 11, 2),
(10, '124588', 'Guante de béisbol Easton Professional Collection', 610.00, 780.00, 5, 4, 12, 1),
(26, '124589', 'Bat de béisbol Rawlings Quatro Pro T-8', 200.00, 250.00, 30, 10, 30, 2),
(39, '202308163365', 'Pelota de béisbol Wilson A1030', 60.00, 90.00, 80, 100, 400, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_suplidor`
--

CREATE TABLE `productos_suplidor` (
  `idProdTienda` int(11) NOT NULL,
  `idProdSuplidor` int(11) NOT NULL,
  `cantDisponible` int(11) NOT NULL,
  `precioProd` decimal(8,2) NOT NULL,
  `tiempoEntregaProd` int(2) NOT NULL,
  `idSuplidor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_suplidor`
--

INSERT INTO `productos_suplidor` (`idProdTienda`, `idProdSuplidor`, `cantDisponible`, `precioProd`, `tiempoEntregaProd`, `idSuplidor`) VALUES
(1, 15, 60, 30000.00, 15, 1),
(2, 16, 180, 500.00, 2, 1),
(3, 17, 130, 3500.00, 1, 1),
(4, 18, 150, 5000.00, 6, 1),
(5, 19, 80, 27900.00, 4, 1),
(6, 20, 300, 3000.00, 8, 1),
(7, 21, 250, 750.00, 9, 1),
(8, 22, 110, 3000.51, 7, 1),
(10, 24, 120, 610.00, 25, 1),
(26, 25, 300, 200.00, 30, 1),
(39, 26, 4000, 56.00, 1, 1),
(1, 30, 138, 31500.00, 16, 2),
(2, 31, 414, 525.00, 4, 2),
(3, 32, 299, 7350.00, 2, 2),
(4, 33, 345, 5250.00, 5, 2),
(5, 34, 184, 29295.00, 3, 2),
(6, 35, 690, 3150.00, 1, 2),
(7, 36, 575, 787.50, 3, 2),
(8, 37, 253, 3150.54, 12, 2),
(10, 39, 276, 640.50, 4, 2),
(26, 40, 690, 210.00, 5, 2),
(39, 41, 9200, 63.00, 10, 2),
(1, 60, 144, 28500.00, 16, 8),
(2, 61, 432, 475.00, 4, 8),
(3, 62, 312, 6650.00, 4, 8),
(4, 63, 360, 4750.00, 9, 8),
(5, 64, 192, 26505.00, 6, 8),
(6, 65, 720, 2850.00, 1, 8),
(7, 66, 600, 712.50, 7, 8),
(8, 67, 264, 2850.48, 10, 8),
(10, 69, 288, 579.50, 4, 8),
(26, 70, 720, 190.00, 5, 8),
(39, 71, 9600, 57.00, 10, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_ventas`
--

CREATE TABLE `productos_ventas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(8,2) NOT NULL,
  `idProducto` bigint(20) NOT NULL,
  `idVenta` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_ventas`
--

INSERT INTO `productos_ventas` (`id`, `cantidad`, `precio`, `idProducto`, `idVenta`) VALUES
(1, 1, 3500.00, 6, 1),
(2, 1, 30400.00, 5, 2),
(3, 2, 3500.00, 6, 3),
(4, 1, 3500.00, 6, 4),
(5, 1, 8500.00, 4, 4),
(6, 1, 9996.00, 3, 5),
(7, 1, 8500.00, 4, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suplidor`
--

CREATE TABLE `suplidor` (
  `id` int(11) NOT NULL,
  `nombreSuplidor` varchar(60) NOT NULL,
  `emailSuplidor` varchar(100) DEFAULT NULL,
  `emailPedidos` varchar(100) DEFAULT NULL,
  `telefonoSuplidor` varchar(25) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `suplidor`
--

INSERT INTO `suplidor` (`id`, `nombreSuplidor`, `emailSuplidor`, `emailPedidos`, `telefonoSuplidor`, `direccion`) VALUES
(1, 'Purdy Group', 'lderechter0@cnet.com', 'anthonynaudts@hotmail.com', '8954112965', 'Santiago'),
(2, 'Marvin-Connelly', 'dmountlow1@feedburner.com', 'anthonynaudts@hotmail.com', '4397833618', 'Santiago'),
(3, 'Konopelski, Turcotte and Kozey', 'ccoonihan2@amazon.de', 'anthonynaudts@hotmail.com', '4672664642', 'Santiago'),
(4, 'Ullrich Inc', 'rkarolewski3@csmonitor.com', 'anthonynaudts@hotmail.com', '5528727091', 'Santiago'),
(5, 'Keeling-Wolff', 'jwillison4@state.tx.us', 'anthonynaudts@hotmail.com', '8043937948', 'Santiago'),
(6, 'Cummerata-Howe', 'laurelius5@1688.com', 'anthonynaudts@hotmail.com', '5674215355', 'Santiago'),
(7, 'Rodriguez, McKenzie and Lindgren', 'tbrandle6@wsj.com', 'anthonynaudts@hotmail.com', '8639600386', 'Santiago'),
(8, 'Nicolas Inc', 'checkle7@slashdot.org', 'anthonynaudts@hotmail.com', '7967859875', 'Santiago'),
(9, 'Bechtelar, Runte and Schumm', 'tellens8@hp.com', 'anthonynaudts@hotmail.com', '4515671120', 'Santiago'),
(10, 'Schamberger-Zboncak', 'kkrimmer9@bbc.co.uk', 'anthonynaudts@hotmail.com', '4201751520', 'Santiago'),
(11, 'Harvey-Zulauf', 'bfleischmanna@furl.net', 'anthonynaudts@hotmail.com', '2958935423', 'Santiago'),
(12, 'Wuckert, Oberbrunner and Tillman', 'rtoffanob@imgur.com', 'anthonynaudts@hotmail.com', '6899856218', 'Santiago'),
(13, 'Kiehn-Vandervort', 'mheinritzc@narod.ru', 'anthonynaudts@hotmail.com', '2519625349', 'Santiago'),
(14, 'Kohler and Sons', 'wpittd@live.com', 'anthonynaudts@hotmail.com', '8085675044', 'Santiago');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `telefono` varchar(25) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `nombre`, `telefono`, `direccion`, `password`) VALUES
(1, 'admin', 'Administrador', '6667771234', 'Santiago', '$2y$10$AnGWooQ.WKBBu0wrSZmkn.ihYTKx0V/RD/jNzyUKX6C8X9.sGbv6C'),
(2, 'anthonynaudts', 'Anthony Naudts', '8294073003', 'Santiago', '$2y$10$/jZ9SiaVVbEbbQsHhV.dB.gR7NVMEAkAU/b/wDCFdOiSiaSGedaLq');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha` datetime NOT NULL,
  `total` decimal(9,2) NOT NULL,
  `idUsuario` bigint(20) NOT NULL,
  `idCliente` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `fecha`, `total`, `idUsuario`, `idCliente`) VALUES
(1, '2023-08-07 01:15:36', 3500.00, 1, 5),
(2, '2023-08-08 03:57:57', 30400.00, 1, 13),
(3, '2023-08-08 03:58:36', 7000.00, 1, 0),
(4, '2023-08-09 23:20:47', 12000.00, 1, 4),
(5, '2023-08-09 23:31:01', 9996.00, 1, 7),
(6, '2023-08-09 23:31:26', 8500.00, 1, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos_pedidos`
--
ALTER TABLE `articulos_pedidos`
  ADD KEY `id_prod` (`idProd`),
  ADD KEY `idPedido` (`idPedido`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`idEstado`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idPedido`);

--
-- Indices de la tabla `prioridad_productos`
--
ALTER TABLE `prioridad_productos`
  ADD PRIMARY KEY (`idPrioridad`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `productos_un` (`codigo`),
  ADD KEY `id_prioridad` (`idPrioridad`);

--
-- Indices de la tabla `productos_suplidor`
--
ALTER TABLE `productos_suplidor`
  ADD KEY `id_prod_tienda` (`idProdTienda`);

--
-- Indices de la tabla `productos_ventas`
--
ALTER TABLE `productos_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idProducto` (`idProducto`),
  ADD KEY `idVenta` (`idVenta`);

--
-- Indices de la tabla `suplidor`
--
ALTER TABLE `suplidor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suplidor_email_suplidor_IDX` (`emailSuplidor`) USING BTREE;

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUsuario` (`idUsuario`),
  ADD KEY `idCliente` (`idCliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `idEstado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idPedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de la tabla `prioridad_productos`
--
ALTER TABLE `prioridad_productos`
  MODIFY `idPrioridad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `productos_ventas`
--
ALTER TABLE `productos_ventas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `suplidor`
--
ALTER TABLE `suplidor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articulos_pedidos`
--
ALTER TABLE `articulos_pedidos`
  ADD CONSTRAINT `fk_foreign_key_name_id_pedido` FOREIGN KEY (`idPedido`) REFERENCES `pedidos` (`idPedido`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
