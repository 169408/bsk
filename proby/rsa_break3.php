<?php

// Generate prime numbers
function getRandomPrime($min, $max) {
    do {
        $num = rand($min, $max);
    } while (!isPrime($num));
    return $num;
}

// Перевірка, чи є число простим
function isPrime($num) {
    if ($num <= 1) return false;
    if ($num <= 3) return true;
    if ($num % 2 == 0 || $num % 3 == 0) return false;
    for ($i = 5; $i * $i <= $num; $i += 6) {
        if ($num % $i == 0 || $num % ($i + 2) == 0) return false;
    }
    return true;
}

// Алгоритм Евкліда для НСД
function gcd($a, $b) {
    while ($b != 0) {
        $temp = $b;
        $b = $a % $b;
        $a = $temp;
    }
    return $a;
}

// Розширений алгоритм Евкліда для оберненого елемента
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

// Генерація RSA-ключів
function generateRSAKeys() {
    // Генеруємо два простих числа
    $p = getRandomPrime(30, 180);
    $q = getRandomPrime(30, 180);

    // Обчислюємо n
    $n = $p * $q;
    // Обчислюємо φ(n)
    $phi = ($p - 1) * ($q - 1);

    // Вибираємо e таке, щоб НСД(e, φ(n)) = 1
    $e = 3;
    while ($e < $phi && gcd($e, $phi) != 1) {
        $e += 2;
    }

    // Обчислюємо d
    $d = modInverse($e, $phi);

    return [
        'public' => ['n' => $n, 'e' => $e],
        'private' => ['n' => $n, 'd' => $d],
    ];
}

// Шифрування повідомлення
function encrypt($message, $e, $n) {
    $encryptedMessage = [];
    foreach (str_split($message) as $char) {
        $encryptedMessage[] = bcpowmod(ord($char), $e, $n); // m^e mod n
    }
    return $encryptedMessage;
}

// Розшифрування повідомлення
function decrypt($encryptedMessage, $d, $n) {
    $decryptedMessage = "";
    foreach ($encryptedMessage as $c) {
        $decryptedMessage .= chr(bcpowmod($c, $d, $n)); // c^d mod n
    }
    return $decryptedMessage;
}

// Тепер генеруємо нові ключі для кожного повідомлення
$message = "Siemanooo, jak tam? Coś tam lorem lorem lorem lorem lorem";
echo "Original Message: $message<br/>";

// Генеруємо нові ключі
$keys = generateRSAKeys();
$publicKey = $keys['public'];
$privateKey = $keys['private'];

// Шифруємо повідомлення
$encryptedMessage = encrypt($message, $publicKey['e'], $publicKey['n']);
echo "Encrypted Message: " . implode(" ", $encryptedMessage) . "<br/>";

// Дешифруємо повідомлення
$decryptedMessage = decrypt($encryptedMessage, $privateKey['d'], $privateKey['n']);
echo "Decrypted Message: $decryptedMessage<br/>";



