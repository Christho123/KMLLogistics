DELIMITER $$
-- PROCEDIMIENTO: SP_BUSCAR_PRODUCTO_POR_ID
-- Busca un producto activo por su ID autoincremental
CREATE PROCEDURE sp_buscar_producto_por_id(IN p_id_producto INT)
BEGIN
    SELECT
        p.id_producto,
        p.producto,
        p.costo,
        p.ganancia,
        ROUND(p.costo / NULLIF(1 - p.ganancia, 0), 2) AS precio,
        p.stock,
        (ROUND(p.costo / NULLIF(1 - p.ganancia, 0), 2) * p.stock) AS total,
        c.nombre_categoria AS categoria,
        m.nombre_marca AS marca
    FROM productos p
    INNER JOIN categorias c ON c.id_categoria = p.id_categoria
    INNER JOIN marcas m ON m.id_marca = p.id_marca
    WHERE p.estado = 1
      AND p.id_producto = p_id_producto;
END $$
DELIMITER ;

DELIMITER $$
-- PROCEDIMIENTO: SP_FILTRAR_POR_NOMBRE
-- Lista productos activos por coincidencia de nombre
CREATE PROCEDURE sp_filtrar_por_nombre(IN p_nombre VARCHAR(150))
BEGIN
    SELECT
        p.id_producto,
        p.producto,
        p.costo,
        p.ganancia,
        ROUND(p.costo / NULLIF(1 - p.ganancia, 0), 2) AS precio,
        p.stock,
        (ROUND(p.costo / NULLIF(1 - p.ganancia, 0), 2) * p.stock) AS total,
        c.nombre_categoria AS categoria,
        m.nombre_marca AS marca
    FROM productos p
    INNER JOIN categorias c ON c.id_categoria = p.id_categoria
    INNER JOIN marcas m ON m.id_marca = p.id_marca
    WHERE p.estado = 1
      AND p.producto LIKE CONCAT(p_nombre, '%')
    ORDER BY p.producto ASC;
END $$
DELIMITER ;

-- EJEMPLOS DE USO PRODUCTOS
CALL sp_buscar_producto_por_id(1);
CALL sp_filtrar_por_nombre('Disco');