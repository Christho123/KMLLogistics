<?php
declare(strict_types=1);

// =========================================================
// HELPER: API REQUEST JSON
// Permite que los endpoints acepten form-data, x-www-form-urlencoded y raw JSON.
// =========================================================

function getRequestPayload(): array
{
    static $payload = null;

    if (is_array($payload)) {
        return $payload;
    }

    $contentType = strtolower((string) ($_SERVER['CONTENT_TYPE'] ?? ''));
    $payload = str_contains($contentType, 'multipart/form-data') ? $_POST : [];
    $rawBody = trim((string) file_get_contents('php://input'));

    if ($rawBody === '') {
        return $payload;
    }

    if (str_contains($contentType, 'multipart/form-data')) {
        $multipartPayload = parseMultipartPayload($rawBody, $contentType);
        $payload = array_replace($multipartPayload, $payload);

        return $payload;
    }

    $decodedBody = json_decode($rawBody, true);

    if (is_array($decodedBody)) {
        $payload = array_replace($decodedBody, $payload);
    }

    return $payload;
}

function parseMultipartPayload(string $rawBody, string $contentType): array
{
    if (!preg_match('/boundary=(.*)$/', $contentType, $matches)) {
        return [];
    }

    $payload = [];
    $boundary = '--' . trim($matches[1], '"');
    $parts = array_slice(explode($boundary, $rawBody), 1, -1);

    foreach ($parts as $part) {
        $part = ltrim($part, "\r\n");
        [$rawHeaders, $body] = array_pad(explode("\r\n\r\n", $part, 2), 2, '');
        $body = preg_replace("/\r\n$/", '', $body);

        if (!preg_match('/name="([^"]+)"/', $rawHeaders, $nameMatches)) {
            continue;
        }

        $name = $nameMatches[1];

        if (preg_match('/filename="([^"]*)"/', $rawHeaders, $fileMatches)) {
            $fileName = $fileMatches[1];

            if ($fileName === '') {
                continue;
            }

            $tmpName = tempnam(sys_get_temp_dir(), 'api_upload_');
            file_put_contents($tmpName, $body);

            $_FILES[$name] = [
                'name' => $fileName,
                'type' => preg_match('/Content-Type:\s*([^\r\n]+)/i', $rawHeaders, $typeMatches) ? trim($typeMatches[1]) : 'application/octet-stream',
                'tmp_name' => $tmpName,
                'error' => UPLOAD_ERR_OK,
                'size' => filesize($tmpName),
            ];

            continue;
        }

        $payload[$name] = $body;
    }

    return $payload;
}

function requireApiMethod(string $method): void
{
    $method = strtoupper($method);
    $currentMethod = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));

    if ($currentMethod === $method) {
        return;
    }

    http_response_code(405);
    header('Allow: ' . $method);
    echo json_encode([
        'success' => false,
        'message' => 'Metodo no permitido. Usa ' . $method . ' para este endpoint.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function requestValue(array $payload, string $key, mixed $default = null): mixed
{
    return array_key_exists($key, $payload) ? $payload[$key] : $default;
}

function requestString(array $payload, string $key): string
{
    return trim((string) requestValue($payload, $key, ''));
}

function requestInt(array $payload, string $key, ?int $minRange = null): ?int
{
    $value = requestValue($payload, $key);

    if ($value === null || $value === '') {
        return null;
    }

    $options = [];

    if ($minRange !== null) {
        $options['options'] = ['min_range' => $minRange];
    }

    $filtered = filter_var($value, FILTER_VALIDATE_INT, $options);

    return $filtered === false ? null : (int) $filtered;
}

function requestFloat(array $payload, string $key): ?float
{
    $value = requestValue($payload, $key);

    if ($value === null || $value === '') {
        return null;
    }

    $filtered = filter_var($value, FILTER_VALIDATE_FLOAT);

    return $filtered === false ? null : (float) $filtered;
}
