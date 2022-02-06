<?php
namespace App\Tests\unit\Persistence\Service;

use App\Domain\Entity\Image\Image;
use App\Persistence\Service\FileSystemImageStorageService;
use App\Tests\_support\Helper\AssertionTrait\CheckImageTrait;
use App\Tests\_support\Helper\DependencyTrait\CreateFilesystemTrait;
use Codeception\Test\Unit;
use Exception;

class FileSystemImageStorageServiceTest extends Unit
{
    use CheckImageTrait;
    use CreateFilesystemTrait;

    /**
     * @test
     *
     * @throws Exception
     */
    public function I_can_save_a_new_image_to_the_file_system()
    {
        $imageDir = '/images';

        list(
            'data' => $data,
            'identifier' => $identifier,
            'extension' => $extension,
            ) = $this->getTestImageData();

        $filesystem = $this->createFilesystem([
            'exists' => false,
            'appendToFile' => function () {
            },
        ]);

        $image = new Image($data, $identifier, $extension);

        $fileSystemImageStorageService = new FileSystemImageStorageService($filesystem, $imageDir);

        expect($fileSystemImageStorageService->create($image))->toBeTrue();
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function I_can_see_an_image_already_exists_in_the_file_system()
    {
        $imageDir = '/images';

        list(
            'identifier' => $identifier,
            'extension' => $extension,
            ) = $this->getTestImageData();

        $filesystem = $this->createFilesystem([
            'exists' => function (string $path) use ($imageDir, $identifier, $extension) {
                return $path === $imageDir.'/'.md5($identifier).'.'.$extension;
            },
        ]);

        $image = new Image(identifier: $identifier, extension: $extension);

        $fileSystemImageStorageService = new FileSystemImageStorageService($filesystem, $imageDir);

        expect($fileSystemImageStorageService->exists($image))->toBeTrue();
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function I_can_see_an_image_does_not_exist_in_the_file_system()
    {
        $imageDir = '/images';

        list(
            'identifier' => $identifier,
            'extension' => $extension,
            ) = $this->getTestImageData();

        $filesystem = $this->createFilesystem([
            'exists' => false,
        ]);

        $image = new Image(identifier: $identifier, extension: $extension);

        $fileSystemImageStorageService = new FileSystemImageStorageService($filesystem, $imageDir);

        expect($fileSystemImageStorageService->exists($image))->toBeFalse();
    }
}
