-- =========================================================
-- PROCEDIMIENTOS ALMACENADOS: MODULO AUDIT
-- Tabla y SP de auditoria del sistema.
-- =========================================================

CREATE TABLE IF NOT EXISTS audits (
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

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_audit_registrar $$
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

DROP PROCEDURE IF EXISTS sp_audit_listar_activas $$
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

DROP PROCEDURE IF EXISTS sp_audit_contar_activas $$
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

DROP PROCEDURE IF EXISTS sp_audit_obtener_activa_por_id $$
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
