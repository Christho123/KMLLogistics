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
CALL sp_categoria_listar_activas(0, 10, 'La');
CALL sp_categoria_contar_activas('La');
CALL sp_categoria_listar_inactivas('Se');
CALL sp_categoria_obtener_activa_por_id(1);
CALL sp_categoria_obtener_por_id(1);
CALL sp_categoria_crear('Nueva categoria', 'Descripcion demo', 1);
CALL sp_categoria_actualizar(1, 'Categoria editada', 'Descripcion actualizada', 1);
CALL sp_categoria_eliminar_logico(1);
CALL sp_categoria_restaurar(1);
CALL sp_categoria_eliminar_definitivo(1);
CALL sp_categoria_existe_nombre('Laptops', NULL);