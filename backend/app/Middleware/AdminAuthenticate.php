<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;


class AdminAuthenticate implements Middleware
{
    public function handle(Request $request): void
    {
        $token = $this->getBearerToken();

        $decoded = Auth::check($token);

        if (!$token || !$decoded) {
            $this->sendUnauthorizedResponse('Não autorizado');
        }

        if ($decoded->user['role'] == 'user') {
            $this->sendUnauthorizedResponse('Acesso restrito a administradores');
        }
    }

    private function getBearerToken(): ?string
    {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            return null;
        }

        if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function sendUnauthorizedResponse(string $message): void
    {
        header('HTTP/1.0 401 Unauthorized');
        header('Content-Type: application/json');
        echo json_encode(['error' => $message]);
        exit;
    }
}
