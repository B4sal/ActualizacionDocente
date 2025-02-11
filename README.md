-- Crear la tabla periodo
CREATE TABLE periodo (
    id_periodo INT(3) AUTO_INCREMENT PRIMARY KEY,
    nombre_periodo VARCHAR(10) COLLATE utf8mb3_general_ci NOT NULL
);

-- Crear la tabla cursos
CREATE TABLE cursos (
    id INT(3) AUTO_INCREMENT PRIMARY KEY,
    curso VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
    status VARCHAR(2) COLLATE utf8mb4_general_ci NOT NULL
);

-- Crear la tabla Encabezado con id_encabezado como clave primaria
CREATE TABLE Encabezado (
    id_encabezado INT AUTO_INCREMENT PRIMARY KEY,  -- Nueva clave primaria
    id_curso INT NOT NULL,
    id_periodo INT(3) NOT NULL,
    curso VARCHAR(50) NOT NULL,
    horario VARCHAR(20) NOT NULL,
    no_profesores INT NOT NULL,
    FOREIGN KEY (id_curso) REFERENCES cursos(id), 
    FOREIGN KEY (id_periodo) REFERENCES periodo(id_periodo)
);

-- Crear la tabla Detalle con relación a Encabezado y periodo
CREATE TABLE Detalle (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_periodo INT(3) NOT NULL,
    id_encabezado INT NOT NULL,  -- Ahora referencia id_encabezado, no id_curso
    expediente VARCHAR(10) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_encabezado) REFERENCES Encabezado(id_encabezado), -- Corrección
    FOREIGN KEY (id_periodo) REFERENCES periodo(id_periodo)
);

-- Crear la tabla horario_cursos con relación a cursos
CREATE TABLE horario_cursos (
    id_curso INT(3),
    num INT(3),
    horario VARCHAR(20),
    PRIMARY KEY (id_curso, num),
    FOREIGN KEY (id_curso) REFERENCES cursos(id)
);
