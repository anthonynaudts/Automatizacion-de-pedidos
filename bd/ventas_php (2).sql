-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-08-2023 a las 05:04:23
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `idPedido` int(11) NOT NULL,
  `fechaPedido` date NOT NULL,
  `fechaRecepcion` date NOT NULL,
  `montoPedido` decimal(18,2) NOT NULL,
  `idEstado` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prioridad_productos`
--

CREATE TABLE `prioridad_productos` (
  `idPrioridad` int(11) NOT NULL,
  `prioridad` varchar(30) NOT NULL,
  `tiempoLlegadaDias` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prioridad_productos`
--

INSERT INTO `prioridad_productos` (`idPrioridad`, `prioridad`, `tiempoLlegadaDias`) VALUES
(1, 'Prioridad alta', 1),
(2, 'Prioridad moderadamente alta', 2),
(3, 'Prioridad media', 5),
(4, 'Prioridad moderadamente baja', 8),
(5, 'Prioridad baja', 15),
(6, 'Sin prioridad', 30);

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
(1, '123459', 'Balón de fútbol', 800.00, 1500.00, 60, 20, 60, 3),
(3, '1208674', 'Raqueta de tenis Babolat Pure Strike 18x20 3rd Gen	', 7000.00, 10000.00, 30, 10, 30, 1),
(4, '9875210', 'Raqueta de tenis Yonex VCORE Pro 97HD 18x20', 5000.00, 8500.00, 15, 5, 15, 1),
(5, '5423681', 'Palos de golf - Set 14 palos', 27900.00, 30400.00, 7, 3, 8, 1),
(6, '62346324', 'Pelota de baloncesto - Tamaño 7 (reglamentario)', 3000.00, 3500.00, 27, 15, 30, 1),
(7, '546753644745', 'Pelota de voleibol - Tamaño 5 (estándar)', 750.00, 900.00, 25, 10, 25, 1),
(8, '45375457', 'Red de tenis - Tamaño estándar', 3000.00, 3500.00, 11, 3, 11, 1);

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
(3, 2, 3500.00, 6, 3);

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
(1, 'Dickens LLC', 'lderechter0@cnet.com', 'cabad0@ezinearticles.com', '8954112965', 'Santiago'),
(2, 'Marvin-Connelly', 'dmountlow1@feedburner.com', 'kwrankling1@pinterest.com', '4397833618', 'Santiago'),
(3, 'Konopelski, Turcotte and Kozey', 'ccoonihan2@amazon.de', 'bgallyhaock2@stanford.edu', '4672664642', 'Santiago'),
(4, 'Ullrich Inc', 'rkarolewski3@csmonitor.com', 'tschlagh3@china.com.cn', '5528727091', 'Santiago'),
(5, 'Keeling-Wolff', 'jwillison4@state.tx.us', 'yferres4@dropbox.com', '8043937948', 'Santiago'),
(6, 'Cummerata-Howe', 'laurelius5@1688.com', 'upendergast5@msn.com', '5674215355', 'Santiago'),
(7, 'Rodriguez, McKenzie and Lindgren', 'tbrandle6@wsj.com', 'hyurov6@yolasite.com', '8639600386', 'Santiago'),
(8, 'Nicolas Inc', 'checkle7@slashdot.org', 'tkyston7@cbslocal.com', '7967859875', 'Santiago'),
(9, 'Bechtelar, Runte and Schumm', 'tellens8@hp.com', 'pespy8@lulu.com', '4515671120', 'Santiago'),
(10, 'Schamberger-Zboncak', 'kkrimmer9@bbc.co.uk', 'ftames9@imageshack.us', '4201751520', 'Santiago'),
(11, 'Harvey-Zulauf', 'bfleischmanna@furl.net', 'rtiptona@redcross.org', '2958935423', 'Santiago'),
(12, 'Wuckert, Oberbrunner and Tillman', 'rtoffanob@imgur.com', 'mgarrb@dropbox.com', '6899856218', 'Santiago'),
(13, 'Kiehn-Vandervort', 'mheinritzc@narod.ru', 'nvinec@is.gd', '2519625349', 'Santiago'),
(14, 'Kohler and Sons', 'wpittd@live.com', 'dtomikd@home.pl', '8085675044', 'Santiago'),
(15, 'Purdy Group', 'epawsone@bandcamp.com', 'egeraulte@ebay.co.uk', '8928222693', 'Santiago');

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
(3, '2023-08-08 03:58:36', 7000.00, 1, 0);

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
  ADD UNIQUE KEY `suplidor_email_suplidor_IDX` (`emailSuplidor`) USING BTREE,
  ADD UNIQUE KEY `suplidor_email_pedidos_IDX` (`emailPedidos`) USING BTREE;

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
  MODIFY `idEstado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idPedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prioridad_productos`
--
ALTER TABLE `prioridad_productos`
  MODIFY `idPrioridad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `productos_ventas`
--
ALTER TABLE `productos_ventas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
