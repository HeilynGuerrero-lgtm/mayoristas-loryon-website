-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql309.infinityfree.com
-- Tiempo de generación: 06-02-2026 a las 17:30:18
-- Versión del servidor: 11.4.9-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_40727679_mayoristas_loryon`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `ID_CARRITO` bigint(20) NOT NULL,
  `ID_USUARIO` bigint(20) NOT NULL,
  `FECHA_GEN` datetime DEFAULT current_timestamp(),
  `ESTADO` varchar(30) DEFAULT 'EN_PROCESO'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_carrito`
--

CREATE TABLE `detalle_carrito` (
  `ID_DETALLE_CARRITO` bigint(20) NOT NULL,
  `ID_CARRITO` bigint(20) NOT NULL,
  `ID_PRODUCTO` varchar(15) NOT NULL,
  `CANTIDAD` int(11) NOT NULL,
  `SUBTOTAL` decimal(10,2) NOT NULL,
  `FECHA_AGG` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial`
--

CREATE TABLE `historial` (
  `ID_HISTORIAL` bigint(20) NOT NULL,
  `ID_CARRITO` bigint(20) NOT NULL,
  `MENSAJE` varchar(255) DEFAULT NULL,
  `FECHA` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `ID_PRODUCTO` varchar(15) NOT NULL,
  `NOMBRE` varchar(150) NOT NULL,
  `CATEGORIA` varchar(50) NOT NULL,
  `PRECIO_COMPRA` decimal(10,2) NOT NULL,
  `PRECIO_VENTA` decimal(10,2) NOT NULL,
  `STOCK` int(11) NOT NULL DEFAULT 0,
  `DESCRIPCION` text DEFAULT NULL,
  `IMAGEN` varchar(255) DEFAULT NULL,
  `PROV_COD` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `PROV_COD` varchar(15) NOT NULL,
  `NOMBRE` varchar(150) NOT NULL,
  `TELEFONO` varchar(20) DEFAULT NULL,
  `DIRECCION` varchar(255) DEFAULT NULL,
  `CATEGORIA` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


--
-- Estructura de tabla para la tabla `recuperacion_clave`
--

CREATE TABLE `recuperacion_clave` (
  `ID_RECUPERACION` bigint(20) NOT NULL,
  `ID_USUARIO` bigint(20) NOT NULL,
  `CODIGO_HASH` varchar(255) NOT NULL,
  `FECHA_CREACION` datetime DEFAULT current_timestamp(),
  `FECHA_EXPIRA` datetime NOT NULL,
  `USADO` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `ID_ROL` int(11) NOT NULL,
  `NOMBRE_ROL` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`ID_ROL`, `NOMBRE_ROL`) VALUES
(1, 'ADMIN'),
(2, 'CLIENTE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `ID_USUARIO` bigint(20) NOT NULL,
  `NOMBRE_COMPLETO` varchar(150) NOT NULL,
  `TELEFONO` varchar(20) DEFAULT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `CLAVE` varchar(255) NOT NULL,
  `ID_ROL` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`ID_CARRITO`),
  ADD KEY `fk_carrito_usuario` (`ID_USUARIO`);

--
-- Indices de la tabla `detalle_carrito`
--
ALTER TABLE `detalle_carrito`
  ADD PRIMARY KEY (`ID_DETALLE_CARRITO`),
  ADD KEY `fk_detalle_carrito` (`ID_CARRITO`),
  ADD KEY `fk_detalle_producto` (`ID_PRODUCTO`);

--
-- Indices de la tabla `historial`
--
ALTER TABLE `historial`
  ADD PRIMARY KEY (`ID_HISTORIAL`),
  ADD KEY `fk_historial_carrito` (`ID_CARRITO`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`ID_PRODUCTO`),
  ADD KEY `fk_producto_proveedor` (`PROV_COD`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`PROV_COD`);

--
-- Indices de la tabla `recuperacion_clave`
--
ALTER TABLE `recuperacion_clave`
  ADD PRIMARY KEY (`ID_RECUPERACION`),
  ADD KEY `fk_recuperacion_usuario` (`ID_USUARIO`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`ID_ROL`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID_USUARIO`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `ID_CARRITO` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_carrito`
--
ALTER TABLE `detalle_carrito`
  MODIFY `ID_DETALLE_CARRITO` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial`
--
ALTER TABLE `historial`
  MODIFY `ID_HISTORIAL` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recuperacion_clave`
--
ALTER TABLE `recuperacion_clave`
  MODIFY `ID_RECUPERACION` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `ID_ROL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID_USUARIO` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `fk_carrito_usuario` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`);

--
-- Filtros para la tabla `detalle_carrito`
--
ALTER TABLE `detalle_carrito`
  ADD CONSTRAINT `fk_detalle_carrito` FOREIGN KEY (`ID_CARRITO`) REFERENCES `carrito` (`ID_CARRITO`),
  ADD CONSTRAINT `fk_detalle_producto` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `producto` (`ID_PRODUCTO`);

--
-- Filtros para la tabla `historial`
--
ALTER TABLE `historial`
  ADD CONSTRAINT `fk_historial_carrito` FOREIGN KEY (`ID_CARRITO`) REFERENCES `carrito` (`ID_CARRITO`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_producto_proveedor` FOREIGN KEY (`PROV_COD`) REFERENCES `proveedor` (`PROV_COD`);

--
-- Filtros para la tabla `recuperacion_clave`
--
ALTER TABLE `recuperacion_clave`
  ADD CONSTRAINT `fk_recuperacion_usuario` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
