<?php
$jwt = $_GET["token"];

// Split the JWT into its three parts
list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $jwt);

// Recreate the data that was signed (header + payload)
$data = $base64UrlHeader . '.' . $base64UrlPayload;

// Decode the signature
$signature = base64_decode(strtr($base64UrlSignature, '-_', '+/'));

// Load the public key
$publicKeyPath = getenv('IHOUSE_PUBLIC_KEY_PATH'); // FIX: Add quotes around the getenv key
$publicKey = file_get_contents($publicKeyPath);

if (!$publicKey) {
    die('Failed to load public key');
}

// Verify the signature using the public key
$ok = openssl_verify($data, $signature, $publicKey, OPENSSL_ALGO_SHA256);

if ($ok == 1) {
    echo "Signature is valid!";
} elseif ($ok == 0) {
    echo "Signature is invalid.";
} else {
    echo "Error during verification.";
}
?>
