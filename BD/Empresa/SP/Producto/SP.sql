-- =========================================================
-- PROCEDIMIENTOS ALMACENADOS: MODULO PRODUCT
-- Tabla: productos
-- Precio esperado: costo / (1 - ganancia)
-- Ganancia se guarda como decimal: 24% = 0.2400
-- =========================================================

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_producto_listar_activas $$
CREATE PROCEDURE sp_producto_listar_activas(
    IN p_offset INT,
    IN p_limit INT,
    IN p_search VARCHAR(150),
    IN p_id_categoria INT
)
BEGIN
    DECLARE v_search VARCHAR(150);
    DECLARE v_id_categoria INT;
    SET v_search = TRIM(COALESCE(p_search, ''));
    SET v_id_categoria = COALESCE(p_id_categoria, 0);

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
      AND (
            v_id_categoria = 0
            OR p.id_categoria = v_id_categoria
      )
    ORDER BY p.created_at DESC, p.id_producto DESC
    LIMIT p_offset, p_limit;
END $$

DROP PROCEDURE IF EXISTS sp_producto_contar_activas $$
CREATE PROCEDURE sp_producto_contar_activas(
    IN p_search VARCHAR(150),
    IN p_id_categoria INT
)
BEGIN
    DECLARE v_search VARCHAR(150);
    DECLARE v_id_categoria INT;
    SET v_search = TRIM(COALESCE(p_search, ''));
    SET v_id_categoria = COALESCE(p_id_categoria, 0);

    SELECT COUNT(*) AS total
    FROM productos p
    INNER JOIN categorias c ON c.id_categoria = p.id_categoria
    INNER JOIN marcas m ON m.id_marca = p.id_marca
    WHERE p.deleted_at IS NULL
      AND p.estado = 1
      AND (
            v_search = ''
            OR p.producto LIKE CONCAT(v_search, '%')
      )
      AND (
            v_id_categoria = 0
            OR p.id_categoria = v_id_categoria
      );
END $$

DROP PROCEDURE IF EXISTS sp_producto_listar_inactivas $$
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

DROP PROCEDURE IF EXISTS sp_producto_obtener_activa_por_id $$
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

DROP PROCEDURE IF EXISTS sp_producto_obtener_por_id $$
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

DROP PROCEDURE IF EXISTS sp_producto_crear $$
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

DROP PROCEDURE IF EXISTS sp_producto_actualizar $$
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

DROP PROCEDURE IF EXISTS sp_producto_eliminar_logico $$
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

DROP PROCEDURE IF EXISTS sp_producto_restaurar $$
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

DROP PROCEDURE IF EXISTS sp_producto_eliminar_definitivo $$
CREATE PROCEDURE sp_producto_eliminar_definitivo(
    IN p_id_producto INT
)
BEGIN
    DELETE FROM productos
    WHERE id_producto = p_id_producto
      AND (deleted_at IS NOT NULL OR estado = 0);

    SELECT ROW_COUNT() AS deleted_product;
END $$

DROP PROCEDURE IF EXISTS sp_producto_existe_nombre $$
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

DROP PROCEDURE IF EXISTS sp_producto_listar_categorias_activas $$
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

DROP PROCEDURE IF EXISTS sp_producto_listar_marcas_activas $$
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
-- CALL sp_producto_listar_activas(0, 10, 'Laptop', 1);
-- CALL sp_producto_contar_activas('Laptop', 1);
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
