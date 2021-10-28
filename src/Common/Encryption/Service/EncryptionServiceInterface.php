<?php declare(strict_types=1);

namespace App\Common\Encryption\Service;

use Exception;

interface EncryptionServiceInterface
{
    /**
     * @param string      $message
     * @param string      $method
     * @param string      $secret
     * @param string|null $hmac
     *
     * @return string
     */
    public function encrypt(string $message, string $method, string $secret, string|null &$hmac): string;

    /**
     * @param string $encrypted
     * @param string $method
     * @param string $secret
     * @param string $hmac
     *
     * @return string|bool
     */
    public function decrypt(string $encrypted, string $method, string $secret, string $hmac): string|bool;

    /**
     * @param string      $message
     * @param string      $method
     * @param string      $secret
     * @param string|null $hmac
     *
     * @return string
     *
     * @throws Exception
     */
    public function encryptWithTSValidation(string $message, string $method, string $secret, string|null &$hmac): string;

    /**
     * @param string $encrypted
     * @param string $method
     * @param string $secret
     * @param string $hmac
     * @param int    $intervalThreshold
     *
     * @return string|bool
     *
     * @throws Exception
     */
    public function decryptWithTSValidation(string $encrypted, string $method, string $secret, string $hmac, int $intervalThreshold): string|bool;
}
