<?php
namespace App\Tests\unit\Domain\Entity\Image;

use App\Domain\Entity\Image\Image;
use App\Tests\_support\Helper\AssertionTrait\CheckImageTrait;
use Codeception\Test\Unit;
use JetBrains\PhpStorm\ArrayShape;

class ImageTest extends Unit
{
    use CheckImageTrait;

    /**
     * @test
     */
    public function I_can_create_an_image_with_the_given_data()
    {
        list(
            'data' => $data,
            'path' => $path,
            'identifier' => $identifier
            ) = $this->getTestImageData();

        $image = new Image($data, $path, $identifier);

        $this->checkImage($image, $data, $path, $identifier);
    }

    /**
     * @test
     */
    public function I_can_create_an_image_with_no_data()
    {
        $image = new Image();

        $this->checkImage($image, null, null, null);
    }

    /**
     * @test
     */
    public function I_can_set_the_data_property_for_an_image()
    {
        list(
            'data' => $data
            ) = $this->getTestImageData();

        $image = new Image();

        expect($image->getData())->toBeNull();

        $image->setData($data);

        expect($image->getData())->toBe($data);
    }

    /**
     * @test
     */
    public function I_can_set_the_path_property_for_an_image()
    {
        list(
            'path' => $path
            ) = $this->getTestImageData();

        $image = new Image();

        expect($image->getPath())->toBeNull();

        $image->setPath($path);

        expect($image->getPath())->toBe($path);
    }

    /**
     * @test
     */
    public function I_can_set_the_identifier_property_for_an_image()
    {
        list(
            'identifier' => $identifier
            ) = $this->getTestImageData();

        $image = new Image();

        expect($image->getIdentifier())->tobeNull();

        $image->setIdentifier($identifier);

        expect($image->getIdentifier())->toBe($identifier);
    }
}
