<?php

$encryptionKey = "senhaCriptografia";
$signatureKey = "senhaAssinatura";

function encrypt_id($id, $encryptionKey, $signatureKey) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));
    $encryptedId = openssl_encrypt($id, 'AES-256-CBC', $encryptionKey, 0, $iv);
    $signature = hash_hmac('sha256', $encryptedId, $signatureKey);
    $token = base64_encode($iv . $encryptedId . $signature);
    error_log('Token gerado: ' . $token);

    return $token;
}

function decrypt_id($token, $encryptionKey, $signatureKey, $campo) {
    $data = base64_decode($token);
    $iv = substr($data, 0, openssl_cipher_iv_length('AES-256-CBC'));
    $encryptedIdWithSignature = substr($data, openssl_cipher_iv_length('AES-256-CBC'));
    $encryptedId = substr($encryptedIdWithSignature, 0, -64); 
    $incomingSignature = substr($encryptedIdWithSignature, -64);

    $calculatedSignature = hash_hmac('sha256', $encryptedId, $signatureKey);

    if ($incomingSignature !== $calculatedSignature) {
        
        throw new Exception( $campo . ' Campo Inválido');
    }

    $id = openssl_decrypt($encryptedId, 'AES-256-CBC', $encryptionKey, 0, $iv);
    return $id;
}
