<?php

/**
 * Class VernamCipher
 * Idea from https://en.wikipedia.org/wiki/One-time_pad
 * One time encryption key is generated out of consecutive hashing iterator + password + salt
 *
 * $crypto = new VernamCipher();
 * $crypto->setPassword("MySecretPassword");
 * $crypto->setSalt("MyOpenSalt");
 * $openText = <<<EOT
 * In cryptography, the one-time pad (OTP) is an encryption technique that cannot be cracked,
 * but requires the use of a one-time pre-shared key the same size as, or longer than, the message being sent.
 * In this technique, a plaintext is paired with a random secret key (also referred to as a one-time pad).
 * Then, each bit or character of the plaintext is encrypted by combining it with the corresponding bit or character
 * from the pad using modular addition. If the key is:
 * (1) truly random,
 * (2) at least as long as the plaintext,
 * (3) never reused in whole or in part,
 * and (4) kept completely secret, then the resulting ciphertext will be impossible to decrypt or break.
 * EOT;
 * $encrypted = base64_encode($crypto->encrypt($openText));
 * $decrypted = $crypto->decrypt(base64_decode($e));
 *
 */
class VernamCipher
{
    private $password;
    private $salt;
    private $hashType = "sha256";

    function setPassword($password)
    {
        $this->password = $password;
    }

    function setSalt($salt)
    {
        $this->salt = $salt;
    }

    function setHashType($hashType)
    {
        $this->hashType = $hashType;
    }

    function decrypt($data)
    {
        $this->validate();
        $key = $this->generateKey($this->password, $this->salt, strlen($data));
        return $this->xorIt($data, $key);
    }

    function encrypt($data)
    {
        return $this->decrypt($data);
    }

    private function generateKey($password, $salt, $length)
    {
        $i = 0;
        $secretKey = "";
        while (true) {
            $hash = hash($this->hashType, $i . $password . $salt, true);
            for ($s = 0; $s < strlen($hash); $s++) {
                $secretKey .= $hash[$s];
                if (strlen($secretKey) >= $length) {
                    break 2;
                }
            }
            $i++;
        }
        return $secretKey;
    }

    private function xorIt($string, $key)
    {
        $strLength = strlen($string);

        for ($i = 0; $i < $strLength; $i++) {
            $string[$i] = $string[$i] ^ $key[$i];
        }

        return $string;
    }

    private function validate()
    {
        if (!$this->password) {
            throw new Exception("password required");
        }

        if (!$this->salt) {
            throw new Exception("salt required");
        }
    }
}