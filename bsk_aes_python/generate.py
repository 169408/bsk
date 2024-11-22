from Crypto.Cipher import AES
from Crypto.Random import get_random_bytes
import binascii
import time

# Wiadomość do zaszyfrowania
message = "Hello, this is a secret message! Don't tell anyone that you got it, You must help Me. Right now I'm sitting in the prison"

# Generowanie losowego 128-bitowego klucza (16 bajtów)
key = get_random_bytes(16)
print("Generated Key (hex):", binascii.hexlify(key).decode())

# Zapisanie klucza do pliku
with open('aes_key.bin', 'wb') as key_file:
    key_file.write(key)

# Dopasowanie wiadomości do wielokrotności 16 bajtów
padded_message = message.ljust(16 * ((len(message) + 15) // 16)).encode()

# Inicjalizacja szyfrowania AES w trybie ECB
cipher = AES.new(key, AES.MODE_ECB)
encrypted_message = cipher.encrypt(padded_message)

# Zapisanie zaszyfrowanej wiadomości do pliku
with open('aes_encrypted_message.bin', 'wb') as encrypted_file:
    encrypted_file.write(encrypted_message)

print("Encrypted message saved successfully.")