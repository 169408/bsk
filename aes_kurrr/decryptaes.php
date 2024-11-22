<?php

// Зчитуємо зашифроване повідомлення з файлу
$encryptedMessage = file_get_contents('aes_encrypted_message.bin');

if ($encryptedMessage === false) {
    echo "Помилка читання зашифрованого повідомлення.\n";
    exit;
}

// Початковий ключ у шістнадцятковому форматі
//86e70f3663d7bf635eb8a042d3720413
$startKey = '86e70f3663d7bf635eb8a042d3400000'; // Починаємо з ключа 128-біт (16 байтів, всі нулі)

function bruteForceDecrypt($encryptedMessage, $startKey)
{
    $maxKey = gmp_pow(2, 128); // Максимальне значення для 128-бітового ключа
    $currentKey = gmp_init($startKey, 16); // Перетворення початкового ключа в GMP

    $startTime = microtime(true); // Засікання часу

    for ($i = gmp_init(0); gmp_cmp($i, $maxKey) < 0; $i = gmp_add($i, 1)) {
        $currentKeyHex = str_pad(gmp_strval(gmp_add($currentKey, $i), 16), 32, '0', STR_PAD_LEFT);
        $keyBin = hex2bin($currentKeyHex);

        // Спроба розшифрувати повідомлення
        $decryptedMessage = openssl_decrypt($encryptedMessage, 'AES-128-ECB', $keyBin, OPENSSL_RAW_DATA);
        echo "Try key: " . $currentKeyHex . "\n";
        if ($decryptedMessage && isReadableMessage($decryptedMessage)) {
            file_put_contents('aes_found_key.bin', $keyBin);
            $executionTime = round(microtime(true) - $startTime, 4);
            echo "Message decrypted successfully in $executionTime seconds!\n";
            echo "Key (hex): " . $currentKeyHex . "\n";
            echo "Decrypted message: " . $decryptedMessage . "\n";
            return $decryptedMessage;
        }
    }

    return null; // Якщо цикл завершився без результату
}

function isReadableMessage($message)
{
    // Перевірка на читабельність (наприклад, ASCII символи)
    return preg_match('/^[\w\s.,!?\'"]+$/u', $message);
}

// Розшифрування brute force
$decryptedMessage = bruteForceDecrypt($encryptedMessage, $startKey);

if ($decryptedMessage !== null) {
    echo "The message has been decrypted: " . $decryptedMessage . "\n";
} else {
    echo "Failed to decrypt message.\n";
}

