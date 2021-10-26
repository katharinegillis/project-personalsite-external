<?php declare(strict_types=1);

namespace App\Common\ApiSecret\Symfony;

use App\Common\ApiSecret\Query\CheckSecretMatchesQuery;
use App\Common\CQRS\Query\QueryBusInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class CheckApiSecretHeaderSubscriber implements EventSubscriberInterface
{
    private string $apiSecret;
    private string $encryptionSecret;
    private string $encryptionMethod;
    private int $encryptionTimestampInterval;
    private QueryBusInterface $queryBus;

    /**
     * @param string            $apiSecret
     * @param string            $encryptionSecret
     * @param string            $encryptionMethod
     * @param int               $encryptionTimestampInterval
     * @param QueryBusInterface $queryBus
     */
    public function __construct(string $apiSecret, string $encryptionSecret, string $encryptionMethod, int $encryptionTimestampInterval, QueryBusInterface $queryBus)
    {
        $this->apiSecret = $apiSecret;
        $this->encryptionSecret = $encryptionSecret;
        $this->encryptionMethod = $encryptionMethod;
        $this->encryptionTimestampInterval = $encryptionTimestampInterval;
        $this->queryBus = $queryBus;
    }

    /**
     * @inheritDoc
     */
    #[ArrayShape([
        KernelEvents::CONTROLLER => "string",
    ])] public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'execute',
        ];
    }

    /**
     * @param ControllerEvent $event
     */
    public function execute(ControllerEvent $event): void
    {
        $receivedSecret = $event->getRequest()->headers->get('X-Api-Secret');
        $receivedHmac = $event->getRequest()->headers->get('X-Api-HMAC');

        if (null === $receivedSecret || null === $receivedHmac || !$this->checkSecretMatches($receivedSecret, $receivedHmac)) {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @param string $encryptedSecret
     * @param string $hmac
     *
     * @return bool
     */
    private function checkSecretMatches(string $encryptedSecret, string $hmac): bool
    {
        $query = new CheckSecretMatchesQuery(
            $encryptedSecret,
            $this->apiSecret,
            $this->encryptionMethod,
            $this->encryptionSecret,
            $hmac,
            $this->encryptionTimestampInterval
        );

        return $this->queryBus->handle($query);
    }
}
