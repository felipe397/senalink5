-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-06-2025 a las 22:26:58
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones_preguntas`
--

CREATE TABLE `opciones_preguntas` (
  `id_opciones_preguntas` int(11) NOT NULL,
  `id_pregunta` int(11) NOT NULL,
  `opcion_texto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Estructura de tabla para la tabla `preguntas_diagnostico`
--

CREATE TABLE `preguntas_diagnostico` (
  `id_preguntas_diagnostico` int(11) NOT NULL,
  `enunciado` varchar(255) NOT NULL,
  `tipo` enum('texto','opcion_multiple') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas_formacion`
--

CREATE TABLE `programas_formacion` (
  `id` int(11) NOT NULL,
  `nombre_programa` varchar(50) NOT NULL,
  `duracion_meses` int(11) NOT NULL,
  `nivel_formacion` enum('Auxiliar','Operario','Tecnico','Tecnologo') DEFAULT NULL,
  `estado` enum('En curso','Disponible','Finalizado') DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `ficha` int(11) NOT NULL,
  `habilidades_requeridas` text NOT NULL,
  `fecha_finalizacion` date NOT NULL,
  `descripcion` text NOT NULL,
  `codigo` int(11) NOT NULL,
  `sector_programa` enum('Industrial','Primario','Servicios') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programas_formacion`
--

INSERT INTO `programas_formacion` (`id`, `nombre_programa`, `duracion_meses`, `nivel_formacion`, `estado`, `fecha_creacion`, `ficha`, `habilidades_requeridas`, `fecha_finalizacion`, `descripcion`, `codigo`, `sector_programa`) VALUES
(1, 'software', 27, 'Tecnologo', 'En curso', '2025-06-22 00:04:59', 2896365, 'Saber python, JavaScript, SQL Y node.js', '2025-10-06', 'Crear software y hardware', 12345, NULL),
(2, 'Contenidos Digitales', 21, 'Tecnico', 'Disponible', '2025-06-22 02:26:07', 2344221, 'Ser migue ', '2025-06-26', 'Migue la pampara ', 9898989, NULL),
(3, 'Audiovisuales', 23, 'Auxiliar', 'En curso', '2025-06-22 02:29:33', 99887766, 'Manejar contenido', '2025-06-25', 'Crear contenido audiovisual', 44556677, NULL);

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
-- Estructura de tabla para la tabla `respuestas_diagnostico`
--

CREATE TABLE `respuestas_diagnostico` (
  `id_respuestas_diagnostico` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_pregunta` int(11) NOT NULL,
  `respuesta_texto` text DEFAULT NULL,
  `id_opcion` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
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
  `numero_documento` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `correo`, `contrasena`, `rol`, `estado`, `fecha_creacion`, `nit`, `direccion`, `razon_social`, `telefono`, `nickname`, `representante_legal`, `tipo_empresa`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `tipo_documento`, `numero_documento`) VALUES
(15, '1@gmail.com', '$2y$10$2Zl9GXH61P.hQvAF/4S6c.rjVB9KbL65Do.xFnvkWM/AfQYQjxwUS', 'empresa', 'Activo', '2025-05-13 12:41:32', '4721', 'cr 6 calle 9-12 primavera azul', 'Migue sas', '1234567891', NULL, 'Migue', 'Industrial', '', NULL, '', NULL, NULL, 0),
(16, 'brauliolapampara@gmail.com', '$2y$10$GBRcdFQgwR4.fZ06B1K1j.GecKahretHi9Lhl/7diaS8/OMqiqSAK', 'AdminSENA', 'Activo', '2025-06-13 03:32:23', NULL, '', NULL, '4444444', NULL, NULL, NULL, 'Braulio', 'Laura', 'Leon', 'Alzate', '', 12345678),
(17, 'crisberx@gmail.com', '$2y$10$r/BPPtq9.jQ.rPjXYYw19.c.vUemlmOITViw5KsWqhuXTa.oiUeV.', 'super_admin', 'Activo', '2025-06-17 02:34:16', NULL, 'Calle 123', NULL, '3001234567', NULL, NULL, NULL, 'Breiner', NULL, 'Chica', 'alzate', 'Cedula de ciudadania', 3001234567),
(19, 'chicaalzateb@gmail.com', '$2y$10$MuUNkZMGj0EGdwCLTGe4fO8oLkfYNxZlcKJeAoURY9bIrjVPkkajm', 'empresa', 'Activo', '2025-06-17 12:23:36', '4422', 'direccion ejemplo', 'Braulio ejemplo sas', '1234567891', NULL, 'Braulio', 'Industrial', '', NULL, '', NULL, NULL, 0),
(20, '', '$2y$10$qFb1tX1qfpR8TZzCDeVkROrs4nNUg7UgPKpckPpKSo5qQ852NEHga', '', 'Activo', '2025-06-30 07:18:15', NULL, '', NULL, '', NULL, NULL, NULL, '', '', '', '', '', 0),
(24, 'osoriolopezjuanfelipe98@gmail.com', '$2y$10$gQp4g5O5jRPd8zenNWy7eOQO8SZ2gmp3J/ygVK5dFxMUUg9/Mvtae', 'empresa', 'Activo', '2025-06-30 09:07:53', '4721233', 'Arabia', 'Monster y cuates', '345333555', NULL, 'Edwin Manito', 'Industrial', '', NULL, '', NULL, NULL, 0),
(25, 'osoriolopezjuanfelipe@gmail.com', '$2y$10$.2Rlugl6baAoj7qA.nenN.urRMg0lcMjaJluQgxGiPj3vT9wfnMoW', 'AdminSENA', 'Activo', '2025-07-01 02:13:30', NULL, '', NULL, '322222222', NULL, NULL, NULL, 'Pele', 'Felipe', 'Osorio', 'Lopez', 'Cedula de ciudadania', 23456789);

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
-- Indices de la tabla `opciones_preguntas`
--
ALTER TABLE `opciones_preguntas`
  ADD PRIMARY KEY (`id_opciones_preguntas`),
  ADD KEY `fk_opciones_preguntas_pregunta` (`id_pregunta`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `preguntas_diagnostico`
--
ALTER TABLE `preguntas_diagnostico`
  ADD PRIMARY KEY (`id_preguntas_diagnostico`);

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
-- Indices de la tabla `respuestas_diagnostico`
--
ALTER TABLE `respuestas_diagnostico`
  ADD PRIMARY KEY (`id_respuestas_diagnostico`),
  ADD KEY `fk_respuestas_diagnostico_empresa` (`id_empresa`),
  ADD KEY `fk_respuestas_diagnostico_pregunta` (`id_pregunta`),
  ADD KEY `fk_respuestas_diagnostico_opcion` (`id_opcion`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `opciones_preguntas`
--
ALTER TABLE `opciones_preguntas`
  MODIFY `id_opciones_preguntas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `preguntas_diagnostico`
--
ALTER TABLE `preguntas_diagnostico`
  MODIFY `id_preguntas_diagnostico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `programas_formacion`
--
ALTER TABLE `programas_formacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT de la tabla `respuestas_diagnostico`
--
ALTER TABLE `respuestas_diagnostico`
  MODIFY `id_respuestas_diagnostico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `opciones_preguntas`
--
ALTER TABLE `opciones_preguntas`
  ADD CONSTRAINT `fk_opciones_preguntas_pregunta` FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas_diagnostico` (`id_preguntas_diagnostico`) ON DELETE CASCADE;

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

--
-- Filtros para la tabla `respuestas_diagnostico`
--
ALTER TABLE `respuestas_diagnostico`
  ADD CONSTRAINT `fk_respuestas_diagnostico_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_respuestas_diagnostico_opcion` FOREIGN KEY (`id_opcion`) REFERENCES `opciones_preguntas` (`id_opciones_preguntas`),
  ADD CONSTRAINT `fk_respuestas_diagnostico_pregunta` FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas_diagnostico` (`id_preguntas_diagnostico`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
