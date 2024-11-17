<?php

//phpinfo();
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
    $startKey = hex2bin($startKeyHex); // Konwertujemy klucz początkowy z formatu heksadecymalnego na binarny
    $keyInt = gmp_init(bin2hex($startKey), 16); // Konwertujemy klucz na dużą liczbę całkowitą za pomocą GMP

    // Pętla do sprawdzenia wszystkich możliwych kluczy 8-bajtowych
    for ($i = 0; $i < pow(2, 56); $i++) { // 2^56, ponieważ klucze DES są 56-bitowe
        // Generowanie bieżącego klucza
        $currentKeyInt = gmp_add($keyInt, $i); // Dodajemy `i` do klucza początkowego
        $currentKeyHex = gmp_strval($currentKeyInt, 16); // Przekształcamy na format szesnastkowy
        $currentKey = hex2bin(str_pad($currentKeyHex, 16, "0", STR_PAD_LEFT)); // Konwertujemy do postaci binarnej

        // Deszyfrowanie przy pomocy obecnego klucza
        echo "Sprawdzam klucz: " . bin2hex($currentKey) . "\n";

        $decryptedText = decryptDES($encryptedText, $currentKey);

        // Sprawdzanie, czy tekst jest czytelny
        if ($decryptedText !== false && preg_match('//u', $decryptedText)) {
            echo "Możliwy klucz: " . bin2hex($currentKey) . "\n";
            echo "Odszyfrowany tekst: $decryptedText\n";
            return $decryptedText; // Zwracamy odszyfrowany tekst, jeśli jest poprawny
        }
    }

    return null; // Brak poprawnego wyniku
}

function readEncryptedFile($filePath) {
    // Odczytanie zawartości pliku
    return file_get_contents($filePath);
}

// Główna część skryptu
$startKeyHex = "6cc87452b058b000"; // Klucz początkowy w formacie heksadecymalnym
$filePath = "encrypted_data.bin"; // Ścieżka do pliku z zaszyfrowanym tekstem

// Wczytanie zaszyfrowanego tekstu z pliku
$encryptedText = readEncryptedFile($filePath);

if ($encryptedText !== false) {
    echo "Zaszyfrowany tekst z pliku: " . bin2hex($encryptedText) . "\n";
    $startTime = microtime(true);
    bruteForceDES($encryptedText, $startKeyHex);
    $endTime = microtime(true);
    echo "\nCzas wykonania: " . round($endTime - $startTime, 2) . " sekund\n";
} else {
    echo "Błąd: Nie udało się odczytać pliku zaszyfrowanego tekstu.\n";
}


