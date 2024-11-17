<?php

require 'vendor/autoload.php';

use phpseclib3\Crypt\DES;


function generateAndEncrypt($message, $keyFilename = 'key.bin', $encryptedDataFilename = 'encrypted_data.bin')
{
    // Generowanie losowego 8-bajtowego klucza dla DES
    $key = openssl_random_pseudo_bytes(8);

    // Sprawdzanie długości klucza
    if (strlen($key) !== 8) {
        die("Błąd: Klucz musi mieć 8 bajtów.");
    }

    // Wyświetlenie klucza jako liczby w systemie dziesiętnym
    echo "Klucz (dziesiętny): " . unpack("P", $key)[1] . "\n";
    // Wyświetlenie klucza jako liczby szesnastkowej
    echo "Klucz (szesnastkowy): " . bin2hex($key) . "\n";


    // Wypełnienie wiadomości do wielokrotności rozmiaru bloku DES (8 bajtów)
    $blockSize = 8;
    $paddingSize = $blockSize - (strlen($message) % $blockSize);
    $paddedMessage = $message . str_repeat(chr($paddingSize), $paddingSize);

    // Szyfrowanie wiadomości z użyciem DES w trybie ECB
    $cipher = new DES('ecb');
    $cipher->setKey($key);
    $encryptedData = $cipher->encrypt($message);
    //$encryptedData = openssl_encrypt($paddedMessage, 'DES-ECB', $key, OPENSSL_RAW_DATA);
    if ($encryptedData === false) {
        echo "Błąd szyfrowania: " . openssl_error_string() . "\n";
        return;
    }


    // Zapisanie klucza do pliku
    file_put_contents($keyFilename, $key);
    echo "Klucz zapisano w pliku '{$keyFilename}'\n";

    // Zapisanie zaszyfrowanych danych do pliku
    file_put_contents($encryptedDataFilename, $encryptedData);
    echo "Zaszyfrowane dane zapisano w pliku '{$encryptedDataFilename}'\n";
}

// Przykładowe użycie
$message = "To jest testowa wiadomość";
generateAndEncrypt($message, 'key.bin', 'encrypted_data.bin');
