DELIMITER $$

-- ============================================
-- LISTAR ACTIVOS
-- ============================================
CREATE PROCEDURE sp_proveedor_listar_activas(
    IN p_offset INT,
    IN p_limit INT,
    IN p_search VARCHAR(150)
)
BEGIN
    SET p_search = IFNULL(p_search, '');

    -- SI ES NUMERO: SOLO BUSCA POR ID
    IF p_search REGEXP '^[0-9]+$' THEN

        SELECT *
        FROM proveedores
        WHERE estado = 1
        AND deleted_at IS NULL
        AND id_proveedor = p_search
        ORDER BY id_proveedor DESC
        LIMIT p_offset, p_limit;

    ELSE

        -- SI ES TEXTO: BUSCA NORMAL
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

-- ============================================
-- CONTAR ACTIVOS
-- ============================================
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

-- ============================================
-- LISTAR INACTIVOS
-- ============================================
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

-- ============================================
-- OBTENER ACTIVO POR ID
-- ============================================
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

-- ============================================
-- OBTENER POR ID
-- ============================================
CREATE PROCEDURE sp_proveedor_obtener_por_id(
    IN p_id INT
)
BEGIN
    SELECT *
    FROM proveedores
    WHERE id_proveedor = p_id
    LIMIT 1;
END $$

-- ============================================
-- CREAR
-- ============================================
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

-- ============================================
-- ACTUALIZAR
-- ============================================
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

-- ============================================
-- DELETE LOGICO
-- ============================================
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

-- ============================================
-- RESTORE
-- ============================================
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

-- ============================================
-- HARD DELETE
-- ============================================
CREATE PROCEDURE sp_proveedor_eliminar_definitivo(
    IN p_id INT
)
BEGIN
    DELETE FROM proveedores
    WHERE id_proveedor = p_id;

    SELECT 1 AS deleted_provider;
END $$

-- ============================================
-- VALIDAR DUPLICADO
-- ============================================
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
