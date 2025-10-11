<?php
namespace Lib\Jwt;

class JWT
{
    private $secretKey;

    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }


    public function generateToken(array $payload, $expirationInSeconds = 3600): string
    {
        $header = self::encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload['exp'] = time() + $expirationInSeconds;
        $payload = self::encode($payload);

        $signature = self::sign($header, $payload, $this->secretKey);

        return "$header.$payload.$signature";
    }

    public function validateToken(string $token): bool
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        [$header, $payload, $signature] = $parts;

        if (!$this->verify($header, $payload, $signature)) {
            return false;
        }

        $decodedPayload = $this->decode($payload);
        if (isset($decodedPayload['exp']) && time() > $decodedPayload['exp']) {
            return false;
        }

        return true;
    }

    public static function decodeToken(string $token): ?object
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$header, $payload, $signature] = $parts;

        $decoded = self::decode($payload);
        return is_array($decoded) ? (object)$decoded : null;
    }

    private static function sign(string $header, string $payload, string $secretKey): string
    {
        $data = "$header.$payload";
        $signature = hash_hmac('sha256', $data, $secretKey, true);

        return self::encode($signature);
    }

    private function verify(string $header, string $payload, string $signature): bool
    {
        $expectedSignature = self::sign($header, $payload, $this->secretKey);
        return hash_equals($expectedSignature, $signature);
    }

    private static function encode($data): string
    {
        return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
    }

    private static function decode(string $data): array
    {
        return json_decode(base64_decode(strtr($data, '-_', '+/')), true);
    }
}
