-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-06-2026 a las 23:56:36
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_it`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `area` varchar(100) NOT NULL,
  `estado` enum('En progreso','Finalizada','Cancelada') NOT NULL DEFAULT 'En progreso',
  `responsables_data` longtext DEFAULT NULL,
  `fecha_inicio` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_fin` datetime DEFAULT NULL,
  `rango_dias` int(11) DEFAULT NULL,
  `fecha_limite` date DEFAULT NULL,
  `recordatorio_enviado` tinyint(1) DEFAULT 0,
  `id_usuario` int(11) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `descripcion`, `area`, `estado`, `responsables_data`, `fecha_inicio`, `fecha_fin`, `rango_dias`, `fecha_limite`, `recordatorio_enviado`, `id_usuario`, `visible`) VALUES
(188, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'En progreso', '[{\"id\":23,\"nombre\":\"Edmundo Oberto\"},{\"id\":42,\"nombre\":\"Daniel Sanchez\"},{\"id\":28,\"nombre\":\"Andreina Medina\"},{\"id\":43,\"nombre\":\"Emmanuel Lujan\"},{\"id\":24,\"nombre\":\"Jorgue Lazaro\"}]', '2026-06-21 20:08:00', NULL, NULL, NULL, 0, 72, 1),
(189, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'En progreso', '[{\"id\":42,\"nombre\":\"Daniel Sanchez\"},{\"id\":23,\"nombre\":\"Edmundo Oberto\"}]', '2026-06-21 20:08:00', NULL, NULL, NULL, 0, 72, 1),
(190, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'Informática', 'En progreso', '[{\"id\":28,\"nombre\":\"Andreina Medina\"}]', '2026-06-21 20:08:00', NULL, NULL, NULL, 0, 72, 1),
(191, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'Informática', 'En progreso', '[{\"id\":42,\"nombre\":\"Daniel Sanchez\"}]', '2026-06-21 20:08:00', NULL, NULL, NULL, 0, 72, 1),
(192, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'Informática', 'En progreso', '[{\"id\":30,\"nombre\":\"Gladys Muñoz\"},{\"id\":42,\"nombre\":\"Daniel Sanchez\"}]', '2026-06-21 20:09:00', NULL, NULL, NULL, 0, 72, 1),
(193, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'Informática', 'En progreso', '[{\"id\":28,\"nombre\":\"Andreina Medina\"},{\"id\":30,\"nombre\":\"Gladys Muñoz\"}]', '2026-06-21 20:09:00', NULL, NULL, NULL, 0, 72, 1),
(194, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'Informática', 'En progreso', '[{\"id\":42,\"nombre\":\"Daniel Sanchez\"}]', '2026-06-21 20:09:00', NULL, NULL, NULL, 0, 72, 1),
(195, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'Informática', 'En progreso', '[{\"id\":28,\"nombre\":\"Andreina Medina\"},{\"id\":43,\"nombre\":\"Emmanuel Lujan\"}]', '2026-06-21 20:09:00', NULL, NULL, NULL, 0, 72, 1),
(196, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'Informática', 'En progreso', '[{\"id\":28,\"nombre\":\"Andreina Medina\"}]', '2026-06-21 20:09:00', NULL, NULL, NULL, 0, 72, 1),
(197, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'Informática', 'En progreso', '[{\"id\":28,\"nombre\":\"Andreina Medina\"}]', '2026-06-21 20:09:00', NULL, NULL, NULL, 0, 72, 1),
(198, 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', 'En progreso', '[{\"id\":28,\"nombre\":\"Andreina Medina\"}]', '2026-06-21 20:36:00', NULL, NULL, NULL, 0, 76, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_empleado`
--

CREATE TABLE `actividad_empleado` (
  `id` int(11) NOT NULL,
  `actividad_id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_historial`
--

CREATE TABLE `actividad_historial` (
  `id` int(11) NOT NULL,
  `actividad_id` int(11) NOT NULL,
  `accion` varchar(120) NOT NULL,
  `detalle` text DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `usuario_nombre` varchar(160) DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividad_historial`
--

INSERT INTO `actividad_historial` (`id`, `actividad_id`, `accion`, `detalle`, `usuario_id`, `usuario_nombre`, `creado_en`) VALUES
(1, 6, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 22:06:19'),
(2, 5, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 22:06:24'),
(3, 4, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 22:06:27'),
(4, 2, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 22:06:30'),
(5, 1, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 22:06:32'),
(6, 7, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 22:11:12'),
(7, 7, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a En progreso', 1, 'Gladys Muñoz', '2026-04-25 22:11:28'),
(8, 7, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a En progreso', 1, 'Gladys Muñoz', '2026-04-25 22:13:50'),
(9, 7, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a En progreso', 1, 'Gladys Muñoz', '2026-04-25 22:20:52'),
(10, 7, 'ACTUALIZACION', 'El usuario Juan Villazmil actualizó la actividad y cambió estado a En progreso', 3, 'Juan Villazmil', '2026-04-25 22:33:16'),
(11, 7, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a En progreso', 1, 'Gladys Muñoz', '2026-04-25 22:33:50'),
(12, 8, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 22:36:17'),
(13, 8, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a En progreso', 1, 'Gladys Muñoz', '2026-04-25 22:36:44'),
(14, 9, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 22:46:20'),
(15, 9, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 22:46:43'),
(16, 8, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 22:46:46'),
(17, 7, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 22:46:49'),
(18, 10, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 22:51:44'),
(19, 10, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 22:52:33'),
(20, 11, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:05:19'),
(21, 11, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a Finalizada', 1, 'Gladys Muñoz', '2026-04-25 23:05:41'),
(22, 11, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a Finalizada', 1, 'Gladys Muñoz', '2026-04-25 23:25:13'),
(23, 11, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 23:25:39'),
(24, 12, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:26:47'),
(25, 13, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:26:52'),
(26, 14, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:26:59'),
(27, 15, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:27:10'),
(28, 16, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:27:16'),
(29, 17, 'CREACION', 'Actividad creada por Gladys Muñoz con estado Finalizada', 1, 'Gladys Muñoz', '2026-04-25 23:27:32'),
(30, 18, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:27:43'),
(31, 19, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:27:49'),
(32, 20, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:27:56'),
(33, 21, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:28:00'),
(34, 22, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:28:04'),
(35, 23, 'CREACION', 'Actividad creada por Gladys Muñoz con estado Finalizada', 1, 'Gladys Muñoz', '2026-04-25 23:28:16'),
(36, 24, 'CREACION', 'Actividad creada por Gladys Muñoz con estado Finalizada', 1, 'Gladys Muñoz', '2026-04-25 23:28:32'),
(37, 25, 'CREACION', 'Actividad creada por Gladys Muñoz con estado Cancelada', 1, 'Gladys Muñoz', '2026-04-25 23:28:43'),
(38, 25, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a Cancelada', 1, 'Gladys Muñoz', '2026-04-25 23:28:57'),
(39, 26, 'CREACION', 'Actividad creada por Gladys Muñoz con estado Finalizada', 1, 'Gladys Muñoz', '2026-04-25 23:29:09'),
(40, 27, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:29:15'),
(41, 28, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:29:23'),
(42, 29, 'CREACION', 'Actividad creada por Gladys Muñoz con estado Finalizada', 1, 'Gladys Muñoz', '2026-04-25 23:29:29'),
(43, 30, 'CREACION', 'Actividad creada por Gladys Muñoz con estado Finalizada', 1, 'Gladys Muñoz', '2026-04-25 23:29:52'),
(44, 31, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:29:59'),
(45, 32, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:30:03'),
(46, 33, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:30:18'),
(47, 34, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:30:22'),
(48, 35, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-25 23:30:28'),
(49, 36, 'CREACION', 'Actividad creada por Gladys Muñoz con estado Finalizada', 1, 'Gladys Muñoz', '2026-04-25 23:30:37'),
(50, 37, 'CREACION', 'Actividad creada por Gladys Muñoz con estado Finalizada', 1, 'Gladys Muñoz', '2026-04-25 23:30:45'),
(51, 13, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 23:30:59'),
(52, 37, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 23:31:02'),
(53, 36, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 23:31:05'),
(54, 35, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 23:31:07'),
(55, 34, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 23:31:09'),
(56, 33, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 23:31:12'),
(57, 32, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 23:31:15'),
(58, 31, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 23:31:18'),
(59, 30, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 23:31:22'),
(60, 29, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 23:31:25'),
(61, 28, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 23:31:27'),
(62, 27, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 23:31:29'),
(63, 26, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-25 23:31:32'),
(64, 25, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 03:15:44'),
(65, 24, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 03:15:46'),
(66, 23, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 03:15:49'),
(67, 22, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 03:15:51'),
(68, 21, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 03:15:54'),
(69, 20, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 03:15:56'),
(70, 19, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 03:15:58'),
(71, 18, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 03:16:00'),
(72, 17, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 03:16:02'),
(73, 16, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 03:16:04'),
(74, 15, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 03:16:07'),
(75, 14, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 03:16:10'),
(76, 12, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 03:16:12'),
(77, 38, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-26 03:24:17'),
(78, 38, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a Cancelada', 1, 'Gladys Muñoz', '2026-04-26 03:24:34'),
(79, 39, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-26 03:33:26'),
(80, 39, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a Cancelada', 1, 'Gladys Muñoz', '2026-04-26 03:39:10'),
(81, 39, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a Finalizada', 1, 'Gladys Muñoz', '2026-04-26 03:39:16'),
(82, 40, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-26 03:53:03'),
(83, 40, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 03:55:27'),
(84, 39, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 03:55:30'),
(85, 38, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 03:55:33'),
(86, 41, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-26 03:55:47'),
(87, 42, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-26 03:58:52'),
(88, 43, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-26 04:02:59'),
(89, 43, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a En progreso', 1, 'Gladys Muñoz', '2026-04-26 12:55:37'),
(90, 43, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a En progreso', 1, 'Gladys Muñoz', '2026-04-26 12:55:53'),
(91, 43, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a En progreso', 1, 'Gladys Muñoz', '2026-04-26 12:56:11'),
(92, 44, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-26 13:08:20'),
(93, 45, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-26 13:19:01'),
(94, 43, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a En progreso', 1, 'Gladys Muñoz', '2026-04-26 13:33:48'),
(95, 45, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 13:41:27'),
(96, 44, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 13:41:30'),
(97, 43, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 13:41:33'),
(98, 42, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 13:41:37'),
(99, 41, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 13:41:40'),
(100, 46, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-26 14:09:13'),
(101, 47, 'CREACION', 'Actividad creada por Gladys Muñoz con estado Finalizada', 1, 'Gladys Muñoz', '2026-04-26 14:09:33'),
(102, 46, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a Finalizada', 1, 'Gladys Muñoz', '2026-04-26 14:09:52'),
(103, 48, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-26 14:39:14'),
(104, 48, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 1, 'Gladys Muñoz', '2026-04-26 15:02:28'),
(105, 48, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a Cancelada', 1, 'Gladys Muñoz', '2026-04-26 15:02:53'),
(106, 48, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a En progreso', 1, 'Gladys Muñoz', '2026-04-26 15:34:01'),
(107, 48, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 1, 'Gladys Muñoz', '2026-04-26 15:34:11'),
(108, 48, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-04-26 15:43:13'),
(109, 49, 'CREACION', 'Actividad creada por José Ventura con estado En progreso', 24, 'José Ventura', '2026-04-26 16:01:15'),
(110, 50, 'CREACION', 'Actividad creada por José Ventura con estado En progreso', 24, 'José Ventura', '2026-04-26 16:01:27'),
(111, 51, 'CREACION', 'Actividad creada por José Ventura con estado En progreso', 24, 'José Ventura', '2026-04-26 16:01:31'),
(112, 52, 'CREACION', 'Actividad creada por José Ventura con estado En progreso', 24, 'José Ventura', '2026-04-26 16:01:35'),
(113, 53, 'CREACION', 'Actividad creada por José Ventura con estado En progreso', 24, 'José Ventura', '2026-04-26 16:01:43'),
(114, 54, 'CREACION', 'Actividad creada por José Ventura con estado En progreso', 24, 'José Ventura', '2026-04-26 16:01:51'),
(115, 55, 'CREACION', 'Actividad creada por José Ventura con estado En progreso', 24, 'José Ventura', '2026-04-26 16:01:58'),
(116, 56, 'CREACION', 'Actividad creada por José Ventura con estado En progreso', 24, 'José Ventura', '2026-04-26 16:02:03'),
(117, 57, 'CREACION', 'Actividad creada por José Ventura con estado En progreso', 24, 'José Ventura', '2026-04-26 16:02:28'),
(118, 58, 'CREACION', 'Actividad creada por José Ventura con estado En progreso', 24, 'José Ventura', '2026-04-26 16:02:43'),
(119, 59, 'CREACION', 'Actividad creada por José Ventura con estado En progreso', 24, 'José Ventura', '2026-04-26 16:02:53'),
(120, 60, 'CREACION', 'Actividad creada por José Ventura con estado En progreso', 24, 'José Ventura', '2026-04-26 16:03:13'),
(121, 61, 'CREACION', 'Actividad creada por José Ventura con estado En progreso', 24, 'José Ventura', '2026-04-26 16:03:22'),
(122, 61, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad y cambió estado a En progreso', 1, 'Gladys Muñoz', '2026-04-26 16:04:37'),
(123, 62, 'CREACION', 'Actividad creada por Jessica Ventura con estado En progreso', 40, 'Jessica Ventura', '2026-04-27 03:30:09'),
(124, 63, 'CREACION', 'Actividad creada por Jessica Ventura con estado En progreso', 40, 'Jessica Ventura', '2026-04-27 03:30:15'),
(125, 64, 'CREACION', 'Actividad creada por Jessica Ventura con estado En progreso', 40, 'Jessica Ventura', '2026-04-27 03:30:29'),
(126, 65, 'CREACION', 'Actividad creada por Jessica Ventura con estado Finalizada', 40, 'Jessica Ventura', '2026-04-27 03:30:37'),
(127, 66, 'CREACION', 'Actividad creada por Jessica Ventura con estado En progreso', 40, 'Jessica Ventura', '2026-04-27 03:30:44'),
(128, 67, 'CREACION', 'Actividad creada por Jessica Ventura con estado En progreso', 40, 'Jessica Ventura', '2026-04-27 03:30:51'),
(129, 68, 'CREACION', 'Actividad creada por Jessica Ventura con estado En progreso', 40, 'Jessica Ventura', '2026-04-27 03:31:00'),
(130, 69, 'CREACION', 'Actividad creada por Jessica Ventura con estado En progreso', 40, 'Jessica Ventura', '2026-04-27 03:31:24'),
(131, 70, 'CREACION', 'Actividad creada por Jessica Ventura con estado En progreso', 40, 'Jessica Ventura', '2026-04-27 03:31:24'),
(132, 71, 'CREACION', 'Actividad creada por Jessica Ventura con estado En progreso', 40, 'Jessica Ventura', '2026-04-27 03:31:36'),
(133, 72, 'CREACION', 'Actividad creada por Jessica Ventura con estado En progreso', 40, 'Jessica Ventura', '2026-04-27 03:31:36'),
(134, 73, 'CREACION', 'Actividad creada por Jessica Ventura con estado En progreso', 40, 'Jessica Ventura', '2026-04-27 03:31:43'),
(135, 74, 'CREACION', 'Actividad creada por Jessica Ventura con estado Finalizada', 40, 'Jessica Ventura', '2026-04-27 03:31:51'),
(136, 75, 'CREACION', 'Actividad creada por Jessica Ventura con estado Finalizada', 40, 'Jessica Ventura', '2026-04-27 03:32:02'),
(137, 76, 'CREACION', 'Actividad creada por Jessica Ventura con estado En progreso', 40, 'Jessica Ventura', '2026-04-27 03:32:24'),
(138, 77, 'CREACION', 'Actividad creada por Jessica Ventura con estado En progreso', 40, 'Jessica Ventura', '2026-04-27 03:32:41'),
(139, 78, 'CREACION', 'Actividad creada por Jessica Ventura con estado En progreso', 40, 'Jessica Ventura', '2026-04-27 03:32:52'),
(140, 79, 'CREACION', 'Actividad creada por Jessica Ventura con estado Finalizada', 40, 'Jessica Ventura', '2026-04-27 03:33:10'),
(141, 78, 'ACTUALIZACION', 'El usuario Jessica Ventura actualizó la actividad y cambió estado a En progreso', 40, 'Jessica Ventura', '2026-04-27 03:48:49'),
(142, 78, 'FINALIZACION', 'El usuario Jessica Ventura finalizó la actividad', 40, 'Jessica Ventura', '2026-04-27 03:51:03'),
(143, 78, 'ACTUALIZACION', 'El usuario Jessica Ventura actualizó la actividad y cambió estado a En progreso', 40, 'Jessica Ventura', '2026-04-27 03:51:27'),
(144, 78, 'ACTUALIZACION', 'El usuario Jessica Ventura actualizó la actividad y cambió estado a En progreso', 40, 'Jessica Ventura', '2026-04-27 03:51:53'),
(145, 78, 'ACTUALIZACION', 'El usuario Jessica Ventura actualizó la actividad y cambió estado a Cancelada', 40, 'Jessica Ventura', '2026-04-27 03:52:29'),
(146, 78, 'ACTUALIZACION', 'El usuario Jessica Ventura actualizó la actividad y cambió estado a En progreso', 40, 'Jessica Ventura', '2026-04-27 03:52:43'),
(147, 80, 'CREACION', 'Actividad creada por Jessica Ventura con estado En progreso', 40, 'Jessica Ventura', '2026-04-27 04:10:54'),
(148, 80, 'ELIMINACION', 'El usuario Jessica Ventura eliminó la actividad', 40, 'Jessica Ventura', '2026-04-27 04:10:59'),
(149, 79, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la descripción', 1, 'Gladys Muñoz', '2026-04-27 04:29:48'),
(150, 79, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la descripción y cambió estado a En progreso', 1, 'Gladys Muñoz', '2026-04-27 04:30:17'),
(151, 79, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la descripción, cambió el estado a Finalizada, actualizó la fecha y añadió un nuevo responsable', 1, 'Gladys Muñoz', '2026-04-27 04:41:44'),
(152, 81, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-04-28 02:17:50'),
(153, 81, 'ELIMINACION', 'El usuario José Ventura eliminó la actividad', 64, 'José Ventura', '2026-05-09 18:53:21'),
(154, 82, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-05-11 04:56:42'),
(155, 82, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 1, 'Gladys Muñoz', '2026-05-11 04:57:29'),
(156, 83, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-05-11 05:06:47'),
(157, 83, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 1, 'Gladys Muñoz', '2026-05-11 05:07:40'),
(158, 83, 'ACTUALIZACION', 'El usuario Gladys Muñoz eliminó un responsable', 1, 'Gladys Muñoz', '2026-05-11 05:14:22'),
(159, 83, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la descripción, actualizó la fecha y añadió un nuevo responsable', 1, 'Gladys Muñoz', '2026-05-11 05:14:50'),
(160, 84, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-05-11 05:17:20'),
(161, 85, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-05-11 05:17:26'),
(162, 85, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la descripción, actualizó el campo Área, actualizó la fecha y añadió un nuevo responsable', 1, 'Gladys Muñoz', '2026-05-11 05:37:46'),
(163, 86, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-05-11 06:11:21'),
(164, 86, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-05-11 06:11:47'),
(165, 85, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-05-11 06:11:50'),
(166, 84, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-05-11 06:11:53'),
(167, 87, 'CREACION', 'Actividad creada por Edmundo Villazmil con estado En progreso', 67, 'Edmundo Villazmil', '2026-05-11 14:39:24'),
(168, 87, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 1, 'Gladys Muñoz', '2026-05-11 14:57:22'),
(169, 88, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-05-11 15:18:34'),
(170, 89, 'CREACION', 'Actividad creada por JOSEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE ADRIANNNNNNNNNNNNNNNNNNNNNNNNNNNN con estado En progreso', 69, 'JOSEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE ADRIANNNNNNNNNNNNNNNNNNNNNNNNNNNN', '2026-05-12 03:09:34'),
(171, 89, 'ELIMINACION', 'El usuario JOSEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE ADRIANNNNNNNNNNNNNNNNNNNNNNNNNNNN eliminó la actividad', 69, 'JOSEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE ADRIANNNNNNNNNNNNNNNNNNNNNNNNNNNN', '2026-05-12 03:09:47'),
(172, 88, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 1, 'Gladys Muñoz', '2026-05-13 00:00:04'),
(173, 90, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-05-13 00:00:24'),
(174, 91, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-05-13 00:00:27'),
(175, 92, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-05-13 00:00:39'),
(176, 93, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-05-13 00:00:44'),
(177, 94, 'CREACION', 'Actividad creada por Gladys Muñoz con estado En progreso', 1, 'Gladys Muñoz', '2026-05-13 00:19:00'),
(178, 95, 'CREACION', 'Actividad creada por Gladys Muñoz el 12-05-2026', 1, 'Gladys Muñoz', '2026-05-13 00:31:04'),
(179, 96, 'CREACION', 'Actividad creada por Gladys Muñoz el 12-05-2026', 1, 'Gladys Muñoz', '2026-05-13 00:41:43'),
(180, 96, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 1, 'Gladys Muñoz', '2026-05-13 00:42:00'),
(181, 96, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó el campo Área', 1, 'Gladys Muñoz', '2026-05-13 01:16:27'),
(182, 96, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-05-13 01:16:46'),
(183, 95, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-05-13 01:16:49'),
(184, 94, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-05-13 01:16:52'),
(185, 93, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-05-13 01:16:54'),
(186, 92, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-05-13 01:16:57'),
(187, 91, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-05-13 01:17:00'),
(188, 90, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-05-13 01:17:02'),
(189, 88, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-05-13 01:17:04'),
(190, 87, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-05-13 01:17:07'),
(191, 83, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-05-13 01:17:09'),
(192, 82, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-05-13 01:17:12'),
(193, 47, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-05-13 01:17:14'),
(194, 46, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 1, 'Gladys Muñoz', '2026-05-13 01:17:16'),
(195, 97, 'CREACION', 'Actividad creada por Gladys Muñoz el 13-05-2026', 70, 'Gladys Muñoz', '2026-05-13 16:38:52'),
(196, 97, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la descripción, actualizó el campo Área y añadió un nuevo responsable', 70, 'Gladys Muñoz', '2026-05-13 16:39:27'),
(197, 97, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad', 70, 'Gladys Muñoz', '2026-05-13 16:39:55'),
(198, 97, 'ACTUALIZACION', 'El usuario Gladys Muñoz añadió un nuevo responsable', 70, 'Gladys Muñoz', '2026-05-13 16:50:12'),
(199, 98, 'CREACION', 'Actividad creada por Gladys Muñoz el 02-05-2026', 70, 'Gladys Muñoz', '2026-05-13 16:52:47'),
(200, 99, 'CREACION', 'Actividad creada por Gladys Muñoz el 02-05-2026', 70, 'Gladys Muñoz', '2026-05-13 16:52:52'),
(201, 99, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 70, 'Gladys Muñoz', '2026-05-13 17:22:32'),
(202, 100, 'CREACION', 'Actividad creada por Gladys Muñoz el 13-05-2026', 70, 'Gladys Muñoz', '2026-05-13 17:23:07'),
(203, 100, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 70, 'Gladys Muñoz', '2026-05-13 17:23:20'),
(204, 99, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 70, 'Gladys Muñoz', '2026-05-13 17:23:22'),
(205, 98, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 70, 'Gladys Muñoz', '2026-05-13 17:23:26'),
(206, 97, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 70, 'Gladys Muñoz', '2026-05-13 17:23:30'),
(207, 101, 'CREACION', 'Actividad creada por Gladys Muñoz el 13-05-2026', 70, 'Gladys Muñoz', '2026-05-13 17:25:11'),
(208, 101, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la fecha', 70, 'Gladys Muñoz', '2026-05-13 17:25:24'),
(209, 101, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 70, 'Gladys Muñoz', '2026-05-13 17:25:35'),
(210, 102, 'CREACION', 'Actividad creada por Gladys Muñoz el 14-05-2026', 70, 'Gladys Muñoz', '2026-05-14 23:46:20'),
(211, 102, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó el campo Área, cambió el estado a Finalizada, actualizó la fecha y añadió un nuevo responsable', 70, 'Gladys Muñoz', '2026-05-14 23:46:57'),
(212, 102, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la fecha y eliminó un responsable', 70, 'Gladys Muñoz', '2026-05-14 23:47:24'),
(213, 102, 'ACTUALIZACION', 'El usuario Gladys Muñoz añadió un nuevo responsable', 70, 'Gladys Muñoz', '2026-05-14 23:47:47'),
(214, 103, 'CREACION', 'Actividad creada por Gladys Muñoz el 14-05-2026', 70, 'Gladys Muñoz', '2026-05-15 00:01:25'),
(215, 103, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 70, 'Gladys Muñoz', '2026-05-15 00:01:32'),
(216, 104, 'CREACION', 'Actividad creada por Gladys Muñoz el 17-05-2026', 72, 'Gladys Muñoz', '2026-05-18 04:13:04'),
(217, 104, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó el campo Área, cambió el estado a Finalizada y añadió nuevos responsables', 72, 'Gladys Muñoz', '2026-05-18 04:13:33'),
(218, 105, 'CREACION', 'Actividad creada por Gladys Muñoz el 17-05-2026', 72, 'Gladys Muñoz', '2026-05-18 05:07:54'),
(219, 105, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-05-18 05:07:58'),
(220, 105, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó el campo Área y cambió el estado a En progreso', 72, 'Gladys Muñoz', '2026-05-18 05:08:07'),
(221, 105, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 72, 'Gladys Muñoz', '2026-06-14 13:31:08'),
(222, 104, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 72, 'Gladys Muñoz', '2026-06-14 13:31:14'),
(223, 106, 'CREACION', 'Actividad creada por Gladys Muñoz el 14-06-2026 07:31', 72, 'Gladys Muñoz', '2026-06-14 13:31:24'),
(224, 106, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-14 13:31:27'),
(225, 106, 'ACTUALIZACION', 'El usuario Gladys Muñoz cambió el estado a En progreso', 72, 'Gladys Muñoz', '2026-06-14 13:34:01'),
(226, 106, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-14 13:34:03'),
(227, 106, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 72, 'Gladys Muñoz', '2026-06-14 07:40:00'),
(228, 107, 'CREACION', 'Actividad creada por Gladys Muñoz el 14-06-2026 07:40', 72, 'Gladys Muñoz', '2026-06-14 07:40:32'),
(229, 107, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-14 07:40:34'),
(230, 107, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 72, 'Gladys Muñoz', '2026-06-14 07:43:54'),
(231, 108, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-14 07:44:05'),
(232, 108, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-14 07:44:15'),
(233, 108, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 72, 'Gladys Muñoz', '2026-06-14 07:56:16'),
(234, 109, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-14 07:56:29'),
(235, 109, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-14 07:56:31'),
(236, 110, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-14 07:57:42'),
(237, 110, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-14 08:01:46'),
(238, 110, 'ACTUALIZACION', 'El usuario Gladys Muñoz cambió el estado a En progreso', 72, 'Gladys Muñoz', '2026-06-14 08:01:55'),
(239, 111, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-14 08:05:47'),
(240, 111, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 72, 'Gladys Muñoz', '2026-06-14 08:05:52'),
(241, 110, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-14 08:12:52'),
(242, 110, 'ACTUALIZACION', 'El usuario Gladys Muñoz cambió el estado a En progreso', 72, 'Gladys Muñoz', '2026-06-14 08:13:01'),
(243, 110, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-14 08:34:10'),
(244, 110, 'ACTUALIZACION', 'El usuario Gladys Muñoz cambió el estado a En progreso', 72, 'Gladys Muñoz', '2026-06-14 08:34:18'),
(245, 110, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-14 08:46:16'),
(246, 109, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 72, 'Gladys Muñoz', '2026-06-14 08:46:26'),
(247, 110, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 72, 'Gladys Muñoz', '2026-06-14 08:46:29'),
(248, 112, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-14 08:46:37'),
(249, 112, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-14 08:46:39'),
(250, 112, 'ACTUALIZACION', 'El usuario Gladys Muñoz cambió el estado a En progreso', 72, 'Gladys Muñoz', '2026-06-14 08:46:45'),
(251, 112, 'ACTUALIZACION', 'El usuario jose ventura añadió un nuevo responsable', 74, 'jose ventura', '2026-06-14 08:49:00'),
(252, 112, 'FINALIZACION', 'El usuario jose ventura finalizó la actividad', 74, 'jose ventura', '2026-06-14 08:49:07'),
(253, 112, 'ACTUALIZACION', 'El usuario jose ventura actualizó la actividad', 74, 'jose ventura', '2026-06-14 08:49:19'),
(254, 112, 'ACTUALIZACION', 'El usuario jose ventura actualizó la actividad', 74, 'jose ventura', '2026-06-14 08:49:32'),
(255, 112, 'ACTUALIZACION', 'El usuario Gladys Muñoz cambió el estado a En progreso', 72, 'Gladys Muñoz', '2026-06-14 08:49:43'),
(256, 113, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-14 08:51:18'),
(257, 113, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 72, 'Gladys Muñoz', '2026-06-14 08:51:32'),
(258, 112, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-14 08:51:34'),
(259, 112, 'ACTUALIZACION', 'El usuario Gladys Muñoz cambió el estado a En progreso', 72, 'Gladys Muñoz', '2026-06-14 08:51:42'),
(260, 112, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-14 09:12:27'),
(261, 112, 'ACTUALIZACION', 'El usuario Gladys Muñoz cambió el estado a En progreso', 72, 'Gladys Muñoz', '2026-06-14 09:12:53'),
(262, 112, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-14 09:24:17'),
(263, 112, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la descripción, actualizó el campo Área y actualizó los responsables', 72, 'Gladys Muñoz', '2026-06-14 09:25:09'),
(264, 112, 'ACTUALIZACION', 'El usuario Gladys Muñoz cambió el estado a En progreso', 72, 'Gladys Muñoz', '2026-06-14 09:25:52'),
(265, 112, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-14 09:25:53'),
(266, 114, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-14 09:26:28'),
(267, 114, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-14 09:30:34'),
(268, 114, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 72, 'Gladys Muñoz', '2026-06-14 09:30:38'),
(269, 112, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 72, 'Gladys Muñoz', '2026-06-14 09:30:42'),
(270, 115, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-15 17:54:26'),
(271, 115, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-16 17:58:18'),
(272, 115, 'ACTUALIZACION', 'El usuario Gladys Muñoz cambió el estado a En progreso', 72, 'Gladys Muñoz', '2027-06-16 17:58:49'),
(273, 115, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-15 17:59:12'),
(274, 115, 'ACTUALIZACION', 'El usuario Gladys Muñoz cambió el estado a En progreso', 72, 'Gladys Muñoz', '2026-06-15 17:59:54'),
(275, 115, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-07-31 18:00:05'),
(276, 115, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 72, 'Gladys Muñoz', '2026-06-15 12:00:48'),
(277, 116, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-15 12:32:08'),
(278, 117, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-15 12:36:26'),
(279, 187, 'ACTUALIZACION', 'El usuario jose ventura actualizó la actividad', 74, 'jose ventura', '2026-06-21 06:49:33'),
(280, 147, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-20 22:59:01'),
(281, 146, 'ELIMINACION', 'El usuario Gladys Muñoz eliminó la actividad', 72, 'Gladys Muñoz', '2026-06-20 23:50:12'),
(282, 117, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-20 23:59:35'),
(283, 187, 'ACTUALIZACION', 'El usuario Gladys Muñoz actualizó la actividad', 72, 'Gladys Muñoz', '2026-06-21 00:20:44'),
(284, 187, 'FINALIZACION', 'El usuario Gladys Muñoz finalizó la actividad', 72, 'Gladys Muñoz', '2026-06-21 09:57:12'),
(285, 188, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-21 16:08:32'),
(286, 189, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-21 16:08:44'),
(287, 190, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-21 16:08:49'),
(288, 191, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-21 16:08:54'),
(289, 192, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-21 16:09:02'),
(290, 193, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-21 16:09:09'),
(291, 194, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-21 16:09:15'),
(292, 195, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-21 16:09:22'),
(293, 196, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-21 16:09:28'),
(294, 197, 'CREACION', 'Actividad creada por Gladys Muñoz', 72, 'Gladys Muñoz', '2026-06-21 16:09:32'),
(295, 198, 'CREACION', 'Actividad creada por Jose Ventura', 76, 'Jose Ventura', '2026-06-21 16:36:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `formacion` varchar(150) DEFAULT NULL,
  `correo` varchar(100) DEFAULT 'N/D',
  `telefono` varchar(20) DEFAULT 'N/D'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `apellido`, `formacion`, `correo`, `telefono`) VALUES
(22, 'Luis', 'Ruiz', 'Informática y Sistema', '', ''),
(23, 'Edmundo', 'Oberto', 'Informática y Sistema', '', ''),
(24, 'Jorgue', 'Lazaro', 'Informatica y Sistemas', '', ''),
(25, 'Yedcibel', 'Diaz', 'Informatica y Sistemas', '', ''),
(26, 'Neri', 'la Cruz', 'Informatica y Sistemas', '', ''),
(27, 'Nakary', 'Garcia', 'Informatica y Sistemas', '', ''),
(28, 'Andreina', 'Medina', 'Informatica y Sistemas', '', ''),
(29, 'Julio', 'Acosta', 'Informatica y Sistemas', '', ''),
(30, 'Gladys', 'Muñoz', 'Informatica y Sistemas', '', ''),
(42, 'Daniel', 'Sanchez', 'Ing. Sistemas', 'N/D', 'N/D');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs_sistema`
--

CREATE TABLE `logs_sistema` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `accion` text NOT NULL,
  `detalle` text DEFAULT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `logs_sistema`
--

INSERT INTO `logs_sistema` (`id`, `usuario_id`, `accion`, `detalle`, `fecha`) VALUES
(1, 72, 'Registró la actividad ID 188... (Ver info)', '{\"tipo\":\"actividad\",\"accion\":\"creacion\",\"actividad_id\":188,\"descripcion\":\"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\",\"area\":\"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\",\"estado\":\"En progreso\"}', '2026-06-21'),
(2, 72, 'Registró la actividad ID 189... (Ver info)', '{\"tipo\":\"actividad\",\"accion\":\"creacion\",\"actividad_id\":189,\"descripcion\":\"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\",\"area\":\"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\",\"estado\":\"En progreso\"}', '2026-06-21'),
(3, 72, 'Registró la actividad ID 190... (Ver info)', '{\"tipo\":\"actividad\",\"accion\":\"creacion\",\"actividad_id\":190,\"descripcion\":\"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\",\"area\":\"Informática\",\"estado\":\"En progreso\"}', '2026-06-21'),
(4, 72, 'Registró la actividad ID 191... (Ver info)', '{\"tipo\":\"actividad\",\"accion\":\"creacion\",\"actividad_id\":191,\"descripcion\":\"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\",\"area\":\"Informática\",\"estado\":\"En progreso\"}', '2026-06-21'),
(5, 72, 'Registró la actividad ID 192... (Ver info)', '{\"tipo\":\"actividad\",\"accion\":\"creacion\",\"actividad_id\":192,\"descripcion\":\"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\",\"area\":\"Informática\",\"estado\":\"En progreso\"}', '2026-06-21'),
(6, 72, 'Registró la actividad ID 193... (Ver info)', '{\"tipo\":\"actividad\",\"accion\":\"creacion\",\"actividad_id\":193,\"descripcion\":\"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\",\"area\":\"Informática\",\"estado\":\"En progreso\"}', '2026-06-21'),
(7, 72, 'Registró la actividad ID 194... (Ver info)', '{\"tipo\":\"actividad\",\"accion\":\"creacion\",\"actividad_id\":194,\"descripcion\":\"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\",\"area\":\"Informática\",\"estado\":\"En progreso\"}', '2026-06-21'),
(8, 72, 'Registró la actividad ID 195... (Ver info)', '{\"tipo\":\"actividad\",\"accion\":\"creacion\",\"actividad_id\":195,\"descripcion\":\"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\",\"area\":\"Informática\",\"estado\":\"En progreso\"}', '2026-06-21'),
(9, 72, 'Registró la actividad ID 196... (Ver info)', '{\"tipo\":\"actividad\",\"accion\":\"creacion\",\"actividad_id\":196,\"descripcion\":\"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\",\"area\":\"Informática\",\"estado\":\"En progreso\"}', '2026-06-21'),
(10, 72, 'Registró la actividad ID 197... (Ver info)', '{\"tipo\":\"actividad\",\"accion\":\"creacion\",\"actividad_id\":197,\"descripcion\":\"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\",\"area\":\"Informática\",\"estado\":\"En progreso\"}', '2026-06-21'),
(11, 72, 'Generó reporte PDF', '{\"fecha_inicio\":\"2026-06-21\",\"fecha_fin\":\"2026-06-21\",\"total\":10}', '2026-06-21'),
(12, 72, 'Eliminó al usuario ID 75... (Ver info)', '{\"tipo\":\"usuario\",\"accion\":\"eliminacion\",\"usuario_id\":75,\"nombre\":\"Jose Ventura\"}', '2026-06-21'),
(13, 72, 'Eliminó al empleado ID 43... (Ver info)', '{\"tipo\":\"empleado\",\"accion\":\"eliminacion\",\"empleado_id\":43,\"nombre\":\"Emmanuel\",\"apellido\":\"Lujan\",\"formacion\":\"Ing. Sistemas\",\"correo\":\"N\\/D\",\"telefono\":\"N\\/D\"}', '2026-06-21'),
(14, 72, 'Registró al usuario ID 76... (Ver info)', '{\"tipo\":\"usuario\",\"accion\":\"registro\",\"nombre\":\"Jose Ventura\",\"username\":\"jose\",\"formacion\":\"\",\"correo\":\"\"}', '2026-06-21'),
(15, 76, 'Registró la actividad ID 198... (Ver info)', '{\"tipo\":\"actividad\",\"accion\":\"creacion\",\"actividad_id\":198,\"descripcion\":\"AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA\",\"area\":\"AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA\",\"estado\":\"En progreso\"}', '2026-06-21'),
(16, 72, 'Generó reporte PDF', '{\"fecha_inicio\":\"2026-06-21\",\"fecha_fin\":\"2026-06-21\",\"total\":11}', '2026-06-21'),
(17, 72, 'Registró al empleado ID 45... (Ver info)', '{\"tipo\":\"empleado\",\"accion\":\"registro\",\"nombre\":\"Emmanuel\",\"apellido\":\"Lujan\",\"formacion\":\"Ing. Sistemas\",\"correo\":\"N\\/D\",\"telefono\":\"N\\/D\"}', '2026-06-22'),
(18, 72, 'Eliminó al empleado ID 45... (Ver info)', '{\"tipo\":\"empleado\",\"accion\":\"eliminacion\",\"empleado_id\":45,\"nombre\":\"Emmanuel\",\"apellido\":\"Lujan\",\"formacion\":\"Ing. Sistemas\",\"correo\":\"N\\/D\",\"telefono\":\"N\\/D\"}', '2026-06-22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `formacion` varchar(180) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `username` varchar(120) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `rol` enum('admin','tecnico') DEFAULT 'tecnico',
  `pregunta_1` varchar(80) DEFAULT NULL,
  `respuesta_1_hash` char(64) DEFAULT NULL,
  `pregunta_2` varchar(80) DEFAULT NULL,
  `respuesta_2_hash` char(64) DEFAULT NULL,
  `pregunta_3` varchar(80) DEFAULT NULL,
  `respuesta_3_hash` char(64) DEFAULT NULL,
  `recuperacion_actualizado_en` datetime DEFAULT NULL,
  `permisos` text DEFAULT NULL COMMENT 'JSON permisos granulares'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_completo`, `formacion`, `password`, `correo`, `username`, `telefono`, `rol`, `pregunta_1`, `respuesta_1_hash`, `pregunta_2`, `respuesta_2_hash`, `pregunta_3`, `respuesta_3_hash`, `recuperacion_actualizado_en`, `permisos`) VALUES
(72, 'Gladys Muñoz', 'Informatica y Sistemas', '$2y$10$0pY8Vrc.Pxznj1yvVAb5b.myQFRf8WJtQB9MUSXYUQv4aRvX/qdBu', 'gladys1@gmail.com', 'gladys', '', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '{\"actividades_editar\":true,\"actividades_eliminar\":true,\"actividades_finalizar\":true,\"actividades_info\":true,\"empleados_listar\":true,\"empleados_registrar\":true,\"empleados_editar\":true,\"empleados_eliminar\":true,\"empleados_info\":true,\"usuarios_listar\":true,\"usuarios_registrar\":true,\"usuarios_editar\":true,\"usuarios_eliminar\":true,\"reportes_pdf\":true,\"bitacora\":true,\"roles_gestionar\":true}'),
(76, 'Jose Ventura', '', '$2y$10$X6TzTTI39sYBmZrajfg09ePlrAY5AbnO1I2Do7womzmwB6hRb1XvC', '', 'jose', '', 'tecnico', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '{\"actividades_editar\":true,\"actividades_eliminar\":false,\"actividades_finalizar\":true,\"actividades_info\":true,\"empleados_listar\":true,\"empleados_registrar\":false,\"empleados_editar\":false,\"empleados_eliminar\":false,\"empleados_info\":false,\"usuarios_listar\":false,\"usuarios_registrar\":false,\"usuarios_editar\":false,\"usuarios_eliminar\":true,\"reportes_pdf\":false,\"bitacora\":false,\"super_admin\":false}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_recuperacion`
--

CREATE TABLE `usuario_recuperacion` (
  `usuario_id` int(11) NOT NULL,
  `pregunta_1` varchar(80) NOT NULL,
  `respuesta_1_hash` char(64) NOT NULL,
  `pregunta_2` varchar(80) NOT NULL,
  `respuesta_2_hash` char(64) NOT NULL,
  `pregunta_3` varchar(80) NOT NULL,
  `respuesta_3_hash` char(64) NOT NULL,
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_recuperacion`
--

INSERT INTO `usuario_recuperacion` (`usuario_id`, `pregunta_1`, `respuesta_1_hash`, `pregunta_2`, `respuesta_2_hash`, `pregunta_3`, `respuesta_3_hash`, `actualizado_en`) VALUES
(72, 'mascota', '297581d6cd198a6e6df740f13288cb13a1e76cebe3f0ebc3fe259977addfd646', 'pelicula', '8c2a25260209b2db50e9d7c369876ddeeaebde2472a38426ca4907fbe4135921', 'comida', 'a4c18ee0ada59e343691ef4ddc0e502b86679f2eaa6a5576a0ec3c9ea0658e36', '2026-05-15 00:24:20'),
(76, 'mascota', '52032260ad6b5f6d43ff16181fb5e28baffb7ba8f1a8f377f79b05998c4e2d6b', 'pelicula', '8c2a25260209b2db50e9d7c369876ddeeaebde2472a38426ca4907fbe4135921', 'comida', 'a4c18ee0ada59e343691ef4ddc0e502b86679f2eaa6a5576a0ec3c9ea0658e36', '2026-06-21 16:35:15');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `actividad_empleado`
--
ALTER TABLE `actividad_empleado`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_act_emp` (`actividad_id`,`empleado_id`),
  ADD KEY `idx_ae_actividad` (`actividad_id`),
  ADD KEY `idx_ae_empleado` (`empleado_id`);

--
-- Indices de la tabla `actividad_historial`
--
ALTER TABLE `actividad_historial`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_hist_actividad` (`actividad_id`),
  ADD KEY `idx_hist_usuario` (`usuario_id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logs_sistema`
--
ALTER TABLE `logs_sistema`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_logs_fecha` (`fecha`),
  ADD KEY `idx_logs_usuario` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `usuario_recuperacion`
--
ALTER TABLE `usuario_recuperacion`
  ADD PRIMARY KEY (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT de la tabla `actividad_empleado`
--
ALTER TABLE `actividad_empleado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `actividad_historial`
--
ALTER TABLE `actividad_historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=296;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `logs_sistema`
--
ALTER TABLE `logs_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `actividades_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `actividad_empleado`
--
ALTER TABLE `actividad_empleado`
  ADD CONSTRAINT `fk_ae_actividad` FOREIGN KEY (`actividad_id`) REFERENCES `actividades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ae_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `logs_sistema`
--
ALTER TABLE `logs_sistema`
  ADD CONSTRAINT `fk_logs_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario_recuperacion`
--
ALTER TABLE `usuario_recuperacion`
  ADD CONSTRAINT `fk_recuperacion_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
