-- PROCEDIMIENTO: SP_MARCA_LISTAR_ACTIVAS
-- Lista marcas activas con paginación y filtro opcional por nombre

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
DROP PROCEDURE IF EXISTS sp_marca_obtener_activa_por_id;

DELIMITER $$

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

DELIMITER ;

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
-- Nota: Si hay productos asociados, la FK de la tabla productos impedirá el borrado a menos que se maneje.
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
-- CALL sp_marca_eliminar_definitivo(1);
-- CALL sp_marca_existe_nombre ('Dell', NULL);