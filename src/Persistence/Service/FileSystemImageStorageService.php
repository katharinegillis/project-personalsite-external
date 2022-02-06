<?php declare(strict_types=1);

namespace App\Persistence\Service;

use App\Application\Service\ImageStorageServiceInterface;
use App\Domain\Entity\Image\ImageInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class FileSystemImageStorageService implements ImageStorageServiceInterface
{
    private Filesystem $fileSystem;
    private string $imageDir;

    /**
     * @param Filesystem $fileSystem
     * @param string     $imageDir
     */
    public function __construct(Filesystem $fileSystem, string $imageDir)
    {
        $this->fileSystem = $fileSystem;
        $this->imageDir = $imageDir;
    }

    /**
     * @inheritDoc
     */
    public function create(ImageInterface $image): bool
    {
        if ($this->exists($image)) {
            return false;
        }

        $filePath = $this->generateFilePath($image);

        try {
            $this->fileSystem->appendToFile($filePath, $image->getData());
        } catch (IOExceptionInterface) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function exists(ImageInterface $image): bool
    {
        $filePath = $this->generateFilePath($image);

        return $this->fileSystem->exists($filePath);
    }

    /**
     * @param ImageInterface $image
     *
     * @return string
     */
    private function generateFilePath(ImageInterface $image): string
    {
        return $this->imageDir.'/'.$image->getFileName();
    }
}
