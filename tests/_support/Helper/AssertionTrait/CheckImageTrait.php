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
     * @param string         $identifier
     * @param string         $extension
     * @param string         $fileName
     * @param string|null    $data
     */
    public function checkImage(ImageInterface $image, string $identifier, string $extension, string $fileName, string $data = null): void
    {
        expect($image)->toBeInstanceOf(Image::class);
        expect($image->getData())->toBe($data);
        expect($image->getFileName())->toBe($fileName);
        expect($image->getIdentifier())->toBe($identifier);
        expect($image->getExtension())->toBe($extension);
        expect($image->isNull())->toBeFalse();
    }

    /**
     * @param ImageInterface $image
     */
    public function checkNullImage(ImageInterface $image)
    {
        expect($image)->toBeInstanceOf(NullImage::class);
        expect($image->getData())->toBeNull();
        expect($image->getFileName())->toBe('');
        expect($image->getIdentifier())->toBe('');
        expect($image->getExtension())->toBe('');
        expect($image->isNull())->toBeTrue();
    }

    /**
     * @return string[]
     */
    #[ArrayShape([
        'data' => "string",
        'fileName' => "string",
        'identifier' => "string",
        'extension' => "string",
    ])] private function getTestImageData(): array
    {
        return [
            'data' => 'imagedata',
            'fileName' => md5('Personal Site - Externals').'.svg',
            'identifier' => 'Personal Site - Externals',
            'extension' => 'svg',
        ];
    }
}
