-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-08-2025 a las 22:41:11
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
-- Estructura de tabla para la tabla `debug_log`
--

CREATE TABLE `debug_log` (
  `mensaje` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `debug_log`
--

INSERT INTO `debug_log` (`mensaje`) VALUES
('🚀 INSERT DESDE db_check.php');

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
  `ficha` int(11) DEFAULT NULL,
  `fecha_finalizacion` date NOT NULL,
  `codigo` int(11) NOT NULL,
  `sector_programa` varchar(50) DEFAULT NULL,
  `nombre_ocupacion` varchar(100) DEFAULT NULL,
  `sector_economico` varchar(50) DEFAULT NULL,
  `etapa_ficha` enum('Lectiva','Practica') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programas_formacion`
--

INSERT INTO `programas_formacion` (`id`, `nombre_programa`, `duracion_programa`, `nivel_formacion`, `estado`, `ficha`, `fecha_finalizacion`, `codigo`, `sector_programa`, `nombre_ocupacion`, `sector_economico`, `etapa_ficha`) VALUES
(1, 'MARKETING DIGITAL PARA EL SISTEMA MODA', 2208, 'TECNICO', 'En ejecucion', 3158891, '2027-02-28', 135329, 'INDUSTRIA', 'Auxiliares de información y servicio al cliente', 'SERVICIOS', 'Lectiva'),
(2, 'MECANICA DE MAQUINARIA INDUSTRIAL', 2208, 'TÉCNICO', 'En ejecucion', 2915418, '2025-05-25', 837101, 'INDUSTRIAL', 'Mecánicos Industriales', 'INDUSTRIAL', 'Practica'),
(3, 'MECANICA DE MAQUINARIA INDUSTRIAL', 2208, 'TÉCNICO', 'En ejecucion', 3155930, '2026-06-02', 837101, 'INDUSTRIAL', 'Mecánicos Industriales', 'INDUSTRIAL', 'Lectiva'),
(4, 'CONSTRUCCION, MANTENIMIENTO Y REPARACION DE ESTRUC', 2208, 'TÉCNICO', 'En ejecucion', 3077333, '2025-12-15', 836136, 'INDUSTRIAL', 'Oficiales de Construcción', 'CONSTRUCCION', 'Lectiva'),
(5, 'CONSTRUCCION DE INFRAESTRUCTURA VIAL', 3984, 'TECNÓLOGO', 'En ejecucion', 2716120, '2025-06-13', 223107, 'INDUSTRIAL', 'Técnicos en Construcción y Arquitectura', 'CONSTRUCCION', 'Lectiva'),
(6, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'TECNICO', 'En ejecucion', 3158973, '2027-02-28', 839317, 'INDUSTRIA', 'Auxiliares técnicos en electrónica', 'ELECTRICIDAD', 'Lectiva'),
(7, 'DIBUJO DIGITAL DE ARQUITECTURA E INGENIERIA', 2208, 'TÉCNICO', 'En ejecucion', 3146802, '2026-05-09', 225224, 'INDUSTRIAL', 'Dibujantes Técnicos', 'SERVICIOS', 'Lectiva'),
(8, 'MANTENIMIENTO DE VEHICULOS LIVIANOS', 2208, 'TÉCNICO', 'En ejecucion', 2917239, '2025-05-26', 838109, 'SERVICIOS', 'Mecánicos de vehículos automotores', 'INDUSTRIAL', 'Lectiva'),
(9, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'TECNÓLOGO', 'En ejecucion', 3097887, '2026-10-06', 223104, 'INDUSTRIAL', 'Técnicos en Construcción y Arquitectura', 'CONSTRUCCION', 'Lectiva'),
(10, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL .', 3984, 'TECNÓLOGO', 'En ejecucion', 2888464, '2026-03-26', 223213, 'INDUSTRIAL', 'Técnicos en mecánica y construcción mecánica', 'INDUSTRIAL', 'Lectiva'),
(11, 'MANTENIMIENTO Y REPARACION DE EDIFICACIONES.', 2208, 'TÉCNICO', 'En ejecucion', 3173613, '2026-06-09', 836137, 'INDUSTRIAL', 'Oficiales de Construcción', 'CONSTRUCCION', 'Lectiva'),
(12, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'TÉCNICO', 'En ejecucion', 3052407, '2025-11-22', 524500, 'INDUSTRIAL', 'Patronistas de Productos de Tela, Cuero y Piel', 'TEXTILES', 'Lectiva'),
(13, 'MANTENIMIENTO DE SISTEMAS DE PROPULSION ELECTRICA ', 2208, 'TÉCNICO', 'En ejecucion', 3099954, '2025-12-14', 838210, 'SERVICIOS', 'Electricistas de Vehículos Automotores', 'ELECTRICIDAD', 'Lectiva'),
(14, 'INSTALACION DE SISTEMAS ELECTRICOS RESIDENCIALES Y', 2208, 'TÉCNICO', 'En ejecucion', 2902746, '2025-04-21', 832202, 'INDUSTRIAL', 'Electricistas Residenciales', 'ELECTRICIDAD', 'Practica'),
(15, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'TECNÓLOGO', 'En ejecucion', 3146794, '2027-05-09', 224312, 'INDUSTRIAL', 'Técnicos en automatización e instrumentación', 'INDUSTRIAL', 'Lectiva'),
(16, 'DIBUJO MECANICO', 2208, 'TÉCNICO', 'En ejecucion', 3150420, '2026-05-23', 225220, 'INDUSTRIAL', 'Dibujantes Técnicos', 'SERVICIOS', 'Lectiva'),
(17, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'TECNÓLOGO', 'En ejecucion', 2901222, '2026-03-12', 223104, 'INDUSTRIAL', 'Técnicos en Construcción y Arquitectura', 'CONSTRUCCION', 'Lectiva'),
(18, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'TECNÓLOGO', 'En ejecucion', 2769284, '2025-09-19', 224312, 'INDUSTRIAL', 'Técnicos en automatización e instrumentación', 'INDUSTRIAL', 'Lectiva'),
(19, 'PRODUCCION DE COMPONENTES MECANICOS CON MAQUINAS D', 3984, 'TECNÓLOGO', 'En ejecucion', 2901290, '2026-03-18', 821100, 'INDUSTRIAL', 'Supervisores de ajustadores de máquinas herramientas', 'INDUSTRIAL', 'Lectiva'),
(20, 'DESARROLLO DE COMPONENTES MECANICOS', 3984, 'TECNÓLOGO', 'En ejecucion', 2971522, '2026-07-28', 223211, 'INDUSTRIAL', 'Técnicos en mecánica y construcción mecánica', 'INDUSTRIAL', 'Lectiva'),
(21, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'TECNÓLOGO', 'En ejecucion', 3005315, '2026-10-07', 224312, 'INDUSTRIAL', 'Técnicos en automatización e instrumentación', 'INDUSTRIAL', 'Lectiva'),
(22, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'TECNÓLOGO', 'En ejecucion', 3161729, '2027-06-09', 223104, 'INDUSTRIAL', 'Técnicos en Construcción y Arquitectura', 'CONSTRUCCION', 'Lectiva'),
(23, 'OPERACION EN TORNO Y FRESADORA', 2208, 'TÉCNICO', 'En ejecucion', 3165868, '2027-02-28', 831102, 'INDUSTRIAL', 'Ajustadores y operadores de máquinas herramientas', 'INDUSTRIAL', 'Lectiva'),
(24, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'TECNÓLOGO', 'En ejecucion', 2693288, '2025-12-20', 223104, 'INDUSTRIAL', 'Técnicos en Construcción y Arquitectura', 'CONSTRUCCION', 'Lectiva'),
(25, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'TÉCNICO', 'En ejecucion', 3151698, '2026-12-11', 224315, 'INDUSTRIAL', 'Técnicos en automatización e instrumentación', 'INDUSTRIAL', 'Lectiva'),
(26, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'TÉCNICO', 'En ejecucion', 2907302, '2025-12-15', 224315, 'INDUSTRIAL', 'Técnicos en automatización e instrumentación', 'INDUSTRIAL', 'Lectiva'),
(27, 'ELABORACION DE PRENDAS DE VESTIR SOBRE MEDIDAS', 2208, 'TÉCNICO', 'En ejecucion', 3080988, '2025-12-29', 842200, 'INDUSTRIAL', 'Sastres, Modistos, Peleteros y Sombrereros', 'TEXTILES', 'Lectiva'),
(28, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'TÉCNICO', 'En ejecucion', 3154718, '2026-12-11', 224315, 'INDUSTRIAL', 'Técnicos en automatización e instrumentación', 'INDUSTRIAL', 'Lectiva'),
(29, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'TÉCNICO', 'En ejecucion', 3150794, '2026-12-11', 224315, 'INDUSTRIAL', 'Técnicos en automatización e instrumentación', 'INDUSTRIAL', 'Lectiva'),
(30, 'OPERACION EN TORNO Y FRESADORA', 2208, 'TÉCNICO', 'En ejecucion', 3150876, '2026-12-11', 831102, 'INDUSTRIAL', 'Ajustadores y operadores de máquinas herramientas', 'INDUSTRIAL', 'Lectiva'),
(31, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'TÉCNICO', 'En ejecucion', 2906650, '2025-12-15', 839317, 'INDUSTRIAL', 'Auxiliares técnicos en electrónica', 'ELECTRICIDAD', 'Lectiva'),
(32, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'TÉCNICO', 'En ejecucion', 3150968, '2026-12-11', 839317, 'INDUSTRIAL', 'Auxiliares técnicos en electrónica', 'ELECTRICIDAD', 'Lectiva'),
(33, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'TÉCNICO', 'En ejecucion', 3017101, '2025-09-23', 836138, 'INDUSTRIAL', 'Oficiales de Construcción', 'CONSTRUCCION', 'Lectiva'),
(34, 'CONSTRUCCION, MANTENIMIENTO Y REPARACION DE ESTRUC', 2208, 'TÉCNICO', 'En ejecucion', 3076366, '2025-12-15', 836136, 'INDUSTRIAL', 'Oficiales de Construcción', 'CONSTRUCCION', 'Lectiva'),
(35, 'ELABORACION DE PRENDAS DE VESTIR SOBRE MEDIDAS', 2208, 'TÉCNICO', 'En ejecucion', 3086930, '2025-12-29', 842200, 'INDUSTRIAL', 'Sastres, Modistos, Peleteros y Sombrereros', 'TEXTILES', 'Lectiva'),
(36, 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS.', 2208, 'TÉCNICO', 'En ejecucion', 2920871, '2025-06-03', 838318, 'SERVICIOS', 'Mecánicos de Motos', 'INDUSTRIAL', 'Lectiva'),
(37, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'TECNÓLOGO', 'En ejecucion', 3170004, '2027-06-09', 223104, 'INDUSTRIAL', 'Técnicos en Construcción y Arquitectura', 'CONSTRUCCION', 'Lectiva'),
(38, 'CARPINTERIA DE ALUMINIO', 1792, 'TÉCNICO', 'En ejecucion', 2975276, '2025-04-27', 841100, 'INDUSTRIAL', 'Instaladores Residenciales y Comerciales', 'SERVICIOS', 'Lectiva'),
(39, 'ELABORACION DE PRENDAS DE VESTIR SOBRE MEDIDAS', 2208, 'TÉCNICO', 'En ejecucion', 3087051, '2025-12-29', 842200, 'INDUSTRIAL', 'Sastres, Modistos, Peleteros y Sombrereros', 'TEXTILES', 'Lectiva'),
(40, 'INSTALACION DE SISTEMAS ELECTRICOS RESIDENCIALES Y', 2208, 'TÉCNICO', 'En ejecucion', 3172084, '2026-06-24', 832202, 'INDUSTRIAL', 'Electricistas Residenciales', 'ELECTRICIDAD', 'Lectiva'),
(41, 'DIBUJO Y MODELADO ARQUITECTONICO Y DE INGENIERIA', 3984, 'TECNÓLOGO', 'En ejecucion', 2809616, '2025-11-01', 225219, 'INDUSTRIAL', 'Dibujantes Técnicos', 'SERVICIOS', 'Lectiva'),
(42, 'ELECTRICIDAD INDUSTRIAL', 3984, 'TECNÓLOGO', 'En ejecucion', 2890965, '2026-03-12', 821222, 'INDUSTRIAL', 'Supervisores de electricidad y telecomunicaciones', 'ELECTRICIDAD', 'Lectiva'),
(43, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'TECNÓLOGO', 'En ejecucion', 3050400, '2026-11-26', 524300, 'INDUSTRIAL', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'TEXTILES', 'Lectiva'),
(44, 'INSTALACION DE SISTEMAS ELECTRICOS RESIDENCIALES Y', 2208, 'TÉCNICO', 'En ejecucion', 3146795, '2026-05-09', 832202, 'INDUSTRIAL', 'Electricistas Residenciales', 'ELECTRICIDAD', 'Lectiva'),
(45, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'TÉCNICO', 'En ejecucion', 3150819, '2026-12-11', 839317, 'INDUSTRIAL', 'Auxiliares técnicos en electrónica', 'ELECTRICIDAD', 'Lectiva'),
(46, 'ELABORACION DE AUDIOVISUALES.', 2208, 'TÉCNICO', 'En ejecucion', 3150812, '2026-12-11', 522211, 'SERVICIOS', 'Operadores de Cámara de Cine y Televisión', 'SERVICIOS', 'Lectiva'),
(47, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'TÉCNICO', 'En ejecucion', 3150894, '2026-12-11', 839317, 'INDUSTRIAL', 'Auxiliares técnicos en electrónica', 'ELECTRICIDAD', 'Lectiva'),
(48, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'TÉCNICO', 'En ejecucion', 2906695, '2025-12-15', 224315, 'INDUSTRIAL', 'Técnicos en automatización e instrumentación', 'INDUSTRIAL', 'Lectiva'),
(49, 'EBANISTERIA.', 2208, 'TÉCNICO', 'En ejecucion', 2915170, '2025-12-15', 939203, 'INDUSTRIAL', 'Ensambladores e Inspectores de Muebles y Accesorios', 'INDUSTRIAL', 'Lectiva'),
(50, 'MANTENIMIENTO DE VEHICULOS LIVIANOS', 2208, 'TÉCNICO', 'En ejecucion', 2907124, '2025-12-15', 838109, 'SERVICIOS', 'Mecánicos de vehículos automotores', 'INDUSTRIAL', 'Lectiva'),
(51, 'EBANISTERIA.', 2208, 'TÉCNICO', 'En ejecucion', 2908057, '2025-12-15', 939203, 'INDUSTRIAL', 'Ensambladores e Inspectores de Muebles y Accesorios', 'INDUSTRIAL', 'Lectiva'),
(52, 'EBANISTERIA.', 2208, 'TÉCNICO', 'En ejecucion', 3150764, '2026-12-11', 939203, 'INDUSTRIAL', 'Ensambladores e Inspectores de Muebles y Accesorios', 'INDUSTRIAL', 'Lectiva'),
(53, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'TÉCNICO', 'En ejecucion', 2908128, '2025-12-15', 224315, 'INDUSTRIAL', 'Técnicos en automatización e instrumentación', 'INDUSTRIAL', 'Lectiva'),
(54, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'TÉCNICO', 'En ejecucion', 3150834, '2026-12-11', 224315, 'INDUSTRIAL', 'Técnicos en automatización e instrumentación', 'INDUSTRIAL', 'Lectiva'),
(55, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'TÉCNICO', 'En ejecucion', 2907050, '2025-12-15', 839317, 'INDUSTRIAL', 'Auxiliares técnicos en electrónica', 'ELECTRICIDAD', 'Lectiva'),
(56, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'TÉCNICO', 'En ejecucion', 3151676, '2026-12-11', 224315, 'INDUSTRIAL', 'Técnicos en automatización e instrumentación', 'INDUSTRIAL', 'Lectiva'),
(57, 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES..', 2352, 'TÉCNICO', 'En ejecucion', 3160581, '2026-12-11', 224315, 'INDUSTRIAL', 'Técnicos en automatización e instrumentación', 'INDUSTRIAL', 'Lectiva'),
(58, 'SUPERVISION EN PROCESOS DE CONFECCION', 3984, 'TECNÓLOGO', 'En ejecucion', 2899433, '2026-03-16', 922500, 'INDUSTRIAL', 'Supervisores de Fabricación de Productos de Tela, Cuero y Piel', 'TEXTILES', 'Practica'),
(59, 'ELABORACION DE AUDIOVISUALES.', 2208, 'TÉCNICO', 'En ejecucion', 2907236, '2025-12-15', 522211, 'SERVICIOS', 'Operadores de Cámara de Cine y Televisión', 'SERVICIOS', 'Lectiva'),
(60, 'OPERACION EN TORNO Y FRESADORA', 2208, 'TÉCNICO', 'En ejecucion', 3150760, '2026-12-11', 831102, 'INDUSTRIAL', 'Ajustadores y operadores de máquinas herramientas', 'INDUSTRIAL', 'Lectiva'),
(61, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'TÉCNICO', 'En ejecucion', 2907126, '2025-12-15', 839317, 'INDUSTRIAL', 'Auxiliares técnicos en electrónica', 'ELECTRICIDAD', 'Lectiva'),
(62, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'TÉCNICO', 'En ejecucion', 2907224, '2025-12-15', 524500, 'INDUSTRIAL', 'Patronistas de Productos de Tela, Cuero y Piel', 'TEXTILES', 'Lectiva'),
(63, 'ELECTRICIDAD INDUSTRIAL', 3984, 'TECNÓLOGO', 'En ejecucion', 2950353, '2026-07-15', 821222, 'INDUSTRIAL', 'Supervisores de electricidad y telecomunicaciones', 'ELECTRICIDAD', 'Lectiva'),
(64, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'TÉCNICO', 'En ejecucion', 2907183, '2025-12-15', 839317, 'INDUSTRIAL', 'Auxiliares técnicos en electrónica', 'ELECTRICIDAD', 'Lectiva'),
(65, 'ELABORACION DE AUDIOVISUALES.', 2208, 'TÉCNICO', 'En ejecucion', 3150749, '2026-12-11', 522211, 'SERVICIOS', 'Operadores de Cámara de Cine y Televisión', 'SERVICIOS', 'Lectiva'),
(66, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'TÉCNICO', 'En ejecucion', 2908058, '2025-12-15', 836138, 'INDUSTRIAL', 'Oficiales de Construcción', 'CONSTRUCCION', 'Lectiva'),
(67, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'TÉCNICO', 'En ejecucion', 3151079, '2026-12-11', 836138, 'INDUSTRIAL', 'Oficiales de Construcción', 'CONSTRUCCION', 'Lectiva'),
(68, 'ELABORACION DE AUDIOVISUALES.', 2208, 'TÉCNICO', 'En ejecucion', 2907176, '2025-12-15', 522211, 'SERVICIOS', 'Operadores de Cámara de Cine y Televisión', 'SERVICIOS', 'Lectiva'),
(69, 'ELABORACION DE AUDIOVISUALES.', 2208, 'TÉCNICO', 'En ejecucion', 2907222, '2025-12-15', 522211, 'SERVICIOS', 'Operadores de Cámara de Cine y Televisión', 'SERVICIOS', 'Lectiva'),
(70, 'MECANIZADO EN TORNO Y FRESADORA CONVENCIONAL', 2208, 'TÉCNICO', 'En ejecucion', 2907111, '2025-12-15', 941106, 'INDUSTRIAL', 'Operadores de Máquinas Herramientas', 'INDUSTRIAL', 'Lectiva'),
(71, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'TÉCNICO', 'En ejecucion', 2906672, '2025-12-15', 836138, 'INDUSTRIAL', 'Oficiales de Construcción', 'CONSTRUCCION', 'Lectiva'),
(72, 'ELABORACION DE AUDIOVISUALES.', 2208, 'TÉCNICO', 'En ejecucion', 2907170, '2025-12-15', 522211, 'SERVICIOS', 'Operadores de Cámara de Cine y Televisión', 'SERVICIOS', 'Lectiva'),
(73, 'MECANIZADO EN TORNO Y FRESADORA CONVENCIONAL', 2208, 'TÉCNICO', 'En ejecucion', 2907042, '2025-12-15', 941106, 'INDUSTRIAL', 'Operadores de Máquinas Herramientas', 'INDUSTRIAL', 'Lectiva'),
(74, 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS.', 2304, 'TÉCNICO', 'En ejecucion', 2907151, '2025-12-15', 839317, 'INDUSTRIAL', 'Auxiliares técnicos en electrónica', 'ELECTRICIDAD', 'Lectiva'),
(75, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'TECNÓLOGO', 'En ejecucion', 2928832, '2026-07-14', 224312, 'INDUSTRIAL', 'Técnicos en automatización e instrumentación', 'INDUSTRIAL', 'Lectiva'),
(76, 'GESTION DEL MANTENIMIENTO DE AUTOMOTORES.', 3984, 'TECNÓLOGO', 'En ejecucion', 3142341, '2027-05-10', 821620, 'SERVICIOS', 'Supervisores de mecánica', 'INDUSTRIAL', 'Lectiva'),
(77, 'PRODUCCION DE MEDIOS AUDIOVISUALES DIGITALES', 3984, 'TECNÓLOGO', 'En ejecucion', 2999918, '2026-10-07', 513101, 'SERVICIOS', 'Productores, directores artísticos y ocupaciones relacionadas', 'SERVICIOS', 'Lectiva'),
(78, 'MANEJO DE MAQUINARIA DE CONFECCION INDUSTRIAL PARA', 1296, 'OPERARIO', 'En ejecucion', 3065543, '2025-07-14', 935105, 'INDUSTRIAL', 'Operadores de Máquinas para Coser y Bordar', 'TEXTILES', 'Lectiva'),
(79, 'PINTURA ARQUITECTONICA Y ACABADOS ESPECIALES', 2208, 'TÉCNICO', 'En ejecucion', 2929317, '2025-07-14', 836600, 'INDUSTRIAL', 'Pintores y Empapeladores', 'CONSTRUCCION', 'Lectiva'),
(80, 'DIBUJO DIGITAL DE ARQUITECTURA E INGENIERIA', 2208, 'TÉCNICO', 'En ejecucion', 3142322, '2026-05-10', 225224, 'INDUSTRIAL', 'Dibujantes Técnicos', 'SERVICIOS', 'Lectiva'),
(81, 'CATASTRO MULTIPROPOSITO', 2208, 'TÉCNICO', 'En ejecucion', 2928912, '2025-07-14', 225314, 'INDUSTRIAL', 'Topógrafos', 'CONSTRUCCION', 'Lectiva'),
(82, 'PRODUCCION DE MEDIOS AUDIOVISUALES DIGITALES', 3984, 'TECNÓLOGO', 'En ejecucion', 2928823, '2026-07-14', 513101, 'SERVICIOS', 'Productores, directores artísticos y ocupaciones relacionadas', 'SERVICIOS', 'Lectiva'),
(83, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'TÉCNICO', 'En ejecucion', 3150602, '2026-12-11', 836138, 'INDUSTRIAL', 'Oficiales de Construcción', 'CONSTRUCCION', 'Lectiva'),
(84, 'CONSTRUCCION EN EDIFICACIONES.', 3984, 'TECNÓLOGO', 'En ejecucion', 3064703, '2027-01-14', 223104, 'INDUSTRIAL', 'Técnicos en Construcción y Arquitectura', 'CONSTRUCCION', 'Lectiva'),
(85, 'SOLDADURA DE PRODUCTOS METALICOS EN PLATINA', 2208, 'TÉCNICO', 'En ejecucion', 2928893, '2025-07-14', 834258, 'INDUSTRIAL', 'Soldadores', 'INDUSTRIAL', 'Practica'),
(86, 'SOLDADURA DE PRODUCTOS METALICOS EN PLATINA', 2208, 'TÉCNICO', 'En ejecucion', 2876721, '2025-04-21', 834258, 'INDUSTRIAL', 'Soldadores', 'INDUSTRIAL', 'Practica'),
(87, 'MANTENIMIENTO DE EQUIPOS DE AIRE ACONDICIONADO Y R', 2208, 'TÉCNICO', 'En ejecucion', 3142373, '2026-05-10', 837501, 'INDUSTRIAL', 'Técnicos de aire acondicionado y refrigeración', 'INDUSTRIAL', 'Lectiva'),
(88, 'ELECTRICIDAD INDUSTRIAL', 3984, 'TECNÓLOGO', 'En ejecucion', 3142337, '2027-05-10', 821222, 'INDUSTRIAL', 'Supervisores de electricidad y telecomunicaciones', 'ELECTRICIDAD', 'Lectiva'),
(89, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL .', 3984, 'TECNÓLOGO', 'En ejecucion', 3002078, '2026-10-07', 223213, 'INDUSTRIAL', 'Técnicos en mecánica y construcción mecánica', 'INDUSTRIAL', 'Lectiva'),
(90, 'DESARROLLO DE SISTEMAS ELECTRONICOS INDUSTRIALES', 3984, 'TECNÓLOGO', 'En ejecucion', 2876683, '2026-04-21', 224201, 'INDUSTRIAL', 'Técnicos en electrónica', 'ELECTRICIDAD', 'Lectiva'),
(91, 'ELECTRICIDAD INDUSTRIAL', 3984, 'TECNÓLOGO', 'En ejecucion', 3002091, '2026-10-07', 821222, 'INDUSTRIAL', 'Supervisores de electricidad y telecomunicaciones', 'ELECTRICIDAD', 'Lectiva'),
(92, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'TECNÓLOGO', 'En ejecucion', 2711289, '2025-07-16', 224312, 'INDUSTRIAL', 'Técnicos en automatización e instrumentación', 'INDUSTRIAL', 'Lectiva'),
(93, 'ANIMACION DIGITAL', 3984, 'TECNÓLOGO', 'En ejecucion', 2671596, '2025-04-22', 513601, 'SERVICIOS', 'Pintores, escultores y otros artistas visuales', 'SERVICIOS', 'Lectiva'),
(94, 'SOLDADURA DE PRODUCTOS METALICOS EN PLATINA', 2208, 'TÉCNICO', 'En ejecucion', 3142372, '2026-05-10', 834258, 'INDUSTRIAL', 'Soldadores', 'INDUSTRIAL', 'Lectiva'),
(95, 'DIBUJO DIGITAL DE ARQUITECTURA E INGENIERIA', 2208, 'TÉCNICO', 'En ejecucion', 3002084, '2025-10-07', 225224, 'INDUSTRIAL', 'Dibujantes Técnicos', 'SERVICIOS', 'Lectiva'),
(96, 'ANIMACION DIGITAL', 3984, 'TECNÓLOGO', 'En ejecucion', 2876688, '2026-04-21', 513601, 'SERVICIOS', 'Pintores, escultores y otros artistas visuales', 'SERVICIOS', 'Lectiva'),
(97, 'ELECTRICIDAD INDUSTRIAL', 3984, 'TECNÓLOGO', 'En ejecucion', 2928820, '2026-07-14', 821222, 'INDUSTRIAL', 'Supervisores de electricidad y telecomunicaciones', 'ELECTRICIDAD', 'Lectiva'),
(98, 'INSTALACION DE SISTEMAS ELECTRICOS RESIDENCIALES Y', 2208, 'TÉCNICO', 'En ejecucion', 2999846, '2025-10-07', 832202, 'INDUSTRIAL', 'Electricistas Residenciales', 'ELECTRICIDAD', 'Lectiva'),
(99, 'CONSTRUCCION DE EDIFICACIONES', 2208, 'TÉCNICO', 'En ejecucion', 3151620, '2026-12-11', 836138, 'INDUSTRIAL', 'Oficiales de Construcción', 'CONSTRUCCION', 'Lectiva'),
(100, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'TECNÓLOGO', 'En ejecucion', 2826643, '2025-12-31', 524300, 'INDUSTRIAL', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'TEXTILES', 'Lectiva'),
(101, 'MANTENIMIENTO ELECTRICO Y CONTROL ELECTRONICO DE A', 2208, 'TÉCNICO', 'En ejecucion', 3142366, '2026-05-10', 838200, 'SERVICIOS', 'Electricistas de Vehículos Automotores', 'ELECTRICIDAD', 'Lectiva'),
(102, 'MARKETING DIGITAL PARA EL SISTEMA MODA', 2208, 'TÉCNICO', 'En ejecucion', 3070032, '2025-12-15', 135329, 'INDUSTRIAL', 'Auxiliares de información y servicio al cliente', 'SERVICIOS', 'Lectiva'),
(103, 'INSTALACION DE SISTEMAS ELECTRICOS RESIDENCIALES Y', 2208, 'TÉCNICO', 'En ejecucion', 2928844, '2025-07-14', 832202, 'INDUSTRIAL', 'Electricistas Residenciales', 'ELECTRICIDAD', 'Lectiva'),
(104, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'TÉCNICO', 'En ejecucion', 2999845, '2025-10-07', 524500, 'INDUSTRIAL', 'Patronistas de Productos de Tela, Cuero y Piel', 'TEXTILES', 'Lectiva'),
(105, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'TÉCNICO', 'En ejecucion', 2977524, '2025-08-13', 524500, 'INDUSTRIAL', 'Patronistas de Productos de Tela, Cuero y Piel', 'TEXTILES', 'Lectiva'),
(106, 'CONSTRUCCIONES LIVIANAS INDUSTRIALIZADAS EN SECO', 2208, 'TÉCNICO', 'En ejecucion', 3002083, '2025-10-07', 836135, 'INDUSTRIAL', 'Oficiales de Construcción', 'CONSTRUCCION', 'Lectiva'),
(107, 'DIBUJO Y MODELADO ARQUITECTONICO Y DE INGENIERIA', 3984, 'TECNÓLOGO', 'En ejecucion', 2826683, '2025-12-31', 225219, 'INDUSTRIAL', 'Dibujantes Técnicos', 'SERVICIOS', 'Lectiva'),
(108, 'MANTENIMIENTO DE EQUIPOS DE REFRIGERACION, VENTILA', 2205, 'TÉCNICO', 'En ejecucion', 2876709, '2025-04-21', 837530, 'SERVICIOS', 'Técnicos de aire acondicionado y refrigeración', 'INDUSTRIAL', 'Lectiva'),
(109, 'MARKETING DIGITAL PARA EL SISTEMA MODA', 2208, 'TÉCNICO', 'En ejecucion', 2999871, '2025-10-07', 135329, 'INDUSTRIAL', 'Auxiliares de información y servicio al cliente', 'SERVICIOS', 'Lectiva'),
(110, 'PRODUCCION DE MEDIOS AUDIOVISUALES DIGITALES', 3984, 'TECNÓLOGO', 'En ejecucion', 2826641, '2025-12-31', 513101, 'SERVICIOS', 'Productores, directores artísticos y ocupaciones relacionadas', 'SERVICIOS', 'Lectiva'),
(111, 'MANTENIMIENTO DE EQUIPOS DE AIRE ACONDICIONADO Y R', 2208, 'TÉCNICO', 'En ejecucion', 2999886, '2025-10-07', 837501, 'INDUSTRIAL', 'Técnicos de aire acondicionado y refrigeración', 'INDUSTRIAL', 'Lectiva'),
(112, 'AUTOMATIZACION DE SISTEMAS MECATRONICOS', 3984, 'TECNÓLOGO', 'En ejecucion', 2876678, '2026-04-21', 224312, 'INDUSTRIAL', 'Técnicos en automatización e instrumentación', 'INDUSTRIAL', 'Lectiva'),
(113, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL .', 3984, 'TECNÓLOGO', 'En ejecucion', 3142344, '2027-05-10', 223213, 'INDUSTRIAL', 'Técnicos en mecánica y construcción mecánica', 'INDUSTRIAL', 'Lectiva'),
(114, 'DESARROLLO DE MEDIOS GRAFICOS VISUALES', 3984, 'TECNÓLOGO', 'En ejecucion', 3186012, '2027-06-30', 522309, 'INDUSTRIAL', 'Técnicos en Diseño y Arte Gráfico', 'INDUSTRIAL', 'Lectiva'),
(115, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'TECNÓLOGO', 'En ejecucion', 3002070, '2026-10-07', 524300, 'INDUSTRIAL', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'TEXTILES', 'Lectiva'),
(116, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'TECNÓLOGO', 'En ejecucion', 2879551, '2026-02-19', 524300, 'INDUSTRIAL', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'TEXTILES', 'Lectiva'),
(117, 'DESARROLLO DE MEDIOS GRAFICOS VISUALES', 3984, 'TECNÓLOGO', 'En ejecucion', 2721314, '2025-06-26', 522309, 'INDUSTRIAL', 'Técnicos en Diseño y Arte Gráfico', 'INDUSTRIAL', 'Lectiva'),
(118, 'ELECTRICIDAD INDUSTRIAL', 3984, 'TECNÓLOGO', 'En ejecucion', 2671600, '2025-04-22', 821222, 'INDUSTRIAL', 'Supervisores de electricidad y telecomunicaciones', 'ELECTRICIDAD', 'Practica'),
(119, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'TECNÓLOGO', 'En ejecucion', 3070425, '2026-12-15', 524300, 'INDUSTRIAL', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'TEXTILES', 'Lectiva'),
(120, 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS.', 2208, 'TÉCNICO', 'En ejecucion', 3064630, '2026-01-14', 838318, 'SERVICIOS', 'Mecánicos de Motos', 'INDUSTRIAL', 'Lectiva'),
(121, 'MANTENIMIENTO ELECTROMECANICO INDUSTRIAL .', 3984, 'TECNÓLOGO', 'En ejecucion', 2671605, '2025-04-22', 223213, 'INDUSTRIAL', 'Técnicos en mecánica y construcción mecánica', 'INDUSTRIAL', 'Lectiva'),
(122, 'DESARROLLO DE COLECCIONES PARA LA INDUSTRIA DE LA ', 3984, 'TECNÓLOGO', 'En ejecucion', 2834702, '2025-12-10', 524300, 'INDUSTRIAL', 'Diseñadores de teatro, moda, exhibición y otros diseñadores creativos', 'TEXTILES', 'Lectiva'),
(123, 'PATRONAJE INDUSTRIAL DE PRENDAS DE VESTIR', 2208, 'TÉCNICO', 'En ejecucion', 2928915, '2025-07-14', 524500, 'INDUSTRIAL', 'Patronistas de Productos de Tela, Cuero y Piel', 'TEXTILES', 'Lectiva'),
(162, 'ANÁLISIS Y DESARROLLO DE SOFTWARE', 3894, 'TECNOLOGO', 'Finalizado', 2899410, '2026-03-12', 228118, 'SERVICIOS', 'Técnicos en Tecnologías de la Información', 'SERVICIOS', 'Lectiva'),
(163, 'ANÁLISIS Y DESARROLLO DE SOFTWARE', 3984, 'TECNOLOGO', 'En ejecucion', 3147206, '2027-05-09', 228118, 'SERVICIOS', 'Técnicos en Tecnologías de la Información', 'Servicios', 'Lectiva'),
(164, 'Audiovisuales y redes sociales', 20, 'TECNICO', 'Finalizado', 2896476, '2025-07-31', 2899988, 'INDUSTRIA', 'hacer videos de tiktok', 'INDUSTRIA', 'Lectiva');

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
  `nit` varchar(9) DEFAULT NULL,
  `direccion` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `razon_social` varchar(50) DEFAULT NULL,
  `telefono` bigint(20) NOT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `representante_legal` varchar(100) DEFAULT NULL,
  `tipo_empresa` enum('INDUSTRIAL','SERVICIOS') DEFAULT NULL,
  `primer_nombre` varchar(50) NOT NULL,
  `segundo_nombre` varchar(50) DEFAULT NULL,
  `primer_apellido` varchar(50) NOT NULL,
  `segundo_apellido` varchar(50) DEFAULT NULL,
  `tipo_documento` enum('Cédula de ciudadanía','Cédula de extranjería') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_documento` bigint(20) DEFAULT NULL,
  `diagnostico_realizado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `correo`, `contrasena`, `rol`, `estado`, `fecha_creacion`, `nit`, `direccion`, `razon_social`, `telefono`, `nickname`, `representante_legal`, `tipo_empresa`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `tipo_documento`, `numero_documento`, `diagnostico_realizado`) VALUES
(16, 'correodebraulioperra@gmail.com', '$2y$10$GBRcdFQgwR4.fZ06B1K1j.GecKahretHi9Lhl/7diaS8/OMqiqSAK', 'AdminSENA', 'Desactivado', '2025-06-13 03:32:23', NULL, '', NULL, 3156907026, NULL, NULL, NULL, 'Breiner', '', 'Chica', 'Alzate', '', 12345678, 0),
(17, 'crisberx@gmail.com', '$2y$10$r/BPPtq9.jQ.rPjXYYw19.c.vUemlmOITViw5KsWqhuXTa.oiUeV.', 'super_admin', 'Activo', '2025-06-17 02:34:16', NULL, 'Calle 123', NULL, 3001234567, NULL, NULL, NULL, 'Breiner', NULL, 'Chica', 'alzate', 'Cédula de ciudadanía', 3001234567, 0),
(25, 'osoriolopezjuanfelipe9898@gmail.com', '$2y$10$.2Rlugl6baAoj7qA.nenN.urRMg0lcMjaJluQgxGiPj3vT9wfnMoW', 'AdminSENA', 'Desactivado', '2025-07-01 02:13:30', NULL, '', NULL, 322222222, NULL, NULL, NULL, 'Juan', 'Felipe', 'Osorio', 'Lopez', '', 23456789, 0),
(36, 'Ruth@gmail.com', '$2y$10$rg0EFfewl18AixhFG5aMiuEiKyOEv6HRWo9lPNKnYcIkoqpqfOz1e', 'super_admin', 'Activo', '2025-07-02 15:48:00', NULL, 'Calle 2488 #49-17', NULL, 3024345634, NULL, NULL, NULL, 'Ruth', NULL, 'Gerrero', 'Figueroa', 'Cédula de ciudadanía', 1111111111, 0),
(37, 'felipe@gmail.com', '$2y$10$FSZjTGQZPfU72N7q4wHst.yweQCTZE2FiaYMgxsVVl3OjCVIslAOq', 'super_admin', 'Activo', '2025-07-02 15:51:24', NULL, 'Calle 2488 #49-17', NULL, 3024345635, NULL, NULL, NULL, 'Juan', NULL, 'Osorio', 'Lopez', 'Cédula de ciudadanía', 2222222222, 0),
(38, 'edwin@gmail.com', '$2y$10$CLevKj2BLTZzB2bTTvwZPuYnmRAIsIyOyLzNAdnvpbV9p8bMeTVfS', 'super_admin', 'Activo', '2025-07-02 15:53:46', NULL, 'Calle 2488 #49-17', NULL, 3024345636, NULL, NULL, NULL, 'Edwin', '', 'Banol', 'Cardona', 'Cédula de ciudadanía', 3333333333, 0),
(44, 'miguelmarin@gmail.com', '$2y$10$IfGPyP2FX2UTeaf/yi.TK.BMjprDOU5nmtMUwRXqzAvXeCGxBFUwG', 'AdminSENA', 'Activo', '2025-07-10 14:23:17', NULL, '', NULL, 1234567891, NULL, NULL, NULL, 'miguel', 'angel', 'lopez', 'marin', 'Cédula de ciudadanía', 1122334455, 0),
(45, 'felipeosorio@gmail.com', '$2y$10$SHDidYvcH8jiAe0/NNyDVOJOSegNPE2SOA7uP6kNZW.bt2t1p7SMW', 'AdminSENA', 'Activo', '2025-07-17 00:47:27', NULL, '', NULL, 3156907026, NULL, NULL, NULL, 'pele', 'felipe', 'pamparoso', 'crack', 'Cédula de ciudadanía', 1121506109, 0),
(46, 'breineralzatechica@gmail.com', '$2y$10$hv78hIcC6862iKhcVsQfXuqMFjakdTMy7ig2qkTxLKSqDWt47t7cW', 'empresa', 'Activo', '2025-07-17 01:40:42', '123456789', 'Carrera 9 # 56-34', 'Frisby S.A', 3104785544, NULL, 'Frisby', 'INDUSTRIAL', '', NULL, '', NULL, NULL, 0, 0),
(48, 'brauliomk@gmail.com', '$2y$10$ZN72f/S9JXqD5Az.ZH0nfOdlAtbZSdYshlkDsgRFYvmy/NtUTz4uC', 'AdminSENA', 'Activo', '2025-07-18 01:49:54', NULL, '', NULL, 3152928734, NULL, NULL, NULL, 'pele', 'Felipe', 'Osorio', 'marulanda', 'Cédula de ciudadanía', 1234567898, 0),
(49, 'braulioloodioperrazungamk@gmail.com', '$2y$10$oyPTJwGYPDHyUF8x1rh6I.J3Y.ttapB4aNtsUNck.m/O1ar7i7FY.', 'AdminSENA', 'Activo', '2025-07-18 02:14:34', NULL, '', NULL, 3152928626, NULL, NULL, NULL, 'Braulio', 'odio', 'perra', 'zunga', 'Cédula de ciudadanía', 987654321, 0),
(54, 'braulioloodioperra@gmail.com', '$2y$10$1a02kFD/W9jQdFdVoOSfKe.1T4pKT9W7BDBHP81fnYFFh.y9Oh4XC', 'AdminSENA', 'Activo', '2025-07-18 03:18:10', NULL, '', NULL, 3152928725, NULL, NULL, NULL, 'Ruth', 'Danyely', 'Guerrero', 'Figueroa', 'Cédula de ciudadanía', 123456789, 0),
(58, 'correo@gmail.com', '$2y$10$QqUK2zUb.g6M4k.2LJd2Ie6JCUPysL4VD4wSSHYJ6bb1F/pbuftFu', 'empresa', 'Activo', '2025-07-28 03:06:26', '567890234', 'Carrera 12 # 5-31', 'Monster S.A', 3104574487, NULL, 'Chocolates Ruthsita', 'INDUSTRIAL', '', NULL, '', NULL, NULL, NULL, 0),
(59, 'andreshenao@gmail.com', '$2y$10$EhmvIxNOmDXpr/dW/VXDmuVZuFGjUsDd648X4QWIuCDkDp4OJYF6a', 'AdminSENA', 'Activo', '2025-07-28 05:03:06', NULL, NULL, NULL, 3156809028, NULL, NULL, NULL, 'Alfonso', 'Andres', 'Lopez', 'Henao', 'Cédula de ciudadanía', 1099765443, 0),
(60, 'jj2673782@gmail.com', '$2y$10$sj0gkQC16GTO8PzCkckKlOb9cLvj308yfZAzXNlfSMqtJNhOsmN5K', 'empresa', 'Activo', '2025-08-21 01:41:32', '897654441', 'Carrera 11 #12-12', 'Senalink S.A', 3152928725, NULL, 'Braulio', 'INDUSTRIAL', '', NULL, '', NULL, NULL, NULL, 0),
(61, 'dovi7523@hotmail.com', '$2y$10$5Lycsl7Qnq0FiJEuPvpFp.aKcmrOieqU9VA1beBo3mN8TAXHbwOVC', 'empresa', 'Desactivado', '2025-08-21 01:48:59', '556663332', 'Carrera 1 #20-30', 'Headshots S.A.S', 3217774442, NULL, 'Miguel Uribe Turbay', 'SERVICIOS', '', NULL, '', NULL, NULL, NULL, 0),
(62, 'edwintrabaje@hotmail.com', '$2y$10$bktMZhSARnUpz72A8Ufg.uXc6DMDpxd1/klvsfnjRANKo4QZPWLAu', 'empresa', 'Desactivado', '2025-08-21 01:53:13', '444777111', 'Carrera 2 #21-37', 'Edwin manito trabaje S.A', 3226664441, NULL, 'Edwin trabaje', 'SERVICIOS', '', NULL, '', NULL, NULL, NULL, 0),
(63, 'migueelmejor@gmail.com', '$2y$10$NbbqptW8ERfIm5fwNL4ycuzGbCcn1XD6sdFJuWfutUUPWFgmOonri', 'AdminSENA', 'Activo', '2025-08-21 02:16:01', NULL, NULL, NULL, 3334445551, NULL, NULL, NULL, 'Migue', 'Lopez', 'Marin', 'Pampara', 'Cédula de ciudadanía', 1088987654, 0),
(64, 'edwinadmin123@gmail.com', '$2y$10$TlM5SOPPWX6IBAPhYQeZpu0PVRW8hU61JwUm21W3MpGpNT7zookE6', 'empresa', 'Activo', '2025-08-22 02:58:01', '999666333', 'Calle 8 #12-118', 'Juan Valdez S.A', 3112938826, NULL, 'Pele', 'INDUSTRIAL', '', NULL, '', NULL, NULL, NULL, 0),
(65, 'migueelmejordelmundo@gmail.com', '$2y$10$JzW74fEBosXLrX0nm1aQ2uo///PbjWmVW9La4U3Cc/MxRQvCeV1/u', 'empresa', 'Activo', '2025-08-22 03:25:28', '999777444', 'Calle 9 #12-113', 'Cocosette S.A', 3128652233, NULL, 'Grajales', 'INDUSTRIAL', '', NULL, '', NULL, NULL, NULL, 0),
(66, 'migueelmejordelagalaxia@gmail.com', '$2y$10$AbipquvEj./UHaTPpFfVh.mfQ0cR/gcHkMoldPpJ22CvI43.5IbLi', 'empresa', 'Activo', '2025-08-22 03:27:20', '999777555', 'Calle 9 #12-113', 'Rolex S.A', 3128652244, NULL, 'Grajales', 'INDUSTRIAL', '', NULL, '', NULL, NULL, NULL, 0),
(67, 'edwintrabajemanito@gmail.com', '$2y$10$KCXYhluVmJBP2cFNFRw.7OxlG6/25z78X8eVP6CaI9Eg/zABp6dDi', 'empresa', 'Activo', '2025-08-22 03:28:25', '555333111', 'Calle 12 #11-33', 'Minecraft S.A.S', 3146907027, NULL, 'Braulios', 'INDUSTRIAL', '', NULL, '', NULL, NULL, NULL, 0),
(68, 'osoriolopezjuanfelipe98@gmail.com', '$2y$10$33vNfs4A26IFGAmXrPDkg.pBoipDrMp0lZ7vLUHj14zTr0K6lXKB6', 'empresa', 'Activo', '2025-08-28 01:33:26', '999888111', 'Carrera 9 #16-18', 'Bombones ruthsita S.A', 3215554321, NULL, 'Bombones ruthsita', 'INDUSTRIAL', '', NULL, '', NULL, NULL, NULL, 0);

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
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `numero_documento` (`numero_documento`),
  ADD UNIQUE KEY `nit` (`nit`),
  ADD UNIQUE KEY `nickname` (`nickname`),
  ADD UNIQUE KEY `nit_2` (`nit`),
  ADD UNIQUE KEY `razon_social` (`razon_social`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `opciones`
--
ALTER TABLE `opciones`
  ADD CONSTRAINT `opciones_ibfk_1` FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
