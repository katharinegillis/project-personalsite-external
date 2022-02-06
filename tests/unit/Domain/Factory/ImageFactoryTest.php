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
            'fileName' => $fileName,
            'identifier' => $identifier,
            'extension' => $extension,
            ) = $this->getTestImageData();

        $imageFactory = new ImageFactory();

        $image = $imageFactory->createImage($identifier, $extension, $data);

        $this->checkImage($image, $identifier, $extension, $fileName, $data);
    }

    /**
     * @test
     */
    public function I_can_create_an_image_with_no_data()
    {
        list(
            'fileName' => $fileName,
            'identifier' => $identifier,
            'extension' => $extension,
            ) = $this->getTestImageData();

        $imageFactory = new ImageFactory();

        $image = $imageFactory->createImage($identifier, $extension);

        $this->checkImage($image, $identifier, $extension, $fileName, null);
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
