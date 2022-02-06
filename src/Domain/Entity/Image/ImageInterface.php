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
     * @return string
     */
    public function getFileName(): string;

    /**
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * @param string $identifier
     */
    public function setIdentifier(string $identifier): void;

    /**
     * @return string
     */
    public function getExtension(): string;

    /**
     * @param string $extension
     */
    public function setExtension(string $extension): void;

    /**
     * @return bool
     */
    public function isNull(): bool;
}
