<?php

class Jwt
{
    private $secretKey;

    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }
}

public function generateToken(array $payload, $expirationInSeconds = 3600): string
{
    $header = $this->encode(['alg' => 'HS256', 'typ' => 'JWT']);
    $payload['exp'] = time() + $expirationInSeconds;
    $payload = $this->encode($payload);

    $signature = $this->sign($header, $payload);

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

public function decodeToken(string $token): ?array
{
    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        return null;
    }

    [$header, $payload, $signature] = $parts;

    return $this->decode($payload);
}

private function sign(string $header, string $payload): string
{
    $data = "$header.$payload";
    $signature = hash_hmac('sha256', $data, $this->secretKey, true);

    return $this->encode($signature);
}

private function verify(string $header, string $payload, string $signature): bool
{
    $expectedSignature = $this->sign($header, $payload);
    return hash_equals($expectedSignature, $signature);
}

private function encode($data): string
{
    return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
}

private function decode(string $data): array
{
    return json_decode(base64_decode(strtr($data, '-_', '+/')), true);
}