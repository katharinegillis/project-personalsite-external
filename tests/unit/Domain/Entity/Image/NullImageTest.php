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
    public function I_cannot_set_the_identifier_property_for_a_null_image()
    {
        list(
            'identifier' => $identifier
            ) = $this->getTestImageData();

        $image = new NullImage();

        expect($image->getIdentifier())->toBe('');

        $image->setIdentifier($identifier);

        expect($image->getIdentifier())->toBe('');
    }

    /**
     * @test
     */
    public function I_cannot_set_the_extension_property_for_a_null_image()
    {
        list(
            'extension' => $extension,
            ) = $this->getTestImageData();

        $image = new NullImage();

        expect($image->getExtension())->toBe('');

        $image->setExtension($extension);

        expect($image->getExtension())->toBe('');
    }
}
