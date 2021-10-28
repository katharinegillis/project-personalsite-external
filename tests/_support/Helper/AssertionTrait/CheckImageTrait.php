<?php declare(strict_types=1);

namespace App\Tests\_support\Helper\AssertionTrait;

use App\Domain\Entity\Image\Image;
use App\Domain\Entity\Image\ImageInterface;
use App\Domain\Entity\Image\NullImage;
use JetBrains\PhpStorm\ArrayShape;

trait CheckImageTrait
{
    /**
     * @param ImageInterface $image
     * @param string|null    $data
     * @param string|null    $path
     * @param string|null    $identifier
     */
    public function checkImage(ImageInterface $image, string|null $data, string|null $path, string|null $identifier): void
    {
        expect($image)->toBeInstanceOf(Image::class);
        expect($image->getData())->toBe($data);
        expect($image->getPath())->toBe($path);
        expect($image->getIdentifier())->toBe($identifier);
        expect($image->isNull())->toBeFalse();
    }

    /**
     * @param ImageInterface $image
     */
    public function checkNullImage(ImageInterface $image)
    {
        expect($image)->toBeInstanceOf(NullImage::class);
        expect($image->getData())->toBeNull();
        expect($image->getPath())->toBeNull();
        expect($image->getIdentifier())->toBeNull();
        expect($image->isNull())->toBeTrue();
    }

    /**
     * @return string[]
     */
    #[ArrayShape([
        'data' => "string",
        'path' => "string",
        'identifier' => "string",
    ])] private function getTestImageData(): array
    {
        return [
            'data' => 'imagedata',
            'path' => 'public/images',
            'identifier' => 'Personal Site - Externals',
        ];
    }
}
