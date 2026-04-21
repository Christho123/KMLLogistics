<?php

declare(strict_types=1);

// Entidad base para el modulo Category.
class Category
{
    public array $categories;

    // Constructor simple de apoyo.
    public function __construct(array $categories = [])
    {
        $this->categories = $categories;
    }
}
