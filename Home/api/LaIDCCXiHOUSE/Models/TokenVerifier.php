<?php
// namespace 

use Nyholm\Psr7\Response;

class TokenVerifier
{
    private $publicKeyPath;

    public function __construct()
    {
        // Get the path to the public key
        $this->publicKeyPath = getenv('IHOUSE_PUBLIC_KEY_PATH');

        if (!$this->publicKeyPath) {
            throw new \Exception('Public key path not found in environment variables');
        }
    }

    public function verifyToken(string $data, string $signature_base64): Response
    {
        // Decode the signature from Base64
        $signature = base64_decode($signature_base64);
        
        // Load the public key from the PEM file
        $publicKey = file_get_contents($this->publicKeyPath);

        if (!$publicKey) {
            return new Response(500, [], json_encode([
                'error' => 'Failed to load public key'
            ]));
        }

        // Verify the signature using the public key
        $ok = openssl_verify($data, $signature, $publicKey, OPENSSL_ALGO_SHA256);

        // Prepare response based on verification result
        if ($ok === 1) {
            return new Response(200, [], json_encode([
                'message' => 'Signature is valid'
            ]));
        } elseif ($ok === 0) {
            return new Response(400, [], json_encode([
                'error' => 'Signature is invalid'
            ]));
        } else {
            return new Response(500, [], json_encode([
                'error' => 'Error during verification'
            ]));
        }
    }
}
