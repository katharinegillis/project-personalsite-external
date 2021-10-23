<?php declare(strict_types=1);

namespace App\Common\ApiSecret\Query;

use App\Common\CQRS\Query\QueryHandlerInterface;
use App\Common\Encryption\Service\EncryptionService;
use Exception;

class CheckSecretMatchesQueryHandler implements QueryHandlerInterface
{
    protected EncryptionService $encryptionService;

    /**
     * @param EncryptionService $encryptionService
     */
    public function __construct(EncryptionService $encryptionService)
    {

        $this->encryptionService = $encryptionService;
    }

    /**
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