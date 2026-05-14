-- ============================================================
--  SISTEMA DE GESTIÓN VETERINARIA
--  Base de datos: veterinaria_db
--  Autor: Sistema Informático - Politécnico Superior de Ibagué
-- ============================================================

CREATE DATABASE IF NOT EXISTS veterinaria_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE veterinaria_db;

-- ============================================================
-- TABLAS PRINCIPALES
-- ============================================================

-- Tabla DUENO
CREATE TABLE DUENO (
  ID_Dueno    INT AUTO_INCREMENT PRIMARY KEY,
  Nombre      VARCHAR(100) NOT NULL,
  Apellido    VARCHAR(100) NOT NULL,
  Telefono    VARCHAR(20),
  Direccion   VARCHAR(200),
  Email       VARCHAR(100),
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla VETERINARIO
CREATE TABLE VETERINARIO (
  ID_Vet       INT AUTO_INCREMENT PRIMARY KEY,
  Nombre       VARCHAR(100) NOT NULL,
  Especialidad VARCHAR(80),
  Horario      VARCHAR(50),
  Telefono     VARCHAR(20),
  NumLicencia  VARCHAR(30) UNIQUE,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla MASCOTA
CREATE TABLE MASCOTA (
  ID_Mascota      INT AUTO_INCREMENT PRIMARY KEY,
  Nombre          VARCHAR(80) NOT NULL,
  Especie         VARCHAR(50),
  Raza            VARCHAR(50),
  FechaNacimiento DATE,
  ID_Dueno        INT NOT NULL,
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_mascota_dueno FOREIGN KEY (ID_Dueno)
    REFERENCES DUENO(ID_Dueno) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabla MEDICAMENTO
CREATE TABLE MEDICAMENTO (
  ID_Med      INT AUTO_INCREMENT PRIMARY KEY,
  Nombre      VARCHAR(100) NOT NULL,
  Dosis       VARCHAR(50),
  Precio      DECIMAL(8,2) DEFAULT 0.00,
  Stock       INT DEFAULT 0,
  Descripcion TEXT,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla CITA
CREATE TABLE CITA (
  ID_Cita    INT AUTO_INCREMENT PRIMARY KEY,
  Fecha      DATE NOT NULL,
  Motivo     VARCHAR(200),
  ID_Mascota INT NOT NULL,
  ID_Vet     INT NOT NULL,
  ID_Med     INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_cita_mascota FOREIGN KEY (ID_Mascota)
    REFERENCES MASCOTA(ID_Mascota) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_cita_vet FOREIGN KEY (ID_Vet)
    REFERENCES VETERINARIO(ID_Vet) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_cita_med FOREIGN KEY (ID_Med)
    REFERENCES MEDICAMENTO(ID_Med) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- DATOS DE EJEMPLO
-- ============================================================

INSERT INTO DUENO (Nombre, Apellido, Telefono, Direccion, Email) VALUES
('Carlos',   'Ramírez',  '3101234567', 'Calle 15 #23-45, Ibagué',    'carlos.ramirez@email.com'),
('Ana',      'Gómez',    '3209876543', 'Carrera 5 #12-30, Ibagué',   'ana.gomez@email.com'),
('Pedro',    'Martínez', '3154567890', 'Av. Ambala #67-89, Ibagué',  'pedro.martinez@email.com'),
('Luisa',    'Torres',   '3001112233', 'Calle 40 #18-22, Ibagué',    'luisa.torres@email.com'),
('Andrés',   'López',    '3178889900', 'Carrera 10 #5-15, Ibagué',   'andres.lopez@email.com'),
('Beatriz',  'Ruiz',     '3113334444', 'Calle 20 #10-10, Ibagué',    'beatriz.ruiz@email.com'),
('Fernando', 'Castro',   '3124445555', 'Av. Guabinal #40-20, Ibagué', 'fernando.castro@email.com'),
('Gloria',   'Díaz',     '3135556666', 'Carrera 4 #15-30, Ibagué',   'gloria.diaz@email.com'),
('Hugo',     'Silva',    '3146667777', 'Calle 80 #5-50, Ibagué',     'hugo.silva@email.com'),
('Isabel',   'Mendoza',  '3157778888', 'Av. Mirolindo #12-34, Ibagué','isabel.mendoza@email.com');

INSERT INTO VETERINARIO (Nombre, Especialidad, Horario, Telefono, NumLicencia) VALUES
('Dra. Sofía Herrera',  'Medicina General',    'Lun-Vie 8am-5pm',  '3112223344', 'VET-001'),
('Dr. Julián Castro',   'Cirugía Animal',      'Mar-Sab 9am-6pm',  '3223334455', 'VET-002'),
('Dra. Paola Sánchez',  'Dermatología Animal', 'Lun-Jue 7am-4pm',  '3334445566', 'VET-003'),
('Dr. Camilo Vargas',   'Odontología Animal',  'Mie-Dom 10am-7pm', '3445556677', 'VET-004'),
('Dr. Roberto Peña',    'Cardiología',         'Lun-Vie 9am-4pm',  '3101112222', 'VET-005'),
('Dra. Martha Ortiz',   'Oftalmología',        'Mar-Sab 8am-3pm',  '3112223333', 'VET-006'),
('Dr. Jorge Luna',      'Neurología',          'Lun-Vie 10am-6pm', '3123334444', 'VET-007'),
('Dra. Elena Soler',    'Nutrición',           'Jue-Dom 9am-5pm',  '3134445555', 'VET-008'),
('Dr. Fabio Cruz',      'Ortopedia',           'Lun-Mie 7am-2pm',  '3145556666', 'VET-009'),
('Dra. Clara Joven',    'Medicina General',    'Vie-Lun 8am-5pm',  '3156667777', 'VET-010');

INSERT INTO MASCOTA (Nombre, Especie, Raza, FechaNacimiento, ID_Dueno) VALUES
('Toby',    'Perro', 'Labrador',         '2020-03-15', 1),
('Luna',    'Gato',  'Siamés',           '2019-07-22', 2),
('Max',     'Perro', 'Pastor Alemán',    '2021-01-10', 3),
('Mia',     'Gato',  'Persa',            '2018-11-05', 4),
('Rocky',   'Perro', 'Bulldog',          '2022-05-20', 5),
('Nala',    'Gato',  'Angora',           '2020-09-12', 1),
('Bruno',   'Perro', 'Golden Retriever', '2019-02-28', 2),
('Coco',    'Perro', 'Poodle',           '2023-01-15', 6),
('Simba',   'Gato',  'Criollo',          '2021-06-10', 7),
('Milo',    'Perro', 'Beagle',           '2020-12-05', 8),
('Lola',    'Gato',  'Maine Coon',       '2022-03-20', 9),
('Baco',    'Perro', 'Dálmata',          '2019-11-30', 10);

INSERT INTO MEDICAMENTO (Nombre, Dosis, Precio, Stock, Descripcion) VALUES
('Amoxicilina 500mg',  '1 cápsula cada 8h', 25000.00,  50, 'Antibiótico de amplio espectro'),
('Ibuprofeno Vet',     '5mg/kg cada 12h',   18000.00,  40, 'Antiinflamatorio para mascotas'),
('Ivermectina 1%',     '0.2ml/kg mensual',  32000.00,  30, 'Antiparasitario externo e interno'),
('Metronidazol 250mg', '15mg/kg cada 12h',  22000.00,  60, 'Antibiótico para infecciones GI'),
('Prednisona 5mg',     '0.5mg/kg cada 24h', 28000.00,  35, 'Corticosteroide para inflamación'),
('Frontline Spray',    'Aplicar cada 30 días', 45000.00, 20, 'Antipulgas y garrapatas'),
('Vitamina B12',       '1ml semanal',       15000.00, 100, 'Suplemento vitamínico'),
('NexGard',            '1 tableta mensual', 55000.00,  15, 'Antipulgas masticable'),
('Apoquel 5.4mg',      '0.4-0.6 mg/kg c/12h', 120000.00, 10, 'Tratamiento para prurito'),
('Tramadol 50mg',      '2mg/kg cada 8h',    35000.00,  25, 'Analgésico potente');

INSERT INTO CITA (Fecha, Motivo, ID_Mascota, ID_Vet, ID_Med) VALUES
('2026-01-10', 'Vacunación anual',          1, 1, 3),
('2026-01-15', 'Control de peso',           2, 3, NULL),
('2026-01-20', 'Infección gastrointestinal',3, 2, 4),
('2026-02-05', 'Dermatitis alérgica',       4, 3, 2),
('2026-02-12', 'Revisión dental',           5, 4, NULL),
('2026-02-18', 'Desparasitación',           6, 1, 3),
('2026-03-01', 'Infección respiratoria',    7, 2, 1),
('2026-03-10', 'Control postoperatorio',    1, 2, 5),
('2026-03-22', 'Chequeo general',           3, 1, NULL),
('2026-04-01', 'Pulgas y garrapatas',       5, 3, 6),
('2026-04-10', 'Control de alergias',       8, 5, 9),
('2026-04-15', 'Dolor articular',           9, 9, 10),
('2026-04-20', 'Chequeo de ojos',           10, 6, NULL),
('2026-05-02', 'Control cardiaco',          11, 5, NULL),
('2026-05-05', 'Vacunación refuerzo',       12, 1, 8);

-- ============================================================
-- CONSULTAS DE REPORTES (Para referencia)
-- ============================================================

-- 1. Citas con nombre de mascota, dueño y veterinario
-- SELECT C.ID_Cita, C.Fecha, C.Motivo,
--        M.Nombre AS Mascota, CONCAT(D.Nombre,' ',D.Apellido) AS Dueño,
--        V.Nombre AS Veterinario
-- FROM CITA C
-- JOIN MASCOTA M ON C.ID_Mascota = M.ID_Mascota
-- JOIN DUENO D   ON M.ID_Dueno   = D.ID_Dueno
-- JOIN VETERINARIO V ON C.ID_Vet = V.ID_Vet
-- ORDER BY C.Fecha DESC;

-- 2. Mascotas con sus dueños
-- SELECT M.Nombre AS Mascota, M.Especie, M.Raza,
--        CONCAT(D.Nombre,' ',D.Apellido) AS Dueño, D.Telefono
-- FROM MASCOTA M JOIN DUENO D ON M.ID_Dueno = D.ID_Dueno
-- ORDER BY D.Apellido;

-- 3. Citas con medicamentos prescritos
-- SELECT C.ID_Cita, C.Fecha, M.Nombre AS Mascota,
--        Med.Nombre AS Medicamento, Med.Dosis
-- FROM CITA C
-- JOIN MASCOTA M ON C.ID_Mascota = M.ID_Mascota
-- LEFT JOIN MEDICAMENTO Med ON C.ID_Med = Med.ID_Med
-- ORDER BY C.Fecha DESC;

-- 4. Historial completo de citas por mascota
-- SELECT CONCAT(D.Nombre,' ',D.Apellido) AS Dueño,
--        M.Nombre AS Mascota, M.Especie,
--        COUNT(C.ID_Cita) AS TotalCitas
-- FROM MASCOTA M
-- JOIN DUENO D ON M.ID_Dueno = D.ID_Dueno
-- LEFT JOIN CITA C ON M.ID_Mascota = C.ID_Mascota
-- GROUP BY M.ID_Mascota
-- ORDER BY TotalCitas DESC;

-- 5. Veterinarios con mayor número de citas
-- SELECT V.Nombre AS Veterinario, V.Especialidad,
--        COUNT(C.ID_Cita) AS TotalCitas
-- FROM VETERINARIO V
-- LEFT JOIN CITA C ON V.ID_Vet = C.ID_Vet
-- GROUP BY V.ID_Vet
-- ORDER BY TotalCitas DESC;
