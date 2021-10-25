<?php declare(strict_types=1);

namespace App\Tests\unit\Common\ApiSecret\Query;

use App\Common\ApiSecret\Query\CheckSecretMatchesQuery;
use App\Common\ApiSecret\Query\CheckSecretMatchesQueryHandler;
use App\Tests\_support\Helper\DependencyTrait\CreateEncryptionServiceInterfaceTrait;
use Codeception\Test\Unit;
use DateTime;
use DateTimeZone;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

final class CheckSecretMatchesQueryHandlerTest extends Unit
{
    use CreateEncryptionServiceInterfaceTrait;

    protected function createEncryptedString(string $message, string $method, string $secret, DateTime $timestamp): string
    {
        return $message . '|' . $method . '|' . $secret . '|' . substr($timestamp->format('c'),0,19);
    }

    /**
     * @throws Exception
     */
    #[ArrayShape([
        'secret' => "string",
        'method' => "string",
        'decryptionSecret' => "string",
        'hmac' => "string",
        'wrongSecret' => "string",
        'wrongHmac' => "string"
    ])] protected function getSecretData(): array
    {
        return [
            'secret' => 'I am a secret!',
            'method' => 'some-method',
            'decryptionSecret' => '12345678901234567890123456789012',
            'hmac' => 'blah',
            'wrongSecret' => 'I am a secret and I don\'t match!',
            'wrongHmac' => 'blah-blah',
        ];
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_can_check_that_a_secret_matches()
    {
        list(
            'secret' => $secret,
            'method' => $method,
            'decryptionSecret' => $decryptionSecret,
            'hmac' => $hmac,
            ) = $this->getSecretData();

        $timestamp = new DateTime(timezone: new DateTimeZone('UTC'));

        $encryptedSecret = $this->createEncryptedString($secret, $method, $decryptionSecret, $timestamp);

        $checkSecretMatchesQuery = new CheckSecretMatchesQuery($encryptedSecret, $secret, $method, $decryptionSecret, $hmac, 60);

        $checkSecretMatchesQueryHandler = new CheckSecretMatchesQueryHandler($this->createEncryptionServiceInterface());

        $secretMatches = $checkSecretMatchesQueryHandler->__invoke($checkSecretMatchesQuery);

        expect($secretMatches)->toBeTrue();
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_can_check_that_a_secret_doesnt_match()
    {
        list(
            'secret' => $secret,
            'method' => $method,
            'decryptionSecret' => $decryptionSecret,
            'hmac' => $hmac,
            'wrongSecret' => $wrongSecret,
            ) = $this->getSecretData();

        $timestamp = new DateTime(timezone: new DateTimeZone('UTC'));

        $encryptedSecret = $this->createEncryptedString($wrongSecret, $method, $decryptionSecret, $timestamp);

        $checkSecretMatchesQuery = new CheckSecretMatchesQuery($encryptedSecret, $secret, $method, $decryptionSecret, $hmac, 60);

        $checkSecretMatchesQueryHandler = new CheckSecretMatchesQueryHandler($this->createEncryptionServiceInterface());

        $secretMatches = $checkSecretMatchesQueryHandler->__invoke($checkSecretMatchesQuery);

        expect($secretMatches)->toBeFalse();
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_can_check_that_a_secret_doesnt_match_because_the_hmac_is_wrong()
    {
        list(
            'secret' => $secret,
            'method' => $method,
            'decryptionSecret' => $decryptionSecret,
            'wrongHmac' => $wrongHmac,
            ) = $this->getSecretData();

        $timestamp = new DateTime(timezone: new DateTimeZone('UTC'));

        $encryptedSecret = $secret . '|' . $method . '|' . $decryptionSecret . '|' . substr($timestamp->format('c'),0,19);

        $checkSecretMatchesQuery = new CheckSecretMatchesQuery($encryptedSecret, $secret, $method, $decryptionSecret, $wrongHmac, 60);

        $checkSecretMatchesQueryHandler = new CheckSecretMatchesQueryHandler($this->createEncryptionServiceInterface());

        $secretMatches = $checkSecretMatchesQueryHandler->__invoke($checkSecretMatchesQuery);

        expect($secretMatches)->toBeFalse();
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_can_check_that_a_secret_doesnt_match_because_the_timestamp_is_outside_the_interval()
    {
        list(
            'secret' => $secret,
            'method' => $method,
            'decryptionSecret' => $decryptionSecret,
            'hmac' => $hmac,
            ) = $this->getSecretData();

        $timestamp = new DateTime(timezone: new DateTimeZone('UTC'));

        $encryptedSecret = $this->createEncryptedString($secret, $method, $decryptionSecret, $timestamp);

        $checkSecretMatchesQuery = new CheckSecretMatchesQuery($encryptedSecret, $secret, $method, $decryptionSecret, $hmac, 1);

        sleep(2);

        $checkSecretMatchesQueryHandler = new CheckSecretMatchesQueryHandler($this->createEncryptionServiceInterface());

        $secretMatches = $checkSecretMatchesQueryHandler->__invoke($checkSecretMatchesQuery);

        expect($secretMatches)->toBeFalse();
    }
}
