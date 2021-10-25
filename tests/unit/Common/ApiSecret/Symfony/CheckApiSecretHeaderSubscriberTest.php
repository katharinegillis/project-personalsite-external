<?php
namespace App\Tests\unit\Common\ApiSecret\Symfony;

use App\Common\ApiSecret\Query\CheckSecretMatchesQuery;
use App\Common\ApiSecret\Symfony\CheckApiSecretHeaderSubscriber;
use App\Common\CQRS\Query\QueryBusInterface;
use App\Tests\_support\Helper\DependencyTrait\CreateControllerEventTrait;
use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Exception;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class CheckApiSecretHeaderSubscriberTest extends Unit
{
    use CreateControllerEventTrait;

    /**
     * @param string $encryptedSecret
     * @param string $hmac
     * @return CheckApiSecretHeaderSubscriber
     * @throws Exception
     */
    private function createCheckApiSecretHeaderSubscriber(string $encryptedSecret, string $hmac): CheckApiSecretHeaderSubscriber
    {
        $apiSecret = 'apiSecret';
        $encryptionSecret = '12345678901234567890123456789012';
        $encryptionMethod = 'AES-256-CBC';
        $encryptionTimestampInterval = 60;

        $queryBus = Stub::makeEmpty(QueryBusInterface::class, [
            'handle' => function (CheckSecretMatchesQuery $query) use ($apiSecret, $encryptionSecret, $encryptionMethod, $encryptionTimestampInterval, $encryptedSecret, $hmac) {
                if (
                    $query->getEncryptedSecret() === $encryptedSecret &&
                    $query->getSecretToMatch() === $apiSecret &&
                    $query->getMethod() === $encryptionMethod &&
                    $query->getDecryptionSecret() === $encryptionSecret &&
                    $query->getTimestampIntervalThreshold() === $encryptionTimestampInterval &&
                    $query->getHmac() === $hmac
                ) {
                    return true;
                }

                return false;
            }
        ]);

        return new CheckApiSecretHeaderSubscriber($apiSecret, $encryptionSecret, $encryptionMethod, $encryptionTimestampInterval, $queryBus);
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_get_no_result_or_exception_when_subscriber_executes_with_correct_headers()
    {
        $encryptedSecret = 'encryptedSecret';
        $hmac = 'hmac';

        $controllerEvent = $this->createControllerEvent($encryptedSecret, $hmac);

        $checkApiSecretHeaderSubscriber = $this->createCheckApiSecretHeaderSubscriber($encryptedSecret, $hmac);

        $checkApiSecretHeaderSubscriber->execute($controllerEvent);
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_get_an_AccessDeniedHttpException_when_the_secret_in_the_header_is_wrong()
    {
        $encryptedSecret = 'encryptedSecret';
        $hmac = 'hmac';

        $receivedSecret = $encryptedSecret.'2';

        $controllerEvent = $this->createControllerEvent($receivedSecret, $hmac);

        $checkApiSecretHeaderSubscriber = $this->createCheckApiSecretHeaderSubscriber($encryptedSecret, $hmac);

        $this->expectException(AccessDeniedHttpException::class);
        $checkApiSecretHeaderSubscriber->execute($controllerEvent);
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_get_an_AccessDeniedHttpException_when_the_hmac_in_the_header_is_wrong()
    {
        $encryptedSecret = 'encryptedSecret';
        $hmac = 'hmac';

        $receivedHmac = $hmac.'2';

        $controllerEvent = $this->createControllerEvent($encryptedSecret, $receivedHmac);

        $checkApiSecretHeaderSubscriber = $this->createCheckApiSecretHeaderSubscriber($encryptedSecret, $hmac);

        $this->expectException(AccessDeniedHttpException::class);
        $checkApiSecretHeaderSubscriber->execute($controllerEvent);
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_get_an_AccessDeniedHttpException_when_the_secret_in_the_header_is_missing()
    {
        $encryptedSecret = 'encryptedSecret';
        $hmac = 'hmac';

        $controllerEvent = $this->createControllerEvent(hmacHeader: $hmac);

        $checkApiSecretHeaderSubscriber = $this->createCheckApiSecretHeaderSubscriber($encryptedSecret, $hmac);

        $this->expectException(AccessDeniedHttpException::class);
        $checkApiSecretHeaderSubscriber->execute($controllerEvent);
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_get_an_AccessDeniedHttpException_when_the_hmac_in_the_header_is_missing()
    {
        $encryptedSecret = 'encryptedSecret';
        $hmac = 'hmac';

        $controllerEvent = $this->createControllerEvent(apiSecretHeader: $encryptedSecret);

        $checkApiSecretHeaderSubscriber = $this->createCheckApiSecretHeaderSubscriber($encryptedSecret, $hmac);

        $this->expectException(AccessDeniedHttpException::class);
        $checkApiSecretHeaderSubscriber->execute($controllerEvent);
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_can_see_that_KernelController_event_is_in_the_subscribed_events_list()
    {
        $subscribedEvents = CheckApiSecretHeaderSubscriber::getSubscribedEvents();

        expect($subscribedEvents)->arrayToHaveKey(KernelEvents::CONTROLLER);
    }
}