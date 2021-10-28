<?php declare(strict_types=1);

namespace App\Domain\Entity\Image;

interface ImageInterface
{
    /**
     * @return string|null
     */
    public function getData(): string|null;

    /**
     * @param string|null $data
     */
    public function setData(string|null $data): void;

    /**
     * @return string|null
     */
    public function getPath(): string|null;

    /**
     * @param string|null $path
     */
    public function setPath(string|null $path): void;

    /**
     * @return string|null
     */
    public function getIdentifier(): string|null;

    /**
     * @param string|null $identifier
     */
    public function setIdentifier(string|null $identifier): void;

    /**
     * @return bool
     */
    public function isNull(): bool;
}
