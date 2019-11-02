-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-03-2019 a las 13:24:03
-- Versión del servidor: 10.1.35-MariaDB
-- Versión de PHP: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mensajeria`
--
CREATE DATABASE IF NOT EXISTS `mensajeria` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `mensajeria`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--

DROP TABLE IF EXISTS `mensaje`;
CREATE TABLE `mensaje` (
  `idmensaje` int(11) NOT NULL,
  `remitente` int(11) DEFAULT NULL,
  `destinatario` int(11) DEFAULT NULL,
  `mensaje` varchar(600) DEFAULT NULL,
  `fechaalta` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `mensaje`
--

INSERT INTO `mensaje` (`idmensaje`, `remitente`, `destinatario`, `mensaje`, `fechaalta`) VALUES
(2, 19, 48, 'mensaje de prueba', '2019-03-26 10:47:20'),
(3, 19, 51, 'mensaje de prueba', '2019-03-26 10:47:20'),
(4, 19, 47, 'mensaje de prueba', '2019-03-26 10:47:20'),
(13, 19, 47, 'mensaje a múltiples destinatarios', '2019-03-26 11:14:21'),
(14, 19, 45, 'mensaje a múltiples destinatarios', '2019-03-26 11:14:21'),
(15, 19, 57, 'mensaje a múltiples destinatarios', '2019-03-26 11:14:21'),
(16, 19, 40, 'mensaje a múltiples destinatarios', '2019-03-26 11:14:21'),
(17, 19, 51, 'mensaje de prueba', '2019-03-26 11:28:13'),
(18, 19, 47, 'mensaje de prueba', '2019-03-26 11:28:13'),
(19, 19, 45, 'mensaje de prueba', '2019-03-26 11:28:13'),
(20, 19, 58, 'mensaje de prueba', '2019-03-26 11:28:13'),
(22, 19, 58, 'otro mensaje de prueba', '2019-03-26 12:23:21'),
(23, 19, 57, 'otro mensaje de prueba', '2019-03-26 12:23:21'),
(24, 19, 63, 'otro mensaje de prueba', '2019-03-26 12:23:21'),
(25, 19, 56, 'otro mensaje de prueba', '2019-03-26 12:23:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `tipousuario` char(1) NOT NULL,
  `nif` varchar(10) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `fechaalta` date DEFAULT NULL,
  `password` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `tipousuario`, `nif`, `nombre`, `apellidos`, `fechaalta`, `password`) VALUES
(19, 'A', '5656455', 'jose luis', 'torrente', '2012-12-15', 'torrente'),
(36, '', '75646555', 'jaimita', 'pinganillo', '2014-03-03', 'pnganillo'),
(38, '', '5746478565', 'mariana', 'cuchufletas', '2003-02-01', 'cuchufleta'),
(40, '', '11111111', 'juanito', 'pepinillo', '2000-02-02', 'pepinillo'),
(45, '', '33333333', 'fina', 'gramenower', '2012-02-02', 'fina'),
(47, '', '555555555', 'faemino', ' y cansado', '1942-01-01', 'faemino'),
(48, '', '5649588', 'Adolph', 'Suareznegger', '2010-02-03', 'adolph'),
(49, '', '857373838', 'perico', 'el de los palotes', '2008-10-10', 'perico'),
(50, '', '74462888', 'Johny', 'mentero', '2001-04-02', 'johny'),
(51, '', '536367778', 'Doctor', 'maligno', '2011-01-01', 'maligno'),
(56, '', '575757575', 'marianico', 'el corto', '2000-01-01', 'pepe'),
(57, '', '746464665', 'Juana', 'La Loca', '1900-12-12', 'juana'),
(58, '', '74646355', 'Igor', 'Fronkonstin', '1980-12-23', 'pepe'),
(63, '', '5567666G', 'Mariana', 'Cuchufleta', '2005-12-22', 'mariana');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD PRIMARY KEY (`idmensaje`),
  ADD KEY `remitente` (`remitente`),
  ADD KEY `destinatario` (`destinatario`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD UNIQUE KEY `nif_UNIQUE` (`nif`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  MODIFY `idmensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD CONSTRAINT `mensaje_ibfk_1` FOREIGN KEY (`remitente`) REFERENCES `usuario` (`idusuario`),
  ADD CONSTRAINT `mensaje_ibfk_2` FOREIGN KEY (`destinatario`) REFERENCES `usuario` (`idusuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
