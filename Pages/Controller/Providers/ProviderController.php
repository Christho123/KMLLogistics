<?php
declare(strict_types=1);

// =========================================================
// CONTROLADOR: PROVIDERS
// Orquesta reglas de negocio y respuestas del modulo.
// =========================================================



class ProviderController
{
    private ProviderCRUD $providerCRUD;

    public function __construct()
    {
        $this->providerCRUD = new ProviderCRUD();
    }

    // =====================================================
    // HANDLE REQUEST (OBLIGATORIO PARA INDEX)
    // =====================================================
    public function handleRequest(): array
    {
        return [
            'current_user' => $_SESSION['user'] ?? null,
        ];
    }

    // =====================================================
    // LIST
    // =====================================================
    public function listProviders(int $page, int $pageSize, string $search): array
    {
        $result = $this->providerCRUD->listProviders($page, $pageSize, $search);

        $total = (int)($result['total'] ?? 0);
        $totalPages = max(1, (int)ceil($total / $pageSize));
        $currentPage = min($page, $totalPages);

        if ($currentPage !== $page) {
            $result = $this->providerCRUD->listProviders($currentPage, $pageSize, $search);
        }

        return [
            'success' => true,
            'providers' => $result['providers'] ?? [],
            'pagination' => [
                'page' => $currentPage,
                'page_size' => $pageSize,
                'total' => $total,
                'total_pages' => $totalPages,
            ],
            'search' => $search,
        ];
    }

    // =====================================================
    // GET
    // =====================================================
    public function getProvider(int $id): array
    {
        $provider = $this->providerCRUD->findProviderById($id);

        if ($provider === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'El proveedor solicitado no existe.',
            ];
        }

        return [
            'success' => true,
            'provider' => $provider,
        ];
    }

    // =====================================================
    // CREATE
    // =====================================================
    public function createProvider(
        string $razonSocial,
        ?string $nombreComercial,
        int $idTipoDocumento,
        string $numeroDocumento,
        ?string $telefono,
        ?string $correo,
        ?string $direccion,
        ?string $contacto,
        int $estado
    ): array {

        if ($this->providerCRUD->existsByDocument($idTipoDocumento, $numeroDocumento)) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'Ya existe un proveedor con ese documento.',
            ];
        }

        $provider = new Provider(
            0,
            $razonSocial,
            $nombreComercial,
            $idTipoDocumento,
            $numeroDocumento,
            $telefono,
            $correo,
            $direccion,
            $contacto,
            $estado
        );

        $id = $this->providerCRUD->create($provider);
        AuditLogger::log('Proveedor', 'Crear proveedor', 'Se creo un proveedor.', ['id_proveedor' => $id, 'razon_social' => $razonSocial]);

        return [
            'success' => true,
            'message' => 'Proveedor creado correctamente.',
            'id_proveedor' => $id,
        ];
    }

    // =====================================================
    // UPDATE
    // =====================================================
    public function updateProvider(
        int $id,
        string $razonSocial,
        ?string $nombreComercial,
        int $idTipoDocumento,
        string $numeroDocumento,
        ?string $telefono,
        ?string $correo,
        ?string $direccion,
        ?string $contacto,
        int $estado
    ): array {

        $current = $this->providerCRUD->findProviderById($id);

        if ($current === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'El proveedor ya no existe.',
            ];
        }

        if ($this->providerCRUD->existsByDocument($idTipoDocumento, $numeroDocumento, $id)) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'Ya existe otro proveedor con ese documento.',
            ];
        }

        $provider = new Provider(
            $id,
            $razonSocial,
            $nombreComercial,
            $idTipoDocumento,
            $numeroDocumento,
            $telefono,
            $correo,
            $direccion,
            $contacto,
            $estado
        );

        $updated = $this->providerCRUD->update($provider);
        AuditLogger::log('Proveedor', 'Actualizar proveedor', 'Se actualizo un proveedor.', ['id_proveedor' => $id, 'razon_social' => $razonSocial]);

        return [
            'success' => true,
            'message' => $updated
                ? 'Proveedor actualizado correctamente.'
                : 'No hubo cambios.',
        ];
    }

    // =====================================================
    // DELETE (LOGICO)
    // =====================================================
    public function deleteProvider(int $id): array
    {
        $current = $this->providerCRUD->findProviderById($id);

        if ($current === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'Proveedor no disponible.',
            ];
        }

        $deleted = $this->providerCRUD->delete($id);
        if ($deleted) {
            AuditLogger::log('Proveedor', 'Eliminar proveedor', 'Se elimino logicamente un proveedor.', ['id_proveedor' => $id]);
        }

        return [
            'success' => $deleted,
            'message' => $deleted
                ? 'Proveedor eliminado correctamente.'
                : 'No se pudo eliminar.',
        ];
    }

    // =====================================================
    // RESTORE
    // =====================================================
    public function restoreProvider(int $id): array
    {
        $provider = $this->providerCRUD->findAnyProviderById($id);

        if ($provider === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'Proveedor no disponible.',
            ];
        }

        $restored = $this->providerCRUD->restore($id);
        if ($restored) {
            AuditLogger::log('Proveedor', 'Restaurar proveedor', 'Se restauro un proveedor.', ['id_proveedor' => $id]);
        }

        return [
            'success' => $restored,
            'message' => $restored
                ? 'Proveedor restaurado correctamente.'
                : 'No se pudo restaurar.',
        ];
    }

    // =====================================================
    // HARD DELETE
    // =====================================================
    public function hardDeleteProvider(int $id): array
    {
        $provider = $this->providerCRUD->findAnyProviderById($id);

        if ($provider === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'Proveedor no disponible.',
            ];
        }

        $deleted = $this->providerCRUD->hardDelete($id);
        $deletedProvider = (bool) ($deleted['deleted_provider'] ?? false);
        if ($deletedProvider) {
            AuditLogger::log('Proveedor', 'Eliminar definitivo', 'Se elimino definitivamente un proveedor.', ['id_proveedor' => $id]);
        }

        return [
            'success' => $deletedProvider,
            'message' => $deletedProvider
                ? 'Proveedor eliminado definitivamente.'
                : 'No se pudo eliminar.',
        ];
    }
    public function listInactiveProviders(string $search = ''): array
{
$providers = $this->providerCRUD->listInactiveProviders($search);

    return [
        'success' => true,
        'providers' => $providers
    ];
}
}

