<?php

$message = "Hello, this is a secret message! Don't tell anyone that you got it, You must help Me. Right now I'm sitting in the prison"; // Wiadomość do zaszyfrowania

// Generowanie losowego 128-bitowego klucza (16 bajtów)
$key = openssl_random_pseudo_bytes(16);
echo "Generated Key (hex): " . bin2hex($key) . "\n";

// Zapisanie klucza do pliku
file_put_contents('aes_key.bin', $key);

// Inicjalizacja szyfrowania AES w trybie ECB
$encryptedMessage = openssl_encrypt($message, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);

// Sprawdzenie poprawności szyfrowania
if ($encryptedMessage === false) {
    echo "Encryption failed.\n";
    exit;
}

// Zapisanie zaszyfrowanej wiadomości do pliku
file_put_contents('aes_encrypted_message.bin', $encryptedMessage);
echo "Encrypted message saved successfully.\n";
