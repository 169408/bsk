<?php

// Функція для спроби розшифрувати повідомлення
//function bruteForceDecrypt($encryptedMessage, $correctKey)
//{
//    // Ініціалізаційний вектор (створюємо IV з 8 байтів)
//    $iv = substr(hex2bin($correctKey), 0, 8);
//
//    // Перебір ключів у шістнадцятковому форматі
//    for ($i = 0; $i < 256; $i++) {
//        // Генерація шістнадцяткового ключа
//        $keyHex = str_pad(dechex($i), 16, '0', STR_PAD_LEFT);
//
//        // Конвертуємо шістнадцятковий ключ у бінарний формат
//        $keyBin = hex2bin($keyHex);
//
//        // Розшифровка за допомогою DES
//        $decryptedMessage = openssl_decrypt($encryptedMessage, 'DES-CBC', $keyBin, OPENSSL_RAW_DATA, $iv);
//
//        // Перевірка, чи є це правильним повідомленням (за допомогою методу, наприклад, співпадіння з оригіналом)
//        if ($decryptedMessage === "Hello, this is a secret message!") {
//            return $decryptedMessage; // Якщо повідомлення розшифроване, повертаємо його
//        }
//    }
//    return null; // Якщо не вдалося знайти правильний ключ
//}
//
//// Зчитуємо зашифроване повідомлення з файлу
//$encryptedMessage = file_get_contents('encrypted_mess.bin');
//
//// Читаємо ключ з файлу (він буде використовуватися для порівняння)
//$correctKey = file_get_contents('key.bin');
//
//// Розшифровуємо повідомлення методом брутфорс
//$decryptedMessage = bruteForceDecrypt($encryptedMessage, $correctKey);
//
//if ($decryptedMessage !== null) {
//    echo "Повідомлення розшифровано: " . $decryptedMessage . "\n";
//} else {
//    echo "Не вдалося розшифрувати повідомлення.\n";
//}

//function bruteForceDecrypt($encryptedMessage, $startKey, $endKey)
//{
//    // Ініціалізаційний вектор (створюємо IV з 8 байтів)
//    $iv = substr(hex2bin($startKey), 0, 8);
//
//    // Починаємо перебір ключів
//    $currentKey = gmp_init($startKey, 16); // Використовуємо GMP для великих чисел
//    $endKeyGMP = gmp_init($endKey, 16);  // Кінцевий ключ у форматі GMP
//
//    while (gmp_cmp($currentKey, $endKeyGMP) <= 0) {
//        // Перетворюємо поточний ключ з GMP в шістнадцятковий формат
//        $currentKeyHex = gmp_strval($currentKey, 16);
//
//        // Конвертуємо шістнадцятковий ключ у бінарний
//        $keyBin = hex2bin(str_pad($currentKeyHex, 16, '0', STR_PAD_LEFT));
//
//        // Розшифровуємо за допомогою DES
//        $decryptedMessage = openssl_decrypt($encryptedMessage, 'DES-CBC', $keyBin, OPENSSL_RAW_DATA, $iv);
//
//        // Перевіряємо, чи розшифроване повідомлення правильне
//        if ($decryptedMessage === "Hello, this is a secret message!") {
//            return $decryptedMessage; // Якщо розшифроване, повертаємо результат
//        }
//
//        // Збільшуємо ключ на одиницю
//        $currentKey = gmp_add($currentKey, 1);
//    }
//
//    return null; // Якщо не знайшли правильний ключ
//}

//function bruteForceDecrypt($encryptedMessage, $startKey, $endKey)
//{
//    // Читання IV з файлу
//    $iv = file_get_contents('iv.bin');
//    if (!$iv) {
//        echo "Помилка при читанні IV з файлу.\n";
//        exit;
//    }
//
//    // Ініціалізація початкового та кінцевого ключа
//    $currentKey = gmp_init($startKey, 16);
//    $endKeyGMP = gmp_init($endKey, 16);
//
//    $maxAttempts = gmp_add($endKeyGMP, 10); // Кінець пошуку на 10 кроків більше
//
//    while (gmp_cmp($currentKey, $maxAttempts) <= 0) {
//        $currentKeyHex = gmp_strval($currentKey, 16);
//        $keyBin = hex2bin(str_pad($currentKeyHex, 16, '0', STR_PAD_LEFT));
//
//        // Розшифровуємо повідомлення
//        $decryptedMessage = openssl_decrypt($encryptedMessage, 'DES-CBC', $keyBin, OPENSSL_RAW_DATA, $iv);
//
//        if ($decryptedMessage === "Hello, this is a secret message!") {
//            return $decryptedMessage; // Якщо розшифровано, повертаємо повідомлення
//        }
//
//        // Збільшуємо ключ на одиницю
//        $currentKey = gmp_add($currentKey, 1);
//    }
//
//    return null; // Якщо не знайшли правильний ключ
//}

// Функція для спроби розшифрувати повідомлення
//function bruteForceDecrypt($encryptedMessage, $startKey, $endKey)
//{
//    // Читання IV з файлу
//    $iv = file_get_contents('iv.bin');
//    if (!$iv) {
//        echo "Помилка при читанні IV з файлу.\n";
//        exit;
//    }
//
//    // Ініціалізація початкового та кінцевого ключа
//    $currentKey = gmp_init($startKey, 16);
//    $endKeyGMP = gmp_init($endKey, 16);
//
//    // Засікаємо час початку виконання функції
//    $startTime = microtime(true);
//
//    // Проходимо через всі можливі ключі в межах startKey і endKey
//    while (gmp_cmp($currentKey, $endKeyGMP) <= 0) {
//        $currentKeyHex = gmp_strval($currentKey, 16);
//        $keyBin = hex2bin(str_pad($currentKeyHex, 16, '0', STR_PAD_LEFT));
//
//        // Виводимо на екран поточний ключ
//        echo "Try to decrypt by key: " . $currentKeyHex . "\n";
//
//        // Розшифровуємо повідомлення
//        $decryptedMessage = openssl_decrypt($encryptedMessage, 'DES-CBC', $keyBin, OPENSSL_RAW_DATA, $iv);
//
//        if ($decryptedMessage === "Hello, this is a secret message!") {
//            // Засікаємо час після успішної розшифровки
//            $endTime = microtime(true);
//            $executionTime = $endTime - $startTime;
//            echo "Повідомлення розшифровано! Час розшифровки: " . round($executionTime, 4) . " секунд\n";
//            return $decryptedMessage; // Якщо розшифровано, повертаємо повідомлення
//        }
//
//        // Збільшуємо ключ на одиницю
//        $currentKey = gmp_add($currentKey, 1);
//    }
//
//    return null; // Якщо не знайшли правильний ключ
//}