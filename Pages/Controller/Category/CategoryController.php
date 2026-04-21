<?php

declare(strict_types=1);

// Controlador principal del modulo Category.
class CategoryController
{
    private CategoryCRUD $categoryCRUD;

    // Inicializa dependencias del modulo.
    public function __construct()
    {
        $this->categoryCRUD = new CategoryCRUD();
    }

    // Entrega los datos necesarios para la vista principal.
    public function handleRequest(): array
    {
        return [
            'current_user' => $_SESSION['user'] ?? null,
        ];
    }
}
