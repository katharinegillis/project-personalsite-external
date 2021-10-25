<?php declare(strict_types=1);

namespace App\Tests\_support\Helper\DependencyTrait;

use Codeception\Util\Stub;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelInterface;

trait CreateControllerEventTrait
{
    /**
     * @param string|null $apiSecretHeader
     * @param string|null $hmacHeader
     * @return ControllerEvent
     * @throws Exception
     */
    private function createControllerEvent(?string $apiSecretHeader = null, ?string $hmacHeader = null): ControllerEvent
    {
        $kernel = Stub::makeEmpty(KernelInterface::class);
        $controller = function() {};

        $server = [];
        if (null !== $apiSecretHeader) {
            $server['HTTP_X_API_SECRET'] = $apiSecretHeader;
        }
        if (null !== $hmacHeader) {
            $server['HTTP_X_API_HMAC'] = $hmacHeader;
        }

        $request = new Request(server: $server);

        return new ControllerEvent($kernel, $controller, $request, null);
    }
}