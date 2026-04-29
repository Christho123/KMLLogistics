-- =========================================================
-- PROCEDIMIENTOS ALMACENADOS: MODULO USER
-- Consultas de usuarios usadas por login y registro.
-- =========================================================

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_usuario_obtener_por_correo $$
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
        estado
    FROM usuarios
    WHERE correo = TRIM(p_correo)
    LIMIT 1;
END $$

DROP PROCEDURE IF EXISTS sp_usuario_registrar $$
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
