<?php
namespace App\Tests\unit\Infrastructure\Service;

use App\Domain\Factory\ImageFactory;
use App\Infrastructure\Service\TinyGraphImageGenerationService;
use App\Tests\_support\Helper\AssertionTrait\CheckImageTrait;
use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Exception;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class TinyGraphImageGenerationServiceTest extends Unit
{
    use CheckImageTrait;

    /**
     * @test
     *
     * @throws Exception
     */
    public function I_can_generate_an_image_based_on_an_identifier()
    {
        list(
            'data' => $data,
            'fileName' => $fileName,
            'identifier' => $identifier,
            'extension' => $extension,
            ) = $this->getTestImageData();

        $httpClient = Stub::makeEmpty(HttpClientInterface::class, [
            'request' => function (string $method, string $url) use ($identifier, $data) {
                if ('GET' === $method && 'https://tinygraphs.com/isogrids/'.urlencode($identifier).'?theme=heatwave&numcolors=3&size=220&fmt=svg' === $url) {
                    return Stub::makeEmpty(ResponseInterface::class, [
                        'getContent' => $data,
                    ]);
                }

                return null;
            },
        ]);

        $tinyGraphImageGenerationService = new TinyGraphImageGenerationService($httpClient, new ImageFactory());

        $image = $tinyGraphImageGenerationService->generateImage($identifier);

        $this->checkImage($image, $identifier, $extension, $fileName, $data);
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function I_get_a_null_image_if_tiny_graph_gives_a_service_unavailable_response()
    {
        list(
            'identifier' => $identifier,
            ) = $this->getTestImageData();

        $httpClient = Stub::makeEmpty(HttpClientInterface::class, [
            'request' => function (string $method, string $url) use ($identifier) {
                if ('GET' === $method && 'https://tinygraphs.com/isogrids/'.urlencode($identifier).'?theme=heatwave&numcolors=3&size=220&fmt=svg' === $url) {
                    return Stub::makeEmpty(ResponseInterface::class, [
                        'getContent' => function () {
                            throw new ServerException(Stub::makeEmpty(ResponseInterface::class, [
                                'getInfo' => function (string $type) {
                                    if ('response_headers' === $type) {
                                        return [];
                                    }

                                    return null;
                                },
                            ]));
                        },
                    ]);
                }

                return null;
            },
        ]);

        $tinyGraphImageGenerationService = new TinyGraphImageGenerationService($httpClient, new ImageFactory());

        $image = $tinyGraphImageGenerationService->generateImage($identifier);

        $this->checkNullImage($image);
    }
}
