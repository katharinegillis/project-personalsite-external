<?php declare(strict_types=1);

namespace App\Tests\_support\Helper\DependencyTrait;

use Codeception\Util\Stub;
use Exception;
use Symfony\Component\Filesystem\Filesystem;

trait CreateFilesystemTrait
{
    /**
     * @param array|null $filesystemParams
     *
     * @return Filesystem
     *
     * @throws Exception
     */
    public function createFilesystem(array|null $filesystemParams = null): Filesystem
    {
        if (null === $filesystemParams) {
            $filesystemParams = $this->getDefaultFilesystemParams();
        }

        return Stub::make(Filesystem::class, $filesystemParams);
    }

    /**
     * @return array
     */
    public function getDefaultFilesystemParams(): array
    {
        return [];
    }
}
