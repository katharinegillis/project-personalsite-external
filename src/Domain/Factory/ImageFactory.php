<?php declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Image\Image;
use App\Domain\Entity\Image\NullImage;
use JetBrains\PhpStorm\Pure;

class ImageFactory
{
    /**
     * @param string      $identifier
     * @param string      $extension
     * @param string|null $data
     *
     * @return Image
     */
    #[Pure] public function createImage(string $identifier, string $extension, string $data = null): Image
    {
        return new Image($identifier, $extension, $data);
    }

    /**
     * @return NullImage
     */
    #[Pure] public function createNullImage(): NullImage
    {
        return new NullImage();
    }
}
