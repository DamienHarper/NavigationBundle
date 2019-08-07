<?php

namespace DH\NavigationBundle\Contract\DistanceMatrix;

use DH\NavigationBundle\Exception\DestinationException;
use DH\NavigationBundle\Exception\OriginException;
use DH\NavigationBundle\Exception\ResponseException;
use DH\NavigationBundle\Provider\ProviderInterface;
use GuzzleHttp\Client;
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
     * @var \DateTime
     */
    private $departure_time;

    /**
     * DistanceMatrixQuery constructor.
     *
     * @param ProviderInterface $provider
     */
    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function getProvider(): ProviderInterface
    {
        return $this->provider;
    }

    /**
     * @return \DateTime
     */
    public function getDepartureTime(): \DateTime
    {
        return $this->departure_time;
    }

    /**
     * @param \DateTime $departureTime
     *
     * @return DistanceMatrixQueryInterface
     */
    public function setDepartureTime(\DateTime $departureTime): DistanceMatrixQueryInterface
    {
        $this->departure_time = $departureTime;

        return $this;
    }

    /**
     * @param string $origin
     *
     * @return DistanceMatrixQueryInterface
     */
    public function addOrigin($origin): DistanceMatrixQueryInterface
    {
        $this->origins[] = $origin;

        return $this;
    }

    /**
     * @return array
     */
    public function getOrigins(): array
    {
        return $this->origins;
    }

    /**
     * @param string $destination
     *
     * @return DistanceMatrixQueryInterface
     */
    public function addDestination($destination): DistanceMatrixQueryInterface
    {
        $this->destinations[] = $destination;

        return $this;
    }

    /**
     * @return array
     */
    public function getDestinations(): array
    {
        return $this->destinations;
    }

    /**
     * @return DistanceMatrixResponseInterface
     *
     * @throws DestinationException
     * @throws OriginException
     * @throws ResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function execute(): DistanceMatrixResponseInterface
    {
        $this->validateRequest();
        $request = $this->buildRequest();
        $rawResponse = $this->request('GET', $request);
        $response = $this->processRawResponse($rawResponse);
        $this->validateResponse($response);

        return $response;
    }

    /**
     * @return string
     */
    abstract protected function buildRequest(): string;

    /**
     * @param ResponseInterface $response
     *
     * @return DistanceMatrixResponseInterface
     */
    abstract protected function processRawResponse(ResponseInterface $response): DistanceMatrixResponseInterface;

    /**
     * @param string $method
     * @param string $url
     *
     * @return ResponseInterface
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function request(string $method, string $url): ResponseInterface
    {
        $client = new Client();
        $response = $client->request($method, $url);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception('Response with status code '.$response->getStatusCode());
        }

        return $response;
    }

    /**
     * @param DistanceMatrixResponseInterface $response
     *
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