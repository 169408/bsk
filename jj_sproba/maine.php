<?php


//$message = "Hello, this is a secret message!"; // Повідомлення для шифрування
//
//// Генерація випадкового 8-бітного ключа
//$key = openssl_random_pseudo_bytes(8);
//
//// Збереження ключа в файл
//file_put_contents('key.bin', $key);
//
//// Шифрування повідомлення
//$iv = openssl_random_pseudo_bytes(8); // Ініціалізаційний вектор для DES
//$encryptedMessage = openssl_encrypt($message, 'DES-CBC', $key, OPENSSL_RAW_DATA, $iv);
//
//// Збереження зашифрованого повідомлення в файл
//file_put_contents('encrypted_mess.bin', $encryptedMessage);
//
//echo "Ключ і зашифроване повідомлення збережено.\n";
//
//$key = file_get_contents('key.bin');
//echo $key . "\n";
//$key_hex = bin2hex($key);
//echo $key_hex . "\n";
//
//$decryptedMessage = openssl_decrypt($encryptedMessage, 'DES-CBC', $key, OPENSSL_RAW_DATA, $iv);
//var_dump($decryptedMessage);
//echo $decryptedMessage . "\n";

$message = "Hello, this is a secret message! Don't tell anyone that u got it, You must help Me. Right now i'm sitting in the prison"; // Повідомлення для шифрування

// Генерація випадкового 8-бітного ключа
$key = openssl_random_pseudo_bytes(8);
echo $key . "\n";

// Збереження ключа в файл
file_put_contents('key.bin', $key);

// Ініціалізаційний вектор для DES (повинен бути випадковим)
$iv = openssl_random_pseudo_bytes(8);

// Шифрування повідомлення
$encryptedMessage = openssl_encrypt($message, 'DES-CBC', $key, OPENSSL_RAW_DATA, $iv);

// Перевірка, чи шифрування вдалося
if ($encryptedMessage === false) {
    echo "Помилка шифрування.\n";
    while ($error = openssl_error_string()) {
        echo "OpenSSL Error: $error\n";
    }
    exit;
}

// Збереження зашифрованого повідомлення і IV в файл
file_put_contents('encrypted_mess.bin', $encryptedMessage);
file_put_contents('iv.bin', $iv); // Збереження IV окремо

echo "The key and encrypted message are saved.\n";

// Читання ключа з файлу
$key = file_get_contents('key.bin');
echo "Key (hex): " . bin2hex($key) . "\n"; // Виведення ключа у шістнадцятковому вигляді

// Читання зашифрованого повідомлення з файлу
$encryptedMessage = file_get_contents('encrypted_mess.bin');
if (!$encryptedMessage) {
    echo "Error reading an encrypted message from a file.\n";
    exit;
}

// Читання IV з файлу
$iv = file_get_contents('iv.bin');
if (!$iv) {
    echo "Помилка при читанні IV з файлу.\n";
    exit;
}

// Розшифровка повідомлення
$decryptedMessage = openssl_decrypt($encryptedMessage, 'DES-CBC', $key, OPENSSL_RAW_DATA, $iv);

// Виведення результату
if ($decryptedMessage === false) {
    echo "The decryption failed.\n";
} else {
    echo "Decrypted message: " . $decryptedMessage . "\n";
}





