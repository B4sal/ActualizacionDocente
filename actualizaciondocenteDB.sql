-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-06-2025 a las 22:01:01
-- Versión del servidor: 11.4.4-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `actualizaciondocente`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id` int(3) NOT NULL,
  `curso` varchar(100) NOT NULL,
  `status` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id`, `curso`, `status`) VALUES
(1, 'Matemáticas', 'A'),
(2, 'Física', 'I'),
(3, 'Química', 'A'),
(14, 'Matematicas 4', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encabezado`
--

CREATE TABLE `encabezado` (
  `id_curso` int(3) NOT NULL,
  `periodo` varchar(15) NOT NULL,
  `curso` varchar(50) NOT NULL,
  `horario` varchar(20) NOT NULL,
  `no_profesores` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `encabezado`
--

INSERT INTO `encabezado` (`id_curso`, `periodo`, `curso`, `horario`, `no_profesores`) VALUES
(1, 'Ago-Dic-2024-1', 'Matemáticas', '08:00 - 10:00', 3),
(2, 'Ene-Jun-2025-1', 'Física', '10:00 - 12:00', 2),
(3, 'Ago-Dic-2025-2', 'Química', '12:00 - 14:00', 4),
(14, 'Ago-Dic-2024-1', 'Matematicas 4', 'Horario del curso', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario_cursos`
--

CREATE TABLE `horario_cursos` (
  `id_curso` int(3) NOT NULL,
  `num` int(3) NOT NULL,
  `horario` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horario_cursos`
--

INSERT INTO `horario_cursos` (`id_curso`, `num`, `horario`) VALUES
(1, 1, '08:00 - 09:00'),
(1, 2, '09:00 - 10:00'),
(2, 1, '10:00 - 11:00'),
(2, 2, '11:00 - 12:00'),
(3, 1, '12:00 - 13:00'),
(3, 2, '13:00 - 14:00'),
(14, 1, 'lun 9:00-13:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodo`
--

CREATE TABLE `periodo` (
  `id_periodo` int(3) NOT NULL,
  `nombre_periodo` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `periodo`
--

INSERT INTO `periodo` (`id_periodo`, `nombre_periodo`) VALUES
(1, 'Ago-Dic-2024-1'),
(3, 'Ago-Dic-2025-2'),
(2, 'Ene-Jun-2025-1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro`
--

CREATE TABLE `registro` (
  `id_periodo` varchar(15) NOT NULL,
  `id_curso` int(3) NOT NULL,
  `id_profesor` int(3) NOT NULL,
  `expediente` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `id_horario_curso` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro`
--

INSERT INTO `registro` (`id_periodo`, `id_curso`, `id_profesor`, `expediente`, `nombre`, `correo`, `id_horario_curso`) VALUES
('Ago-Dic-2024-1', 1, 101, 'EXP001', 'Juan Pérez', 'juan.perez@example.com', 1),
('Ago-Dic-2024-1', 1, 102, 'EXP002', 'María López', 'maria.lopez@example.com', 2),
('Ago-Dic-2024-1', 14, 105, '234', 'María López', 'russell@correo.com', 1),
('Ago-Dic-2024-1', 14, 106, '234', 'María López', 'russell@correo.com', 1),
('Ago-Dic-2024-1', 14, 107, '234', 'juan', 'russell@correo.com', 1),
('Ago-Dic-2025-2', 3, 104, 'EXP004', 'Ana Torres', 'ana.torres@example.com', 2),
('Ene-Jun-2025-1', 2, 103, 'EXP003', 'Carlos Díaz', 'carlos.diaz@example.com', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `encabezado`
--
ALTER TABLE `encabezado`
  ADD PRIMARY KEY (`id_curso`,`periodo`),
  ADD KEY `periodo` (`periodo`);

--
-- Indices de la tabla `horario_cursos`
--
ALTER TABLE `horario_cursos`
  ADD PRIMARY KEY (`id_curso`,`num`);

--
-- Indices de la tabla `periodo`
--
ALTER TABLE `periodo`
  ADD PRIMARY KEY (`id_periodo`),
  ADD KEY `idx_nombre_periodo` (`nombre_periodo`);

--
-- Indices de la tabla `registro`
--
ALTER TABLE `registro`
  ADD PRIMARY KEY (`id_periodo`,`id_curso`,`id_profesor`,`expediente`),
  ADD KEY `id_curso` (`id_curso`,`id_periodo`),
  ADD KEY `id_curso_2` (`id_curso`,`id_horario_curso`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `periodo`
--
ALTER TABLE `periodo`
  MODIFY `id_periodo` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `encabezado`
--
ALTER TABLE `encabezado`
  ADD CONSTRAINT `encabezado_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `encabezado_ibfk_2` FOREIGN KEY (`periodo`) REFERENCES `periodo` (`nombre_periodo`);

--
-- Filtros para la tabla `horario_cursos`
--
ALTER TABLE `horario_cursos`
  ADD CONSTRAINT `horario_cursos_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id`);

--
-- Filtros para la tabla `registro`
--
ALTER TABLE `registro`
  ADD CONSTRAINT `registro_ibfk_1` FOREIGN KEY (`id_curso`,`id_periodo`) REFERENCES `encabezado` (`id_curso`, `periodo`),
  ADD CONSTRAINT `registro_ibfk_2` FOREIGN KEY (`id_curso`,`id_horario_curso`) REFERENCES `horario_cursos` (`id_curso`, `num`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
