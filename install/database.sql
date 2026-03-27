-- =============================================
-- SISTEMA DE GESTION ESCOLAR - database.sql
-- Importar en phpMyAdmin de cPanel
-- =============================================

CREATE DATABASE IF NOT EXISTS school_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE school_db;

-- Tabla de institucion educativa
CREATE TABLE institucion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL,
  nit VARCHAR(50),
  direccion VARCHAR(255),
  telefono VARCHAR(50),
  email VARCHAR(100),
  logo VARCHAR(255),
  ciudad VARCHAR(100) DEFAULT 'Colombia',
  ano_lectivo INT DEFAULT 2026
);
INSERT INTO institucion (nombre, nit, ciudad, ano_lectivo)
VALUES ('Instituto Docente Gotita de Gente', '900.123.456-7', 'Colombia', 2026);

-- Tabla de usuarios administradores
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  username VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  rol ENUM('admin','coordinador','secretaria','docente') DEFAULT 'admin',
  activo TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Usuario admin por defecto: admin / admin123
INSERT INTO usuarios (nombre, email, username, password, rol)
VALUES ('Administrador', 'admin@escuela.com', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Tabla de padres y acudientes
CREATE TABLE padres (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL,
  documento VARCHAR(50) UNIQUE NOT NULL,
  tipo_doc ENUM('CC','CE','PA') DEFAULT 'CC',
  telefono VARCHAR(50),
  email VARCHAR(100),
  direccion VARCHAR(255),
  username VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  activo TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de grados academicos
CREATE TABLE grados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  nivel ENUM('preescolar','primaria','secundaria','media') DEFAULT 'primaria',
  orden INT DEFAULT 0,
  activo TINYINT(1) DEFAULT 1
);
INSERT INTO grados (nombre, nivel, orden) VALUES
('Jardin', 'preescolar', 1), ('Transicion', 'preescolar', 2),
('Primero', 'primaria', 3), ('Segundo', 'primaria', 4),
('Tercero', 'primaria', 5), ('Cuarto', 'primaria', 6),
('Quinto', 'primaria', 7), ('Sexto', 'secundaria', 8),
('Septimo', 'secundaria', 9), ('Octavo', 'secundaria', 10),
('Noveno', 'secundaria', 11), ('Decimo', 'media', 12),
('Once', 'media', 13);

-- Tabla de grupos / salones
CREATE TABLE grupos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  grado_id INT NOT NULL,
  nombre VARCHAR(10) NOT NULL,
  ano_lectivo INT DEFAULT 2026,
  docente_director INT,
  activo TINYINT(1) DEFAULT 1,
  FOREIGN KEY (grado_id) REFERENCES grados(id)
);

-- Tabla de docentes
CREATE TABLE docentes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL,
  documento VARCHAR(50) UNIQUE NOT NULL,
  telefono VARCHAR(50),
  email VARCHAR(100),
  especialidad VARCHAR(200),
  activo TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de estudiantes
CREATE TABLE estudiantes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL,
  documento VARCHAR(50),
  fecha_nacimiento DATE,
  genero ENUM('M','F') DEFAULT 'M',
  grupo_id INT,
  padre_id INT,
  estado ENUM('activo','retirado','graduado') DEFAULT 'activo',
  foto VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (grupo_id) REFERENCES grupos(id) ON DELETE SET NULL,
  FOREIGN KEY (padre_id) REFERENCES padres(id) ON DELETE SET NULL
);

-- Tabla de materias / asignaturas
CREATE TABLE materias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL,
  area VARCHAR(100),
  activo TINYINT(1) DEFAULT 1
);
INSERT INTO materias (nombre, area) VALUES
('Matematicas','Matematicas'), ('Espanol','Lenguaje'),
('Ciencias Naturales','Ciencias'), ('Ciencias Sociales','Sociales'),
('Ingles','Idiomas'), ('Educacion Fisica','Ed. Fisica'),
('Artistica','Artistica'), ('Etica','Etica'),
('Religion','Religion'), ('Informatica','Tecnologia'),
('Robotica','Tecnologia');

-- Asignacion de materias a grupos con docente
CREATE TABLE grupo_materia (
  id INT AUTO_INCREMENT PRIMARY KEY,
  grupo_id INT NOT NULL,
  materia_id INT NOT NULL,
  docente_id INT,
  ano_lectivo INT DEFAULT 2026,
  FOREIGN KEY (grupo_id) REFERENCES grupos(id),
  FOREIGN KEY (materia_id) REFERENCES materias(id),
  FOREIGN KEY (docente_id) REFERENCES docentes(id) ON DELETE SET NULL
);

-- Tabla de periodos academicos
CREATE TABLE periodos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  ano_lectivo INT DEFAULT 2026,
  fecha_inicio DATE,
  fecha_fin DATE,
  porcentaje DECIMAL(5,2) DEFAULT 25.00,
  activo TINYINT(1) DEFAULT 1
);
INSERT INTO periodos (nombre, ano_lectivo, porcentaje) VALUES
('Periodo 1', 2026, 25.00), ('Periodo 2', 2026, 25.00),
('Periodo 3', 2026, 25.00), ('Periodo 4', 2026, 25.00);

-- Tabla de actividades academicas (tareas, talleres, examenes)
CREATE TABLE actividades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  grupo_id INT NOT NULL,
  materia_id INT NOT NULL,
  periodo_id INT NOT NULL,
  docente_id INT,
  titulo VARCHAR(255) NOT NULL,
  descripcion TEXT,
  tipo ENUM('Tarea','Taller','Examen','Actividad','Quiz','Proyecto') DEFAULT 'Actividad',
  fecha_entrega DATE,
  ano_lectivo INT DEFAULT 2026,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (grupo_id) REFERENCES grupos(id),
  FOREIGN KEY (materia_id) REFERENCES materias(id),
  FOREIGN KEY (periodo_id) REFERENCES periodos(id),
  FOREIGN KEY (docente_id) REFERENCES docentes(id) ON DELETE SET NULL
);

-- Tabla de notas por actividad
CREATE TABLE notas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  actividad_id INT NOT NULL,
  estudiante_id INT NOT NULL,
  nota DECIMAL(4,2) DEFAULT 0.00,
  observaciones TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (actividad_id) REFERENCES actividades(id),
  FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id)
);

-- Tabla de horarios
CREATE TABLE horarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  grupo_id INT NOT NULL,
  materia_id INT NOT NULL,
  docente_id INT,
  dia ENUM('Lunes','Martes','Miercoles','Jueves','Viernes') NOT NULL,
  hora_inicio TIME NOT NULL,
  hora_fin TIME NOT NULL,
  ano_lectivo INT DEFAULT 2026,
  FOREIGN KEY (grupo_id) REFERENCES grupos(id),
  FOREIGN KEY (materia_id) REFERENCES materias(id),
  FOREIGN KEY (docente_id) REFERENCES docentes(id) ON DELETE SET NULL
);

-- Tabla de avisos / circulares
CREATE TABLE avisos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255) NOT NULL,
  contenido TEXT,
  tipo ENUM('General','Urgente','Academico','Administrativo') DEFAULT 'General',
  fecha DATE NOT NULL,
  activo TINYINT(1) DEFAULT 1,
  usuario_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Tabla de conceptos de pago
CREATE TABLE conceptos_pago (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL,
  descripcion TEXT,
  valor DECIMAL(12,2) DEFAULT 0.00,
  tipo ENUM('mensualidad','matricula','servicio','otro') DEFAULT 'otro',
  activo TINYINT(1) DEFAULT 1
);
INSERT INTO conceptos_pago (nombre, tipo, valor) VALUES
('Matricula 2026', 'matricula', 500000),
('Pension Mensual', 'mensualidad', 150000),
('Transporte', 'servicio', 80000),
('Material Didactico', 'servicio', 50000);

-- Tabla de cargos asignados a estudiantes
CREATE TABLE cargos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  estudiante_id INT NOT NULL,
  concepto_id INT NOT NULL,
  valor DECIMAL(12,2) NOT NULL,
  mes VARCHAR(20),
  ano_lectivo INT DEFAULT 2026,
  estado ENUM('pendiente','pagado','parcial') DEFAULT 'pendiente',
  fecha_vencimiento DATE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id),
  FOREIGN KEY (concepto_id) REFERENCES conceptos_pago(id)
);

-- Tabla de pagos recibidos
CREATE TABLE pagos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cargo_id INT,
  estudiante_id INT NOT NULL,
  padre_id INT,
  valor_pagado DECIMAL(12,2) NOT NULL,
  fecha_pago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  metodo_pago ENUM('efectivo','transferencia','cheque','otro') DEFAULT 'efectivo',
  recibo_num VARCHAR(50),
  observaciones TEXT,
  usuario_id INT,
  FOREIGN KEY (cargo_id) REFERENCES cargos(id) ON DELETE SET NULL,
  FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id),
  FOREIGN KEY (padre_id) REFERENCES padres(id) ON DELETE SET NULL,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Tabla de boletines
CREATE TABLE boletines (
  id INT AUTO_INCREMENT PRIMARY KEY,
  periodo_id INT NOT NULL,
  grupo_id INT NOT NULL,
  titulo VARCHAR(255),
  fecha_publicacion DATE,
  activo TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (periodo_id) REFERENCES periodos(id),
  FOREIGN KEY (grupo_id) REFERENCES grupos(id)
);

-- Tabla de matriculas
CREATE TABLE matriculas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  estudiante_id INT NOT NULL,
  grupo_id INT NOT NULL,
  ano_lectivo INT DEFAULT 2026,
  fecha_matricula DATE,
  estado ENUM('activa','cancelada','trasladada') DEFAULT 'activa',
  observaciones TEXT,
  usuario_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id),
  FOREIGN KEY (grupo_id) REFERENCES grupos(id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);
