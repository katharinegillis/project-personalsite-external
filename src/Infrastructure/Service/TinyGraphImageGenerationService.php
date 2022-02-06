<?php declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Application\Service\ImageGenerationServiceInterface;
use App\Domain\Entity\Image\ImageInterface;
use App\Domain\Factory\ImageFactory;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TinyGraphImageGenerationService implements ImageGenerationServiceInterface
{
    private HttpClientInterface $httpClient;
    private ImageFactory $imageFactory;

    /**
     * @param HttpClientInterface $httpClient
     * @param ImageFactory        $imageFactory
     */
    public function __construct(HttpClientInterface $httpClient, ImageFactory $imageFactory)
    {
        $this->httpClient = $httpClient;
        $this->imageFactory = $imageFactory;
    }

    /**
     * @inheritDoc
     */
    public function generateImage(string $identifier): ImageInterface
    {
        $tinyGraphImageUrl = 'https://tinygraphs.com/isogrids/'.urlencode($identifier).'?theme=heatwave&numcolors=3&size=220&fmt=svg';

        try {
            $response = $this->httpClient->request('GET', $tinyGraphImageUrl);

            return $this->imageFactory->createImage(data: $response->getContent(), identifier: $identifier, extension: 'svg');
        } catch (TransportExceptionInterface | ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface) {
            return $this->imageFactory->createNullImage();
        }
    }
}
