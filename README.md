-- Crear la tabla Encabezado (id_curso como clave primaria)
CREATE TABLE Encabezado (
    id_curso INT PRIMARY KEY, -- id_curso es clave primaria directamente
    periodo VARCHAR(20) NOT NULL,
    curso VARCHAR(50) NOT NULL,
    horario VARCHAR(20) NOT NULL,
    no_profesores INT NOT NULL
);

-- Crear la tabla Detalle
CREATE TABLE Detalle (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    periodo VARCHAR(20) NOT NULL,
    id_curso INT NOT NULL,
    expediente VARCHAR(10) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_curso) REFERENCES Encabezado(id_curso)
);
