<?php


//// Генеруємо RSA ключі
//$p = 61;
//$q = 53;
//$n = $p * $q;
//$e = 17;
//$phi = ($p - 1) * ($q - 1);
//
//// Вибираємо відкритий ключ
//$d = 2753; // закритий ключ (обчислений за алгоритмом)
//
//$message = "HELLO";
//echo "Оригінальне повідомлення: $message\n";
//
//// Перетворення повідомлення в числовий формат
//$message_num = [];
//foreach (str_split($message) as $char) {
//    $message_num[] = ord($char);
//}
//
//// Шифруємо повідомлення
//$encrypted_message = [];
//foreach ($message_num as $m) {
//    $encrypted_message[] = bcpowmod($m, $e, $n); // m^e mod n
//}
//
//echo "Зашифроване повідомлення: " . implode(" ", $encrypted_message) . "\n";
//
//// Факторизація n (імітація злому)
//function factorize($n)
//{
//    for ($i = 2; $i <= sqrt($n); $i++) {
//        if ($n % $i == 0) {
//            return [$i, $n / $i];
//        }
//    }
//    return [null, null];
//}
//
//list($factor1, $factor2) = factorize($n);
//if ($factor1 && $factor2) {
//    echo "Фактори знайдені: $factor1 та $factor2\n";
//} else {
//    echo "Не вдалося знайти фактори.\n";
//}
//
//// Дешифруємо повідомлення
//$decrypted_message = "";
//foreach ($encrypted_message as $c) {
//    $decrypted_message .= chr(bcpowmod($c, $d, $n)); // c^d mod n
//}
//
//echo "Розшифроване повідомлення: $decrypted_message\n";


//// Функція для перевірки, чи є число простим
//function is_prime($num)
//{
//    if ($num < 2) return false;
//    for ($i = 2; $i <= sqrt($num); $i++) {
//        if ($num % $i == 0) return false;
//    }
//    return true;
//}
//
//// Функція для генерації випадкового простого числа
//function generate_prime($min, $max)
//{
//    do {
//        $num = rand($min, $max);
//    } while (!is_prime($num));
//    return $num;
//}
//
//// Генеруємо випадкові прості числа p і q
//$p = generate_prime(50, 100);  // Можна змінити діапазон для більших чисел
//$q = generate_prime(50, 100);
//$n = $p * $q;
//$phi = ($p - 1) * ($q - 1);
//
//// Вибір відкритого ключа e (має бути взаємно простим з phi)
//do {
//    $e = rand(2, $phi - 1);
//} while (gmp_gcd($e, $phi) != 1);
//
//// Обчислення закритого ключа d (мультиплікативний обернений до e за модулем phi)
//$d = gmp_intval(gmp_invert($e, $phi));
//
//echo "Просте число p: $p\n";
//echo "Просте число q: $q\n";
//echo "Модуль n: $n\n";
//echo "Відкритий ключ e: $e\n";
//echo "Закритий ключ d: $d\n";
//
//// Повідомлення для шифрування
//$message = "HELLO";
//echo "Оригінальне повідомлення: $message\n";
//
//// Перетворення повідомлення в числовий формат
//$message_num = [];
//foreach (str_split($message) as $char) {
//    $message_num[] = ord($char);
//}
//
//// Шифруємо повідомлення
//$encrypted_message = [];
//foreach ($message_num as $m) {
//    $encrypted_message[] = bcpowmod($m, $e, $n); // m^e mod n
//}
//
//echo "Зашифроване повідомлення: " . implode(" ", $encrypted_message) . "\n";
//
//// Дешифруємо повідомлення
//$decrypted_message = "";
//foreach ($encrypted_message as $c) {
//    $decrypted_message .= chr(bcpowmod($c, $d, $n)); // c^d mod n
//}
//
//echo "Розшифроване повідомлення: $decrypted_message\n";


// Генеруємо прості числа
function getRandomPrime($min, $max)
{
    do {
        $num = rand($min, $max);
    } while (!isPrime($num));
    return $num;
}

// Перевірка, чи є число простим
function isPrime($num)
{
    if ($num <= 1) return false;
    if ($num <= 3) return true;
    if ($num % 2 == 0 || $num % 3 == 0) return false;
    for ($i = 5; $i * $i <= $num; $i += 6) {
        if ($num % $i == 0 || $num % ($i + 2) == 0) return false;
    }
    return true;
}

// Алгоритм Евкліда для НСД
function gcd($a, $b)
{
    while ($b != 0) {
        $temp = $b;
        $b = $a % $b;
        $a = $temp;
    }
    return $a;
}

// Розширений алгоритм Евкліда для оберненого елемента
function modInverse($a, $m)
{
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

// Генеруємо RSA-ключі
$p = getRandomPrime(50, 100);
$q = getRandomPrime(50, 100);
$n = $p * $q;
$phi = ($p - 1) * ($q - 1);

// Вибираємо відкритий ключ e, щоб НСД(e, phi) = 1
$e = 3;
while ($e < $phi && gcd($e, $phi) != 1) {
    $e += 2;
}

// Обчислюємо закритий ключ d
$d = modInverse($e, $phi);

// Повідомлення для шифрування
$message = "HELLO";
echo "Оригінальне повідомлення: $message\n";

// Перетворення повідомлення в числовий формат
$message_num = [];
foreach (str_split($message) as $char) {
    $message_num[] = ord($char);
}

// Шифруємо повідомлення
$encrypted_message = [];
foreach ($message_num as $m) {
    $encrypted_message[] = bcpowmod($m, $e, $n); // m^e mod n
}
echo "Зашифроване повідомлення: " . implode(" ", $encrypted_message) . "\n";

// Дешифруємо повідомлення
$decrypted_message = "";
foreach ($encrypted_message as $c) {
    $decrypted_message .= chr(bcpowmod($c, $d, $n)); // c^d mod n
}

echo "Розшифроване повідомлення: $decrypted_message\n";

