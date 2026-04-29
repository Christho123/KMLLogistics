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

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_tipo_documento_listar_activos_para_select $$
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
