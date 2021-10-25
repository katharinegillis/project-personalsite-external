<?php
namespace App\Tests\integration\Common\ApiSecret\Symfony;

use App\Common\Encryption\Service\EncryptionService;
use App\Tests\_support\Helper\DependencyTrait\CreateControllerEventTrait;
use App\Tests\IntegrationTester;
use Codeception\Test\Unit;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class CheckApiSecretHeaderSubscriberTest extends Unit
{
    use CreateControllerEventTrait;

    protected IntegrationTester $tester;

    private string $receivedSecret;
    private string $receivedHmac = '';

    /**
     * @throws Exception
     */
    protected function _before()
    {
        $apiSecret = $this->tester->grabParameter('api_secret');
        $encryptionMethod = $this->tester->grabParameter('encryption_method');
        $encryptionSecret = $this->tester->grabParameter('encryption_secret');

        $encryptionService = new EncryptionService();
        $this->receivedSecret = $encryptionService->encryptWithTSValidation($apiSecret, $encryptionMethod, $encryptionSecret, $this->receivedHmac);
    }

    protected function _after()
    {
    }


    /**
     * @test
     * @throws Exception
     */
    public function I_get_no_exception_when_dispatching_a_kernel_controller_event_with_secret_headers()
    {
        /**
         * @var EventDispatcherInterface $eventDispatcher
         */
        $eventDispatcher = $this->tester->grabService(EventDispatcherInterface::class);

        $controllerEvent = $this->createControllerEvent($this->receivedSecret, $this->receivedHmac);

        $eventDispatcher->dispatch($controllerEvent, KernelEvents::CONTROLLER);
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_get_an_AccessDeniedHttpException_when_dispatching_a_kernel_controller_event_with_wrong_secret_in_header()
    {
        $wrongSecret = $this->receivedSecret.'2';

        /**
         * @var EventDispatcherInterface $eventDispatcher
         */
        $eventDispatcher = $this->tester->grabService(EventDispatcherInterface::class);

        $controllerEvent = $this->createControllerEvent($wrongSecret, $this->receivedHmac);

        $this->expectException(AccessDeniedHttpException::class);
        $eventDispatcher->dispatch($controllerEvent, KernelEvents::CONTROLLER);
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_get_an_AccessDeniedHttpException_when_dispatching_a_kernel_controller_event_with_wrong_hmac_in_header()
    {
        $wrongHmac = $this->receivedHmac.'2';

        /**
         * @var EventDispatcherInterface $eventDispatcher
         */
        $eventDispatcher = $this->tester->grabService(EventDispatcherInterface::class);

        $controllerEvent = $this->createControllerEvent($this->receivedSecret, $wrongHmac);

        $this->expectException(AccessDeniedHttpException::class);
        $eventDispatcher->dispatch($controllerEvent, KernelEvents::CONTROLLER);
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_get_an_AccessDeniedHttpException_when_dispatching_a_kernel_controller_event_with_secret_header_missing()
    {
        /**
         * @var EventDispatcherInterface $eventDispatcher
         */
        $eventDispatcher = $this->tester->grabService(EventDispatcherInterface::class);

        $controllerEvent = $this->createControllerEvent(hmacHeader: $this->receivedHmac);

        $this->expectException(AccessDeniedHttpException::class);
        $eventDispatcher->dispatch($controllerEvent, KernelEvents::CONTROLLER);
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_get_an_AccessDeniedHttpException_when_dispatching_a_kernel_controller_event_with_hmac_header_missing()
    {
        /**
         * @var EventDispatcherInterface $eventDispatcher
         */
        $eventDispatcher = $this->tester->grabService(EventDispatcherInterface::class);

        $controllerEvent = $this->createControllerEvent(apiSecretHeader: $this->receivedSecret);

        $this->expectException(AccessDeniedHttpException::class);
        $eventDispatcher->dispatch($controllerEvent, KernelEvents::CONTROLLER);
    }
}