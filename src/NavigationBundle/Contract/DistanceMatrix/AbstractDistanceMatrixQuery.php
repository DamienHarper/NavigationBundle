<?php

namespace DH\NavigationBundle\Contract\DistanceMatrix;

use DateTime;
use DH\NavigationBundle\Exception\DestinationException;
use DH\NavigationBundle\Exception\OriginException;
use DH\NavigationBundle\Exception\ResponseException;
use DH\NavigationBundle\Provider\ProviderInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractDistanceMatrixQuery implements DistanceMatrixQueryInterface
{
    /**
     * @var ProviderInterface
     */
    private $provider;

    /**
     * @var array
     */
    protected $origins;

    /**
     * @var array
     */
    protected $destinations;

    /**
     * @var ?DateTime
     */
    private $departure_time;

    /**
     * @var string|null
     */
    private $language;

    /**
     * DistanceMatrixQuery constructor.
     */
    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
        $this->origins = [];
        $this->destinations = [];
    }

    public function getProvider(): ProviderInterface
    {
        return $this->provider;
    }

    /**
     * {@inheritdoc}
     */
    public function getDepartureTime(): ?DateTime
    {
        return $this->departure_time;
    }

    /**
     * {@inheritdoc}
     */
    public function setDepartureTime(DateTime $departureTime): DistanceMatrixQueryInterface
    {
        $this->departure_time = $departureTime;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addOrigin($origin): DistanceMatrixQueryInterface
    {
        $this->origins[] = $origin;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrigins(): array
    {
        return $this->origins;
    }

    /**
     * {@inheritdoc}
     */
    public function addDestination($destination): DistanceMatrixQueryInterface
    {
        $this->destinations[] = $destination;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDestinations(): array
    {
        return $this->destinations;
    }

    /**
     * {@inheritdoc}
     */
    public function setLanguage(string $language): DistanceMatrixQueryInterface
    {
        $this->language = $language;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguage(): string
    {
        return $this->language ?? 'en-US';
    }

    /**
     * @throws DestinationException
     * @throws OriginException
     * @throws ResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * {@inheritdoc}
     */
    public function execute(): DistanceMatrixResponseInterface
    {
        $this->validateRequest();
        $request = $this->buildRequest();
        $rawResponse = $this->request('GET', $request);
        $response = $this->buildResponse($rawResponse);
        $this->validateResponse($response);

        return $response;
    }

    abstract protected function buildRequest(): string;

    abstract protected function buildResponse(ResponseInterface $response): DistanceMatrixResponseInterface;

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function request(string $method, string $url): ResponseInterface
    {
        $client = $this->getProvider()->getClient();
        $response = $client->request($method, $url);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception('Response with status code '.$response->getStatusCode());
        }

        return $response;
    }

    /**
     * @throws ResponseException
     */
    private function validateResponse(DistanceMatrixResponseInterface $response): void
    {
        switch ($response->getStatus()) {
            case DistanceMatrixResponseInterface::RESPONSE_STATUS_OK:
                break;
            case DistanceMatrixResponseInterface::RESPONSE_STATUS_INVALID_REQUEST:
                throw new ResponseException('Invalid request.', 1);
                break;
            case DistanceMatrixResponseInterface::RESPONSE_STATUS_MAX_ELEMENTS_EXCEEDED:
                throw new ResponseException('The product of the origin and destination exceeds the limit per request.', 2);
                break;
            case DistanceMatrixResponseInterface::RESPONSE_STATUS_OVER_QUERY_LIMIT:
                throw new ResponseException('The service has received too many requests from your application in the allowed time range.', 3);
                break;
            case DistanceMatrixResponseInterface::RESPONSE_STATUS_REQUEST_DENIED:
                throw new ResponseException('The service denied the use of the Distance Matrix API service by your application.', 4);
                break;
            case DistanceMatrixResponseInterface::RESPONSE_STATUS_UNKNOWN_ERROR:
                throw new ResponseException('Unknown error.', 5);
                break;
            default:
                throw new ResponseException(sprintf('Unknown status code: %s', $response->getStatus()), 6);
                break;
        }
    }

    /**
     * @throws DestinationException
     * @throws OriginException
     */
    private function validateRequest(): void
    {
        if (empty($this->getOrigins())) {
            throw new OriginException('Origin must be set.');
        }
        if (empty($this->getDestinations())) {
            throw new DestinationException('Destination must be set.');
        }
    }
}
