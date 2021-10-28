<?php declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Entity\Image\ImageInterface;

interface ImageGenerationServiceInterface
{
    /**
     * @param string $identifier
     *
     * @return ImageInterface
     */
    public function generateImage(string $identifier): ImageInterface;
}
