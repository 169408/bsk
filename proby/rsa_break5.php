<?php

// Налаштування ключа
$config = [
    "private_key_bits" => 512, // Для демонстрації, використовуйте 2048 або більше у реальних проєктах
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
];

// Генеруємо пару ключів RSA
$keyPair = openssl_pkey_new($config);
$keyDetails = openssl_pkey_get_details($keyPair);

// Отримуємо числові значення модуля та експоненти з публічного ключа
$n = $keyDetails["rsa"]["n"]; // Модуль
$e = $keyDetails["rsa"]["e"]; // Публічна експонента

$ncode = base64_encode($n);

echo "<br/>$n<br/>";
echo "<br/>$ncode<br/>";
echo "<br/>$e<br/>";

// Конвертуємо модуль і експоненту в десяткові рядки
$n_decimal = gmp_strval(gmp_init(bin2hex($ncode), 16), 10);
//$e_decimal = gmp_strval(gmp_init(bin2hex($e), 16), 10);
echo "<br/>$n_decimal<br/>";
echo "Модуль (n) у десятковому форматі: $n_decimal<br/>";
echo "Публічна експонента (e) у десятковому форматі: $e_decimal<br/>";
