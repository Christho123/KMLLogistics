-- =========================================================
-- PROCEDIMIENTOS ALMACENADOS: MODULO USER
-- Consultas de usuarios usadas por login y registro.
-- =========================================================

ALTER TABLE usuarios
    ADD COLUMN IF NOT EXISTS foto VARCHAR(255) NULL AFTER rol,
    ADD COLUMN IF NOT EXISTS email_verificado TINYINT(1) NOT NULL DEFAULT 0 AFTER foto,
    ADD COLUMN IF NOT EXISTS email_verified_at TIMESTAMP NULL DEFAULT NULL AFTER email_verificado;

CREATE TABLE IF NOT EXISTS usuario_codigos (
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
        foto,
        email_verificado,
        email_verified_at,
        estado
    FROM usuarios
    WHERE correo = TRIM(p_correo)
    LIMIT 1;
END $$

DROP PROCEDURE IF EXISTS sp_usuario_obtener_perfil $$
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

DROP PROCEDURE IF EXISTS sp_usuario_actualizar_perfil $$
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

DROP PROCEDURE IF EXISTS sp_usuario_actualizar_foto $$
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

DROP PROCEDURE IF EXISTS sp_usuario_codigo_crear $$
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

DROP PROCEDURE IF EXISTS sp_usuario_codigo_obtener_vigente $$
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

DROP PROCEDURE IF EXISTS sp_usuario_codigo_marcar_usado $$
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

DROP PROCEDURE IF EXISTS sp_usuario_cambiar_password $$
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

DROP PROCEDURE IF EXISTS sp_usuario_verificar_email $$
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
