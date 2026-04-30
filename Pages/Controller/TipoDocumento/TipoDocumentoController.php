<?php
declare(strict_types=1);

// =========================================================
// CONTROLADOR: TIPO DOCUMENTO
// Orquesta reglas de negocio y respuestas del modulo.
// =========================================================



// Controlador principal del modulo TipoDocumento.
// Tecnologia asociada: MVC + POO.
class TipoDocumentoController
{
    private TipoDocumentoCRUD $tipoDocumentoCRUD;

    // Inicializa dependencias del modulo.
    public function __construct()
    {
        $this->tipoDocumentoCRUD = new TipoDocumentoCRUD();
    }

    // Entrega los datos necesarios para la vista principal.
    public function handleRequest(): array
    {
        return [
            'current_user' => $_SESSION['user'] ?? null,
            'tipo_documento_status_options' => $this->getStatusOptions(),
        ];
    }

    // Orquesta el listado principal de tipos de documento.
    // Metodo clave consumido por la API de listado.
    public function listDocumentTypes(int $page, int $pageSize, string $search): array
    {
        $result = $this->tipoDocumentoCRUD->listDocumentTypes($page, $pageSize, $search);
        $total = (int) ($result['total'] ?? 0);
        $totalPages = max(1, (int) ceil($total / $pageSize));
        $currentPage = min($page, $totalPages);

        if ($currentPage !== $page) {
            $result = $this->tipoDocumentoCRUD->listDocumentTypes($currentPage, $pageSize, $search);
        }

        return [
            'success' => true,
            'document_types' => $result['document_types'] ?? [],
            'pagination' => [
                'page' => $currentPage,
                'page_size' => $pageSize,
                'total' => $total,
                'total_pages' => $totalPages,
            ],
            'search' => $search,
        ];
    }

    // Orquesta el listado de tipos de documento inactivos.
    public function listInactiveDocumentTypes(string $search): array
    {
        $documentTypes = $this->tipoDocumentoCRUD->listInactiveDocumentTypes($search);

        return [
            'success' => true,
            'document_types' => $documentTypes,
            'total' => count($documentTypes),
            'search' => $search,
        ];
    }

    // Obtiene un tipo de documento activo por ID.
    public function getDocumentType(int $idTipoDocumento): array
    {
        $documentType = $this->tipoDocumentoCRUD->findDocumentTypeById($idTipoDocumento);

        if ($documentType === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'El tipo de documento solicitado no existe o ya no esta disponible.',
            ];
        }

        return [
            'success' => true,
            'document_type' => $documentType,
        ];
    }

    // Crea un nuevo tipo de documento validando nombres duplicados.
    public function createDocumentType(string $nombreTipoDocumento, string $descripcion, int $estado): array
    {
        if ($this->tipoDocumentoCRUD->existsByName($nombreTipoDocumento)) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'Ya existe un tipo de documento activo con este nombre.',
            ];
        }

        $tipoDocumento = new TipoDocumento(0, $nombreTipoDocumento, $descripcion, $estado);
        $idTipoDocumento = $this->tipoDocumentoCRUD->create($tipoDocumento);
        AuditLogger::log('Tipo documento', 'Crear tipo documento', 'Se creo un tipo de documento.', ['id_tipo_documento' => $idTipoDocumento, 'nombre_tipo_documento' => $nombreTipoDocumento]);

        return [
            'success' => true,
            'message' => 'El tipo de documento fue creado correctamente.',
            'id_tipo_documento' => $idTipoDocumento,
        ];
    }

    // Actualiza un tipo de documento existente.
    public function updateDocumentType(int $idTipoDocumento, string $nombreTipoDocumento, string $descripcion, int $estado): array
    {
        $currentDocumentType = $this->tipoDocumentoCRUD->findDocumentTypeById($idTipoDocumento);

        if ($currentDocumentType === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'El tipo de documento que intentas editar ya no esta disponible.',
            ];
        }

        if ($this->tipoDocumentoCRUD->existsByName($nombreTipoDocumento, $idTipoDocumento)) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'Ya existe otro tipo de documento activo con este nombre.',
            ];
        }

        $tipoDocumento = new TipoDocumento($idTipoDocumento, $nombreTipoDocumento, $descripcion, $estado);
        $updated = $this->tipoDocumentoCRUD->update($tipoDocumento);
        AuditLogger::log('Tipo documento', 'Actualizar tipo documento', 'Se actualizo un tipo de documento.', ['id_tipo_documento' => $idTipoDocumento, 'nombre_tipo_documento' => $nombreTipoDocumento]);

        return [
            'success' => true,
            'message' => $updated
                ? 'El tipo de documento fue actualizado correctamente.'
                : 'No hubo cambios para guardar, pero el tipo de documento sigue disponible.',
        ];
    }

    // Elimina logicamente un tipo de documento activo.
    public function deleteDocumentType(int $idTipoDocumento): array
    {
        $currentDocumentType = $this->tipoDocumentoCRUD->findDocumentTypeById($idTipoDocumento);

        if ($currentDocumentType === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'El tipo de documento ya no se encuentra disponible para eliminar.',
            ];
        }

        $deleted = $this->tipoDocumentoCRUD->delete($idTipoDocumento);
        if ($deleted) {
            AuditLogger::log('Tipo documento', 'Eliminar tipo documento', 'Se elimino logicamente un tipo de documento.', ['id_tipo_documento' => $idTipoDocumento]);
        }

        if (!$deleted) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'No se pudo completar la eliminacion del tipo de documento.',
            ];
        }

        return [
            'success' => true,
            'message' => 'El tipo de documento fue eliminado del listado activo correctamente.',
        ];
    }

    // Restaura un tipo de documento inactivo.
    public function restoreDocumentType(int $idTipoDocumento): array
    {
        $documentType = $this->tipoDocumentoCRUD->findAnyDocumentTypeById($idTipoDocumento);

        if ($documentType === null || ($documentType['deleted_at'] === null && (int) $documentType['estado'] !== 0)) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'El tipo de documento inactivo solicitado ya no esta disponible.',
            ];
        }

        if ($this->tipoDocumentoCRUD->existsByName((string) ($documentType['nombre_tipo_documento'] ?? ''), $idTipoDocumento)) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'No se puede restaurar este tipo de documento porque ya existe uno activo con este nombre.',
            ];
        }

        $restored = $this->tipoDocumentoCRUD->restore($idTipoDocumento);
        if ($restored) {
            AuditLogger::log('Tipo documento', 'Restaurar tipo documento', 'Se restauro un tipo de documento.', ['id_tipo_documento' => $idTipoDocumento]);
        }

        return [
            'success' => $restored,
            'status_code' => $restored ? 200 : 409,
            'message' => $restored
                ? 'El tipo de documento fue restaurado correctamente.'
                : 'No se pudo restaurar el tipo de documento seleccionado.',
        ];
    }

    // Elimina definitivamente un tipo de documento inactivo.
    // Si tiene dependencias en usuarios o proveedores, devuelve error controlado.
    public function hardDeleteDocumentType(int $idTipoDocumento): array
    {
        $documentType = $this->tipoDocumentoCRUD->findAnyDocumentTypeById($idTipoDocumento);

        if ($documentType === null || ($documentType['deleted_at'] === null && (int) $documentType['estado'] !== 0)) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'El tipo de documento inactivo ya no esta disponible para eliminacion definitiva.',
            ];
        }

        $result = $this->tipoDocumentoCRUD->hardDelete($idTipoDocumento);

        if ($result['blocked_by_dependencies']) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'No se puede eliminar definitivamente este tipo de documento porque tiene ' . $result['related_providers'] . ' proveedor(es) y ' . $result['related_users'] . ' usuario(s) asociados.',
            ];
        }

        if (!$result['deleted_document_type']) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'No se pudo completar la eliminacion definitiva del tipo de documento.',
            ];
        }

        AuditLogger::log('Tipo documento', 'Eliminar definitivo', 'Se elimino definitivamente un tipo de documento.', ['id_tipo_documento' => $idTipoDocumento]);

        return [
            'success' => true,
            'message' => 'El tipo de documento fue eliminado definitivamente de la base de datos.',
        ];
    }

    // Opciones de estado para formularios del modulo.
    public function getStatusOptions(): array
    {
        return [
            ['value' => 1, 'label' => 'Activo'],
            ['value' => 0, 'label' => 'Inactivo'],
        ];
    }
}

