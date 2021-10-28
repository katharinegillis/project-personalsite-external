<?php declare(strict_types=1);

namespace App\Domain\Entity\Image;

use JetBrains\PhpStorm\Pure;

class NullImage extends AbstractImage
{
    /**
     *
     */
    #[Pure] public function __construct()
    {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function setData(string|null $data): void
    {
    }

    /**
     * @inheritDoc
     */
    public function setPath(string|null $path): void
    {
    }

    /**
     * @inheritDoc
     */
    public function setIdentifier(string|null $identifier): void
    {
    }

    /**
     * @inheritDoc
     */
    public function isNull(): bool
    {
        return true;
    }
}
