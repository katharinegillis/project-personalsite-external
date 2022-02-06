<?php
namespace App\Tests\unit\Domain\Entity\Image;

use App\Domain\Entity\Image\Image;
use App\Tests\_support\Helper\AssertionTrait\CheckImageTrait;
use Codeception\Test\Unit;

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
            'identifier' => $identifier,
            'extension' => $extension,
            ) = $this->getTestImageData();

        $generatedFileName = md5($identifier).'.'.$extension;

        $image = new Image($identifier, $extension, $data);

        $this->checkImage($image, $identifier, $extension, $generatedFileName, $data);
    }

    /**
     * @test
     *
     * @return void
     */
    public function I_can_create_an_image_with_no_data()
    {
        list(
            'identifier' => $identifier,
            'extension' => $extension,
            ) = $this->getTestImageData();

        $generatedFileName = md5($identifier).'.'.$extension;

        $image = new Image($identifier, $extension);

        $this->checkImage($image, $identifier, $extension, $generatedFileName, null);
    }

    /**
     * @test
     */
    public function I_can_set_the_data_property_for_an_image()
    {
        list(
            'data' => $data,
            'identifier' => $identifier,
            'extension' => $extension,
            ) = $this->getTestImageData();

        $image = new Image($identifier, $extension);

        expect($image->getData())->toBeNull();

        $image->setData($data);

        expect($image->getData())->toBe($data);
    }

    /**
     * @test
     */
    public function I_can_set_the_identifier_property_for_an_image()
    {
        list(
            'identifier' => $identifier,
            'extension' => $extension,
            ) = $this->getTestImageData();

        $image = new Image($identifier, $extension);

        expect($image->getIdentifier())->toBe($identifier);

        $newIdentifier = $identifier.$identifier;

        $image->setIdentifier($newIdentifier);

        expect($image->getIdentifier())->toBe($newIdentifier);
    }

    /**
     * @test
     */
    public function I_can_set_the_extension_property_for_an_image()
    {
        list(
            'identifier' => $identifier,
            'extension' => $extension,
            ) = $this->getTestImageData();

        $image = new Image($identifier, $extension);

        expect($image->getExtension())->toBe($extension);

        $newExtension = $extension.$extension;

        $image->setExtension($newExtension);

        expect($image->getExtension())->toBe($newExtension);
    }
}
