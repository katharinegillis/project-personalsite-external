<?php declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Entity\Image\ImageInterface;

interface ImageStorageServiceInterface
{
    /**
     * @param ImageInterface $image
     *
     * @return bool
     */
    public function create(ImageInterface $image): bool;

    /**
     * @param ImageInterface $image
     *
     * @return bool
     */
    public function exists(ImageInterface $image): bool;
}
