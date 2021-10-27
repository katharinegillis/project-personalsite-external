<?php
namespace App\Tests\unit\Infrastructure\Service;

use App\Infrastructure\Service\TinyGraphImageGenerationService;
use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Exception;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class TinyGraphImageGenerationServiceTest extends Unit
{
    /**
     * @test
     *
     * @throws Exception
     */
    public function I_can_generate_an_image_based_on_an_identifier()
    {
        $identifier = 'Personal Site - Externals';
        $expectedImage = $identifier.'image';

        $httpClient = Stub::makeEmpty(HttpClientInterface::class, [
            'request' => function (string $method, string $url) use ($identifier, $expectedImage) {
                if ('GET' === $method && 'https://tinygraphs.com/isogrids/'.urlencode($identifier).'?theme=heatwave&numcolors=3&size=220&fmt=svg' === $url) {
                    return Stub::makeEmpty(ResponseInterface::class, [
                        'getContent' => $expectedImage,
                    ]);
                }

                return null;
            },
        ]);

        $tinyGraphImageGenerationService = new TinyGraphImageGenerationService($httpClient);

        $image = $tinyGraphImageGenerationService->generateImage($identifier);

        expect($image)->toBe($expectedImage);
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function I_get_null_if_tiny_graph_gives_a_service_unavailable_response()
    {
        $identifier = 'Personal Site - Externals';

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

        $tinyGraphImageGenerationService = new TinyGraphImageGenerationService($httpClient);

        $image = $tinyGraphImageGenerationService->generateImage($identifier);

        expect($image)->toBeNull();
    }
}
