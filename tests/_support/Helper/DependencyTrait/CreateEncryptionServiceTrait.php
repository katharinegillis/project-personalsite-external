<?php declare(strict_types=1);

namespace App\Tests\_support\Helper\DependencyTrait;

use App\Common\Encryption\Service\EncryptionService;
use Closure;
use Codeception\Stub;
use DateTime;
use DateTimeZone;
use JetBrains\PhpStorm\ArrayShape;

trait CreateEncryptionServiceTrait
{
    /**
     * @param array|null $encryptionServiceParams
     * @return EncryptionService
     * @throws \Exception
     */
    public function createEncryptionService(?array $encryptionServiceParams = null): EncryptionService
    {
        if (null === $encryptionServiceParams) {
            $encryptionServiceParams = $this->getDefaultEncryptionServiceParams();
        }

        return Stub::makeEmpty(EncryptionService::class, $encryptionServiceParams);
    }

    /**
     * @return Closure[]
     */
    #[ArrayShape([
        'encrypt' => "\Closure",
        'decrypt' => "\Closure",
        'encryptWithTSValidation' => "\Closure",
        'decryptWithTSValidation' => "\Closure"
    ])] public function getDefaultEncryptionServiceParams(): array
    {
        return [
            'encrypt' => function (string $message, string $method, string $secret, ?string &$hmac) {
                $hmac = 'blah';
                return $message . '|' . $method . '|' . $secret;
            },
            'decrypt' => function (string $encryptedMessage, string $method, string $secret, string $hmac) {
                if ('blah' !== $hmac) {
                    return false;
                }

                [ $decryptedMessage, $decryptedMethod, $decryptedSecret ] = explode('|', $encryptedMessage);

                if ($decryptedMethod !== $method || $decryptedSecret !== $secret) {
                    return false;
                }

                return $decryptedMessage;
            },
            'encryptWithTSValidation' => function (string $message, string $method, string $secret, ?string &$hmac) {
                $hmac = 'blah';
                $now = new DateTime(timezone: new DateTimeZone('UTC'));
                return $message . '|' . $method . '|' . $secret . '|' . substr($now->format('c'),0,19);
            },
            'decryptWithTSValidation' => function (string $encryptedMessage, string $method, string $secret, string $hmac, int $timestampIntervalThreshold)
            {
                if ('blah' !== $hmac) {
                    return false;
                }

                [ $decryptedMessage, $decryptedMethod, $decryptedSecret, $timestamp ] = explode('|', $encryptedMessage);

                $now = new DateTime();
                $msgDate = new DateTime(str_replace("T", " ", $timestamp));

                if ($decryptedMethod !== $method || $decryptedSecret !== $secret || ($now->getTimestamp() - $msgDate->getTimestamp()) > $timestampIntervalThreshold) {
                    return false;
                }

                return $decryptedMessage;
            }
        ];
    }
}