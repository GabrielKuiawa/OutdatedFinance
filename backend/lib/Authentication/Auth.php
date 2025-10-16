<?php

namespace Lib\Authentication;

use App\Models\User;
use Lib\Authentication\JWT;

class Auth
{
    public static function createToken(array $userData): string
    {
        return JWT::encode([
            'user' => $userData
        ]);
    }


    public static function isAuthenticate(): bool
    {
        $token = self::getBearerToken();
        if (!$token) {
            return false;
        }

        return JWT::isValid($token);
    }

    public static function isAuthenticateAsAdmin(): bool
    {
        if (!self::isAuthenticate()) {
            return false;
        }
        $decoded = JWT::decode(self::getBearerToken());
        return  isset($decoded->user['role']) && $decoded->user['role'] === 'admin';
    }


    private static function getBearerToken(): ?string
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

    public static function user(): ?User
    {
        if (!self::isAuthenticate()) {
            return null;
        }
        
        $decoded = JWT::decode(self::getBearerToken());
        if (isset($decoded->user['id'])) {
            return User::findById($decoded->user['id']);
        }

        return null;
    }
}
