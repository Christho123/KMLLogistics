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
    foto VARCHAR(255) NULL,
    email_verificado TINYINT(1) NOT NULL DEFAULT 0,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
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

DELIMITER $$

CREATE PROCEDURE sp_tipo_documento_listar_activos_para_select()
BEGIN
    SELECT
        id_tipo_documento,
        nombre_tipo_documento
    FROM tipo_documentos
    WHERE estado = 1
      AND deleted_at IS NULL
    ORDER BY nombre_tipo_documento ASC;
END $$

DROP PROCEDURE IF EXISTS sp_tipo_documento_obtener_activo_para_select_por_id $$
CREATE PROCEDURE sp_tipo_documento_obtener_activo_para_select_por_id(
    IN p_id_tipo_documento INT
)
BEGIN
    SELECT
        id_tipo_documento,
        nombre_tipo_documento
    FROM tipo_documentos
    WHERE id_tipo_documento = p_id_tipo_documento
      AND estado = 1
      AND deleted_at IS NULL
    LIMIT 1;
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
-- EJEMPLOS DE USO TIPO_DOCUMENTO PARA USUARIOS
-- CALL sp_tipo_documento_listar_activos_para_select();
-- CALL sp_tipo_documento_obtener_activo_para_select_por_id(1);


-- =========================================================
-- PROCEDIMIENTOS ALMACENADOS: MODULO USER
-- Consultas de usuarios usadas por login y registro.
-- =========================================================

DELIMITER $$

CREATE PROCEDURE sp_usuario_obtener_por_correo(
    IN p_correo VARCHAR(150)
)
BEGIN
    SELECT
        id_usuario,
        nombres,
        apellidos,
        correo,
        id_tipo_documento,
        numero_documento,
        password_hash,
        rol,
        foto,
        email_verificado,
        email_verified_at,
        estado
    FROM usuarios
    WHERE correo = TRIM(p_correo)
    LIMIT 1;
END $$

CREATE PROCEDURE sp_usuario_obtener_perfil(
    IN p_id_usuario INT
)
BEGIN
    SELECT
        u.id_usuario,
        u.nombres,
        u.apellidos,
        u.correo,
        u.id_tipo_documento,
        td.nombre_tipo_documento,
        u.numero_documento,
        u.rol,
        u.foto,
        u.email_verificado,
        u.email_verified_at,
        u.estado,
        u.created_at,
        u.updated_at
    FROM usuarios u
    LEFT JOIN tipo_documentos td ON td.id_tipo_documento = u.id_tipo_documento
    WHERE u.id_usuario = p_id_usuario
    LIMIT 1;
END $$

CREATE PROCEDURE sp_usuario_actualizar_perfil(
    IN p_id_usuario INT,
    IN p_nombres VARCHAR(100),
    IN p_apellidos VARCHAR(100),
    IN p_correo VARCHAR(150),
    IN p_id_tipo_documento INT,
    IN p_numero_documento VARCHAR(30),
    IN p_rol VARCHAR(50)
)
BEGIN
    UPDATE usuarios
    SET
        nombres = TRIM(p_nombres),
        apellidos = TRIM(p_apellidos),
        correo = TRIM(p_correo),
        id_tipo_documento = p_id_tipo_documento,
        numero_documento = TRIM(p_numero_documento),
        rol = TRIM(p_rol)
    WHERE id_usuario = p_id_usuario;

    SELECT ROW_COUNT() AS affected_rows;
END $$

CREATE PROCEDURE sp_usuario_actualizar_foto(
    IN p_id_usuario INT,
    IN p_foto VARCHAR(255)
)
BEGIN
    UPDATE usuarios
    SET foto = p_foto
    WHERE id_usuario = p_id_usuario;

    SELECT ROW_COUNT() AS affected_rows;
END $$

CREATE PROCEDURE sp_usuario_codigo_crear(
    IN p_id_usuario INT,
    IN p_tipo VARCHAR(30),
    IN p_codigo_hash VARCHAR(255),
    IN p_destino_email VARCHAR(150)
)
BEGIN
    UPDATE usuario_codigos
    SET used_at = NOW()
    WHERE id_usuario = p_id_usuario
      AND tipo = p_tipo
      AND used_at IS NULL;

    INSERT INTO usuario_codigos (
        id_usuario,
        tipo,
        codigo_hash,
        destino_email,
        expires_at
    )
    VALUES (
        p_id_usuario,
        p_tipo,
        p_codigo_hash,
        p_destino_email,
        DATE_ADD(NOW(), INTERVAL 5 MINUTE)
    );

    SELECT LAST_INSERT_ID() AS id_codigo;
END $$

CREATE PROCEDURE sp_usuario_codigo_obtener_vigente(
    IN p_id_usuario INT,
    IN p_tipo VARCHAR(30)
)
BEGIN
    SELECT
        id_codigo,
        id_usuario,
        tipo,
        codigo_hash,
        destino_email,
        expires_at
    FROM usuario_codigos
    WHERE id_usuario = p_id_usuario
      AND tipo = p_tipo
      AND used_at IS NULL
      AND expires_at >= NOW()
    ORDER BY id_codigo DESC
    LIMIT 1;
END $$

CREATE PROCEDURE sp_usuario_codigo_marcar_usado(
    IN p_id_codigo INT
)
BEGIN
    UPDATE usuario_codigos
    SET used_at = NOW()
    WHERE id_codigo = p_id_codigo
      AND used_at IS NULL;

    SELECT ROW_COUNT() AS affected_rows;
END $$

CREATE PROCEDURE sp_usuario_cambiar_password(
    IN p_id_usuario INT,
    IN p_password_hash VARCHAR(255)
)
BEGIN
    UPDATE usuarios
    SET password_hash = p_password_hash
    WHERE id_usuario = p_id_usuario;

    SELECT ROW_COUNT() AS affected_rows;
END $$

CREATE PROCEDURE sp_usuario_verificar_email(
    IN p_id_usuario INT
)
BEGIN
    UPDATE usuarios
    SET
        email_verificado = 1,
        email_verified_at = NOW()
    WHERE id_usuario = p_id_usuario;

    SELECT ROW_COUNT() AS affected_rows;
END $$

CREATE PROCEDURE sp_usuario_registrar(
    IN p_nombres VARCHAR(100),
    IN p_apellidos VARCHAR(100),
    IN p_correo VARCHAR(150),
    IN p_id_tipo_documento INT,
    IN p_numero_documento VARCHAR(30),
    IN p_password_hash VARCHAR(255),
    IN p_rol VARCHAR(50),
    IN p_estado TINYINT
)
BEGIN
    INSERT INTO usuarios (
        nombres,
        apellidos,
        correo,
        id_tipo_documento,
        numero_documento,
        password_hash,
        rol,
        estado
    )
    VALUES (
        TRIM(p_nombres),
        TRIM(p_apellidos),
        TRIM(p_correo),
        p_id_tipo_documento,
        TRIM(p_numero_documento),
        p_password_hash,
        p_rol,
        p_estado
    );

    SELECT ROW_COUNT() AS affected_rows, LAST_INSERT_ID() AS id_usuario;
END $$

DELIMITER ;

-- EJEMPLOS DE USO USER
-- CALL sp_usuario_obtener_por_correo('admin@kmllogistics.com');
-- CALL sp_usuario_registrar('Juan', 'Perez', 'juan@example.com', 1, '12345678', '$2y$hash', 'usuario', 1);

-- ========================================================================
-- PROCEDIMIENTOS ALMACENADOS: MODULO CATEGORY
-- Nota:
-- Los SP de listado filtran solo por nombre_categoria usando prefijo.
-- ========================================================================

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
-- PROCEDIMIENTOS ALMACENADOS: MODULO PRODUCT
-- Tabla: productos
-- Precio esperado: costo / (1 - ganancia)
-- Ganancia se guarda como decimal: 24% = 0.2400
-- =========================================================

DELIMITER $$

CREATE PROCEDURE sp_producto_listar_activas(
    IN p_offset INT,
    IN p_limit INT,
    IN p_search VARCHAR(150)
)
BEGIN
    DECLARE v_search VARCHAR(150);
    SET v_search = TRIM(COALESCE(p_search, ''));

    SELECT
        p.id_producto,
        p.producto,
        p.costo,
        p.ganancia,
        p.precio,
        p.stock,
        p.foto,
        p.id_categoria,
        c.nombre_categoria,
        p.id_marca,
        m.nombre_marca,
        p.estado,
        p.created_at,
        p.updated_at,
        p.deleted_at
    FROM productos p
    INNER JOIN categorias c ON c.id_categoria = p.id_categoria
    INNER JOIN marcas m ON m.id_marca = p.id_marca
    WHERE p.deleted_at IS NULL
      AND p.estado = 1
      AND (
            v_search = ''
            OR p.producto LIKE CONCAT(v_search, '%')
      )
    ORDER BY p.created_at DESC, p.id_producto DESC
    LIMIT p_offset, p_limit;
END $$

CREATE PROCEDURE sp_producto_contar_activas(
    IN p_search VARCHAR(150)
)
BEGIN
    DECLARE v_search VARCHAR(150);
    SET v_search = TRIM(COALESCE(p_search, ''));

    SELECT COUNT(*) AS total
    FROM productos p
    INNER JOIN categorias c ON c.id_categoria = p.id_categoria
    INNER JOIN marcas m ON m.id_marca = p.id_marca
    WHERE p.deleted_at IS NULL
      AND p.estado = 1
      AND (
            v_search = ''
            OR p.producto LIKE CONCAT(v_search, '%')
      );
END $$

CREATE PROCEDURE sp_producto_listar_inactivas(
    IN p_search VARCHAR(150)
)
BEGIN
    DECLARE v_search VARCHAR(150);
    SET v_search = TRIM(COALESCE(p_search, ''));

    SELECT
        p.id_producto,
        p.producto,
        p.costo,
        p.ganancia,
        p.precio,
        p.stock,
        p.foto,
        p.id_categoria,
        c.nombre_categoria,
        p.id_marca,
        m.nombre_marca,
        p.estado,
        p.created_at,
        p.updated_at,
        p.deleted_at
    FROM productos p
    INNER JOIN categorias c ON c.id_categoria = p.id_categoria
    INNER JOIN marcas m ON m.id_marca = p.id_marca
    WHERE (p.deleted_at IS NOT NULL OR p.estado = 0)
      AND (
            v_search = ''
            OR p.producto LIKE CONCAT(v_search, '%')
      )
    ORDER BY p.deleted_at DESC, p.id_producto DESC;
END $$

CREATE PROCEDURE sp_producto_obtener_activa_por_id(
    IN p_id_producto INT
)
BEGIN
    SELECT
        p.id_producto,
        p.producto,
        p.costo,
        p.ganancia,
        p.precio,
        p.stock,
        p.foto,
        p.id_categoria,
        c.nombre_categoria,
        p.id_marca,
        m.nombre_marca,
        p.estado,
        p.created_at,
        p.updated_at,
        p.deleted_at
    FROM productos p
    INNER JOIN categorias c ON c.id_categoria = p.id_categoria
    INNER JOIN marcas m ON m.id_marca = p.id_marca
    WHERE p.id_producto = p_id_producto
      AND p.deleted_at IS NULL
      AND p.estado = 1
    LIMIT 1;
END $$

CREATE PROCEDURE sp_producto_obtener_por_id(
    IN p_id_producto INT
)
BEGIN
    SELECT
        p.id_producto,
        p.producto,
        p.costo,
        p.ganancia,
        p.precio,
        p.stock,
        p.foto,
        p.id_categoria,
        c.nombre_categoria,
        p.id_marca,
        m.nombre_marca,
        p.estado,
        p.created_at,
        p.updated_at,
        p.deleted_at
    FROM productos p
    INNER JOIN categorias c ON c.id_categoria = p.id_categoria
    INNER JOIN marcas m ON m.id_marca = p.id_marca
    WHERE p.id_producto = p_id_producto
    LIMIT 1;
END $$

CREATE PROCEDURE sp_producto_crear(
    IN p_producto VARCHAR(150),
    IN p_costo DECIMAL(10,2),
    IN p_ganancia DECIMAL(5,4),
    IN p_precio DECIMAL(10,2),
    IN p_stock INT,
    IN p_foto VARCHAR(255),
    IN p_id_categoria INT,
    IN p_id_marca INT,
    IN p_estado TINYINT
)
BEGIN
    INSERT INTO productos (
        producto,
        costo,
        ganancia,
        precio,
        stock,
        foto,
        id_categoria,
        id_marca,
        estado,
        deleted_at
    )
    VALUES (
        p_producto,
        p_costo,
        p_ganancia,
        p_precio,
        p_stock,
        p_foto,
        p_id_categoria,
        p_id_marca,
        p_estado,
        CASE WHEN p_estado = 0 THEN CURRENT_TIMESTAMP ELSE NULL END
    );

    SELECT LAST_INSERT_ID() AS id_producto;
END $$

CREATE PROCEDURE sp_producto_actualizar(
    IN p_id_producto INT,
    IN p_producto VARCHAR(150),
    IN p_costo DECIMAL(10,2),
    IN p_ganancia DECIMAL(5,4),
    IN p_precio DECIMAL(10,2),
    IN p_stock INT,
    IN p_foto VARCHAR(255),
    IN p_id_categoria INT,
    IN p_id_marca INT,
    IN p_estado TINYINT
)
BEGIN
    UPDATE productos
    SET producto = p_producto,
        costo = p_costo,
        ganancia = p_ganancia,
        precio = p_precio,
        stock = p_stock,
        foto = p_foto,
        id_categoria = p_id_categoria,
        id_marca = p_id_marca,
        estado = p_estado,
        deleted_at = CASE
            WHEN p_estado = 0 THEN COALESCE(deleted_at, CURRENT_TIMESTAMP)
            ELSE NULL
        END
    WHERE id_producto = p_id_producto
      AND deleted_at IS NULL;

    SELECT ROW_COUNT() AS affected_rows;
END $$

CREATE PROCEDURE sp_producto_eliminar_logico(
    IN p_id_producto INT
)
BEGIN
    UPDATE productos
    SET estado = 0,
        deleted_at = CURRENT_TIMESTAMP
    WHERE id_producto = p_id_producto
      AND deleted_at IS NULL;

    SELECT ROW_COUNT() AS affected_rows;
END $$

CREATE PROCEDURE sp_producto_restaurar(
    IN p_id_producto INT
)
BEGIN
    UPDATE productos
    SET estado = 1,
        deleted_at = NULL
    WHERE id_producto = p_id_producto
      AND (deleted_at IS NOT NULL OR estado = 0);

    SELECT ROW_COUNT() AS affected_rows;
END $$

CREATE PROCEDURE sp_producto_eliminar_definitivo(
    IN p_id_producto INT
)
BEGIN
    DELETE FROM productos
    WHERE id_producto = p_id_producto
      AND (deleted_at IS NOT NULL OR estado = 0);

    SELECT ROW_COUNT() AS deleted_product;
END $$

CREATE PROCEDURE sp_producto_existe_nombre(
    IN p_producto VARCHAR(150),
    IN p_exclude_id INT
)
BEGIN
    SELECT COUNT(*) AS total
    FROM productos
    WHERE deleted_at IS NULL
      AND estado = 1
      AND LOWER(producto) = LOWER(TRIM(p_producto))
      AND (p_exclude_id IS NULL OR id_producto <> p_exclude_id);
END $$

CREATE PROCEDURE sp_producto_listar_categorias_activas()
BEGIN
    SELECT
        id_categoria,
        nombre_categoria
    FROM categorias
    WHERE estado = 1
      AND deleted_at IS NULL
    ORDER BY nombre_categoria ASC;
END $$

CREATE PROCEDURE sp_producto_listar_marcas_activas()
BEGIN
    SELECT
        id_marca,
        nombre_marca
    FROM marcas
    WHERE estado = 1
      AND deleted_at IS NULL
    ORDER BY nombre_marca ASC;
END $$

DELIMITER ;

-- EJEMPLOS DE USO PRODUCT
-- CALL sp_producto_listar_activas(0, 10, 'Laptop');
-- CALL sp_producto_contar_activas('Laptop');
-- CALL sp_producto_listar_inactivas('');
-- CALL sp_producto_obtener_activa_por_id(1);
-- CALL sp_producto_obtener_por_id(1);
-- CALL sp_producto_crear('Producto demo', 100.00, 0.2400, 131.58, 10, NULL, 1, 1, 1);
-- CALL sp_producto_actualizar(1, 'Producto editado', 120.00, 0.2400, 157.89, 8, NULL, 1, 1, 1);
-- CALL sp_producto_eliminar_logico(1);
-- CALL sp_producto_restaurar(1);
-- CALL sp_producto_eliminar_definitivo(1);
-- CALL sp_producto_existe_nombre('Producto demo', NULL);
-- CALL sp_producto_listar_categorias_activas();
-- CALL sp_producto_listar_marcas_activas();

-- =========================================================
-- PROCEDIMIENTOS ALMACENADOS: MODULO PROVIDER
-- Tabla: Proveedor
-- =========================================================

DELIMITER $$

-- LISTAR ACTIVOS
CREATE PROCEDURE sp_proveedor_listar_activas(
    IN p_offset INT,
    IN p_limit INT,
    IN p_search VARCHAR(150)
)
BEGIN
    SET p_search = IFNULL(p_search, '');

    -- SI ES NÚMERO → SOLO BUSCA POR ID
    IF p_search REGEXP '^[0-9]+$' THEN

        SELECT *
        FROM proveedores
        WHERE estado = 1
        AND deleted_at IS NULL
        AND id_proveedor = p_search
        ORDER BY id_proveedor DESC
        LIMIT p_offset, p_limit;

    ELSE

        -- SI ES TEXTO → BUSCA NORMAL
        SELECT *
        FROM proveedores
        WHERE estado = 1
        AND deleted_at IS NULL
        AND (
            p_search = '' OR
            razon_social LIKE CONCAT(p_search, '%') OR
            nombre_comercial LIKE CONCAT(p_search, '%') OR
            numero_documento LIKE CONCAT(p_search, '%')
        )
        ORDER BY id_proveedor DESC
        LIMIT p_offset, p_limit;

    END IF;

END $$

-- CONTAR ACTIVOS
CREATE PROCEDURE sp_proveedor_contar_activas(
    IN p_search VARCHAR(150)
)
BEGIN
    SET p_search = IFNULL(p_search, '');

    SELECT COUNT(*) AS total
    FROM proveedores
    WHERE estado = 1
    AND deleted_at IS NULL
    AND (
        p_search = '' OR
        razon_social LIKE CONCAT(p_search, '%') OR
        numero_documento LIKE CONCAT(p_search, '%')
    );
END $$

-- LISTAR INACTIVOS
CREATE PROCEDURE sp_proveedor_listar_inactivas(
    IN p_search VARCHAR(150)
)
BEGIN
    SET p_search = IFNULL(p_search, '');

    SELECT *
    FROM proveedores
    WHERE (estado = 0 OR deleted_at IS NOT NULL)
    AND (
        p_search = '' OR
        razon_social LIKE CONCAT(p_search, '%')
    )
    ORDER BY id_proveedor DESC;
END $$

-- OBTENER ACTIVO POR ID
CREATE PROCEDURE sp_proveedor_obtener_activa_por_id(
    IN p_id INT
)
BEGIN
    SELECT *
    FROM proveedores
    WHERE id_proveedor = p_id
    AND estado = 1
    AND deleted_at IS NULL
    LIMIT 1;
END $$

-- OBTENER POR ID
CREATE PROCEDURE sp_proveedor_obtener_por_id(
    IN p_id INT
)
BEGIN
    SELECT *
    FROM proveedores
    WHERE id_proveedor = p_id
    LIMIT 1;
END $$

-- CREAR
CREATE PROCEDURE sp_proveedor_crear(
    IN p_razon_social VARCHAR(150),
    IN p_nombre_comercial VARCHAR(150),
    IN p_id_tipo_documento INT,
    IN p_numero_documento VARCHAR(30),
    IN p_telefono VARCHAR(20),
    IN p_correo VARCHAR(150),
    IN p_direccion VARCHAR(255),
    IN p_contacto VARCHAR(150),
    IN p_estado TINYINT
)
BEGIN
    INSERT INTO proveedores(
        razon_social,
        nombre_comercial,
        id_tipo_documento,
        numero_documento,
        telefono,
        correo,
        direccion,
        contacto,
        estado
    )
    VALUES(
        p_razon_social,
        p_nombre_comercial,
        p_id_tipo_documento,
        p_numero_documento,
        p_telefono,
        p_correo,
        p_direccion,
        p_contacto,
        p_estado
    );

    SELECT LAST_INSERT_ID() AS id_proveedor;
END $$

-- ACTUALIZAR
CREATE PROCEDURE sp_proveedor_actualizar(
    IN p_id INT,
    IN p_razon_social VARCHAR(150),
    IN p_nombre_comercial VARCHAR(150),
    IN p_id_tipo_documento INT,
    IN p_numero_documento VARCHAR(30),
    IN p_telefono VARCHAR(20),
    IN p_correo VARCHAR(150),
    IN p_direccion VARCHAR(255),
    IN p_contacto VARCHAR(150),
    IN p_estado TINYINT
)
BEGIN
    UPDATE proveedores SET
        razon_social = p_razon_social,
        nombre_comercial = p_nombre_comercial,
        id_tipo_documento = p_id_tipo_documento,
        numero_documento = p_numero_documento,
        telefono = p_telefono,
        correo = p_correo,
        direccion = p_direccion,
        contacto = p_contacto,
        estado = p_estado
    WHERE id_proveedor = p_id;

    SELECT ROW_COUNT() AS affected_rows;
END $$

-- DELETE LOGICO
CREATE PROCEDURE sp_proveedor_eliminar_logico(
    IN p_id INT
)
BEGIN
    UPDATE proveedores
    SET estado = 0,
        deleted_at = CURRENT_TIMESTAMP
    WHERE id_proveedor = p_id;

    SELECT ROW_COUNT() AS affected_rows;
END $$

-- RESTORE
CREATE PROCEDURE sp_proveedor_restaurar(
    IN p_id INT
)
BEGIN
    UPDATE proveedores
    SET estado = 1,
        deleted_at = NULL
    WHERE id_proveedor = p_id;

    SELECT ROW_COUNT() AS affected_rows;
END $$

-- HARD DELETE
CREATE PROCEDURE sp_proveedor_eliminar_definitivo(
    IN p_id INT
)
BEGIN
    DELETE FROM proveedores
    WHERE id_proveedor = p_id;

    SELECT 1 AS deleted_provider;
END $$

-- VALIDAR DUPLICADO
CREATE PROCEDURE sp_proveedor_existe_documento(
    IN p_tipo INT,
    IN p_numero VARCHAR(30),
    IN p_exclude_id INT
)
BEGIN
    SELECT COUNT(*) AS total
    FROM proveedores
    WHERE id_tipo_documento = p_tipo
    AND numero_documento = p_numero
    AND (p_exclude_id IS NULL OR id_proveedor <> p_exclude_id);
END $$

DELIMITER ;

-- ==================================================================
-- PROCEDIMIENTO: SP_MARCA_LISTAR_ACTIVAS
-- Lista marcas activas con paginación y filtro opcional por nombre
-- ==================================================================

-- MARCAS 

DELIMITER $$
CREATE PROCEDURE sp_marca_listar_activas(
    IN p_offset INT,
    IN p_limit INT,
    IN p_search VARCHAR(100)
)
BEGIN
    DECLARE v_search VARCHAR(100);
    SET v_search = TRIM(COALESCE(p_search, ''));

    SELECT 
        m.id_marca,
        m.nombre_marca,
        m.id_proveedor,
        p.razon_social AS nombre_proveedor,
        m.estado,
        m.created_at,
        m.updated_at,
        m.deleted_at
    FROM marcas m
    INNER JOIN proveedores p ON m.id_proveedor = p.id_proveedor
    WHERE m.deleted_at IS NULL 
      AND m.estado = 1
      AND (
            v_search = '' 
            OR m.nombre_marca LIKE CONCAT(v_search, '%')
      )
    ORDER BY m.created_at DESC, m.id_marca DESC
    LIMIT p_offset, p_limit;
END $$

-- PROCEDIMIENTO: SP_MARCA_CONTAR_ACTIVAS
-- Cuenta marcas activas según el filtro aplicado
CREATE PROCEDURE sp_marca_contar_activas(
    IN p_search VARCHAR(100)
)
BEGIN
    DECLARE v_search VARCHAR(100);
    SET v_search = TRIM(COALESCE(p_search, ''));

    SELECT COUNT(*) AS total
    FROM marcas
    WHERE deleted_at IS NULL 
      AND estado = 1
      AND (
            v_search = '' 
            OR nombre_marca LIKE CONCAT(v_search, '%')
      );
END $$

-- PROCEDIMIENTO: SP_MARCA_LISTAR_INACTIVAS
-- Lista marcas inactivas o eliminadas lógicamente
CREATE PROCEDURE sp_marca_listar_inactivas(
    IN p_search VARCHAR(100)
)
BEGIN
    DECLARE v_search VARCHAR(100);
    SET v_search = TRIM(COALESCE(p_search, ''));

    SELECT 
        m.id_marca,
        m.nombre_marca,
        m.id_proveedor,
        p.razon_social AS nombre_proveedor,
        m.estado,
        m.created_at,
        m.updated_at,
        m.deleted_at
    FROM marcas m
    INNER JOIN proveedores p ON m.id_proveedor = p.id_proveedor
    WHERE (m.deleted_at IS NOT NULL OR m.estado = 0)
      AND (
            v_search = '' 
            OR m.nombre_marca LIKE CONCAT(v_search, '%')
      )
    ORDER BY m.deleted_at DESC, m.id_marca DESC;
END $$

-- PROCEDIMIENTO: SP_MARCA_OBTENER_ACTIVA_POR_ID
CREATE PROCEDURE sp_marca_obtener_activa_por_id(
    IN p_id_marca INT
)
BEGIN
    SELECT 
        m.id_marca,
        m.nombre_marca,
        m.id_proveedor,
        p.razon_social AS nombre_proveedor,
        m.estado,
        m.created_at,
        m.updated_at
    FROM marcas m
    INNER JOIN proveedores p ON m.id_proveedor = p.id_proveedor
    WHERE m.id_marca = p_id_marca 
      AND m.deleted_at IS NULL 
      AND m.estado = 1
    LIMIT 1;
END $$

-- PROCEDIMIENTO: SP_MARCA_OBTENER_POR_ID
CREATE PROCEDURE sp_marca_obtener_por_id(
    IN p_id_marca INT
)
BEGIN
    SELECT 
        m.id_marca,
        m.nombre_marca,
        m.id_proveedor,
        p.razon_social AS nombre_proveedor,
        m.estado,
        m.created_at,
        m.updated_at,
        m.deleted_at
    FROM marcas m
    INNER JOIN proveedores p ON m.id_proveedor = p.id_proveedor
    WHERE m.id_marca = p_id_marca
    LIMIT 1;
END $$

-- PROCEDIMIENTO: SP_MARCA_CREAR
CREATE PROCEDURE sp_marca_crear(
    IN p_nombre_marca VARCHAR(100),
    IN p_id_proveedor INT,
    IN p_estado TINYINT
)
BEGIN
    INSERT INTO marcas (nombre_marca, id_proveedor, estado, deleted_at)
    VALUES (
        p_nombre_marca,
        p_id_proveedor,
        p_estado,
        CASE WHEN p_estado = 0 THEN CURRENT_TIMESTAMP ELSE NULL END
    );
    
    SELECT LAST_INSERT_ID() AS id_marca;
END $$

-- PROCEDIMIENTO: SP_MARCA_ACTUALIZAR
CREATE PROCEDURE sp_marca_actualizar(
    IN p_id_marca INT,
    IN p_nombre_marca VARCHAR(100),
    IN p_id_proveedor INT,
    IN p_estado TINYINT
)
BEGIN
    UPDATE marcas
    SET nombre_marca = p_nombre_marca,
        id_proveedor = p_id_proveedor,
        estado = p_estado,
        deleted_at = CASE 
            WHEN p_estado = 0 THEN COALESCE(deleted_at, CURRENT_TIMESTAMP)
            ELSE NULL 
        END
    WHERE id_marca = p_id_marca 
      AND deleted_at IS NULL;

    SELECT ROW_COUNT() AS affected_rows;
END $$

-- PROCEDIMIENTO: SP_MARCA_ELIMINAR_LOGICO
CREATE PROCEDURE sp_marca_eliminar_logico(
    IN p_id_marca INT
)
BEGIN
    UPDATE marcas
    SET estado = 0,
        deleted_at = CURRENT_TIMESTAMP
    WHERE id_marca = p_id_marca 
      AND deleted_at IS NULL;

    SELECT ROW_COUNT() AS affected_rows;
END $$

-- PROCEDIMIENTO: SP_MARCA_RESTAURAR
CREATE PROCEDURE sp_marca_restaurar(
    IN p_id_marca INT
)
BEGIN
    UPDATE marcas
    SET estado = 1,
        deleted_at = NULL
    WHERE id_marca = p_id_marca 
      AND (deleted_at IS NOT NULL OR estado = 0);

    SELECT ROW_COUNT() AS affected_rows;
END $$

-- PROCEDIMIENTO: SP_MARCA_ELIMINAR_DEFINITIVO
-- Elimina la marca si está marcada como inactiva/eliminada. 
-- Nota: Si hay productos asociados, la FK de la tabla productos impedirá el borrado.
CREATE PROCEDURE sp_marca_eliminar_definitivo(
    IN p_id_marca INT
)
BEGIN
    DECLARE v_deleted_products INT DEFAULT 0;
    DECLARE v_deleted_brand INT DEFAULT 0;

    START TRANSACTION;

    -- Opcional: Borrar productos asociados si se desea cascada manual
    DELETE FROM productos WHERE id_marca = p_id_marca;
    SET v_deleted_products = ROW_COUNT();

    DELETE FROM marcas 
    WHERE id_marca = p_id_marca 
      AND (deleted_at IS NOT NULL OR estado = 0);
    SET v_deleted_brand = ROW_COUNT();

    IF v_deleted_brand = 0 THEN
        ROLLBACK;
        SELECT 0 AS deleted_brand, 0 AS deleted_products;
    ELSE
        COMMIT;
        SELECT 1 AS deleted_brand, v_deleted_products AS deleted_products;
    END IF;
END $$

-- PROCEDIMIENTO: SP_MARCA_EXISTE_NOMBRE
CREATE PROCEDURE sp_marca_existe_nombre(
    IN p_nombre_marca VARCHAR(100),
    IN p_exclude_id INT
)
BEGIN
    SELECT COUNT(*) AS total
    FROM marcas
    WHERE deleted_at IS NULL 
      AND estado = 1
      AND LOWER(nombre_marca) = LOWER(TRIM(p_nombre_marca))
      AND (p_exclude_id IS NULL OR id_marca <> p_exclude_id);
END $$

-- PROCEDIMIENTO: SP_MARCA_LISTAR_PROVEEDORES_ACTIVOS
-- Carga proveedores activos para el selector de marca.
CREATE PROCEDURE sp_marca_listar_proveedores_activos()
BEGIN
    SELECT
        id_proveedor,
        razon_social
    FROM proveedores
    WHERE estado = 1
      AND deleted_at IS NULL
    ORDER BY razon_social ASC;
END $$

DELIMITER ;


-- EJEMPLOS DE USO MARCAS
-- CALL sp_marca_listar_activas(0, 10, 'La');
-- CALL sp_marca_contar_activas('La');
-- CALL sp_marca_listar_inactivas('Inac');
-- CALL sp_marca_obtener_activa_por_id(1);
-- CALL sp_marca_obtener_por_id(1);
-- CALL sp_marca_crear('Nueva marca', 1, 1);
-- CALL sp_marca_actualizar(1, 'Marca editada', 1, 1);
-- CALL sp_marca_eliminar_logico(1);
-- CALL sp_marca_restaurar(1);
-- CALL sp_marca_eliminar_definitivo(1);
-- CALL sp_marca_existe_nombre ('Dell', NULL);
-- CALL sp_marca_listar_proveedores_activos();
-- CALL sp_marca_eliminar_definitivo(1);
-- CALL sp_marca_existe_nombre ('Dell', NULL);

-- =========================================================
-- PROCEDIMIENTOS ALMACENADOS: MODULO AUDIT
-- Tabla y SP de auditoria del sistema.
-- =========================================================

DELIMITER $$

CREATE PROCEDURE sp_audit_registrar(
    IN p_id_usuario INT,
    IN p_modulo VARCHAR(80),
    IN p_accion VARCHAR(100),
    IN p_descripcion VARCHAR(255),
    IN p_datos JSON
)
BEGIN
    INSERT INTO audits (
        id_usuario,
        modulo,
        accion,
        descripcion,
        datos,
        estado
    )
    VALUES (
        NULLIF(p_id_usuario, 0),
        TRIM(p_modulo),
        TRIM(p_accion),
        TRIM(p_descripcion),
        p_datos,
        1
    );

    SELECT LAST_INSERT_ID() AS id_audit;
END $$

CREATE PROCEDURE sp_audit_listar_activas(
    IN p_offset INT,
    IN p_limit INT,
    IN p_search VARCHAR(120)
)
BEGIN
    DECLARE v_search VARCHAR(120);

    SET v_search = TRIM(COALESCE(p_search, ''));

    SELECT
        a.id_audit,
        a.id_usuario,
        COALESCE(CONCAT(u.nombres, ' ', u.apellidos), 'Sistema') AS usuario,
        a.modulo,
        a.accion,
        a.descripcion,
        a.estado,
        a.created_at
    FROM audits a
    LEFT JOIN usuarios u ON u.id_usuario = a.id_usuario
    WHERE a.deleted_at IS NULL
      AND a.estado = 1
      AND (
            v_search = ''
            OR a.modulo LIKE CONCAT(v_search, '%')
            OR COALESCE(CONCAT(u.nombres, ' ', u.apellidos), 'Sistema') LIKE CONCAT(v_search, '%')
      )
    ORDER BY a.created_at DESC, a.id_audit DESC
    LIMIT p_offset, p_limit;
END $$

CREATE PROCEDURE sp_audit_contar_activas(
    IN p_search VARCHAR(120)
)
BEGIN
    DECLARE v_search VARCHAR(120);

    SET v_search = TRIM(COALESCE(p_search, ''));

    SELECT COUNT(*) AS total
    FROM audits a
    LEFT JOIN usuarios u ON u.id_usuario = a.id_usuario
    WHERE a.deleted_at IS NULL
      AND a.estado = 1
      AND (
            v_search = ''
            OR a.modulo LIKE CONCAT(v_search, '%')
            OR COALESCE(CONCAT(u.nombres, ' ', u.apellidos), 'Sistema') LIKE CONCAT(v_search, '%')
      );
END $$

CREATE PROCEDURE sp_audit_obtener_activa_por_id(
    IN p_id_audit INT
)
BEGIN
    SELECT
        a.id_audit,
        a.id_usuario,
        COALESCE(CONCAT(u.nombres, ' ', u.apellidos), 'Sistema') AS usuario,
        a.modulo,
        a.accion,
        a.descripcion,
        a.datos,
        a.estado,
        a.created_at,
        a.updated_at
    FROM audits a
    LEFT JOIN usuarios u ON u.id_usuario = a.id_usuario
    WHERE a.id_audit = p_id_audit
      AND a.deleted_at IS NULL
      AND a.estado = 1
    LIMIT 1;
END $$

DELIMITER ;

-- EJEMPLOS DE USO AUDIT
-- CALL sp_audit_listar_activas(0, 10, '');
-- CALL sp_audit_contar_activas('');
-- CALL sp_audit_obtener_activa_por_id(1);

