-- Insertar datos en la tabla periodo
INSERT INTO periodo (nombre_periodo) VALUES 
('Ago-Dic-2024-1'),
('Ene-Jun-2025-1'),
('Ago-Dic-2025-2');

-- Insertar datos en la tabla cursos
INSERT INTO cursos (curso, status) VALUES 
('Matemáticas', 'A'),
('Física', 'I'),
('Química', 'A');

-- Insertar datos en la tabla Encabezado
INSERT INTO Encabezado (id_curso, periodo, curso, horario, no_profesores) VALUES 
(1, 'Ago-Dic-2024-1', 'Matemáticas', '08:00 - 10:00', 3),
(2, 'Ene-Jun-2025-1', 'Física', '10:00 - 12:00', 2),
(3, 'Ago-Dic-2025-2', 'Química', '12:00 - 14:00', 4);

-- Insertar datos en la tabla horario_cursos
INSERT INTO horario_cursos (id_curso, num, horario) VALUES 
(1, 1, '08:00 - 09:00'),
(1, 2, '09:00 - 10:00'),
(2, 1, '10:00 - 11:00'),
(2, 2, '11:00 - 12:00'),
(3, 1, '12:00 - 13:00'),
(3, 2, '13:00 - 14:00');

-- Insertar datos en la tabla registro
INSERT INTO registro (id_periodo, id_curso, id_profesor, expediente, nombre, correo, id_horario_curso) VALUES 
('Ago-Dic-2024-1', 1, 101, 'EXP001', 'Juan Pérez', 'juan.perez@example.com', 1),
('Ago-Dic-2024-1', 1, 102, 'EXP002', 'María López', 'maria.lopez@example.com', 2),
('Ene-Jun-2025-1', 2, 103, 'EXP003', 'Carlos Díaz', 'carlos.diaz@example.com', 1),
('Ago-Dic-2025-2', 3, 104, 'EXP004', 'Ana Torres', 'ana.torres@example.com', 2);
