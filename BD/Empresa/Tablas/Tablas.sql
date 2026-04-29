CREATE DATABASE KMLLogistics CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE KMLLogistics;

-- TABLA: CATEGORIAS
-- Guarda las categorias del sistema
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL
);

-- TABLA: TIPO_DOCUMENTOS
-- Guarda los tipos de documento permitidos en usuarios y proveedores
CREATE TABLE tipo_documentos (
    id_tipo_documento INT AUTO_INCREMENT PRIMARY KEY,
    nombre_tipo_documento VARCHAR(50) NOT NULL,
    descripcion VARCHAR(150) NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL
);

-- TABLA: PROVEEDORES
-- Guarda los proveedores asociados a las marcas
CREATE TABLE proveedores (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    razon_social VARCHAR(150) NOT NULL,
    nombre_comercial VARCHAR(150) NULL,
    id_tipo_documento INT NOT NULL,
    numero_documento VARCHAR(30) NOT NULL,
    telefono VARCHAR(20) NULL,
    correo VARCHAR(150) NULL,
    direccion VARCHAR(255) NULL,
    contacto VARCHAR(150) NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT fk_proveedores_tipo_documento
        FOREIGN KEY (id_tipo_documento) REFERENCES tipo_documentos(id_tipo_documento),
    UNIQUE KEY uq_proveedor_documento (id_tipo_documento, numero_documento)
);

-- TABLA: MARCAS
-- Guarda las marcas asociadas a los productos
CREATE TABLE marcas (
    id_marca INT AUTO_INCREMENT PRIMARY KEY,
    nombre_marca VARCHAR(100) NOT NULL,
    id_proveedor INT NOT NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT fk_marcas_proveedor FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedor)
);

-- TABLA: PRODUCTOS
-- Guarda el inventario principal del sistema
CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    producto VARCHAR(150) NOT NULL,
    costo DECIMAL(10,2) NOT NULL,
    ganancia DECIMAL(5,4) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    foto VARCHAR(255) NULL,
    id_categoria INT NOT NULL,
    id_marca INT NOT NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT fk_producto_categoria FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria),
    CONSTRAINT fk_producto_marca FOREIGN KEY (id_marca) REFERENCES marcas(id_marca)
);

-- TABLA: USUARIOS
-- Guarda los usuarios que acceden al sistema
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    id_tipo_documento INT NULL,
    numero_documento VARCHAR(30) NULL,
    password_hash VARCHAR(255) NOT NULL,
    rol VARCHAR(50) NOT NULL DEFAULT 'usuario',
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT fk_usuarios_tipo_documento FOREIGN KEY (id_tipo_documento) REFERENCES tipo_documentos(id_tipo_documento)
);

CREATE TABLE usuario_codigos (
    id_codigo INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    tipo VARCHAR(30) NOT NULL,
    codigo_hash VARCHAR(255) NOT NULL,
    destino_email VARCHAR(150) NOT NULL,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_usuario_codigos_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE audits (
    id_audit INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NULL,
    modulo VARCHAR(80) NOT NULL,
    accion VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    datos JSON NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT fk_audit_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- REGISTROS INICIALES: CATEGORIAS
-- Datos base para pruebas del sistema
INSERT INTO categorias (nombre_categoria, descripcion, estado, created_at) VALUES
('Laptops', 'Equipos portatiles para uso empresarial y operativo.', 1, '2026-04-17 09:00:00'),
('Perifericos', 'Accesorios de oficina y productividad.', 1, '2026-04-18 10:00:00'),
('Redes', 'Equipos de conectividad y comunicacion.', 1, '2026-04-19 11:00:00'),
('Seguridad', 'Dispositivos para proteccion y monitoreo.', 1, '2026-04-20 12:00:00'),
('Almacenamiento', 'Soluciones para respaldo y gestion de datos.', 1, '2026-04-21 13:00:00');

-- REGISTROS INICIALES: TIPO_DOCUMENTOS
INSERT INTO tipo_documentos (nombre_tipo_documento, descripcion, estado) VALUES
('DNI', 'Documento Nacional de Identidad para personas naturales.', 1),
('RUC', 'Registro Unico de Contribuyentes para empresas y negocios.', 1),
('PASAPORTE', 'Documento de identidad para ciudadanos extranjeros.', 1),
('Carne Extranjeria', 'Carne de Extranjeria para extranjeros residentes.', 1);

-- REGISTROS INICIALES: PROVEEDORES
INSERT INTO proveedores (
    razon_social,
    nombre_comercial,
    id_tipo_documento,
    numero_documento,
    telefono,
    correo,
    direccion,
    contacto,
    estado
) VALUES
('Dell Peru S.A.C.', 'Dell Peru', 2, '20100011111', '987654321', 'ventas@dellperu.com', 'Av. Javier Prado 100, Lima', 'Carlos Ruiz', 1),
('HP Peru S.R.L.', 'HP Peru', 2, '20100022222', '987654322', 'ventas@hpperu.com', 'Av. La Marina 250, Lima', 'Ana Torres', 1),
('Lenovo Peru S.A.C.', 'Lenovo Peru', 2, '20100033333', '987654323', 'ventas@lenovoperu.com', 'Av. Arequipa 300, Lima', 'Luis Gomez', 1),
('TP-Link Distribuciones S.A.C.', 'TP-Link Peru', 2, '20100044444', '987654324', 'ventas@tplinkperu.com', 'Av. Canada 450, Lima', 'Marta Flores', 1),
('Kingston Technology Peru S.A.C.', 'Kingston Peru', 2, '20100055555', '987654325', 'ventas@kingstonperu.com', 'Av. Primavera 520, Lima', 'Jorge Diaz', 1);

-- REGISTROS INICIALES: MARCAS
INSERT INTO marcas (nombre_marca, id_proveedor, estado) VALUES
('Dell', 1, 1),
('HP', 2, 1),
('Lenovo', 3, 1),
('TP-Link', 4, 1),
('Kingston', 5, 1);

-- REGISTROS INICIALES: PRODUCTOS
INSERT INTO productos (producto, costo, ganancia, precio, stock, id_categoria, id_marca, estado) VALUES
('Laptop Dell Latitude 5440', 2500.00, 0.1525, 2950.00, 15, 1, 1, 1),
('Mouse HP Inalambrico', 45.00, 0.3077, 65.00, 60, 2, 2, 1),
('Router TP-Link Archer AX12', 180.00, 0.2340, 235.00, 25, 3, 4, 1),
('Disco SSD Kingston 1TB', 210.00, 0.2222, 270.00, 40, 5, 5, 1),
('Camara IP Lenovo SecureCam', 320.00, 0.2000, 400.00, 18, 4, 3, 1);

-- REGISTRO INICIAL: USUARIO ADMIN
-- Password base: 123456
INSERT INTO usuarios (nombres, apellidos, correo, id_tipo_documento, numero_documento, password_hash, rol, estado) VALUES
('Admin', 'KML', 'admin@kmllogistics.com', 1, '12345678', '$2y$12$vO638KapJ5QX6ZEB4o893uRTb3Z1gUD1Kd2dPm/rFR874FOatEkKe', 'admin', 1);
