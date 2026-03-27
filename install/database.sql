-- =============================================
-- EduTest 2026 - Base de Datos
-- Importar en phpMyAdmin de cPanel (Namecheap)
-- =============================================

-- IMPORTANTE: Primero crear la base de datos en cPanel
-- y luego importar este archivo en phpMyAdmin
-- No incluir la linea CREATE DATABASE si ya existe

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

-- ---- Tabla de usuarios del sistema ----
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(200) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `usuario` VARCHAR(100) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `rol` ENUM('admin','coordinador','secretaria','docente') DEFAULT 'admin',
  `activo` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuario admin por defecto: admin / admin123
INSERT INTO `usuarios` (`nombre`, `email`, `usuario`, `password`, `rol`) VALUES
('Administrador', 'admin@escuela.com', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- ---- Tabla de cursos ----
CREATE TABLE IF NOT EXISTS `cursos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(200) NOT NULL,
  `descripcion` TEXT,
  `nivel` VARCHAR(100),
  `ano_lectivo` INT DEFAULT 2026,
  `activo` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `cursos` (`nombre`, `nivel`, `ano_lectivo`) VALUES
('Primero A', 'Primaria', 2026),
('Segundo A', 'Primaria', 2026),
('Tercero A', 'Primaria', 2026),
('Cuarto A', 'Primaria', 2026),
('Quinto A', 'Primaria', 2026),
('Sexto A', 'Secundaria', 2026),
('Septimo A', 'Secundaria', 2026),
('Octavo A', 'Secundaria', 2026),
('Noveno A', 'Secundaria', 2026),
('Decimo A', 'Media', 2026),
('Once A', 'Media', 2026);

-- ---- Tabla de profesores ----
CREATE TABLE IF NOT EXISTS `profesores` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(200) NOT NULL,
  `apellido` VARCHAR(200) NOT NULL,
  `documento` VARCHAR(50),
  `email` VARCHAR(100),
  `telefono` VARCHAR(50),
  `especialidad` VARCHAR(200),
  `direccion` VARCHAR(255),
  `fecha_ingreso` DATE,
  `activo` TINYINT(1) DEFAULT 1,
  `foto` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---- Tabla de estudiantes ----
CREATE TABLE IF NOT EXISTS `estudiantes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(200) NOT NULL,
  `apellido` VARCHAR(200) NOT NULL,
  `documento` VARCHAR(50),
  `email` VARCHAR(100),
  `telefono` VARCHAR(50),
  `fecha_nacimiento` DATE,
  `curso_id` INT,
  `direccion` VARCHAR(255),
  `acudiente` VARCHAR(200),
  `tel_acudiente` VARCHAR(50),
  `activo` TINYINT(1) DEFAULT 1,
  `foto` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`curso_id`) REFERENCES `cursos`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---- Tabla de notas ----
CREATE TABLE IF NOT EXISTS `notas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `estudiante_id` INT NOT NULL,
  `curso_id` INT,
  `materia` VARCHAR(200) NOT NULL,
  `nota` DECIMAL(4,2) DEFAULT 0.00,
  `periodo` VARCHAR(50),
  `observaciones` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`curso_id`) REFERENCES `cursos`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---- Tabla de asistencia ----
CREATE TABLE IF NOT EXISTS `asistencia` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `estudiante_id` INT NOT NULL,
  `curso_id` INT,
  `fecha` DATE NOT NULL,
  `estado` ENUM('Presente','Ausente','Tarde','Justificado') DEFAULT 'Presente',
  `observaciones` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`curso_id`) REFERENCES `cursos`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET foreign_key_checks = 1;
