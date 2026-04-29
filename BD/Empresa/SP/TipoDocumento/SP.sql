-- =========================================================
-- PROCEDIMIENTOS ALMACENADOS: MODULO TIPO_DOCUMENTO
-- Selectores y validaciones para llaves foraneas de usuarios.
-- =========================================================

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

-- EJEMPLOS DE USO TIPO_DOCUMENTO PARA USUARIOS
-- CALL sp_tipo_documento_listar_activos_para_select();
-- CALL sp_tipo_documento_obtener_activo_para_select_por_id(1);
