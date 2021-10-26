<?php declare(strict_types=1);

namespace App\Common\CQRS\Query\Symfony;

use App\Common\CQRS\Query\QueryBusInterface;
use App\Common\CQRS\Query\QueryInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerQueryBus implements QueryBusInterface
{
    use HandleTrait {
        handle as handleQuery;
    }

    /**
     * @param MessageBusInterface $queryBus
     */
    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /**
     * @inheritDoc
     */
    public function handle(QueryInterface $message): mixed
    {
        return $this->handleQuery($message);
    }
}
