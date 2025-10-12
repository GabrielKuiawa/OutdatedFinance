<?php

namespace Lib\Authentication;

use App\Models\User;
use Lib\Jwt\JWT;

class Auth
{
    private static string $SECRET_KEY;

    private static function getSecretKey(): string
    {
        if (!isset(self::$SECRET_KEY)) {
            self::$SECRET_KEY = $_ENV['JWT_SECRET'] ?? 'default_secret_key';
        }
        return self::$SECRET_KEY;
    }

    public static function createToken(array $userData): string
    {
        $jwt = new JWT(self::getSecretKey());
        $payload = [
            'user' => $userData
        ];

        return $jwt->generateToken($payload);
    }

    public static function check(string $token): ?object
    {
        try {
            $decoded = JWT::decodeToken($token);
            if (self::isExpired($decoded)) {
                return null;
            }
            return $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }

    private static function isExpired(object $token): bool
    {
        return ($token->exp < time());
    }


    public static function user(): ?User
    {
        if (isset($_SESSION['user']['id'])) {
            $id = $_SESSION['user']['id'];
            return User::findById($id);
        }

        return null;
    }
}