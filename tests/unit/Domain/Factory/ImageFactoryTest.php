<?php
namespace App\Tests\unit\Domain\Factory;

use App\Domain\Factory\ImageFactory;
use App\Tests\_support\Helper\AssertionTrait\CheckImageTrait;
use Codeception\Test\Unit;

class ImageFactoryTest extends Unit
{
    use CheckImageTrait;

    /**
     * @test
     */
    public function I_can_create_an_image_with_given_data()
    {
        list(
            'data' => $data,
            'path' => $path,
            'identifier' => $identifier,
            ) = $this->getTestImageData();

        $imageFactory = new ImageFactory();

        $image = $imageFactory->createImage($data, $path, $identifier);

        $this->checkImage($image, $data, $path, $identifier);
    }

    /**
     * @test
     */
    public function I_can_create_an_image_with_no_data()
    {
        $imageFactory = new ImageFactory();

        $image = $imageFactory->createImage();

        $this->checkImage($image, null, null, null);
    }

    /**
     * @test
     */
    public function I_can_create_a_null_image()
    {
        $imageFactory = new ImageFactory();

        $image = $imageFactory->createNullImage();

        $this->checkNullImage($image);
    }
}
