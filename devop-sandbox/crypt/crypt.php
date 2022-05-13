<?php


class Encryption {

    function generate_random_key($bytes_size) {
        $random_key = openssl_random_pseudo_bytes($bytes_size, $crypto_strong);
        if (!$crypto_strong) {
        throw new \Exception('Not strong cryptography found');
        }
        return $random_key;
    }

    /**
     * Symmetric encryption using a single key
     */
    function encrypt_aes256($clear_text, $key) {
        $method = 'aes-256-ctr';
        $iv_len = openssl_cipher_iv_length($method);
        $init_vector = openssl_random_pseudo_bytes($iv_len);
        $cipher_text = openssl_encrypt($clear_text, $method, $key, OPENSSL_RAW_DATA, $init_vector);
        return $cipher_text . $init_vector;
    }

    /**
     * Symmetric decryption using a single key
     */
    function decrypt_aes256($cipher_text, $key) {
        $method = 'aes-256-ctr';
        $iv_len = openssl_cipher_iv_length($method);
        $init_vector = substr($cipher_text, -$iv_len);
        $cipher_text = substr($cipher_text, 0, -$iv_len);
        $clear_text = openssl_decrypt($cipher_text, $method, $key, OPENSSL_RAW_DATA, $init_vector);
        return $clear_text;
    }
  
    function get_encryption_key_from_environment($key_name_for_hex) {
        if (!array_key_exists($key_name_for_hex, $_SERVER)) {
            throw new \Exception('Variable "' . $key_name_for_hex . '" not defined on server');
        }
        $key_hex = $_SERVER[$key_name_for_hex];
        if (empty($key_hex) || strlen($key_hex) < 16) {
            throw new \Exception('Key not found in ' . $key_name_for_hex);
        }
  
        return hex2bin($key_hex);
    }

    function load_encrypted_file($filepath, $env_variable_containing_key_in_hex) {
        $key = $this->get_encryption_key_from_environment($env_variable_containing_key_in_hex);
        $encrypted = file_get_contents($filepath);
        if (empty($encrypted)) {
            throw new \Exception('Failed reading encrypted file ' . $filepath  . ', or file empty');
        }
        $clear = $this->decrypt_aes256($encrypted, $key);
        return $clear;
    }

    function save_encrypted_file($filepath, $clear_content, $env_variable_containing_key_in_hex) {
        $key = $this->get_encryption_key_from_environment($env_variable_containing_key_in_hex);
        $encrypted = $this->encrypt_aes256($clear_content, $key);
        if (file_put_contents($filepath, $encrypted) === false) {
            throw new \Exception('Failed saving encrypted file ' . $filepath);
        }
    }
}

$c = new Encryption();
$key = $c->generate_random_key(16);
$clear_text = 'Hi there everyone!';
$cipher = $c->encrypt_aes256($clear_text, $key);
$decipher = $c->decrypt_aes256($cipher, $key);

echo 'Symmetric key demonstration AES256' . PHP_EOL;
echo '----------------------------------' . PHP_EOL;
echo 'key (hex)    : ' . bin2hex($key) . PHP_EOL;
echo 'clear        : ' . $clear_text . PHP_EOL;
echo 'enciphered (hex) : ' . bin2hex($cipher) . PHP_EOL;
echo 'deciphered       : ' . $decipher . PHP_EOL;
echo PHP_EOL;


//echo '$_ENV is ' . var_export($_ENV, true) . PHP_EOL;
//echo '$_SERVER is ' . var_export($_SERVER, true) . PHP_EOL;

const VOLAX_ENCRYPTION_KEY_HEX="VOLAX_ENCRYPTION_KEY_HEX";
const FILE_PATH = './encrypted.dat';

$key_hex = bin2hex($c->get_encryption_key_from_environment(VOLAX_ENCRYPTION_KEY_HEX));
echo 'Encryption key in server is: "' . $key_hex . '"' . PHP_EOL;
$c->save_encrypted_file(FILE_PATH, 'Hello there!', VOLAX_ENCRYPTION_KEY_HEX);
$clear = $c->load_encrypted_file(FILE_PATH, VOLAX_ENCRYPTION_KEY_HEX);
echo 'Clear text from file is: "' . $clear . '"' . PHP_EOL;
echo PHP_EOL;



// test asymmetric cryptography.
$config = array(
    "digest_alg" => "sha512",
    "private_key_bits" => 4096,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
);

// Create the private and public key
$res = openssl_pkey_new($config);

// Extract the private key from $res to $privKey
openssl_pkey_export($res, $privKey);

// Extract the public key from $res to $pubKey
$pubKey = openssl_pkey_get_details($res);
$pubKey = $pubKey["key"];

$data = 'plaintext data goes here';

// Encrypt the data to $encrypted using the public key
openssl_public_encrypt($data, $encrypted, $pubKey);

// Decrypt the data using the private key and store the results in $decrypted
openssl_private_decrypt($encrypted, $decrypted, $privKey);

echo 'Asymmetric Cryptography demo' . PHP_EOL;
echo '----------------------------' . PHP_EOL;
echo 'Cleartext : ' . $data . PHP_EOL;
echo 'PubKey    : ' . $pubKey . PHP_EOL;
echo 'PrivKey   : ' . $privKey . PHP_EOL;
echo 'Encrypted : ' . $encrypted . PHP_EOL;
echo 'Decrypted : ' . $decrypted . PHP_EOL;



