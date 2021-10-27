<?php declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Application\Service\ImageGenerationServiceInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TinyGraphImageGenerationService implements ImageGenerationServiceInterface
{
    private HttpClientInterface $httpClient;

    /**
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @inheritDoc
     */
    public function generateImage(string $identifier): ?string
    {
        $tinyGraphImageUrl = 'https://tinygraphs.com/isogrids/'.urlencode($identifier).'?theme=heatwave&numcolors=3&size=220&fmt=svg';

        try {
            $response = $this->httpClient->request('GET', $tinyGraphImageUrl);

            return $response->getContent();
        } catch (TransportExceptionInterface | ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface) {
            return null;
        }
    }
}
