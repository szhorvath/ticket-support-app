<?php
namespace App\Services\Encryption;

class Crypt
{
    protected $method = 'AES-128-CTR'; // default
    private $_key;

    protected function ivBytes()
    {
        return openssl_cipher_iv_length($this->method);
    }

    public function __construct($key = null, $method = null)
    {
        if (!$key) {
            // if you don't supply your own key, this will be the default
            $key = gethostname() . "|" . ip2long($_SERVER['SERVER_ADDR']);
        }

        if (ctype_print($key)) {
            // convert key to binary format
            $this->_key = openssl_digest($key, 'SHA256', true);
        } else {
            $this->_key = $key;
        }
        if ($method) {
            if (in_array($method, openssl_get_cipher_methods())) {
                $this->method = $method;
            } else {
                die(__METHOD__ . ": unrecognised encryption method: {$method}");
            }
        }
    }

    public function encrypt($data)
    {
        $iv = openssl_random_pseudo_bytes($this->ivBytes());
        $encryptedString = bin2hex($iv) . openssl_encrypt($data, $this->method, $this->key, 0, $iv);
        return $encryptedString;
    }

    // decrypt encrypted string
    public function decrypt($data)
    {
        $ivStrlen = 2  * $this->ivBytes();
        if (preg_match("/^(.{" . $ivStrlen . "})(.+)$/", $data, $regs)) {
            list(, $iv, $cryptedString) = $regs;
            $decryptedString = openssl_decrypt($cryptedString, $this->method, $this->_key, 0, hex2bin($iv));
            return $decryptedString;
        } else {
            return true;
        }
    }

    public function hash($password, $hash = null)
    {
        if ($hash) {
            return password_verify($password, $hash);
        }
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
