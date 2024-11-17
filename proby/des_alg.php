<?php

$message = "Hellow";
$key = "27172728";

echo "Słowo: $message<br /> Klucz: $key<br />";

//$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('DES-ECB'));

$encrypted_message = openssl_encrypt($message, 'DES-ECB', $key, 0/*, $iv*/);
echo "Zaszyfrowana wiadomość: " . $encrypted_message . "<br/>";


//$iv = '...';

$possible_key = null;

for($i = 1; $i <= 99999999; $i++) {
    $key = str_pad($i, 8, '0', STR_PAD_LEFT);

    //Próba odszyfrowania
    $decrypted_message = openssl_decrypt($encrypted_message, 'DES-ECB', $key, 0/*, $iv*/);
    //echo $decrypted_message . "<br />";
    if($decrypted_message == $message) {
        echo "Klucz znałeziony! Klucz to: " . $key . "<br />";
        echo $decrypted_message . "<br />";
        $possible_key = $key;
        break;
    }
}

if($possible_key == null) {
    echo "Klucz nie został znaleziony w zakresie. \n";
}