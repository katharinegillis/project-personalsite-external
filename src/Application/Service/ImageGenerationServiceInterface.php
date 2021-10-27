<?php declare(strict_types=1);

namespace App\Application\Service;

interface ImageGenerationServiceInterface
{
    /**
     * @param string $identifier
     *
     * @return string|null
     */
    public function generateImage(string $identifier): ?string;
}
