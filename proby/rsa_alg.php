<?php

$keyConfig = [
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
];

$res = openssl_pkey_new($keyConfig);

openssl_pkey_export($res, $privateKey);

$keyDetails = openssl_pkey_get_details($res);
$publicKey = $keyDetails["key"];

echo "Generated Public Key: <br/>$publicKey<br/>";
echo "Generated Private Key: <br/>$privateKey<br/>";

$message = "Hellow, RSA! USA!";
echo "Original Message: $message<br/>";

openssl_public_encrypt($message, $encryptedMessage, $publicKey);
$encryptedMessageBase64 = base64_encode($encryptedMessage);
echo "Encrypted Message (Base64): $encryptedMessageBase64<br/>";

openssl_private_decrypt($encryptedMessage, $decryptedMessage, $privateKey);
echo "Decrypted Message: $decryptedMessage<br/>";