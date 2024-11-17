<?php

// Читаємо зашифроване повідомлення з файлу
$encryptedMessage = file_get_contents('encrypted_mess.bin');
//0dd26315ad9e9d06
// Перевірка на помилки
if ($encryptedMessage === false) {
    echo "Помилка при читанні зашифрованого повідомлення з файлу.\n";
    exit;
}

// Початковий ключ у шістнадцятковому форматі
$startKey = '6fe85b6ce46708fc'; // Початковий ключ у шістнадцятковому форматі

function bruteForceDecrypt($encryptedMessage, $startKey)
{
    // Константа максимального значення для 64-бітного простору
    $maxKey = gmp_pow(2, 64);

    // Ініціалізація стартового ключа
    $currentKey = gmp_init($startKey, 16);

    // Засікання часу
    $startTime = microtime(true);

    // Цикл перебору ключів
    for ($i = gmp_init(0); gmp_cmp($i, $maxKey) < 0; $i = gmp_add($i, 1)) {
        $currentKeyHex = str_pad(gmp_strval(gmp_add($currentKey, $i), 16), 16, '0', STR_PAD_LEFT);
        $keyBin = hex2bin($currentKeyHex);

        // Виведення поточного ключа
        echo "Trying key: " . $currentKeyHex . "\n";

        try {
            // Спроба розшифрування
            $decryptedMessage = openssl_decrypt($encryptedMessage, 'DES-ECB', $keyBin, OPENSSL_RAW_DATA);

            // Перевірка результату розшифрування
            if ($decryptedMessage && isReadableMessage($decryptedMessage)) {
                file_put_contents('found_key.bin', $keyBin);
                $endTime = microtime(true);
                $executionTime = round($endTime - $startTime, 4);
                echo "Message decrypted successfully in $executionTime seconds!\n";
                echo "Key: " . $currentKeyHex . "\n";
                echo "Decrypted message: " . $decryptedMessage . "\n";
                return $decryptedMessage;
            }
        } catch (Exception $e) {
            // Логування помилки, якщо вона трапилась
            echo "Error decrypting with key: " . $currentKeyHex . " - " . $e->getMessage() . "\n";
        }
    }

    return null; // Якщо цикл завершується без результату
}


// Функція для перевірки чи розшифроване повідомлення має сенс
function isReadableMessage($message)
{
    // Ви можете визначити свою перевірку. Наприклад:
    return preg_match('/^[\w\s.,!?\'"]+$/u', $message);
}

// Розшифровуємо повідомлення методом brute force
$decryptedMessage = bruteForceDecrypt($encryptedMessage, $startKey);

if ($decryptedMessage !== null) {
    echo "The message has been decrypted: " . $decryptedMessage . "\n";
} else {
    echo "Failed to decrypt message.\n";
}

