-- =========================================================
-- KML LOGISTICS
-- Script limpio para crear la base de datos, tablas,
-- registros iniciales y procedimientos almacenados.
-- =========================================================

-- CREACION DE LA BASE DE DATOS
DROP DATABASE IF EXISTS KMLLogistics;
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

-- PROCEDIMIENTOS ALMACENADOS: MODULO PRODUCTOS

DELIMITER $$
-- PROCEDIMIENTO: SP_BUSCAR_PRODUCTO_POR_ID
-- Busca un producto activo por su ID autoincremental
CREATE PROCEDURE sp_buscar_producto_por_id(IN p_id_producto INT)
BEGIN
    SELECT
        p.id_producto,
        p.producto,
        p.costo,
        p.ganancia,
        ROUND(p.costo / NULLIF(1 - p.ganancia, 0), 2) AS precio,
        p.stock,
        (ROUND(p.costo / NULLIF(1 - p.ganancia, 0), 2) * p.stock) AS total,
        c.nombre_categoria AS categoria,
        m.nombre_marca AS marca
    FROM productos p
    INNER JOIN categorias c ON c.id_categoria = p.id_categoria
    INNER JOIN marcas m ON m.id_marca = p.id_marca
    WHERE p.estado = 1
      AND p.id_producto = p_id_producto;
END $$
DELIMITER ;

DELIMITER $$
-- PROCEDIMIENTO: SP_FILTRAR_POR_NOMBRE
-- Lista productos activos por coincidencia de nombre
CREATE PROCEDURE sp_filtrar_por_nombre(IN p_nombre VARCHAR(150))
BEGIN
    SELECT
        p.id_producto,
        p.producto,
        p.costo,
        p.ganancia,
        ROUND(p.costo / NULLIF(1 - p.ganancia, 0), 2) AS precio,
        p.stock,
        (ROUND(p.costo / NULLIF(1 - p.ganancia, 0), 2) * p.stock) AS total,
        c.nombre_categoria AS categoria,
        m.nombre_marca AS marca
    FROM productos p
    INNER JOIN categorias c ON c.id_categoria = p.id_categoria
    INNER JOIN marcas m ON m.id_marca = p.id_marca
    WHERE p.estado = 1
      AND p.producto LIKE CONCAT(p_nombre, '%')
    ORDER BY p.producto ASC;
END $$
DELIMITER ;

-- EJEMPLOS DE USO PRODUCTOS
-- CALL sp_buscar_producto_por_id(1);
-- CALL sp_filtrar_por_nombre('Disco');

-- PROCEDIMIENTOS ALMACENADOS: MODULO CATEGORY
-- Nota:
-- Los SP de listado filtran solo por nombre_categoria usando prefijo.
-- La busqueda por ID se resuelve desde frontend consumiendo
-- sp_categoria_obtener_activa_por_id.

DELIMITER $$

-- PROCEDIMIENTO: SP_CATEGORIA_LISTAR_ACTIVAS
-- Lista categorias activas con paginacion y filtro opcional
CREATE PROCEDURE sp_categoria_listar_activas(
    IN p_offset INT,
    IN p_limit INT,
    IN p_search VARCHAR(100)
)
BEGIN
    DECLARE v_search VARCHAR(100);
    SET v_search = TRIM(COALESCE(p_search, ''));

    SELECT
        id_categoria,
        nombre_categoria,
        descripcion,
        estado,
        created_at,
        updated_at,
        deleted_at
    FROM categorias
    WHERE deleted_at IS NULL
      AND estado = 1
      AND (
            v_search = ''
            OR nombre_categoria LIKE CONCAT(v_search, '%')
      )
    ORDER BY created_at DESC, id_categoria DESC
    LIMIT p_offset, p_limit;
END $$

-- PROCEDIMIENTO: SP_CATEGORIA_CONTAR_ACTIVAS
-- Cuenta categorias activas segun el filtro aplicado
CREATE PROCEDURE sp_categoria_contar_activas(
    IN p_search VARCHAR(100)
)
BEGIN
    DECLARE v_search VARCHAR(100);
    SET v_search = TRIM(COALESCE(p_search, ''));

    SELECT COUNT(*) AS total
    FROM categorias
    WHERE deleted_at IS NULL
      AND estado = 1
      AND (
            v_search = ''
            OR nombre_categoria LIKE CONCAT(v_search, '%')
      );
END $$

-- PROCEDIMIENTO: SP_CATEGORIA_LISTAR_INACTIVAS
-- Lista categorias inactivas o eliminadas logicamente
CREATE PROCEDURE sp_categoria_listar_inactivas(
    IN p_search VARCHAR(100)
)
BEGIN
    DECLARE v_search VARCHAR(100);
    SET v_search = TRIM(COALESCE(p_search, ''));

    SELECT
        id_categoria,
        nombre_categoria,
        descripcion,
        estado,
        created_at,
        updated_at,
        deleted_at
    FROM categorias
    WHERE (deleted_at IS NOT NULL OR estado = 0)
      AND (
            v_search = ''
            OR nombre_categoria LIKE CONCAT(v_search, '%')
      )
    ORDER BY deleted_at DESC, id_categoria DESC;
END $$

-- PROCEDIMIENTO: SP_CATEGORIA_OBTENER_ACTIVA_POR_ID
-- Obtiene una categoria activa por su identificador
CREATE PROCEDURE sp_categoria_obtener_activa_por_id(
    IN p_id_categoria INT
)
BEGIN
    SELECT
        id_categoria,
        nombre_categoria,
        descripcion,
        estado,
        created_at,
        updated_at
    FROM categorias
    WHERE id_categoria = p_id_categoria
      AND deleted_at IS NULL
      AND estado = 1
    LIMIT 1;
END $$

-- PROCEDIMIENTO: SP_CATEGORIA_OBTENER_POR_ID
-- Obtiene una categoria sin importar su estado
CREATE PROCEDURE sp_categoria_obtener_por_id(
    IN p_id_categoria INT
)
BEGIN
    SELECT
        id_categoria,
        nombre_categoria,
        descripcion,
        estado,
        created_at,
        updated_at,
        deleted_at
    FROM categorias
    WHERE id_categoria = p_id_categoria
    LIMIT 1;
END $$

-- PROCEDIMIENTO: SP_CATEGORIA_CREAR
-- Registra una nueva categoria y devuelve su ID
CREATE PROCEDURE sp_categoria_crear(
    IN p_nombre_categoria VARCHAR(100),
    IN p_descripcion VARCHAR(255),
    IN p_estado TINYINT
)
BEGIN
    INSERT INTO categorias (nombre_categoria, descripcion, estado, deleted_at)
    VALUES (
        p_nombre_categoria,
        p_descripcion,
        p_estado,
        CASE WHEN p_estado = 0 THEN CURRENT_TIMESTAMP ELSE NULL END
    );

    SELECT LAST_INSERT_ID() AS id_categoria;
END $$

-- PROCEDIMIENTO: SP_CATEGORIA_ACTUALIZAR
-- Actualiza una categoria activa existente
CREATE PROCEDURE sp_categoria_actualizar(
    IN p_id_categoria INT,
    IN p_nombre_categoria VARCHAR(100),
    IN p_descripcion VARCHAR(255),
    IN p_estado TINYINT
)
BEGIN
    UPDATE categorias
    SET nombre_categoria = p_nombre_categoria,
        descripcion = p_descripcion,
        estado = p_estado,
        deleted_at = CASE
            WHEN p_estado = 0 THEN COALESCE(deleted_at, CURRENT_TIMESTAMP)
            ELSE NULL
        END
    WHERE id_categoria = p_id_categoria
      AND deleted_at IS NULL;

    SELECT ROW_COUNT() AS affected_rows;
END $$

-- PROCEDIMIENTO: SP_CATEGORIA_ELIMINAR_LOGICO
-- Marca una categoria como inactiva y eliminada logicamente
CREATE PROCEDURE sp_categoria_eliminar_logico(
    IN p_id_categoria INT
)
BEGIN
    UPDATE categorias
    SET estado = 0,
        deleted_at = CURRENT_TIMESTAMP
    WHERE id_categoria = p_id_categoria
      AND deleted_at IS NULL;

    SELECT ROW_COUNT() AS affected_rows;
END $$

-- PROCEDIMIENTO: SP_CATEGORIA_RESTAURAR
-- Restaura una categoria previamente desactivada
CREATE PROCEDURE sp_categoria_restaurar(
    IN p_id_categoria INT
)
BEGIN
    UPDATE categorias
    SET estado = 1,
        deleted_at = NULL
    WHERE id_categoria = p_id_categoria
      AND (deleted_at IS NOT NULL OR estado = 0);

    SELECT ROW_COUNT() AS affected_rows;
END $$

-- PROCEDIMIENTO: SP_CATEGORIA_ELIMINAR_DEFINITIVO
-- Elimina en cascada productos asociados y luego la categoria
CREATE PROCEDURE sp_categoria_eliminar_definitivo(
    IN p_id_categoria INT
)
BEGIN
    DECLARE v_deleted_products INT DEFAULT 0;
    DECLARE v_deleted_category INT DEFAULT 0;

    START TRANSACTION;

    DELETE FROM productos
    WHERE id_categoria = p_id_categoria;
    SET v_deleted_products = ROW_COUNT();

    DELETE FROM categorias
    WHERE id_categoria = p_id_categoria
      AND (deleted_at IS NOT NULL OR estado = 0);
    SET v_deleted_category = ROW_COUNT();

    IF v_deleted_category = 0 THEN
        ROLLBACK;
        SELECT 0 AS deleted_category, 0 AS deleted_products;
    ELSE
        COMMIT;
        SELECT 1 AS deleted_category, v_deleted_products AS deleted_products;
    END IF;
END $$

-- PROCEDIMIENTO: SP_CATEGORIA_EXISTE_NOMBRE
-- Verifica si ya existe una categoria activa con el mismo nombre
CREATE PROCEDURE sp_categoria_existe_nombre(
    IN p_nombre_categoria VARCHAR(100),
    IN p_exclude_id INT
)
BEGIN
    SELECT COUNT(*) AS total
    FROM categorias
    WHERE deleted_at IS NULL
      AND estado = 1
      AND LOWER(nombre_categoria) = LOWER(TRIM(p_nombre_categoria))
      AND (p_exclude_id IS NULL OR id_categoria <> p_exclude_id);
END $$

DELIMITER ;

-- EJEMPLOS DE USO CATEGORY
-- CALL sp_categoria_listar_activas(0, 10, 'La');
-- CALL sp_categoria_contar_activas('La');
-- CALL sp_categoria_listar_inactivas('Se');
-- CALL sp_categoria_obtener_activa_por_id(1);
-- CALL sp_categoria_obtener_por_id(1);
-- CALL sp_categoria_crear('Nueva categoria', 'Descripcion demo', 1);
-- CALL sp_categoria_actualizar(1, 'Categoria editada', 'Descripcion actualizada', 1);
-- CALL sp_categoria_eliminar_logico(1);
-- CALL sp_categoria_restaurar(1);
-- CALL sp_categoria_eliminar_definitivo(1);
-- CALL sp_categoria_existe_nombre('Laptops', NULL);

-- =========================================================
-- PROCEDIMIENTOS ALMACENADOS: MODULO TIPO_DOCUMENTO
-- =========================================================
-- Nota:
-- Los SP de listado permiten filtrar por ID o nombre.
-- La eliminacion definitiva se bloquea si existen usuarios
-- o proveedores asociados al tipo de documento.

DELIMITER $$

-- PROCEDIMIENTO: SP_TIPO_DOCUMENTO_LISTAR_ACTIVOS
-- Lista tipos de documento activos con paginacion y filtro opcional
CREATE PROCEDURE sp_tipo_documento_listar_activos(
    IN p_offset INT,
    IN p_limit INT,
    IN p_search VARCHAR(100)
)
BEGIN
    DECLARE v_search VARCHAR(100);
    DECLARE v_is_numeric TINYINT DEFAULT 0;

    SET v_search = TRIM(COALESCE(p_search, ''));
    SET v_is_numeric = v_search REGEXP '^[0-9]+$';

    SELECT
        id_tipo_documento,
        nombre_tipo_documento,
        descripcion,
        estado,
        created_at,
        updated_at,
        deleted_at
    FROM tipo_documentos
    WHERE deleted_at IS NULL
      AND estado = 1
      AND (
            v_search = ''
            OR (v_is_numeric = 1 AND id_tipo_documento = CAST(v_search AS UNSIGNED))
            OR nombre_tipo_documento LIKE CONCAT(v_search, '%')
      )
    ORDER BY created_at DESC, id_tipo_documento DESC
    LIMIT p_offset, p_limit;
END $$

-- PROCEDIMIENTO: SP_TIPO_DOCUMENTO_CONTAR_ACTIVOS
-- Cuenta tipos de documento activos segun el filtro aplicado
CREATE PROCEDURE sp_tipo_documento_contar_activos(
    IN p_search VARCHAR(100)
)
BEGIN
    DECLARE v_search VARCHAR(100);
    DECLARE v_is_numeric TINYINT DEFAULT 0;

    SET v_search = TRIM(COALESCE(p_search, ''));
    SET v_is_numeric = v_search REGEXP '^[0-9]+$';

    SELECT COUNT(*) AS total
    FROM tipo_documentos
    WHERE deleted_at IS NULL
      AND estado = 1
      AND (
            v_search = ''
            OR (v_is_numeric = 1 AND id_tipo_documento = CAST(v_search AS UNSIGNED))
            OR nombre_tipo_documento LIKE CONCAT(v_search, '%')
      );
END $$

-- PROCEDIMIENTO: SP_TIPO_DOCUMENTO_LISTAR_INACTIVOS
-- Lista tipos de documento inactivos o eliminados logicamente
CREATE PROCEDURE sp_tipo_documento_listar_inactivos(
    IN p_search VARCHAR(100)
)
BEGIN
    DECLARE v_search VARCHAR(100);
    DECLARE v_is_numeric TINYINT DEFAULT 0;

    SET v_search = TRIM(COALESCE(p_search, ''));
    SET v_is_numeric = v_search REGEXP '^[0-9]+$';

    SELECT
        id_tipo_documento,
        nombre_tipo_documento,
        descripcion,
        estado,
        created_at,
        updated_at,
        deleted_at
    FROM tipo_documentos
    WHERE (deleted_at IS NOT NULL OR estado = 0)
      AND (
            v_search = ''
            OR (v_is_numeric = 1 AND id_tipo_documento = CAST(v_search AS UNSIGNED))
            OR nombre_tipo_documento LIKE CONCAT(v_search, '%')
      )
    ORDER BY deleted_at DESC, id_tipo_documento DESC;
END $$

-- PROCEDIMIENTO: SP_TIPO_DOCUMENTO_OBTENER_ACTIVO_POR_ID
-- Obtiene un tipo de documento activo por su identificador
CREATE PROCEDURE sp_tipo_documento_obtener_activo_por_id(
    IN p_id_tipo_documento INT
)
BEGIN
    SELECT
        id_tipo_documento,
        nombre_tipo_documento,
        descripcion,
        estado,
        created_at,
        updated_at
    FROM tipo_documentos
    WHERE id_tipo_documento = p_id_tipo_documento
      AND deleted_at IS NULL
      AND estado = 1
    LIMIT 1;
END $$

-- PROCEDIMIENTO: SP_TIPO_DOCUMENTO_OBTENER_POR_ID
-- Obtiene un tipo de documento sin importar su estado
CREATE PROCEDURE sp_tipo_documento_obtener_por_id(
    IN p_id_tipo_documento INT
)
BEGIN
    SELECT
        id_tipo_documento,
        nombre_tipo_documento,
        descripcion,
        estado,
        created_at,
        updated_at,
        deleted_at
    FROM tipo_documentos
    WHERE id_tipo_documento = p_id_tipo_documento
    LIMIT 1;
END $$

-- PROCEDIMIENTO: SP_TIPO_DOCUMENTO_CREAR
-- Registra un nuevo tipo de documento y devuelve su ID
CREATE PROCEDURE sp_tipo_documento_crear(
    IN p_nombre_tipo_documento VARCHAR(50),
    IN p_descripcion VARCHAR(150),
    IN p_estado TINYINT
)
BEGIN
    INSERT INTO tipo_documentos (nombre_tipo_documento, descripcion, estado, deleted_at)
    VALUES (
        p_nombre_tipo_documento,
        NULLIF(TRIM(COALESCE(p_descripcion, '')), ''),
        p_estado,
        CASE WHEN p_estado = 0 THEN CURRENT_TIMESTAMP ELSE NULL END
    );

    SELECT LAST_INSERT_ID() AS id_tipo_documento;
END $$

-- PROCEDIMIENTO: SP_TIPO_DOCUMENTO_ACTUALIZAR
-- Actualiza un tipo de documento activo existente
CREATE PROCEDURE sp_tipo_documento_actualizar(
    IN p_id_tipo_documento INT,
    IN p_nombre_tipo_documento VARCHAR(50),
    IN p_descripcion VARCHAR(150),
    IN p_estado TINYINT
)
BEGIN
    UPDATE tipo_documentos
    SET nombre_tipo_documento = p_nombre_tipo_documento,
        descripcion = NULLIF(TRIM(COALESCE(p_descripcion, '')), ''),
        estado = p_estado,
        deleted_at = CASE
            WHEN p_estado = 0 THEN COALESCE(deleted_at, CURRENT_TIMESTAMP)
            ELSE NULL
        END
    WHERE id_tipo_documento = p_id_tipo_documento
      AND deleted_at IS NULL;

    SELECT ROW_COUNT() AS affected_rows;
END $$

-- PROCEDIMIENTO: SP_TIPO_DOCUMENTO_ELIMINAR_LOGICO
-- Marca un tipo de documento como inactivo y eliminado logicamente
CREATE PROCEDURE sp_tipo_documento_eliminar_logico(
    IN p_id_tipo_documento INT
)
BEGIN
    UPDATE tipo_documentos
    SET estado = 0,
        deleted_at = CURRENT_TIMESTAMP
    WHERE id_tipo_documento = p_id_tipo_documento
      AND deleted_at IS NULL;

    SELECT ROW_COUNT() AS affected_rows;
END $$

-- PROCEDIMIENTO: SP_TIPO_DOCUMENTO_RESTAURAR
-- Restaura un tipo de documento previamente desactivado
CREATE PROCEDURE sp_tipo_documento_restaurar(
    IN p_id_tipo_documento INT
)
BEGIN
    UPDATE tipo_documentos
    SET estado = 1,
        deleted_at = NULL
    WHERE id_tipo_documento = p_id_tipo_documento
      AND (deleted_at IS NOT NULL OR estado = 0);

    SELECT ROW_COUNT() AS affected_rows;
END $$

-- PROCEDIMIENTO: SP_TIPO_DOCUMENTO_ELIMINAR_DEFINITIVO
-- Elimina definitivamente un tipo de documento sin dependencias
CREATE PROCEDURE sp_tipo_documento_eliminar_definitivo(
    IN p_id_tipo_documento INT
)
BEGIN
    DECLARE v_related_providers INT DEFAULT 0;
    DECLARE v_related_users INT DEFAULT 0;
    DECLARE v_deleted_document_type INT DEFAULT 0;

    START TRANSACTION;

    SELECT COUNT(*) INTO v_related_providers
    FROM proveedores
    WHERE id_tipo_documento = p_id_tipo_documento;

    SELECT COUNT(*) INTO v_related_users
    FROM usuarios
    WHERE id_tipo_documento = p_id_tipo_documento;

    IF v_related_providers > 0 OR v_related_users > 0 THEN
        ROLLBACK;
        SELECT
            0 AS deleted_document_type,
            1 AS blocked_by_dependencies,
            v_related_providers AS related_providers,
            v_related_users AS related_users;
    ELSE
        DELETE FROM tipo_documentos
        WHERE id_tipo_documento = p_id_tipo_documento
          AND (deleted_at IS NOT NULL OR estado = 0);
        SET v_deleted_document_type = ROW_COUNT();

        IF v_deleted_document_type = 0 THEN
            ROLLBACK;
            SELECT
                0 AS deleted_document_type,
                0 AS blocked_by_dependencies,
                0 AS related_providers,
                0 AS related_users;
        ELSE
            COMMIT;
            SELECT
                1 AS deleted_document_type,
                0 AS blocked_by_dependencies,
                0 AS related_providers,
                0 AS related_users;
        END IF;
    END IF;
END $$

-- PROCEDIMIENTO: SP_TIPO_DOCUMENTO_EXISTE_NOMBRE
-- Verifica si ya existe un tipo de documento activo con el mismo nombre
CREATE PROCEDURE sp_tipo_documento_existe_nombre(
    IN p_nombre_tipo_documento VARCHAR(50),
    IN p_exclude_id INT
)
BEGIN
    SELECT COUNT(*) AS total
    FROM tipo_documentos
    WHERE deleted_at IS NULL
      AND estado = 1
      AND LOWER(nombre_tipo_documento) = LOWER(TRIM(p_nombre_tipo_documento))
      AND (p_exclude_id IS NULL OR id_tipo_documento <> p_exclude_id);
END $$

DELIMITER ;

-- EJEMPLOS DE USO TIPO_DOCUMENTO
-- CALL sp_tipo_documento_listar_activos(0, 10, 'D');
-- CALL sp_tipo_documento_contar_activos('D');
-- CALL sp_tipo_documento_listar_inactivos('2');
-- CALL sp_tipo_documento_obtener_activo_por_id(1);
-- CALL sp_tipo_documento_obtener_por_id(1);
-- CALL sp_tipo_documento_crear('Licencia', 'Documento de autorizacion', 1);
-- CALL sp_tipo_documento_actualizar(1, 'DNI Actualizado', 'Documento nacional actualizado', 1);
-- CALL sp_tipo_documento_eliminar_logico(1);
-- CALL sp_tipo_documento_restaurar(1);
-- CALL sp_tipo_documento_eliminar_definitivo(1);
-- CALL sp_tipo_documento_existe_nombre('DNI', NULL);
