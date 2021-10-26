<?php declare(strict_types=1);

namespace App\Common\ApiSecret\Query;

use App\Common\CQRS\Query\QueryInterface;

final class CheckSecretMatchesQuery implements QueryInterface
{
    private string $encryptedSecret;
    private string $method;
    private string $decryptionSecret;
    private string $hmac;
    private int $timestampIntervalThreshold;
    private string $secretToMatch;

    /**
     * @param string $encryptedSecret
     * @param string $secretToMatch
     * @param string $method
     * @param string $decryptionSecret
     * @param string $hmac
     * @param int    $timestampIntervalThreshold
     */
    public function __construct(string $encryptedSecret, string $secretToMatch, string $method, string $decryptionSecret, string $hmac, int $timestampIntervalThreshold)
    {
        $this->encryptedSecret = $encryptedSecret;
        $this->method = $method;
        $this->decryptionSecret = $decryptionSecret;
        $this->hmac = $hmac;
        $this->timestampIntervalThreshold = $timestampIntervalThreshold;
        $this->secretToMatch = $secretToMatch;
    }

    /**
     * @return string
     */
    public function getEncryptedSecret(): string
    {
        return $this->encryptedSecret;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getDecryptionSecret(): string
    {
        return $this->decryptionSecret;
    }

    /**
     * @return string
     */
    public function getHmac(): string
    {
        return $this->hmac;
    }

    /**
     * @return int
     */
    public function getTimestampIntervalThreshold(): int
    {
        return $this->timestampIntervalThreshold;
    }

    /**
     * @return string
     */
    public function getSecretToMatch(): string
    {
        return $this->secretToMatch;
    }
}
