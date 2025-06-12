-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-06-2025 a las 21:51:06
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
-- Estructura de tabla para la tabla `programas_formacion`
--

CREATE TABLE `programas_formacion` (
  `id` int(11) NOT NULL,
  `nombre_programa` varchar(50) NOT NULL,
  `duracion_meses` int(11) NOT NULL,
  `nivel` enum('Tecnólogo','Técnico','Profundización Técnica','Operario','Especialización') NOT NULL,
  `estado` enum('En curso','Disponible','Finalizado') DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `ficha` int(11) NOT NULL,
  `habilidades_requeridas` text NOT NULL,
  `fecha_finalizacion` date NOT NULL,
  `descripcion` text NOT NULL,
  `codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recuperacion_contrasenas`
--

CREATE TABLE `recuperacion_contrasenas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `codigo_verificacion` varchar(6) NOT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
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
  `tipo_empresa` enum('Agricola','Industrial','Servicios','Conocimiento, Innovacion y Desarrollo') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `apellidos`, `correo`, `contrasena`, `rol`, `estado`, `fecha_creacion`, `nit`, `direccion`, `razon_social`, `telefono`, `nickname`, `representante_legal`, `tipo_empresa`) VALUES
(13, 'Breiner', 'Chica', 'breiner.chica@admin.com', '$2y$10$FrWem4HXtdOMFgY8kEoDrOZthyYLXrIkGJxaYxT5AHaY9hBiC58t.', 'super_admin', 'Activo', '2025-05-09 14:10:06', NULL, 'Calle 123', NULL, '3001234567', NULL, NULL, NULL),
(15, '', '', '1@gmail.com', '$2y$10$2Zl9GXH61P.hQvAF/4S6c.rjVB9KbL65Do.xFnvkWM/AfQYQjxwUS', 'empresa', 'Activo', '2025-05-13 12:41:32', '4721', 'cr 6 calle 9-12 primavera azul', 'pele sas', '1234567891', NULL, 'pele', 'Industrial');

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
-- Indices de la tabla `programas_formacion`
--
ALTER TABLE `programas_formacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recuperacion_contrasenas`
--
ALTER TABLE `recuperacion_contrasenas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `programas_formacion`
--
ALTER TABLE `programas_formacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recuperacion_contrasenas`
--
ALTER TABLE `recuperacion_contrasenas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `recuperacion_contrasenas`
--
ALTER TABLE `recuperacion_contrasenas`
  ADD CONSTRAINT `recuperacion_contrasenas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

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
