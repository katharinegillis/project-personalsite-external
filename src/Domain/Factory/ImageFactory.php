<?php declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Image\Image;
use App\Domain\Entity\Image\NullImage;
use JetBrains\PhpStorm\Pure;

class ImageFactory
{
    /**
     * @param string|null $data
     * @param string|null $path
     * @param string|null $identifier
     *
     * @return Image
     */
    #[Pure] public function createImage(string|null $data = null, string|null $path = null, string|null $identifier = null): Image
    {
        return new Image($data, $path, $identifier);
    }

    /**
     * @return NullImage
     */
    #[Pure] public function createNullImage(): NullImage
    {
        return new NullImage();
    }
}
