<?php declare(strict_types=1);

namespace App\Domain\Entity\Image;

use App\Common\NullEntity\NullableTrait;

abstract class AbstractImage implements ImageInterface
{
    use NullableTrait;

    private string|null $data;
    private string|null $path;
    protected ?string $identifier;

    /**
     * @param string|null $data
     * @param string|null $path
     * @param string|null $identifier
     */
    public function __construct(string|null $data = null, string|null $path = null, string|null $identifier = null)
    {
        $this->data = $data;
        $this->path = $path;
        $this->identifier = $identifier;
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
    public function getPath(): string|null
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function setPath(string|null $path): void
    {
        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier(): string|null
    {
        return $this->identifier;
    }

    /**
     * @inheritDoc
     */
    public function setIdentifier(string|null $identifier): void
    {
        $this->identifier = $identifier;
    }
}
