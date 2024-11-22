<?php

$message = "Hello, this is a secret message! Don't tell anyone that you got it, You must help Me. Right now I'm sitting in the prison"; // Повідомлення для шифрування

// Генерація випадкового 128-бітного ключа (16 байтів)
$key = openssl_random_pseudo_bytes(16);
echo "Generated Key (hex): " . bin2hex($key) . "\n";

// Збереження ключа у файл
file_put_contents('aes_key.bin', $key);

// Ініціалізація шифру AES у режимі ECB
$encryptedMessage = openssl_encrypt($message, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);

// Перевірка успішності шифрування
if ($encryptedMessage === false) {
    echo "Encryption failed.\n";
    exit;
}

// Збереження зашифрованого повідомлення у файл
file_put_contents('aes_encrypted_message.bin', $encryptedMessage);
echo "Encrypted message saved successfully.\n";
