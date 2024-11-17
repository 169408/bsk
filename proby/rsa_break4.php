<?php

$keyConfig = [
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
];

$res = openssl_pkey_new($keyConfig);

openssl_pkey_export($res, $privateKey);


$keyDetails = openssl_pkey_get_details($res);
var_dump($keyDetails);
$publicKey = $keyDetails["key"];

echo "Generated Public Key: <br/>$publicKey<br/>";
echo "Generated Private Key: <br/>$privateKey<br/>";

$message = "Hellow, RSA! USA!";
echo "Original Message: $message<br/>";

openssl_public_encrypt($message, $encryptedMessage, $publicKey);
$encryptedMessageBase64 = base64_encode($encryptedMessage);
echo "Encrypted Message (Base64): $encryptedMessageBase64<br/>";

// Модуль (n) в RSA
$n = $keyDetails["rsa"]["n"];
$e = $keyDetails["rsa"]["e"];

$kluczn = base64_encode($n);

echo "<br/>$kluczn<br/>";

echo "Attempting brute force on RSA...<br/>";

// Функція для перевірки простих чисел
function isPrime($num) {
    if ($num < 2) return false;
    for ($i = 2; $i <= sqrt($num); $i++) {
        if ($num % $i == 0) return false;
    }
    return true;
}

// Шукаємо прості числа p і q, які є множниками n
for ($p = 2; $p < $n; $p++) {
    if (isPrime($p) && $n % $p == 0) {
        $q = $n / $p;
        if (isPrime($q)) {
            echo "Possible factors found: p = $p, q = $q<br/>";

            // Обчислюємо phi(n)
            $phi = ($p - 1) * ($q - 1);

            // Знаходимо d (модульне обернення e по phi)
            $d = modInverse($e, $phi);
            if ($d === null) continue; // Якщо обернення не знайдено, пропускаємо

            // Розшифровуємо за допомогою знайденого приватного ключа
            $decryptedMessage = "";
            $encryptedMessageDecoded = base64_decode($encryptedMessageBase64);
            $decryptedMessage = openssl_private_decrypt($encryptedMessageDecoded, $output, $privateKey) ? $output : null;

            if ($decryptedMessage) {
                echo "Decrypted Message: $decryptedMessage<br/>";
                break;
            }
        }
    }
}

// Функція для знаходження модульного обернення (розширений алгоритм Евкліда)
function modInverse($a, $m) {
    $m0 = $m;
    $y = 0;
    $x = 1;
    while ($a > 1) {
        $q = intdiv($a, $m);
        $t = $m;
        $m = $a % $m;
        $a = $t;
        $t = $y;
        $y = $x - $q * $y;
        $x = $t;
    }
    if ($x < 0) {
        $x += $m0;
    }
    return $x;
}
?>