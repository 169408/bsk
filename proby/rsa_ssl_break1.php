<?php

// Generate RSA keys (we use a small key for demonstration)
//$config = [
//    "private_key_bits" => 512,
//    "private_key_type" => OPENSSL_KEYTYPE_RSA,
//];
//
//$keyPair = openssl_pkey_new($config);
//openssl_pkey_export($keyPair, $privateKey);
//$keyDetails = openssl_pkey_get_details($keyPair);
//$publicKey = $keyDetails["key"];
//$n = $keyDetails['rsa']['n']; // Module n for factorization
//
//var_dump($keyDetails['rsa']);
//
//echo "Public key: <br/>$publicKey<br/>";
//echo "Private key: <br/>$privateKey<br/>";
//
//// Messages to be encrypted
//$message = "Hello, RSA!";
//openssl_public_encrypt($message, $encryptedMessage, $publicKey);
//$encryptedMessageBase64 = base64_encode($encryptedMessage);
//echo "Encrypted message (Base64): $encryptedMessageBase64<br/>";
//
//// Simulation of brute force attack through factorization of n
//function pollard_rho($n)
//{
//    $x = 2;
//    $y = 2;
//    $d = 1;
//    while ($d == 1) {
//        echo $n;
//        $x = ($x * $x + 1) % $n;
//        $y = ($y * $y + 1) % $n;
//        $y = ($y * $y + 1) % $n;
//        $d = gmp_intval(gmp_gcd(gmp_abs($x - $y), $n));
//    }
//    return $d;
//}
//
//// Decomposition of n into prime factors (p, q)
//echo "Factorization of n for demonstration:<br/>";
//$p = pollard_rho($n);
//$q = $n / $p;
//echo "The found factors are: p = $p, q = $q<br/>";

$config = [
    "private_key_bits" => 512,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
];

$keyPair = openssl_pkey_new($config);
openssl_pkey_export($keyPair, $privateKey);
$keyDetails = openssl_pkey_get_details($keyPair);
$publicKey = $keyDetails["key"];

$n = gmp_import($keyDetails['rsa']['n']); // Module n for factorization
$e = gmp_import($keyDetails['rsa']['e']);

dump($keyDetails['rsa']);

echo "Public key: <br/>$publicKey<br/>";
echo "Private key: <br/>$privateKey<br/>";

// Messages to be encrypted
$message = "Hello, RSA!";
openssl_public_encrypt($message, $encryptedMessage, $publicKey);
$encryptedMessageBase64 = base64_encode($encryptedMessage);
echo "Encrypted message (Base64): $encryptedMessageBase64<br/>";

// Simulation of brute force attack through factorization of n
function pollard_rho($n) {
    $x = gmp_init(2);
    $y = gmp_init(2);
    $d = gmp_init(1);
    while (gmp_cmp($d, 1) == 0) {
        $x = gmp_mod(gmp_add(gmp_pow($x, 2), 1), $n);
        $y = gmp_mod(gmp_add(gmp_pow($y, 2), 1), $n);
        $y = gmp_mod(gmp_add(gmp_pow($y, 2), 1), $n);
        $d = gmp_gcd(gmp_abs(gmp_sub($x, $y)), $n);
    }
    return gmp_intval($d);
}

// Decomposition of n into prime factors (p, q)
echo "Factorization of n for demonstration:<br/>";
$p = pollard_rho($n);
$q = gmp_div($n, $p);
echo "The found factors are: p = $p, q = $q<br/>";


