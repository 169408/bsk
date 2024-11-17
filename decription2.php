<?php

phpinfo();
echo "\n";

if (extension_loaded('gmp')) {
    echo "GMP jest włączone.";
} else {
    echo "GMP nie jest dostępne.";
}
echo "\n";

function decryptDES($encryptedText, $key) {
    // Funkcja deszyfrująca z użyciem OpenSSL w trybie ECB
    return openssl_decrypt($encryptedText, 'DES-ECB', $key, OPENSSL_RAW_DATA);
}

function bruteForceDES($encryptedText, $startKeyHex) {
    // Konwertujemy klucz początkowy з формату шістнадцяткового на бінарний
    $startKey = hex2bin($startKeyHex);
    $keyInt = gmp_init(bin2hex($startKey), 16); // Конвертуємо ключ у велике ціле число за допомогою GMP

    // Потрібно інкрементувати ключ за допомогою GMP для кожного перевіреного значення
    for ($i = 0; $i < pow(2, 56); $i++) { // 2^56, бо ключі DES 56-бітові
        // Генеруємо поточний ключ
        $currentKeyInt = gmp_add($keyInt, $i); // Додаємо i до початкового ключа
        $currentKeyHex = gmp_strval($currentKeyInt, 16); // Перетворюємо на шістнадцятковий формат

        // Виправлення: доповнюємо ключ до 16 символів, щоб це був правильний 8-байтний ключ
        $currentKeyHex = str_pad($currentKeyHex, 16, '0', STR_PAD_LEFT);

        $currentKey = hex2bin($currentKeyHex); // Перетворюємо знову в бінарний формат

        // Дешифрування з поточним ключем
        echo "Sprawdzam klucz: " . bin2hex($currentKey) . "\n";

        $decryptedText = decryptDES($encryptedText, $currentKey);

        // Перевірка, чи текст читабельний
        if ($decryptedText !== false && preg_match('//u', $decryptedText)) {
            echo "Możliwy klucz: " . bin2hex($currentKey) . "\n";
            echo "Odszyfrowany tekst: $decryptedText\n";
            return $decryptedText; // Повертаємо правильний результат
        }
    }

    return null; // Якщо не знайшли правильний результат
}

function readEncryptedFile($filePath) {
    // Очищення даних з файлу
    return file_get_contents($filePath);
}

// Основна частина скрипта
$startKeyHex = "8e459916ee26ed60"; // Початковий ключ у форматі шістнадцяткового
$filePath = "encrypted_data.bin"; // Шлях до файлу з зашифрованим текстом

// Читання зашифрованого тексту з файлу
$encryptedText = readEncryptedFile($filePath);
$encryptedKey = readEncryptedFile("key.bin");
$decryptedText = decryptDES($encryptedText, $encryptedKey);

if ($encryptedText !== false) {
    echo "Zaszyfrowany tekst z pliku: " . bin2hex($encryptedText) . "\n";
    $startTime = microtime(true);
    bruteForceDES($encryptedText, $startKeyHex);
    $endTime = microtime(true);
    echo "\nCzas wykonania: " . round($endTime - $startTime, 2) . " sekund\n";
} else {
    echo "Błąd: Nie udało się odczytać pliku zaszyfrowanego tekstu.\n";
}

