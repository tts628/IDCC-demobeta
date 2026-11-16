<?php
class JWTGenerator
{
    private $secretKey;
    private $header;
    private $payload;

    public function __construct($payloadData = [])
    {
        // Load the shared secret key
        $this->secretKey = getenv('IHOUSE_SECRET_KEY');
        if (!$this->secretKey) {
            die('Failed to load secret key');
        }

        // Create the header with HS256
        $this->header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        // Set the default payload or override it with provided data
        $this->payload = array_merge([
            'iss' => 'Logicart-IDCC'
        ], $payloadData);
    }

    public function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function createJWT()
    {
        // 1. Base64URL encode the header and payload
        $base64UrlHeader = $this->base64UrlEncode(json_encode($this->header));
        $base64UrlPayload = $this->base64UrlEncode(json_encode($this->payload));

        // 2. Concatenate header and payload with a dot
        $data = $base64UrlHeader . '.' . $base64UrlPayload;

        // 3. Sign the data using HMAC with SHA-256
        $signature = hash_hmac('sha256', $data, $this->secretKey, true); // 'true' returns raw binary data

        // 4. Base64URL encode the signature
        $base64UrlSignature = $this->base64UrlEncode($signature);

        // 5. Create the final JWT
        $jwt = $data . '.' . $base64UrlSignature;

        return $jwt;
    }
}

// Example usage:
// Pass additional payload data if needed, otherwise it defaults to the preset payload.
// $jwtGenerator = new JWTGenerator(['sub' => '1234567890']); // Optional payload data
$jwtGenerator = new JWTGenerator(); // Optional payload data
$jwt = $jwtGenerator->createJWT();

echo "JWT: " . $jwt;
?>
