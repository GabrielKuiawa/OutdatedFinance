<?php
namespace App\Middleware\Concerns;
trait Response {
    private function sendUnauthorizedResponse(string $message): void
    {
        header('HTTP/1.0 401 Unauthorized');
        header('Content-Type: application/json');
        echo json_encode(['error' => $message]);
        exit;
    }
}