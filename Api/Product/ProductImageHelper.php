<?php
declare(strict_types=1);

// =========================================================
// HELPER: PRODUCT IMAGE
// Guarda imagenes de productos usando el nombre del producto.
// =========================================================



function saveProductImage(array $file, string $productName): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('No se pudo cargar la imagen del producto.');
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $extension = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions, true)) {
        throw new RuntimeException('La imagen debe ser JPG, PNG, WEBP o GIF.');
    }

    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $productName), '-'));
    $slug = $slug !== '' ? $slug : 'producto';
    $fileName = $slug . '.' . $extension;
    $targetDirectory = dirname(__DIR__, 2) . '/Pages/Images/Products';

    if (!is_dir($targetDirectory)) {
        mkdir($targetDirectory, 0775, true);
    }

    $targetPath = $targetDirectory . '/' . $fileName;

    if (!move_uploaded_file((string) $file['tmp_name'], $targetPath)) {
        throw new RuntimeException('No se pudo guardar la imagen del producto.');
    }

    return 'Pages/Images/Products/' . $fileName;
}

