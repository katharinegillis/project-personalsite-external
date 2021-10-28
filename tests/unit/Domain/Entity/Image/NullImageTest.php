<?php
namespace App\Tests\unit\Domain\Entity\Image;

use App\Domain\Entity\Image\NullImage;
use App\Tests\_support\Helper\AssertionTrait\CheckImageTrait;
use Codeception\Test\Unit;

class NullImageTest extends Unit
{
    use CheckImageTrait;

    /**
     * @test
     */
    public function I_can_create_a_null_image()
    {
        $image = new NullImage();

        $this->checkNullImage($image);
    }

    /**
     * @test
     */
    public function I_cannot_create_a_null_image_with_the_given_data()
    {
        list(
            'data' => $data,
            'path' => $path,
            'identifier' => $identifier,
            ) = $this->getTestImageData();

        $image = new NullImage($data, $path, $identifier);

        $this->checkNullImage($image);
    }

    /**
     * @test
     */
    public function I_cannot_set_the_data_property_for_a_null_image()
    {
        list(
            'data' => $data,
            ) = $this->getTestImageData();

        $image = new NullImage();

        expect($image->getData())->toBeNull();

        $image->setData($data);

        expect($image->getData())->toBeNull();
    }

    /**
     * @test
     */
    public function I_cannot_set_the_path_property_for_a_null_image()
    {
        list(
            'path' => $path,
            ) = $this->getTestImageData();

        $image = new NullImage();

        expect($image->getPath())->toBeNull();

        $image->setPath($path);

        expect($image->getPath())->toBeNull();
    }

    /**
     * @test
     */
    public function I_cannot_set_the_identifier_property_for_a_null_image()
    {
        list(
            'identifier' => $identifier
            ) = $this->getTestImageData();

        $image = new NullImage();

        expect($image->getIdentifier())->toBeNull();

        $image->setIdentifier($identifier);

        expect($image->getIdentifier())->toBeNull();
    }
}
