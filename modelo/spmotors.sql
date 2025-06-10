CREATE DATABASE spmotors;
USE spmotors;

CREATE TABLE eventos_coches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    fecha DATE NOT NULL,
    ubicacion VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL
);