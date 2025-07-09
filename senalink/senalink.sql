-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-07-2025 a las 18:37:42
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `senalink`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `validar_login` (IN `correo` VARCHAR(100), IN `password` VARCHAR(255))   BEGIN
    -- Declaración de variables
    DECLARE stored_password_user VARCHAR(255);
    DECLARE user_role ENUM('SuperAdmin', 'AdminSENA', 'Otro');

    -- Validación para Usuarios (SuperAdmin, AdminSENA, Otro)
    IF EXISTS (SELECT * FROM usuarios WHERE correo = correo) THEN
        -- Si el correo es encontrado, validamos la contraseña
        SELECT contrasena INTO stored_password_user
        FROM usuarios
        WHERE correo = correo;

        -- Verificar si la contraseña ingresada coincide con la almacenada
        IF password = stored_password_user THEN
            -- Obtener el rol del usuario
            SELECT rol INTO user_role
            FROM usuarios
            WHERE correo = correo;

            -- Mensaje según el rol del usuario
            IF user_role = 'SuperAdmin' THEN
                SELECT 'Bienvenido Super Administrador' AS mensaje;
            ELSEIF user_role = 'AdminSENA' THEN
                SELECT 'Bienvenido Administrador SENA' AS mensaje;
            ELSE
                SELECT CONCAT('Bienvenido Usuario con rol: ', user_role) AS mensaje;
            END IF;
        ELSE
            -- Si la contraseña no es correcta
            SELECT 'Credenciales incorrectas para Usuario' AS mensaje;
        END IF;
    ELSE
        -- Si no se encuentra el correo en la tabla de usuarios
        SELECT 'Credenciales incorrectas' AS mensaje;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diagnosticos_empresariales`
--

CREATE TABLE `diagnosticos_empresariales` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `resultado` text NOT NULL,
  `fecha_realizacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `diagnosticos_empresariales`
--

INSERT INTO `diagnosticos_empresariales` (`id`, `empresa_id`, `resultado`, `fecha_realizacion`) VALUES
(1, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Asistentes administrativos\",\"pregunta3\":\"Tecn\\u00f3logo\",\"pregunta4\":\"Log\\u00edstica y transporte\"}', '2025-07-09 02:41:11'),
(2, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Servicios\"}', '2025-07-09 03:05:46'),
(3, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Servicios\"}', '2025-07-09 03:13:11'),
(4, 0, '[]', '2025-07-09 03:13:23'),
(5, 0, '[]', '2025-07-09 03:13:24'),
(6, 0, '[]', '2025-07-09 03:13:24'),
(7, 0, '[]', '2025-07-09 03:13:25'),
(8, 0, '[]', '2025-07-09 03:13:25'),
(9, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Servicios\"}', '2025-07-09 03:20:22'),
(10, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Servicios\"}', '2025-07-09 03:22:05'),
(11, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Servicios\"}', '2025-07-09 03:24:01'),
(12, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Industrial\"}', '2025-07-09 03:33:43'),
(13, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Industrial\"}', '2025-07-09 03:41:00'),
(14, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Industrial\"}', '2025-07-09 03:47:39'),
(15, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Industrial\"}', '2025-07-09 03:49:11'),
(16, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Industrial\"}', '2025-07-09 03:51:28'),
(17, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Industrial\"}', '2025-07-09 03:52:29'),
(18, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Industrial\"}', '2025-07-09 03:55:25'),
(19, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Industrial\"}', '2025-07-09 04:01:22'),
(20, 0, '{\"pregunta1\":\"Industrial\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Industrial\"}', '2025-07-09 04:01:48'),
(21, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Industrial\"}', '2025-07-09 04:02:49'),
(22, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Industrial\"}', '2025-07-09 04:12:26'),
(23, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Industrial\"}', '2025-07-09 04:13:48'),
(24, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Industrial\"}', '2025-07-09 04:16:20'),
(25, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Industrial\"}', '2025-07-09 04:19:37'),
(26, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Industrial\"}', '2025-07-09 04:24:58'),
(27, 0, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Mec\\u00e1nicos de Motos\",\"pregunta3\":\"T\\u00e9cnico\",\"pregunta4\":\"Industrial\"}', '2025-07-09 04:28:18'),
(30, 39, '{\"pregunta1\":\"Servicios\",\"pregunta2\":\"Auxiliares t\\u00e9cnicos en electr\\u00f3nica\",\"pregunta3\":\"Tecn\\u00f3logo\",\"pregunta4\":\"Electricidad\"}', '2025-07-09 06:23:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones`
--

CREATE TABLE `opciones` (
  `id` int(11) NOT NULL,
  `id_pregunta` int(11) NOT NULL,
  `texto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `opciones`
--

INSERT INTO `opciones` (`id`, `id_pregunta`, `texto`) VALUES
(35, 3, 'Técnico'),
(36, 3, 'Tecnólogo'),
(63, 1, 'Industrial'),
(64, 1, 'Servicios'),
(65, 8, 'Servicios'),
(66, 8, 'Electricidad'),
(67, 8, 'Industrial'),
(68, 8, 'Textiles'),
(69, 8, 'Construcción'),
(70, 2, 'Auxiliares de información y servicio al cliente'),
(71, 2, 'Mecánicos Industriales'),
(72, 2, 'Oficiales de Construcción'),
(73, 2, 'Técnicos en Construcción y Arquitectura'),
(74, 2, 'Auxiliares técnicos en electrónica'),
(75, 2, 'Dibujantes Técnicos'),
(76, 2, 'Mecánicos de vehículos automotores'),
(77, 2, 'Técnicos en mecánica y construcción mecánica'),
(78, 2, 'Patronistas de Productos de Tela, Cuero y Piel'),
(79, 2, 'Electricistas de Vehículos Automotores'),
(80, 2, 'Electricistas Residenciales'),
(81, 2, 'Técnicos en automatización e instrumentación'),
(82, 2, 'Supervisores de ajustadores de máquinas herramientas'),
(83, 2, 'Ajustadores y operadores de máquinas herramientas'),
(84, 2, 'Sastres, Modistos, Peleteros y Sombrereros'),
(85, 2, 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos'),
(86, 2, 'Mecánicos de Motos'),
(87, 2, 'Instaladores Residenciales y Comerciales'),
(88, 2, 'Supervisores de electricidad y telecomunicaciones'),
(89, 2, 'Operadores de Cámara de Cine y Televisión'),
(90, 2, 'Ensambladores e Inspectores de Muebles y Accesorios'),
(91, 2, 'Supervisores de Fabricación de Productos de Tela, Cuero y Piel'),
(92, 2, 'Operadores de Máquinas Herramientas'),
(93, 2, 'Supervisores de mecánica'),
(94, 2, 'Productores, directores artísticos y ocupaciones relacionadas'),
(95, 2, 'Pintores y Empapeladores'),
(96, 2, 'Topógrafos'),
(97, 2, 'Pintores, escultores y otros artistas visuales'),
(98, 2, 'Soldadores'),
(99, 2, 'Electricistas Industriales'),
(100, 2, 'Técnicos de aire acondicionado y refrigeración'),
(101, 2, 'Técnicos en electrónica'),
(102, 2, 'Técnicos en Diseño y Arte Gráfico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(1, 'chicaalzateb@gmail.com', '50cfa97bcaf605856bf3ceccd6e9cca4a4c86c98d752dda697a0582309050316', '2025-06-17 08:25:49', '2025-06-17 05:25:49'),
(2, 'chicaalzateb@gmail.com', 'fb1fbc709630882ad640e184d776b8fdc1e2c90d1ca1b413d654ee99d56e6ebe', '2025-06-17 08:26:14', '2025-06-17 05:26:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id` int(11) NOT NULL,
  `enunciado` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id`, `enunciado`, `fecha_creacion`) VALUES
(1, '¿A qué sector productivo pertenece su empresa?', '2025-07-01 22:09:30'),
(2, '¿En qué tipo de ocupación considera que se necesita talento humano?', '2025-07-01 22:09:30'),
(3, 'Que nivel de formacion considera mas adecuado para su empresa?', '2025-07-01 22:09:30'),
(8, '¿Que sector considera que es requerido en la empresa?', '2025-07-09 02:53:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas_formacion`
--

CREATE TABLE `programas_formacion` (
  `id` int(11) NOT NULL,
  `nombre_programa` varchar(50) NOT NULL,
  `duracion_programa` int(11) NOT NULL,
  `nivel_formacion` varchar(50) DEFAULT NULL,
  `estado` enum('En ejecucion','Finalizado') DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT NULL,
  `ficha` int(11) DEFAULT NULL,
  `habilidades_requeridas` text DEFAULT NULL,
  `fecha_finalizacion` date NOT NULL,
  `descripcion` text DEFAULT NULL,
  `codigo` int(11) NOT NULL,
  `sector_programa` varchar(50) DEFAULT NULL,
  `nombre_ocupacion` varchar(100) DEFAULT NULL,
  `sector_economico` varchar(50) DEFAULT NULL,
  `etapa_ficha` enum('Lectiva','Practica') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programas_formacion`
--

INSERT INTO `programas_formacion` (`id`, `nombre_programa`, `duracion_programa`, `nivel_formacion`, `estado`, `fecha_creacion`, `ficha`, `habilidades_requeridas`, `fecha_finalizacion`, `descripcion`, `codigo`, `sector_programa`, `nombre_ocupacion`, `sector_economico`, `etapa_ficha`) VALUES
(8, 'MARKETING DIGITAL PARA EL SISTEMA MODA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2027-02-28', NULL, 135329, '', 'Auxiliares de información y servicio al cliente', 'Servicios', 'Lectiva'),
(9, 'MECANICA DE MAQUINARIA INDUSTRIAL', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-05-15', NULL, 837101, '', 'Mecánicos Industriales', 'Industria', 'Practica'),
(10, 'MECANICA DE MAQUINARIA INDUSTRIAL', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-05-15', NULL, 837101, '', 'Mecánicos Industriales', 'Industria', 'Lectiva'),
(11, 'CONSTRUCCION, MANTENIMIENTO Y REPARACION DE ESTRUC', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 836136, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(12, 'CONSTRUCCION DE INFRAESTRUCTURA VIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-03-18', NULL, 223107, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(13, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-05-15', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(14, 'DIBUJO DIGITAL DE ARQUITECTURA E INGENIERIA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-09-09', NULL, 225224, '', 'Dibujantes Técnicos', 'Servicios', 'Lectiva'),
(15, 'MANTENIMIENTO DE VEHICULOS LIVIANOS', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-05-06', NULL, 838109, '', 'Mecánicos de vehículos automotores', 'Industria', 'Lectiva'),
(16, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-05-06', NULL, 223104, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(17, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL.', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-05-06', NULL, 223213, '', 'Técnicos en mecánica y construcción mecánica', 'Industria', 'Lectiva'),
(18, 'MANTENIMIENTO Y REPARACION DE EDIFICACIONES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-09-06', NULL, 836137, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(19, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-11', NULL, 524500, '', 'Patronistas de Productos de Tela, Cuero y Piel', 'Textiles', 'Lectiva'),
(20, 'MANTENIMIENTO DE SISTEMAS DE PROPULSION ELECTRICA ', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-25', NULL, 838210, '', 'Electricistas de Vehículos Automotores', 'Electricidad', 'Lectiva'),
(21, 'INSTALACION DE SISTEMAS ELECTRICOS RESIDENCIALES Y', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2024-01-21', NULL, 832202, '', 'Electricistas Residenciales', 'Electricidad', 'Practica'),
(22, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-09-05', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(23, 'DIBUJO MECANICO', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-09-05', NULL, 225220, '', 'Dibujantes Técnicos', 'Servicios', 'Lectiva'),
(24, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-03-12', NULL, 223104, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(25, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-03-12', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(26, 'PRODUCCION DE COMPONENTES MECANICOS CON MAQUINAS D', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-03-12', NULL, 821100, '', 'Supervisores de ajustadores de máquinas herramientas', 'Industria', 'Lectiva'),
(27, 'DESARROLLO DE COMPONENTES MECANICOS', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-28', NULL, 223211, '', 'Técnicos en mecánica y construcción mecánica', 'Industria', 'Lectiva'),
(28, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-10', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(29, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-03-12', NULL, 223104, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(30, 'OPERACION EN TORNO Y FRESADORA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-02-26', NULL, 831102, '', 'Ajustadores y operadores de máquinas herramientas', 'Construccion', 'Lectiva'),
(31, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-12-10', NULL, 223104, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(32, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-03-12', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(33, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-03-12', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(34, 'ELABORACION DE PRENDAS DE VESTIR SOBRE MEDIDAS', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-29', NULL, 842200, '', 'Sastres, Modistos, Peleteros y Sombrereros', 'Textiles', 'Lectiva'),
(35, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-11', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(36, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-11', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(37, 'OPERACION EN TORNO Y FRESADORA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-11', NULL, 831102, 'Servicios', 'Ajustadores y operadores de máquinas herramientas', 'Servicios', 'Lectiva'),
(38, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-03-29', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Industria', 'Lectiva'),
(39, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Industria', 'Lectiva'),
(40, 'MANTENIMIENTO DE VEHICULOS LIVIANOS', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 838109, 'Servicios', 'Mecánicos de vehículos automotores', 'Industria', 'Lectiva'),
(41, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-03-29', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(42, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-01-14', NULL, 524300, 'Servicios', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(43, 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-01-14', NULL, 838318, 'Servicios', 'Mecánicos de Motos', 'servicios', 'Lectiva'),
(44, 'CONSTRUCCION, MANTENIMIENTO Y REPARACION DE ESTRUC', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(45, 'ELABORACION DE PRENDAS DE VESTIR SOBRE MEDIDAS', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-29', NULL, 842200, '', 'Sastres, Modistos, Peleteros y Sombrereros', 'Textiles', 'Lectiva'),
(46, 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-03-06', NULL, 838318, 'Servicios', 'Mecánicos de Motos', 'servicios', 'Lectiva'),
(47, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 223104, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(48, 'CARPINTERIA DE ALUMINIO', 1792, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-04-27', NULL, 841100, '', 'Instaladores Residenciales y Comerciales', 'Servicios', 'Lectiva'),
(49, 'ELABORACION DE PRENDAS DE VESTIR SOBRE MEDIDAS', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-04-29', NULL, 842200, '', 'Sastres, Modistos, Peleteros y Sombrereros', 'Textiles', 'Lectiva'),
(50, 'INSTALACION DE SISTEMAS ELECTRICOS RESIDENCIALES Y', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-04-26', NULL, 832202, '', 'Electricistas Residenciales', 'Electricidad', 'Lectiva'),
(51, 'ELECTRICIDAD INDUSTRIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-03-12', NULL, 821222, '', 'Supervisores de electricidad y telecomunicaciones', 'Electricidad', 'Lectiva'),
(52, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-09-05', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(53, 'INSTALACION DE SISTEMAS ELECTRICOS RESIDENCIALES Y', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-09-05', NULL, 832202, '', 'Electricistas Residenciales', 'Electricidad', 'Lectiva'),
(54, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-15', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(55, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-15', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(56, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-15', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(57, 'SUPERVISION EN PROCESOS DE CONFECCION', 3984, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-03-16', NULL, 922500, 'Servicios', 'Supervisores de Fabricación de Productos de Tela, Cuero y Piel', 'Textiles', 'Practica'),
(58, 'ELABORACION DE AUDIOVISUALES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 522211, '', 'Operadores de Cámara de Cine y Televisión', 'Servicios', 'Lectiva'),
(59, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(60, 'ELABORACION DE AUDIOVISUALES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 522211, '', 'Operadores de Cámara de Cine y Televisión', 'Servicios', 'Lectiva'),
(61, 'ELABORACION DE AUDIOVISUALES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 522211, '', 'Operadores de Cámara de Cine y Televisión', 'Servicios', 'Lectiva'),
(62, 'MECANIZADO EN TORNO Y FRESADORA CONVENCIONAL', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 941106, '', 'Operadores de Máquina Herramientas', 'Industria', 'Lectiva'),
(63, 'MECANIZADO EN TORNO Y FRESADORA CONVENCIONAL', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 941106, 'Servicios', 'Operadores de Máquina Herramientas', 'Servicios', 'Lectiva'),
(64, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(65, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES', 2352, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(66, 'GESTION DEL MANTENIMIENTO DE AUTOMOTORES.', 3984, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-05-10', NULL, 821620, 'Servicios', 'Supervisores de mecánica', 'Industria', 'Lectiva'),
(67, 'PRODUCCION DE MEDIOS AUDIOVISUALES DIGITALES', 3984, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 513101, 'Servicios', 'Productores, directores artísticos y ocupaciones relacionadas', 'Servicios', 'Lectiva'),
(68, 'PINTURA ARQUITECTONICA Y ACABADOS ESPECIALES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 836600, '', 'Pintores y Empapeladores', 'Construccion', 'Lectiva'),
(69, 'DIBUJO DIGITAL DE ARQUITECTURA E INGENIERIA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 225224, 'Servicios', 'Dibujantes Técnicos', 'Servicios', 'Lectiva'),
(70, 'ANIMACION DIGITAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 513601, 'Servicios', 'Pintores, escultores y otros artistas visuales', 'Servicios', 'Lectiva'),
(71, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 836138, '', 'Soldadores', 'Construccion', 'Lectiva'),
(72, 'CONSTRUCCION EN EDIFICACIONES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 223104, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(73, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(74, 'DESARROLLO DE MEDIOS GRAFICOS VISUALES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 522309, '', 'Técnicos en Diseño y Arte Gráfico', 'Industria', 'Lectiva'),
(75, 'ELECTRICIDAD INDUSTRIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 821222, '', 'Supervisores de electricidad y telecomunicaciones', 'Electricidad', 'Lectiva'),
(76, 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS.', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 836138, '', 'Soldadores', 'Industria', 'Lectiva'),
(77, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 839317, 'Servicios', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(78, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(79, 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 838318, 'Servicios', 'Mecánicos de motos', 'servicios', 'Lectiva'),
(80, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL .', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 223213, '', 'Técnicos en mecánica y construcción mecánica', 'Industria', 'Lectiva'),
(81, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 524500, '', 'Patronistas de Productos de Tela, Cuero y Piel', 'Textiles', 'Lectiva'),
(82, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(83, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(84, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(85, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES', 2352, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(86, 'GESTION DEL MANTENIMIENTO DE AUTOMOTORES.', 3984, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-10', NULL, 821620, 'Servicios', 'Supervisores de mecánica', 'Industria', 'Lectiva'),
(87, 'PRODUCCION DE MEDIOS AUDIOVISUALES DIGITALES', 3984, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 513101, 'Servicios', 'Productores, directores artísticos y ocupaciones relacionadas', 'Servicios', 'Lectiva'),
(88, 'PINTURA ARQUITECTONICA Y ACABADOS ESPECIALES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 836600, '', 'Pintores y Empapeladores', 'Construccion', 'Lectiva'),
(89, 'DIBUJO DIGITAL DE ARQUITECTURA E INGENIERIA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 225224, 'Servicios', 'Dibujantes Técnicos', 'Servicios', 'Lectiva'),
(90, 'ANIMACION DIGITAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 513601, 'Servicios', 'Pintores, escultores y otros artistas visuales', 'Servicios', 'Lectiva'),
(91, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 836138, '', 'Soldadores', 'Construccion', 'Lectiva'),
(92, 'CONSTRUCCION EN EDIFICACIONES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 223104, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(93, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(94, 'DESARROLLO DE MEDIOS GRAFICOS VISUALES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 522309, '', 'Técnicos en Diseño y Arte Gráfico', 'Industria', 'Lectiva'),
(95, 'ELECTRICIDAD INDUSTRIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 821222, '', 'Supervisores de electricidad y telecomunicaciones', 'Electricidad', 'Lectiva'),
(96, 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS.', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 836138, '', 'Soldadores', 'Industria', 'Lectiva'),
(97, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 839317, 'Servicios', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(98, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(99, 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 838318, 'Servicios', 'Mecánicos de motos', 'servicios', 'Lectiva'),
(100, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL .', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 223213, '', 'Técnicos en mecánica y construcción mecánica', 'Industria', 'Lectiva'),
(101, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 524500, '', 'Patronistas de Productos de Tela, Cuero y Piel', 'Textiles', 'Lectiva'),
(102, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(103, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(104, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(105, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES', 2352, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(106, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-12-11', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(107, 'SUPERVISION EN PROCESOS DE CONFECCION', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-03-16', NULL, 922500, '', 'Supervisores de Fabricación de Productos de Tela, Cuero y Piel', 'Textiles', 'Practica'),
(108, 'ELABORACION DE AUDIOVISUALES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 522211, 'Servicios', 'Operadores de Cámara de Cine y Televisión', 'Servicios', 'Lectiva'),
(109, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(110, 'OPERACION EN TORNO Y FRESADORA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-12-11', NULL, 831102, '', 'Ajustadores y operadores de máquinas herramientas', 'Industria', 'Lectiva'),
(111, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(112, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 524500, '', 'Patronistas de Productos de Tela, Cuero y Piel', 'Textiles', 'Lectiva'),
(113, 'ELECTRICIDAD INDUSTRIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-15', NULL, 821222, '', 'Supervisores de electricidad y telecomunicaciones', 'Electricidad', 'Lectiva'),
(114, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(115, 'ELABORACION DE AUDIOVISUALES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-12-11', NULL, 522211, 'Servicios', 'Operadores de Cámara de Cine y Televisión', 'Servicios', 'Lectiva'),
(116, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(117, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-12-11', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(118, 'ELABORACION DE AUDIOVISUALES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 522211, 'Servicios', 'Operadores de Cámara de Cine y Televisión', 'Servicios', 'Lectiva'),
(119, 'ELABORACION DE AUDIOVISUALES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 522211, 'Servicios', 'Operadores de Cámara de Cine y Televisión', 'Servicios', 'Lectiva'),
(120, 'MECANIZADO EN TORNO Y FRESADORA CONVENCIONAL', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 941106, '', 'Operadores de Máquinas Herramientas', 'Industria', 'Lectiva'),
(121, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(122, 'ELABORACION DE AUDIOVISUALES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 522211, 'Servicios', 'Operadores de Cámara de Cine y Televisión', 'Servicios', 'Lectiva'),
(123, 'MECANIZADO EN TORNO Y FRESADORA CONVENCIONAL', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 941106, '', 'Operadores de Máquinas Herramientas', 'Industria', 'Lectiva'),
(124, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(125, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-14', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(126, 'GESTION DEL MANTENIMIENTO DE AUTOMOTORES.', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2027-05-10', NULL, 821620, 'Servicios', 'Supervisores de mecánica', 'Industria', 'Lectiva'),
(127, 'PRODUCCION DE MEDIOS AUDIOVISUALES DIGITALES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-10-07', NULL, 513101, 'Servicios', 'Productores, directores artísticos y ocupaciones relacionadas', 'Servicios', 'Lectiva'),
(128, 'PINTURA ARQUITECTONICA Y ACABADOS ESPECIALES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 836600, '', 'Pintores y Empapeladores', 'Construccion', 'Lectiva'),
(129, 'DIBUJO DIGITAL DE ARQUITECTURA E INGENIERIA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-05-10', NULL, 225224, '', 'Dibujantes Técnicos', 'Servicios', 'Lectiva'),
(130, 'CATASTRO MULTIPROPOSITO', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 225314, '', 'Topógrafos', 'Construccion', 'Lectiva'),
(131, 'PRODUCCION DE MEDIOS AUDIOVISUALES DIGITALES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-14', NULL, 513101, 'Servicios', 'Productores, directores artísticos y ocupaciones relacionadas', 'Servicios', 'Lectiva'),
(132, 'ANIMACION DIGITAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-14', NULL, 513601, 'Servicios', 'Pintores, escultores y otros artistas visuales', 'Servicios', 'Lectiva'),
(133, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-12-11', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(134, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2027-01-14', NULL, 223104, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(135, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-14', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(136, 'SOLDADURA DE PRODUCTOS METALICOS EN PLATINA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 834258, '', 'Soldadores', 'Industria', 'Practica'),
(137, 'ELECTRICISTA INDUSTRIAL', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-01-14', NULL, 832102, '', 'Electricistas Industriales', 'Electricidad', 'Lectiva'),
(138, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2027-05-10', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(139, 'SOLDADURA DE PRODUCTOS METALICOS EN PLATINA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-04-21', NULL, 834258, '', 'Soldadores', 'Industria', 'Practica'),
(140, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL .', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-14', NULL, 223213, '', 'Técnicos en mecánica y construcción mecánica', 'Industria', 'Lectiva'),
(141, 'MANTENIMIENTO DE EQUIPOS DE AIRE ACONDICIONADO Y R', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-05-10', NULL, 837501, '', 'Técnicos de aire acondicionado y refrigeración', 'Industria', 'Lectiva'),
(142, 'PRODUCCION DE MEDIOS AUDIOVISUALES DIGITALES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-10-07', NULL, 513101, 'Servicios', 'Productores, directores artísticos y ocupaciones relacionadas', 'Servicios', 'Lectiva'),
(143, 'ELECTRICIDAD INDUSTRIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2027-05-10', NULL, 821222, '', 'Supervisores de electricidad y telecomunicaciones', 'Electricidad', 'Lectiva'),
(144, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL .', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-10-07', NULL, 223213, '', 'Técnicos en mecánica y construcción mecánica', 'Industria', 'Lectiva'),
(145, 'DESARROLLO DE SISTEMAS ELECTRONICOS INDUSTRIALES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-04-21', NULL, 224201, '', 'Técnicos en electrónica', 'Electricidad', 'Lectiva'),
(146, 'ELECTRICIDAD INDUSTRIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-10-07', NULL, 821222, '', 'Supervisores de electricidad y telecomunicaciones', 'Electricidad', 'Lectiva'),
(147, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-16', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(148, 'ANIMACION DIGITAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-04-22', NULL, 513601, 'Servicios', 'Pintores, escultores y otros artistas visuales', 'Servicios', 'Lectiva'),
(149, 'SOLDADURA DE PRODUCTOS METALICOS EN PLATINA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-05-10', NULL, 834258, '', 'Soldadores', 'Industria', 'Lectiva'),
(150, 'DIBUJO DIGITAL DE ARQUITECTURA E INGENIERIA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-10-07', NULL, 225224, '', 'Dibujantes Técnicos', 'Servicios', 'Lectiva'),
(151, 'ANIMACION DIGITAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-04-21', NULL, 513601, 'Servicios', 'Pintores, escultores y otros artistas visuales', 'Servicios', 'Lectiva'),
(152, 'ELECTRICIDAD INDUSTRIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-14', NULL, 821222, '', 'Supervisores de electricidad y telecomunicaciones', 'Electricidad', 'Lectiva'),
(153, 'INSTALACION DE SISTEMAS ELECTRICOS RESIDENCIALES Y', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-10-07', NULL, 832202, '', 'Electricistas Residenciales', 'Electricidad', 'Lectiva'),
(154, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-12-11', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(155, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-12-31', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(156, 'MANTENIMIENTO ELECTRICO Y CONTROL ELECTRONICO DE A', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-05-10', NULL, 838200, 'Servicios', 'Electricistas de Vehículos Automotores', 'Electricidad', 'Lectiva'),
(157, 'MARKETING DIGITAL PARA EL SISTEMA MODA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 135329, '', 'Auxiliares de información y servicio al cliente', 'Servicios', 'Lectiva'),
(158, 'INSTALACION DE SISTEMAS ELECTRICOS RESIDENCIALES Y', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 832202, '', 'Electricistas Residenciales', 'Electricidad', 'Lectiva'),
(159, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-10-07', NULL, 524500, '', 'Patronistas de Productos de Tela, Cuero y Piel', 'Textiles', 'Lectiva'),
(160, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-08-13', NULL, 524500, '', 'Patronistas de Productos de Tela, Cuero y Piel', 'Textiles', 'Lectiva'),
(161, 'CONSTRUCCIONES LIVIANAS INDUSTRIALIZADAS EN SECO', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-10-07', NULL, 836135, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(162, 'DIBUJO Y MODELADO ARQUITECTONICO Y DE INGENIERIA', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-12-31', NULL, 225219, '', 'Dibujantes Técnicos', 'Servicios', 'Lectiva'),
(163, 'MANTENIMIENTO DE EQUIPOS DE REFRIGERACION, VENTILA', 2205, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-04-21', NULL, 837530, 'Servicios', 'Técnicos de aire acondicionado y refrigeración', 'Industria', 'Lectiva'),
(164, 'MARKETING DIGITAL PARA EL SISTEMA MODA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-10-07', NULL, 135329, '', 'Auxiliares de información y servicio al cliente', 'Servicios', 'Lectiva'),
(165, 'PRODUCCION DE MEDIOS AUDIOVISUALES DIGITALES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-12-31', NULL, 513101, 'Servicios', 'Productores, directores artísticos y ocupaciones relacionadas', 'Servicios', 'Lectiva'),
(166, 'MANTENIMIENTO DE EQUIPOS DE AIRE ACONDICIONADO Y R', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-10-07', NULL, 837501, '', 'Técnicos de aire acondicionado y refrigeración', 'Industria', 'Lectiva'),
(167, 'MARKETING DIGITAL PARA EL SISTEMA MODA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2027-02-28', NULL, 135329, '', 'Auxiliares de información y servicio al cliente', 'Servicios', 'Lectiva'),
(168, 'MECANICA DE MAQUINARIA INDUSTRIAL', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-05-15', NULL, 837101, '', 'Mecánicos Industriales', 'Industria', 'Practica'),
(169, 'MECANICA DE MAQUINARIA INDUSTRIAL', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-05-15', NULL, 837101, '', 'Mecánicos Industriales', 'Industria', 'Lectiva'),
(170, 'CONSTRUCCION, MANTENIMIENTO Y REPARACION DE ESTRUC', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 836136, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(171, 'CONSTRUCCION DE INFRAESTRUCTURA VIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-03-18', NULL, 223107, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(172, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-05-15', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(173, 'DIBUJO DIGITAL DE ARQUITECTURA E INGENIERIA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-09-09', NULL, 225224, '', 'Dibujantes Técnicos', 'Servicios', 'Lectiva'),
(174, 'MANTENIMIENTO DE VEHICULOS LIVIANOS', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-05-06', NULL, 838109, '', 'Mecánicos de vehículos automotores', 'Industria', 'Lectiva'),
(175, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-05-06', NULL, 223104, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(176, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL.', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-05-06', NULL, 223213, '', 'Técnicos en mecánica y construcción mecánica', 'Industria', 'Lectiva'),
(177, 'MANTENIMIENTO Y REPARACION DE EDIFICACIONES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-09-06', NULL, 836137, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(178, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-11', NULL, 524500, '', 'Patronistas de Productos de Tela, Cuero y Piel', 'Textiles', 'Lectiva'),
(179, 'MANTENIMIENTO DE SISTEMAS DE PROPULSION ELECTRICA ', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-25', NULL, 838210, '', 'Electricistas de Vehículos Automotores', 'Electricidad', 'Lectiva'),
(180, 'INSTALACION DE SISTEMAS ELECTRICOS RESIDENCIALES Y', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2024-01-21', NULL, 832202, '', 'Electricistas Residenciales', 'Electricidad', 'Practica'),
(181, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-09-05', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(182, 'DIBUJO MECANICO', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-09-05', NULL, 225220, '', 'Dibujantes Técnicos', 'Servicios', 'Lectiva'),
(183, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-03-12', NULL, 223104, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(184, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-03-12', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(185, 'PRODUCCION DE COMPONENTES MECANICOS CON MAQUINAS D', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-03-12', NULL, 821100, '', 'Supervisores de ajustadores de máquinas herramientas', 'Industria', 'Lectiva'),
(186, 'DESARROLLO DE COMPONENTES MECANICOS', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-28', NULL, 223211, '', 'Técnicos en mecánica y construcción mecánica', 'Industria', 'Lectiva'),
(187, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-10', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(188, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-03-12', NULL, 223104, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(189, 'OPERACION EN TORNO Y FRESADORA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-02-26', NULL, 831102, '', 'Ajustadores y operadores de máquinas herramientas', 'Construccion', 'Lectiva'),
(190, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-12-10', NULL, 223104, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(191, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-03-12', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(192, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-03-12', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(193, 'ELABORACION DE PRENDAS DE VESTIR SOBRE MEDIDAS', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-29', NULL, 842200, '', 'Sastres, Modistos, Peleteros y Sombrereros', 'Textiles', 'Lectiva'),
(194, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-11', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(195, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-11', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(196, 'OPERACION EN TORNO Y FRESADORA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-11', NULL, 831102, 'Servicios', 'Ajustadores y operadores de máquinas herramientas', 'Servicios', 'Lectiva'),
(197, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-03-29', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Industria', 'Lectiva'),
(198, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Industria', 'Lectiva'),
(199, 'MANTENIMIENTO DE VEHICULOS LIVIANOS', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 838109, 'Servicios', 'Mecánicos de vehículos automotores', 'Industria', 'Lectiva'),
(200, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-03-29', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(201, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-01-14', NULL, 524300, 'Servicios', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(202, 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-01-14', NULL, 838318, 'Servicios', 'Mecánicos de Motos', 'servicios', 'Lectiva'),
(203, 'CONSTRUCCION, MANTENIMIENTO Y REPARACION DE ESTRUC', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(204, 'ELABORACION DE PRENDAS DE VESTIR SOBRE MEDIDAS', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-29', NULL, 842200, '', 'Sastres, Modistos, Peleteros y Sombrereros', 'Textiles', 'Lectiva'),
(205, 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-03-06', NULL, 838318, 'Servicios', 'Mecánicos de Motos', 'servicios', 'Lectiva'),
(206, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 223104, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(207, 'CARPINTERIA DE ALUMINIO', 1792, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-04-27', NULL, 841100, '', 'Instaladores Residenciales y Comerciales', 'Servicios', 'Lectiva'),
(208, 'ELABORACION DE PRENDAS DE VESTIR SOBRE MEDIDAS', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-04-29', NULL, 842200, '', 'Sastres, Modistos, Peleteros y Sombrereros', 'Textiles', 'Lectiva'),
(209, 'INSTALACION DE SISTEMAS ELECTRICOS RESIDENCIALES Y', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-04-26', NULL, 832202, '', 'Electricistas Residenciales', 'Electricidad', 'Lectiva'),
(210, 'ELECTRICIDAD INDUSTRIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-03-12', NULL, 821222, '', 'Supervisores de electricidad y telecomunicaciones', 'Electricidad', 'Lectiva'),
(211, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-09-05', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(212, 'INSTALACION DE SISTEMAS ELECTRICOS RESIDENCIALES Y', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-09-05', NULL, 832202, '', 'Electricistas Residenciales', 'Electricidad', 'Lectiva'),
(213, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-15', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(214, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-15', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(215, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-15', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(216, 'SUPERVISION EN PROCESOS DE CONFECCION', 3984, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-03-16', NULL, 922500, 'Servicios', 'Supervisores de Fabricación de Productos de Tela, Cuero y Piel', 'Textiles', 'Practica'),
(217, 'ELABORACION DE AUDIOVISUALES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 522211, '', 'Operadores de Cámara de Cine y Televisión', 'Servicios', 'Lectiva'),
(218, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(219, 'ELABORACION DE AUDIOVISUALES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 522211, '', 'Operadores de Cámara de Cine y Televisión', 'Servicios', 'Lectiva'),
(220, 'ELABORACION DE AUDIOVISUALES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 522211, '', 'Operadores de Cámara de Cine y Televisión', 'Servicios', 'Lectiva'),
(221, 'MECANIZADO EN TORNO Y FRESADORA CONVENCIONAL', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 941106, '', 'Operadores de Máquina Herramientas', 'Industria', 'Lectiva'),
(222, 'MECANIZADO EN TORNO Y FRESADORA CONVENCIONAL', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 941106, 'Servicios', 'Operadores de Máquina Herramientas', 'Servicios', 'Lectiva'),
(223, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(224, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES', 2352, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-11-12', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(225, 'GESTION DEL MANTENIMIENTO DE AUTOMOTORES.', 3984, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-05-10', NULL, 821620, 'Servicios', 'Supervisores de mecánica', 'Industria', 'Lectiva'),
(226, 'PRODUCCION DE MEDIOS AUDIOVISUALES DIGITALES', 3984, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 513101, 'Servicios', 'Productores, directores artísticos y ocupaciones relacionadas', 'Servicios', 'Lectiva'),
(227, 'PINTURA ARQUITECTONICA Y ACABADOS ESPECIALES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 836600, '', 'Pintores y Empapeladores', 'Construccion', 'Lectiva'),
(228, 'DIBUJO DIGITAL DE ARQUITECTURA E INGENIERIA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 225224, 'Servicios', 'Dibujantes Técnicos', 'Servicios', 'Lectiva'),
(229, 'ANIMACION DIGITAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 513601, 'Servicios', 'Pintores, escultores y otros artistas visuales', 'Servicios', 'Lectiva'),
(230, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 836138, '', 'Soldadores', 'Construccion', 'Lectiva'),
(231, 'CONSTRUCCION EN EDIFICACIONES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 223104, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(232, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(233, 'DESARROLLO DE MEDIOS GRAFICOS VISUALES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 522309, '', 'Técnicos en Diseño y Arte Gráfico', 'Industria', 'Lectiva'),
(234, 'ELECTRICIDAD INDUSTRIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 821222, '', 'Supervisores de electricidad y telecomunicaciones', 'Electricidad', 'Lectiva'),
(235, 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS.', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 836138, '', 'Soldadores', 'Industria', 'Lectiva'),
(236, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 839317, 'Servicios', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(237, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(238, 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 838318, 'Servicios', 'Mecánicos de motos', 'servicios', 'Lectiva'),
(239, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL .', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 223213, '', 'Técnicos en mecánica y construcción mecánica', 'Industria', 'Lectiva'),
(240, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 524500, '', 'Patronistas de Productos de Tela, Cuero y Piel', 'Textiles', 'Lectiva'),
(241, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(242, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(243, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(244, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES', 2352, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(245, 'GESTION DEL MANTENIMIENTO DE AUTOMOTORES.', 3984, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-10', NULL, 821620, 'Servicios', 'Supervisores de mecánica', 'Industria', 'Lectiva'),
(246, 'PRODUCCION DE MEDIOS AUDIOVISUALES DIGITALES', 3984, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 513101, 'Servicios', 'Productores, directores artísticos y ocupaciones relacionadas', 'Servicios', 'Lectiva'),
(247, 'PINTURA ARQUITECTONICA Y ACABADOS ESPECIALES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 836600, '', 'Pintores y Empapeladores', 'Construccion', 'Lectiva'),
(248, 'DIBUJO DIGITAL DE ARQUITECTURA E INGENIERIA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 225224, 'Servicios', 'Dibujantes Técnicos', 'Servicios', 'Lectiva'),
(249, 'ANIMACION DIGITAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 513601, 'Servicios', 'Pintores, escultores y otros artistas visuales', 'Servicios', 'Lectiva'),
(250, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 836138, '', 'Soldadores', 'Construccion', 'Lectiva'),
(251, 'CONSTRUCCION EN EDIFICACIONES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 223104, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(252, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(253, 'DESARROLLO DE MEDIOS GRAFICOS VISUALES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 522309, '', 'Técnicos en Diseño y Arte Gráfico', 'Industria', 'Lectiva'),
(254, 'ELECTRICIDAD INDUSTRIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 821222, '', 'Supervisores de electricidad y telecomunicaciones', 'Electricidad', 'Lectiva');
INSERT INTO `programas_formacion` (`id`, `nombre_programa`, `duracion_programa`, `nivel_formacion`, `estado`, `fecha_creacion`, `ficha`, `habilidades_requeridas`, `fecha_finalizacion`, `descripcion`, `codigo`, `sector_programa`, `nombre_ocupacion`, `sector_economico`, `etapa_ficha`) VALUES
(255, 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS.', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 836138, '', 'Soldadores', 'Industria', 'Lectiva'),
(256, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 839317, 'Servicios', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(257, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(258, 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 838318, 'Servicios', 'Mecánicos de motos', 'servicios', 'Lectiva'),
(259, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL .', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 223213, '', 'Técnicos en mecánica y construcción mecánica', 'Industria', 'Lectiva'),
(260, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 524500, '', 'Patronistas de Productos de Tela, Cuero y Piel', 'Textiles', 'Lectiva'),
(261, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(262, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(263, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(264, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES', 2352, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-22', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(265, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-12-11', NULL, 224315, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(266, 'SUPERVISION EN PROCESOS DE CONFECCION', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-03-16', NULL, 922500, '', 'Supervisores de Fabricación de Productos de Tela, Cuero y Piel', 'Textiles', 'Practica'),
(267, 'ELABORACION DE AUDIOVISUALES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 522211, 'Servicios', 'Operadores de Cámara de Cine y Televisión', 'Servicios', 'Lectiva'),
(268, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(269, 'OPERACION EN TORNO Y FRESADORA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-12-11', NULL, 831102, '', 'Ajustadores y operadores de máquinas herramientas', 'Industria', 'Lectiva'),
(270, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(271, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 524500, '', 'Patronistas de Productos de Tela, Cuero y Piel', 'Textiles', 'Lectiva'),
(272, 'ELECTRICIDAD INDUSTRIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-15', NULL, 821222, '', 'Supervisores de electricidad y telecomunicaciones', 'Electricidad', 'Lectiva'),
(273, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(274, 'ELABORACION DE AUDIOVISUALES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-12-11', NULL, 522211, 'Servicios', 'Operadores de Cámara de Cine y Televisión', 'Servicios', 'Lectiva'),
(275, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(276, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-12-11', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(277, 'ELABORACION DE AUDIOVISUALES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 522211, 'Servicios', 'Operadores de Cámara de Cine y Televisión', 'Servicios', 'Lectiva'),
(278, 'ELABORACION DE AUDIOVISUALES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 522211, 'Servicios', 'Operadores de Cámara de Cine y Televisión', 'Servicios', 'Lectiva'),
(279, 'MECANIZADO EN TORNO Y FRESADORA CONVENCIONAL', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 941106, '', 'Operadores de Máquinas Herramientas', 'Industria', 'Lectiva'),
(280, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(281, 'ELABORACION DE AUDIOVISUALES.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 522211, 'Servicios', 'Operadores de Cámara de Cine y Televisión', 'Servicios', 'Lectiva'),
(282, 'MECANIZADO EN TORNO Y FRESADORA CONVENCIONAL', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 941106, '', 'Operadores de Máquinas Herramientas', 'Industria', 'Lectiva'),
(283, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(284, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-14', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(285, 'GESTION DEL MANTENIMIENTO DE AUTOMOTORES.', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2027-05-10', NULL, 821620, 'Servicios', 'Supervisores de mecánica', 'Industria', 'Lectiva'),
(286, 'PRODUCCION DE MEDIOS AUDIOVISUALES DIGITALES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-10-07', NULL, 513101, 'Servicios', 'Productores, directores artísticos y ocupaciones relacionadas', 'Servicios', 'Lectiva'),
(287, 'PINTURA ARQUITECTONICA Y ACABADOS ESPECIALES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 836600, '', 'Pintores y Empapeladores', 'Construccion', 'Lectiva'),
(288, 'DIBUJO DIGITAL DE ARQUITECTURA E INGENIERIA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-05-10', NULL, 225224, '', 'Dibujantes Técnicos', 'Servicios', 'Lectiva'),
(289, 'CATASTRO MULTIPROPOSITO', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 225314, '', 'Topógrafos', 'Construccion', 'Lectiva'),
(290, 'PRODUCCION DE MEDIOS AUDIOVISUALES DIGITALES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-14', NULL, 513101, 'Servicios', 'Productores, directores artísticos y ocupaciones relacionadas', 'Servicios', 'Lectiva'),
(291, 'ANIMACION DIGITAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-14', NULL, 513601, 'Servicios', 'Pintores, escultores y otros artistas visuales', 'Servicios', 'Lectiva'),
(292, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-12-11', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(293, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2027-01-14', NULL, 223104, '', 'Técnicos en Construcción y Arquitectura', 'Construccion', 'Lectiva'),
(294, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-14', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(295, 'SOLDADURA DE PRODUCTOS METALICOS EN PLATINA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 834258, '', 'Soldadores', 'Industria', 'Practica'),
(296, 'ELECTRICISTA INDUSTRIAL', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-01-14', NULL, 832102, '', 'Electricistas Industriales', 'Electricidad', 'Lectiva'),
(297, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2027-05-10', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(298, 'SOLDADURA DE PRODUCTOS METALICOS EN PLATINA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-04-21', NULL, 834258, '', 'Soldadores', 'Industria', 'Practica'),
(299, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL .', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-14', NULL, 223213, '', 'Técnicos en mecánica y construcción mecánica', 'Industria', 'Lectiva'),
(300, 'MANTENIMIENTO DE EQUIPOS DE AIRE ACONDICIONADO Y R', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-05-10', NULL, 837501, '', 'Técnicos de aire acondicionado y refrigeración', 'Industria', 'Lectiva'),
(301, 'PRODUCCION DE MEDIOS AUDIOVISUALES DIGITALES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-10-07', NULL, 513101, 'Servicios', 'Productores, directores artísticos y ocupaciones relacionadas', 'Servicios', 'Lectiva'),
(302, 'ELECTRICIDAD INDUSTRIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2027-05-10', NULL, 821222, '', 'Supervisores de electricidad y telecomunicaciones', 'Electricidad', 'Lectiva'),
(303, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL .', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-10-07', NULL, 223213, '', 'Técnicos en mecánica y construcción mecánica', 'Industria', 'Lectiva'),
(304, 'DESARROLLO DE SISTEMAS ELECTRONICOS INDUSTRIALES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-04-21', NULL, 224201, '', 'Técnicos en electrónica', 'Electricidad', 'Lectiva'),
(305, 'ELECTRICIDAD INDUSTRIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-10-07', NULL, 821222, '', 'Supervisores de electricidad y telecomunicaciones', 'Electricidad', 'Lectiva'),
(306, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-07-16', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(307, 'ANIMACION DIGITAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-04-22', NULL, 513601, 'Servicios', 'Pintores, escultores y otros artistas visuales', 'Servicios', 'Lectiva'),
(308, 'SOLDADURA DE PRODUCTOS METALICOS EN PLATINA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-05-10', NULL, 834258, '', 'Soldadores', 'Industria', 'Lectiva'),
(309, 'DIBUJO DIGITAL DE ARQUITECTURA E INGENIERIA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-10-07', NULL, 225224, '', 'Dibujantes Técnicos', 'Servicios', 'Lectiva'),
(310, 'ANIMACION DIGITAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-04-21', NULL, 513601, 'Servicios', 'Pintores, escultores y otros artistas visuales', 'Servicios', 'Lectiva'),
(311, 'ELECTRICIDAD INDUSTRIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-07-14', NULL, 821222, '', 'Supervisores de electricidad y telecomunicaciones', 'Electricidad', 'Lectiva'),
(312, 'INSTALACION DE SISTEMAS ELECTRICOS RESIDENCIALES Y', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-10-07', NULL, 832202, '', 'Electricistas Residenciales', 'Electricidad', 'Lectiva'),
(313, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-12-11', NULL, 836138, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(314, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-12-31', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(315, 'MANTENIMIENTO ELECTRICO Y CONTROL ELECTRONICO DE A', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-05-10', NULL, 838200, 'Servicios', 'Electricistas de Vehículos Automotores', 'Electricidad', 'Lectiva'),
(316, 'MARKETING DIGITAL PARA EL SISTEMA MODA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 135329, '', 'Auxiliares de información y servicio al cliente', 'Servicios', 'Lectiva'),
(317, 'INSTALACION DE SISTEMAS ELECTRICOS RESIDENCIALES Y', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 832202, '', 'Electricistas Residenciales', 'Electricidad', 'Lectiva'),
(318, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-10-07', NULL, 524500, '', 'Patronistas de Productos de Tela, Cuero y Piel', 'Textiles', 'Lectiva'),
(319, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-08-13', NULL, 524500, '', 'Patronistas de Productos de Tela, Cuero y Piel', 'Textiles', 'Lectiva'),
(320, 'CONSTRUCCIONES LIVIANAS INDUSTRIALIZADAS EN SECO', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-10-07', NULL, 836135, '', 'Oficiales de Construcción', 'Construccion', 'Lectiva'),
(321, 'DIBUJO Y MODELADO ARQUITECTONICO Y DE INGENIERIA', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-12-31', NULL, 225219, '', 'Dibujantes Técnicos', 'Servicios', 'Lectiva'),
(322, 'MANTENIMIENTO DE EQUIPOS DE REFRIGERACION, VENTILA', 2205, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-04-21', NULL, 837530, 'Servicios', 'Técnicos de aire acondicionado y refrigeración', 'Industria', 'Lectiva'),
(323, 'MARKETING DIGITAL PARA EL SISTEMA MODA', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-10-07', NULL, 135329, '', 'Auxiliares de información y servicio al cliente', 'Servicios', 'Lectiva'),
(324, 'PRODUCCION DE MEDIOS AUDIOVISUALES DIGITALES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-12-31', NULL, 513101, 'Servicios', 'Productores, directores artísticos y ocupaciones relacionadas', 'Servicios', 'Lectiva'),
(325, 'MANTENIMIENTO DE EQUIPOS DE AIRE ACONDICIONADO Y R', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-10-07', NULL, 837501, '', 'Técnicos de aire acondicionado y refrigeración', 'Industria', 'Lectiva'),
(326, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-04-21', NULL, 224312, '', 'Técnicos en automatización e instrumentación', 'Industria', 'Lectiva'),
(327, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL .', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2027-05-10', NULL, 223213, '', 'Técnicos en mecánica y construcción mecánica', 'Industria', 'Lectiva'),
(328, 'DESARROLLO DE MEDIOS GRAFICOS VISUALES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2027-06-30', NULL, 522309, '', 'Técnicos en Diseño y Arte Gráfico', 'Industria', 'Lectiva'),
(329, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-10-07', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(330, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-02-19', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(331, 'DESARROLLO DE MEDIOS GRAFICOS VISUALES', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-06-26', NULL, 522309, '', 'Técnicos en Diseño y Arte Gráfico', 'Industria', 'Lectiva'),
(332, 'ELECTRICIDAD INDUSTRIAL', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-04-22', NULL, 821222, '', 'Supervisores de electricidad y telecomunicaciones', 'Electricidad', 'Practica'),
(333, 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-04-21', NULL, 838318, 'Servicios', 'Mecánicos de Motos', 'servicios', 'Lectiva'),
(334, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-12-15', NULL, 839317, '', 'Auxiliares técnicos en electrónica', 'Electricidad', 'Lectiva'),
(335, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-12-15', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(336, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-06-26', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(337, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL .', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2026-04-21', NULL, 223213, '', 'Técnicos en mecánica y construcción mecánica', 'Industria', 'Lectiva'),
(338, 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS.', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2026-01-14', NULL, 838318, 'Servicios', 'Mecánicos de Motos', 'servicios', 'Lectiva'),
(339, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL .', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-04-22', NULL, 223213, '', 'Técnicos en mecánica y construcción mecánica', 'Industria', 'Lectiva'),
(340, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'Tecnologo', 'En ejecucion', NULL, NULL, NULL, '2025-12-10', NULL, 524300, '', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'Textiles', 'Lectiva'),
(341, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'Tecnico', 'En ejecucion', NULL, NULL, NULL, '2025-07-14', NULL, 524500, '', 'Patronistas de Productos de Tela, Cuero y Piel', 'Textiles', 'Lectiva');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes_diagnosticos`
--

CREATE TABLE `reportes_diagnosticos` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `fecha_generacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `formato` enum('PDF','XML') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes_programas`
--

CREATE TABLE `reportes_programas` (
  `id` int(11) NOT NULL,
  `generado_por` int(11) NOT NULL,
  `fecha_generacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `formato` enum('PDF','XML') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes_usuarios`
--

CREATE TABLE `reportes_usuarios` (
  `id` int(11) NOT NULL,
  `generado_por` int(11) NOT NULL,
  `fecha_generacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `formato` enum('PDF','XML') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('empresa','AdminSENA','super_admin') DEFAULT NULL,
  `estado` enum('Activo','Suspendido','Desactivado') DEFAULT 'Activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `nit` varchar(50) DEFAULT NULL,
  `direccion` varchar(255) NOT NULL,
  `razon_social` varchar(50) DEFAULT NULL,
  `telefono` varchar(255) NOT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `representante_legal` varchar(100) DEFAULT NULL,
  `tipo_empresa` enum('Agricola','Industrial','Servicios','Conocimiento, Innovacion y Desarrollo') DEFAULT NULL,
  `primer_nombre` varchar(50) NOT NULL,
  `segundo_nombre` varchar(50) DEFAULT NULL,
  `primer_apellido` varchar(50) NOT NULL,
  `segundo_apellido` varchar(50) DEFAULT NULL,
  `tipo_documento` enum('Cedula de ciudadania','Cedula de extranjeria','Permiso especial de permanencia','Permiso de proteccion temporal') DEFAULT NULL,
  `numero_documento` bigint(20) NOT NULL,
  `diagnostico_realizado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `correo`, `contrasena`, `rol`, `estado`, `fecha_creacion`, `nit`, `direccion`, `razon_social`, `telefono`, `nickname`, `representante_legal`, `tipo_empresa`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `tipo_documento`, `numero_documento`, `diagnostico_realizado`) VALUES
(15, '1@gmail.com', '$2y$10$2Zl9GXH61P.hQvAF/4S6c.rjVB9KbL65Do.xFnvkWM/AfQYQjxwUS', 'empresa', 'Activo', '2025-05-13 12:41:32', '4721', 'cr 6 calle 9-12 primavera azul', 'Frisby S.A', '1234567891', NULL, 'Frisby', 'Industrial', '', NULL, '', NULL, NULL, 0, 0),
(16, 'brauliolapampara@gmail.com', '$2y$10$GBRcdFQgwR4.fZ06B1K1j.GecKahretHi9Lhl/7diaS8/OMqiqSAK', 'AdminSENA', 'Activo', '2025-06-13 03:32:23', NULL, '', NULL, '4444444', NULL, NULL, NULL, 'Braulio', '', 'Chica', 'Alzate', 'Cedula de ciudadania', 12345678, 0),
(17, 'crisberx@gmail.com', '$2y$10$r/BPPtq9.jQ.rPjXYYw19.c.vUemlmOITViw5KsWqhuXTa.oiUeV.', 'super_admin', 'Activo', '2025-06-17 02:34:16', NULL, 'Calle 123', NULL, '3001234567', NULL, NULL, NULL, 'Breiner', NULL, 'Chica', 'alzate', 'Cedula de ciudadania', 3001234567, 0),
(19, 'chicaalzateb@gmail.com', '$2y$10$MuUNkZMGj0EGdwCLTGe4fO8oLkfYNxZlcKJeAoURY9bIrjVPkkajm', 'empresa', 'Activo', '2025-06-17 12:23:36', '4422', 'direccion ejemplo', 'Braulio ejemplo sas', '1234567891', NULL, 'Braulio', 'Industrial', '', NULL, '', NULL, NULL, 0, 0),
(24, 'osoriolopezjuanfelipe98@gmail.com', '$2y$10$gQp4g5O5jRPd8zenNWy7eOQO8SZ2gmp3J/ygVK5dFxMUUg9/Mvtae', 'empresa', 'Activo', '2025-06-30 09:07:53', '4721233', 'Arabia', 'Monster y cuates', '345333555', NULL, 'Edwin Manito', 'Industrial', '', NULL, '', NULL, NULL, 0, 0),
(25, 'osoriolopezjuanfelipe@gmail.com', '$2y$10$.2Rlugl6baAoj7qA.nenN.urRMg0lcMjaJluQgxGiPj3vT9wfnMoW', 'AdminSENA', 'Activo', '2025-07-01 02:13:30', NULL, '', NULL, '322222222', NULL, NULL, NULL, 'Juan', 'Felipe', 'Osorio', 'Lopez', 'Cedula de ciudadania', 23456789, 0),
(26, 'crisberx1@gmail.com', '$2y$10$FLuzp334nlTSHgt5jg5.4.8GCP4cEHA4r9Gbifj6gPGjFtbBLsUky', 'empresa', 'Activo', '2025-07-02 01:49:59', '987654321', 'Villaverde', 'Migue Monster cuates', '31222333', NULL, 'Migue', 'Agricola', '', NULL, '', NULL, NULL, 0, 0),
(29, 'crisberx12@gmail.com', '$2y$10$FsATLOq0VUFelBfI6cLTDu/0x6bRBL2Dz74NwlXFtxeMUPYCNcoee', 'empresa', 'Activo', '2025-07-02 01:51:27', '9876543212', 'Villaverde 1', 'Migue Monster cuates xd', '312223333', NULL, 'Miguel', 'Servicios', '', NULL, '', NULL, NULL, 0, 0),
(33, 'alejito@gmail.com', '$2y$10$IFmSsKZuEZdGg2bAolpJwuov/vHyg48taJiICN6FXCaXwBO9PD9LW', 'empresa', 'Activo', '2025-07-02 02:02:49', '333666999', 'Islas canarias', 'Alejo asa', '3122233344', NULL, 'alejo', 'Agricola', '', NULL, '', NULL, NULL, 0, 0),
(34, 'raiba1234@gmail.com', '$2y$10$fpJ9K2vdlpo97P5KkxWYbepSefOUr2vJU9Ben0n.UdZDQoJlXbtae', 'empresa', 'Activo', '2025-07-02 02:27:52', '999888777', 'Villaverde', 'Raigosa INC', '312223334', NULL, 'Chocolates ruth', 'Agricola', '', NULL, '', NULL, NULL, 0, 0),
(35, 'appleinc@gmail.com', '$2y$10$3y.5xMj8T.TIhXX3DmIq9.YOxGSE6/9w7S7YX4dD1aG77zXkmDtr.', 'empresa', 'Activo', '2025-07-02 06:20:20', '111222333', 'Beverly Hills', 'Apple INC', '321897654', NULL, 'Steve Jobs', 'Agricola', '', NULL, '', NULL, NULL, 0, 0),
(36, 'Ruth@gmail.com', '$2y$10$rg0EFfewl18AixhFG5aMiuEiKyOEv6HRWo9lPNKnYcIkoqpqfOz1e', 'super_admin', 'Activo', '2025-07-02 15:48:00', NULL, 'Calle 2488 #49-17', NULL, '3024345634', NULL, NULL, NULL, 'Ruth', NULL, 'Gerrero', 'Figueroa', 'Cedula de ciudadania', 1111111111, 0),
(37, 'felipe@gmail.com', '$2y$10$FSZjTGQZPfU72N7q4wHst.yweQCTZE2FiaYMgxsVVl3OjCVIslAOq', 'super_admin', 'Activo', '2025-07-02 15:51:24', NULL, 'Calle 2488 #49-17', NULL, '3024345635', NULL, NULL, NULL, 'Juan', NULL, 'Osorio', 'Lopez', 'Cedula de ciudadania', 2222222222, 0),
(38, 'edwin@gmail.com', '$2y$10$CLevKj2BLTZzB2bTTvwZPuYnmRAIsIyOyLzNAdnvpbV9p8bMeTVfS', 'super_admin', 'Activo', '2025-07-02 15:53:46', NULL, 'Calle 2488 #49-17', NULL, '3024345636', NULL, NULL, NULL, 'Edwin', NULL, 'Banol', 'Cardona', 'Cedula de ciudadania', 3333333333, 0),
(39, 'breih2005@gmail.com', '$2y$10$dqqZTdHs6P3E5NL7k4m8GejSUSEYUM13u4WKOMyflVyO/GaUpPnre', 'empresa', 'Activo', '2025-07-09 12:50:41', '246812345', 'cr 6 calle 9-12 primavera azul', 'pinturas sas', '1234567891', NULL, 'johana delgado', 'Servicios', '', NULL, '', NULL, NULL, 0, 1);

--
-- Disparadores `usuarios`
--
DELIMITER $$
CREATE TRIGGER `validar_nit_empresa` BEFORE INSERT ON `usuarios` FOR EACH ROW BEGIN
    IF NEW.rol = 'empresa' AND (NEW.nit IS NULL OR NEW.nit = '') THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El NIT es obligatorio para el rol empresa';
    END IF;
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `diagnosticos_empresariales`
--
ALTER TABLE `diagnosticos_empresariales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `opciones`
--
ALTER TABLE `opciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pregunta` (`id_pregunta`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `programas_formacion`
--
ALTER TABLE `programas_formacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reportes_diagnosticos`
--
ALTER TABLE `reportes_diagnosticos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reportes_programas`
--
ALTER TABLE `reportes_programas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `generado_por` (`generado_por`);

--
-- Indices de la tabla `reportes_usuarios`
--
ALTER TABLE `reportes_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `generado_por` (`generado_por`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `nit` (`nit`),
  ADD UNIQUE KEY `nickname` (`nickname`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `diagnosticos_empresariales`
--
ALTER TABLE `diagnosticos_empresariales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `opciones`
--
ALTER TABLE `opciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `programas_formacion`
--
ALTER TABLE `programas_formacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=342;

--
-- AUTO_INCREMENT de la tabla `reportes_diagnosticos`
--
ALTER TABLE `reportes_diagnosticos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reportes_programas`
--
ALTER TABLE `reportes_programas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reportes_usuarios`
--
ALTER TABLE `reportes_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `opciones`
--
ALTER TABLE `opciones`
  ADD CONSTRAINT `opciones_ibfk_1` FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reportes_programas`
--
ALTER TABLE `reportes_programas`
  ADD CONSTRAINT `reportes_programas_ibfk_1` FOREIGN KEY (`generado_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reportes_usuarios`
--
ALTER TABLE `reportes_usuarios`
  ADD CONSTRAINT `reportes_usuarios_ibfk_1` FOREIGN KEY (`generado_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
