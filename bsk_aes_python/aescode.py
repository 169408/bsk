from Crypto.Cipher import AES
from Crypto.Random import get_random_bytes
import binascii
import time


# Funkcja do brute-forcingu
def brute_force_decrypt(encrypted_message, start_key_hex):
    # Ustawienie maksymalnego klucza dla 128-bitowego klucza
    max_key = int("FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF", 16)
    current_key = int(start_key_hex, 16)

    start_time = time.time()  # Zapisanie czasu początkowego

    # Iteracja przez wszystkie możliwe klucze
    for i in range(max_key - current_key + 1):
        current_key_hex = hex(current_key + i)[2:].zfill(32)
        key_bin = binascii.unhexlify(current_key_hex)

        print(f"Trying key: {current_key_hex}")
        # Próba odszyfrowania wiadomości
        try:
            cipher = AES.new(key_bin, AES.MODE_ECB)
            decrypted_message = cipher.decrypt(encrypted_message).decode().strip()
            if is_readable_message(decrypted_message):
                elapsed_time = round(time.time() - start_time, 4)
                print(f"Message decrypted successfully in {elapsed_time} seconds!")
                print(f"Key (hex): {current_key_hex}")
                print(f"Decrypted message: {decrypted_message}")
                with open('aes_found_key.bin', 'wb') as key_file:
                    key_file.write(key_bin)
                return decrypted_message
        except Exception:
            continue

    return None

# Funkcja do sprawdzania czy wiadomość jest czytelna
def is_readable_message(message):
    # Sprawdzanie czy wiadomość zawiera tylko czytelne znaki ASCII
    return all(32 <= ord(char) <= 126 for char in message)

# Odczytanie zaszyfrowanej wiadomości z pliku
with open('aes_encrypted_message.bin', 'rb') as encrypted_file:
    encrypted_message = encrypted_file.read()

# Początkowy klucz w formacie szesnastkowym
# 4bf86723221b1b4318fc3ca38ace537d
start_key_hex = '4bf86723221b1b4318fc3ca38ac20000'

# Odszyfrowanie brute force
decrypted_message = brute_force_decrypt(encrypted_message, start_key_hex)

if decrypted_message:
    print("The message has been decrypted:", decrypted_message)
else:
    print("Failed to decrypt message.")