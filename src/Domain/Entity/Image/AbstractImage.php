<?php declare(strict_types=1);

namespace App\Domain\Entity\Image;

use App\Common\NullEntity\NullableTrait;
use JetBrains\PhpStorm\Pure;

abstract class AbstractImage implements ImageInterface
{
    use NullableTrait;

    private string|null $data;
    private string $fileName;
    private string $identifier;
    private string $extension;

    /**
     * @param string      $identifier
     * @param string      $extension
     * @param string|null $data
     */
    #[Pure] public function __construct(string $identifier, string $extension, string $data = null)
    {
        $this->identifier = $identifier;
        $this->extension = $extension;
        $this->fileName = $this->createFileName();
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function getData(): string|null
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function setData(string|null $data): void
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @inheritDoc
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * @inheritDoc
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @inheritDoc
     */
    public function setExtension(string $extension): void
    {
        $this->extension = $extension;
    }

    /**
     * @return string
     */
    #[Pure] private function createFileName(): string
    {
        if ($this->isNull()) {
            return '';
        }

        return md5($this->identifier).'.'.$this->extension;
    }
}
