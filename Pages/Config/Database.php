<?php
// =========================================================
// CONFIGURACION: DATABASE
// Conexion central PDO hacia MySQL para todo el proyecto.
// =========================================================

declare(strict_types=1);

// Conexion PDO reutilizable para todo el proyecto.
// Tecnologia asociada: PDO + MySQL.
function getConnection(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = '127.0.0.1';
    $port = '3306';
    $db = 'KMLLogistics';
    $user = 'root';
    $pass = '123456';
    $charset = 'utf8mb4';

    try {
        // Configuracion segura de PDO con excepciones y consultas preparadas reales.
        $pdo = new PDO(
            "mysql:host={$host};port={$port};dbname={$db};charset={$charset}",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );

        return $pdo;
    } catch (PDOException $exception) {
        // Error controlado de conexion.
        exit('Error de conexion PDO: ' . $exception->getMessage());
    }
}
