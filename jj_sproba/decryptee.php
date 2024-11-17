<?php

// Читаємо зашифроване повідомлення з файлу
// Зчитуємо зашифроване повідомлення з файлу
$encryptedMessage = file_get_contents('encrypted_mess.bin');

// Перевірка на помилки
if ($encryptedMessage === false) {
    echo "Помилка при читанні зашифрованого повідомлення з файлу.\n";
    exit;
}

// Початковий ключ у шістнадцятковому форматі
$startKey = '3cf6aefa0221acec'; // Початковий ключ

$iv = file_get_contents('iv.bin');
// Перевірка вмісту зашифрованого повідомлення у шістнадцятковому вигляді

function bruteForceDecrypt($encryptedMessage, $startKey)
{
    // Завантаження вектора ініціалізації (IV)
    $iv = file_get_contents('iv.bin');
    if (!$iv) {
        echo "Помилка при читанні IV з файлу.\n";
        return null;
    }

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

        // Спроба розшифрування
        try {
            echo "Possible Key: " . $currentKeyHex . "\n";
            $decryptedMessage = openssl_decrypt($encryptedMessage, 'DES-CBC', $keyBin, OPENSSL_RAW_DATA, $iv);

            // Перевірка результату розшифрування
            if ($decryptedMessage && isReadableMessage($decryptedMessage)) {
                $endTime = microtime(true);
                $executionTime = round($endTime - $startTime, 4);
                echo "Message decrypted successfully by $executionTime seconds!\n";
                echo "Key: " . $currentKeyHex . "\n";
                echo "Decrypted message: " . $decryptedMessage . "\n";
                return $decryptedMessage;
            }
        } catch (Exception $e) {
            // Якщо сталася помилка, продовжуємо цикл
            echo "Error trying to decrypt with a key: " . $currentKeyHex . "\n";
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




// Розшифровуємо повідомлення методом брутфорс
$decryptedMessage = bruteForceDecrypt($encryptedMessage, $startKey);
//$decryptedMessage = isReadableMessage(openssl_decrypt($encryptedMessage, 'DES-CBC', hex2bin('fb8997369e87f048'), OPENSSL_RAW_DATA, $iv));

if ($decryptedMessage !== null) {
    echo "The message has been decrypted: " . $decryptedMessage . "\n";
} else {
    echo "Failed to decrypt message\n";
}

$keyHex = 'fb8997369e87f048'; // Шістнадцятковий ключ
$keyBin = hex2bin(str_pad($keyHex, 16, '0', STR_PAD_LEFT));





