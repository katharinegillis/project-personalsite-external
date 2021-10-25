<?php declare(strict_types=1);

namespace App\Common\Encryption\Service;

use DateTime;
use DateTimeZone;
use Exception;

final class EncryptionService implements EncryptionServiceInterface
{
    /**
     * @inheritDoc
     */
    public function encrypt(string $message, string $method, string $secret, ?string &$hmac): string
    {
        $iv = substr(bin2hex(openssl_random_pseudo_bytes(16)),0,16);
        $encrypted = base64_encode($iv) . openssl_encrypt($message, $method, $secret, 0, $iv);
        $hmac = hash_hmac('md5', $encrypted, $secret);
        return $encrypted;
    }

    /**
     * @inheritDoc
     */
    public function decrypt(string $encrypted, string $method, string $secret, string $hmac): string|bool
    {
        if (hash_hmac('md5', $encrypted, $secret) == $hmac) {
            $iv = base64_decode(substr($encrypted, 0, 24));
            return openssl_decrypt(substr($encrypted, 24), $method, $secret, 0, $iv);
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function encryptWithTSValidation(string $message, string $method, string $secret, ?string &$hmac): string
    {
        $now = new DateTime(timezone: new DateTimeZone('UTC'));
        $message = substr($now->format('c'),0,19) . "$message";
        return $this->encrypt($message, $method, $secret, $hmac);
    }

    /**
     * @inheritDoc
     */
    public function decryptWithTSValidation(string $encrypted, string $method, string $secret, string $hmac, int $intervalThreshold): string|bool
    {
        $decrypted = $this->decrypt($encrypted, $method, $secret, $hmac);
        if (! $decrypted) {
            return false;
        }
        $now = new DateTime();
        $msgDate = new DateTime(str_replace("T"," ",substr($decrypted,0,19)));
        if (($now->getTimestamp() - $msgDate->getTimestamp()) <= $intervalThreshold) {
            return substr($decrypted,19);
        }

        return false;
    }
}