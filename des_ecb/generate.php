<?php

$message = "Hello, this is a secret message! Don't tell anyone that u got it, You must help Me. Right now I'm sitting in the prison"; // Повідомлення для шифрування
//
// Генерація випадкового 8-бітного ключа
$key = openssl_random_pseudo_bytes(8);
echo "Generated Key: " . bin2hex($key) . "\n";

// Збереження ключа в файл
file_put_contents('key.bin', $key);

// Ініціалізація шифру DES у режимі ECB
$encryptedMessage = openssl_encrypt($message, 'DES-ECB', $key, OPENSSL_RAW_DATA);

// Перевірка, чи шифрування вдалося
if ($encryptedMessage === false) {
    echo "Помилка шифрування.\n";
    while ($error = openssl_error_string()) {
        echo "OpenSSL Error: $error\n";
    }
    exit;
}

// Збереження зашифрованого повідомлення в файл
file_put_contents('encrypted_mess.bin', $encryptedMessage);
echo "Encrypted message saved successfully.\n";
