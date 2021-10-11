<?php declare(strict_types=1);

namespace App\Tests\unit\Common\ApiSecret\Query;

use App\Common\ApiSecret\Query\CheckSecretMatchesQuery;
use Codeception\Test\Unit;

class CheckSecretMatchesQueryTest extends Unit
{
    /**
     * @test
     */
    public function I_can_create_a_CheckSecretMatchesQuery_with_the_given_data()
    {
        $encryptedSecret = 'ZWJmOTE5YzRmMDI5ZmU3YQ==NHu9dWmq0N2Hcqvfq/qTedSINXyJ1YxKubh1QJ30/Fg=';
        $method = 'AES-256-CBC';
        $decryptionSecret = 'My32charPasswordAndInitVectorStr';
        $hmac = '0e4182b86d04013ad66539ad5b6947d0';
        $timestampIntervalThreshold = 60;
        $secretToMatch = 'blah';

        $checkSecretMatchesQuery = new CheckSecretMatchesQuery($encryptedSecret, $secretToMatch, $method, $decryptionSecret, $hmac, $timestampIntervalThreshold);

        expect($checkSecretMatchesQuery->getEncryptedSecret())->toBe($encryptedSecret);
        expect($checkSecretMatchesQuery->getSecretToMatch())->toBe($secretToMatch);
        expect($checkSecretMatchesQuery->getMethod())->toBe($method);
        expect($checkSecretMatchesQuery->getDecryptionSecret())->toBe($decryptionSecret);
        expect($checkSecretMatchesQuery->getHmac())->toBe($hmac);
        expect($checkSecretMatchesQuery->getTimestampIntervalThreshold())->toBe($timestampIntervalThreshold);
    }
}
