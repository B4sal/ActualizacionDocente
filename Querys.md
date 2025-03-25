-- Crear la tabla periodo 
CREATE TABLE periodo (
    id_periodo INT(3) AUTO_INCREMENT PRIMARY KEY,
    nombre_periodo VARCHAR(15) COLLATE utf8mb4_general_ci NOT NULL
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
    periodo VARCHAR(15) COLLATE utf8mb4_general_ci NOT NULL,
    curso VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
    horario VARCHAR(20) COLLATE utf8mb4_general_ci NOT NULL,
    no_profesores INT NOT NULL,
    PRIMARY KEY (id_curso, periodo),
    FOREIGN KEY (id_curso) REFERENCES cursos(id),
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

-- Crear la tabla Registro con correcciones
CREATE TABLE registro (
    id_periodo VARCHAR(15) COLLATE utf8mb4_general_ci NOT NULL,
    id_curso INT(3) NOT NULL,
    id_profesor INT(3) NOT NULL,
    expediente VARCHAR(10) COLLATE utf8mb4_general_ci NOT NULL,
    nombre VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
    correo VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
    id_horario_curso INT(3) NOT NULL,
    PRIMARY KEY (id_periodo, id_curso, id_profesor, expediente),
    FOREIGN KEY (id_curso, id_periodo) REFERENCES Encabezado(id_curso, periodo),
    FOREIGN KEY (id_curso, id_horario_curso) REFERENCES horario_cursos(id_curso, num)
);
