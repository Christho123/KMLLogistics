create database bd_ventas_ds500;

use bd_ventas_ds500;

create table tb_marca (
    codigo_marca char(5) not null primary key,
    marca varchar(30) not null);

create table tb_categoria (
    codigo_categoria char(5) not null primary key,
    categoria varchar(30) not null);

create table tb_producto (
    codigo_producto char(5) not null primary key,
    producto varchar(40) not null,
    stock_disponible int,
    costo float,
    ganancia float,
    producto_codigo_marca char(5) not null,
    producto_codigo_categoria char(5) not null,
    foreign key (producto_codigo_marca) references tb_marca (codigo_marca),
    foreign key (producto_codigo_categoria) references tb_categoria (codigo_categoria));

insert into tb_marca values
('M0001', 'Sony'),
('M0002', 'LG'),
('M0003', 'Acer');

select * from tb_marca;

insert into tb_categoria values
('C0001', 'Electrodomesticos'),
('C0002', 'Equipo de computo'),
('C0003', 'Celular');

select * from tb_categoria;

insert into tb_producto values
('P0001', 'Lavadora 13Kg', 100, 1420, 0.2, 'M0002', 'C0001'),
('P0002', 'Laptop Core i3 8GB SSD 250 GB', 54, 1762.45, 0.1635, 'M0003', 'C0002');

select * from tb_producto;

-- Listar producto, costo, marca, presentacion
select tb1.producto, tb1.costo, tb2.marca, tb3.categoria
from tb_producto tb1 inner join tb_marca tb2
on (tb1.producto_codigo_marca = tb2.codigo_marca) inner join tb_categoria tb3
on (tb1.producto_codigo_categoria = tb3.codigo_categoria);

-- Crear un Store Procedure (SP) para Listar las marcas
delimiter $$
create procedure sp_listar_marca()
begin
    select * from tb_marca order by marca asc;
end; $$

call sp_listar_marca();
-- drop procedure sp_listar_marca;

-- Crear un Store Procedure (SP) para Listar las categorias
delimiter $$
create procedure sp_listar_categoria()
begin
    select * from tb_categoria order by categoria asc;
end; $$

call sp_listar_categoria();
-- drop procedure sp_listar_categoria;

-- Crear un Store Procedure (SP) para Listar los productos
delimiter $$
create procedure sp_listar_producto()
begin
    select * from tb_producto order by stock_disponible desc;
end; $$

call sp_listar_producto();
-- drop procedure sp_listar_producto;

-- Crear un SP para buscar un producto por codigo
-- Devuelve: producto, costo, ganancia, precio, stock, total, categoria y marca
-- sp_buscar_producto_por_codigo()

-- Crear un SP para filtrar producto por nombre (parcial o completo)
-- Devuelve: producto, costo, ganancia, precio, stock, total, categoria y marca
-- sp_filtrar_por_nombre()

-- Crear un SP para registrar un producto
delimiter $$
create procedure sp_registrar_producto(
    in cod_prod char(5), in prod varchar(40), in stk int,
    in cst float, gnc float, cod_mar char(5), cod_cat char(5))
begin
    insert into tb_producto values (cod_prod, prod, stk, cst, gnc, cod_mar, cod_cat);
end; $$

call sp_registrar_producto('P0003', 'Televisor 65\' LED', 72, 2369.17, 0.15, 'M0001', 'C0001');
-- drop procedure sp_registrar_producto;

-- Crear un SP para editar un producto
delimiter $$
create procedure sp_editar_producto(
    in cod_prod char(5), in prod varchar(40), in stk int,
    in cst float, gnc float, cod_mar char(5), cod_cat char(5))
begin
    update tb_producto set producto = prod, stock_disponible = stk, costo = cst, ganancia = gnc,
                           producto_codigo_marca = cod_mar, producto_codigo_categoria = cod_cat
    where codigo_producto = cod_prod;
end; $$

call sp_editar_producto('P0003', 'Televisor 65\' QLED', 72, 2669.17, 0.16, 'M0001', 'C0001');
-- drop procedure sp_editar_producto;

-- Crear un SP para borrar un producto
delimiter $$
create procedure sp_borrar_producto(in cod_prod char(5))
begin
    delete from tb_producto where codigo_producto = cod_prod;
end; $$

-- call sp_borrar_producto('P0003');
-- drop procedure sp_borrar_producto;