<?php

namespace Lib\Authentication;

class JWT
{
    public static function encode(array $payload, $expirationInSeconds = 3600): string
    {
        $header = self::encodeBase64(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload['exp'] = time() + $expirationInSeconds;
        $payload = self::encodeBase64($payload);

        $signature = self::sign($header, $payload, self::secretKey());

        return "$header.$payload.$signature";
    }

    public static function decode(string $token): ?object
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$header, $payload, $signature] = $parts;

        $decoded = self::decodeBase64($payload);
        return is_array($decoded) ? (object)$decoded : null;
    }

    public static function isValid(string $token): bool
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        [$header, $payload, $signature] = $parts;

        if (!self::verify($header, $payload, $signature)) {
            return false;
        }

        $decodedPayload = self::decodeBase64($payload);
        if (isset($decodedPayload['exp']) && time() > $decodedPayload['exp']) {
            return false;
        }

        return true;
    }

    private static function sign(string $header, string $payload, string $secretKey): string
    {
        $data = "$header.$payload";
        $signature = hash_hmac('sha256', $data, $secretKey, true);

        return self::encodeBase64($signature);
    }

    private static function verify(string $header, string $payload, string $signature): bool
    {
        $expectedSignature = self::sign($header, $payload, self::secretKey());
        return hash_equals($expectedSignature, $signature);
    }

    private static function encodeBase64($data): string
    {
        return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
    }

    private static function decodeBase64(string $data): array
    {
        return json_decode(base64_decode(strtr($data, '-_', '+/')), true);
    }

    private static function secretKey(): string
    {
        return $_ENV['JWT_SECRET'] ?? 'default_secret_key';
    }
}
