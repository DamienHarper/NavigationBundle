<?php

namespace DH\NavigationBundle\Contract\Routing;

use DateTime;
use DH\NavigationBundle\Exception\InvalidArgumentException;
use DH\NavigationBundle\Exception\ResponseException;
use DH\NavigationBundle\Exception\WaypointException;
use DH\NavigationBundle\Provider\ProviderInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractRoutingQuery implements RoutingQueryInterface
{
    /**
     * @var ProviderInterface
     */
    private $provider;

    /**
     * @var array
     */
    protected $waypoints;

    /**
     * @var DateTime|null
     */
    private $departure_time;

    /**
     * @var DateTime|null
     */
    private $arrival_time;

    /**
     * @var string|null
     */
    private $language;

    /**
     * RoutingQuery constructor.
     */
    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
        $this->waypoints = [];
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
    public function setDepartureTime(DateTime $departureTime): RoutingQueryInterface
    {
        $this->departure_time = $departureTime;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getArrivalTime(): ?DateTime
    {
        return $this->arrival_time;
    }

    /**
     * {@inheritdoc}
     */
    public function setArrivalTime(DateTime $arrivalTime): RoutingQueryInterface
    {
        $this->arrival_time = $arrivalTime;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setLanguage(string $language): RoutingQueryInterface
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
     * {@inheritdoc}
     */
    public function addWaypoint(string $waypoint): RoutingQueryInterface
    {
        $this->waypoints[] = $waypoint;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getWaypoints(): array
    {
        return $this->waypoints;
    }

    /**
     * @throws ResponseException
     * @throws WaypointException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws InvalidArgumentException
     *
     * {@inheritdoc}
     */
    public function execute(): RoutingResponseInterface
    {
        $this->validateRequest();
        $request = $this->buildRequest();
        $rawResponse = $this->request('GET', $request);
        $response = $this->buildResponse($rawResponse);
        $this->validateResponse($response);

        return $response;
    }

    abstract protected function buildRequest(): string;

    abstract protected function buildResponse(ResponseInterface $response): RoutingResponseInterface;

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
    private function validateResponse(RoutingResponseInterface $response): void
    {
        switch ($response->getStatus()) {
            case RoutingResponseInterface::RESPONSE_STATUS_OK:
                break;
            case RoutingResponseInterface::RESPONSE_STATUS_INVALID_REQUEST:
                throw new ResponseException('Invalid request.', 1);
                break;
            case RoutingResponseInterface::RESPONSE_STATUS_MAX_ELEMENTS_EXCEEDED:
                throw new ResponseException('The product of the origin and destination exceeds the limit per request.', 2);
                break;
            case RoutingResponseInterface::RESPONSE_STATUS_OVER_QUERY_LIMIT:
                throw new ResponseException('The service has received too many requests from your application in the allowed time range.', 3);
                break;
            case RoutingResponseInterface::RESPONSE_STATUS_REQUEST_DENIED:
                throw new ResponseException('The service denied the use of the Distance Matrix API service by your application.', 4);
                break;
            case RoutingResponseInterface::RESPONSE_STATUS_UNKNOWN_ERROR:
                throw new ResponseException('Unknown error.', 5);
                break;
            default:
                throw new ResponseException(sprintf('Unknown status code: %s', $response->getStatus()), 6);
                break;
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws WaypointException
     */
    private function validateRequest(): void
    {
        if (empty($this->getWaypoints()) || \count($this->getWaypoints()) < 2) {
            throw new WaypointException('At least two waypoints must be set.');
        }

        if (null !== $this->getDepartureTime() && null !== $this->getArrivalTime()) {
            throw new InvalidArgumentException('departure_time and arrival_time cannot be both specified at the same time.');
        }
    }
}
