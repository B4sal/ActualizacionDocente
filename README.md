-- Crear la tabla periodo
CREATE TABLE periodo (
    id_periodo INT(3) AUTO_INCREMENT PRIMARY KEY,
    nombre_periodo VARCHAR(10) COLLATE utf8mb4_general_ci NOT NULL
);

-- Crear un índice en nombre_periodo
CREATE INDEX idx_nombre_periodo ON periodo(nombre_periodo);

-- Crear la tabla cursos
CREATE TABLE cursos (
    id INT(3) AUTO_INCREMENT PRIMARY KEY,
    curso VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
    status VARCHAR(2) COLLATE utf8mb4_general_ci NOT NULL
);

-- Crear la tabla Encabezado con llave compuesta 
CREATE TABLE Encabezado (
    id_curso INT(3) NOT NULL,
    periodo VARCHAR(10) COLLATE utf8mb4_general_ci NOT NULL,  
    curso VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
    horario VARCHAR(20) COLLATE utf8mb4_general_ci NOT NULL,
    no_profesores INT NOT NULL,
    PRIMARY KEY (id_curso, periodo),
    FOREIGN KEY (id_curso) REFERENCES cursos(id), 
    FOREIGN KEY (periodo) REFERENCES periodo(nombre_periodo)
);

-- Crear la tabla Detalle con llave compuesta
CREATE TABLE Detalle (
    periodo VARCHAR(10) COLLATE utf8mb4_general_ci NOT NULL,
    id_curso INT(3) NOT NULL,  
    expediente VARCHAR(10) COLLATE utf8mb4_general_ci NOT NULL,
    nombre VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
    PRIMARY KEY (periodo, id_curso, expediente),
    FOREIGN KEY (id_curso, periodo) REFERENCES Encabezado(id_curso, periodo),
    FOREIGN KEY (periodo) REFERENCES periodo(nombre_periodo)
);

-- Crear la tabla horario_cursos con relación a cursos
CREATE TABLE horario_cursos (
    id_curso INT(3),
    num INT(3),
    horario VARCHAR(20) COLLATE utf8mb4_general_ci NOT NULL,
    PRIMARY KEY (id_curso, num),
    FOREIGN KEY (id_curso) REFERENCES cursos(id)
);




-- Insertar datos en la tabla periodo
INSERT INTO periodo (nombre_periodo) 
VALUES 
    ('Ago-Dic-1'),
    ('Ene-Jul-2'),
    ('Ago-Dic-2'),
    ('Ene-Jul-1');

-- Insertar datos en la tabla cursos
INSERT INTO cursos (curso, status) 
VALUES 
    ('Matemáticas I', 'AC'),
    ('Física II', 'AC'),
    ('Programación Avanzada', 'AC'),
    ('Redes de Computadoras', 'AC');

-- Insertar datos en la tabla Encabezado
INSERT INTO Encabezado (id_curso, periodo, curso, horario, no_profesores)
VALUES 
    (1, 'Ago-Dic-1', 'Matemáticas I', 'Lunes 10:00-12:00', 2),
    (2, 'Ene-Jul-2', 'Física II', 'Martes 14:00-16:00', 1),
    (3, 'Ago-Dic-2', 'Programación Avanzada', 'Miércoles 9:00-11:00', 3),
    (4, 'Ene-Jul-1', 'Redes de Computadoras', 'Jueves 15:00-17:00', 2);

-- Insertar datos en la tabla Detalle
INSERT INTO Detalle (periodo, id_curso, expediente, nombre)
VALUES 
    ('Ago-Dic-1', 1, 'E21081351', 'Russell Sleither'),
    ('Ene-Jul-2', 2, 'E21081352', 'Ana Pérez'),
    ('Ago-Dic-2', 3, 'E21081353', 'Luis García'),
    ('Ene-Jul-1', 4, 'E21081354', 'Marta López');

-- Insertar datos en la tabla horario_cursos
INSERT INTO horario_cursos (id_curso, num, horario)
VALUES 
    (1, 1, 'Lunes 10:00-12:00'),
    (2, 1, 'Martes 14:00-16:00'),
    (3, 1, 'Miércoles 9:00-11:00'),
    (4, 1, 'Jueves 15:00-17:00');
