<?php declare(strict_types=1);

namespace App\Common\ApiSecret\Query;

use App\Common\CQRS\Query\QueryHandlerInterface;
use App\Common\Encryption\Service\EncryptionServiceInterface;
use Exception;

final class CheckSecretMatchesQueryHandler implements QueryHandlerInterface
{
    private EncryptionServiceInterface $encryptionService;

    /**
     * @param EncryptionServiceInterface $encryptionService
     */
    public function __construct(EncryptionServiceInterface $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    /**
     * @param CheckSecretMatchesQuery $query
     *
     * @return bool
     *
     * @throws Exception
     */
    public function __invoke(CheckSecretMatchesQuery $query): bool
    {
        $secretToMatch = $query->getSecretToMatch();

        $receivedSecret = $this->encryptionService->decryptWithTSValidation(
            $query->getEncryptedSecret(),
            $query->getMethod(),
            $query->getDecryptionSecret(),
            $query->getHmac(),
            $query->getTimestampIntervalThreshold()
        );

        return $secretToMatch === $receivedSecret;
    }
}
