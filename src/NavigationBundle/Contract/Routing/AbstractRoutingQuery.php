<?php

namespace DH\NavigationBundle\Contract\Routing;

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
     * @var \DateTime
     */
    private $departure_time;

    /**
     * @var \DateTime
     */
    private $arrival_time;

    /**
     * RoutingQuery constructor.
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
     * @return ?\DateTime
     */
    public function getDepartureTime(): ?\DateTime
    {
        return $this->departure_time;
    }

    /**
     * @param \DateTime $departureTime
     *
     * @return RoutingQueryInterface
     */
    public function setDepartureTime(\DateTime $departureTime): RoutingQueryInterface
    {
        $this->departure_time = $departureTime;

        return $this;
    }

    /**
     * @return ?\DateTime
     */
    public function getArrivalTime(): ?\DateTime
    {
        return $this->arrival_time;
    }

    /**
     * @param \DateTime $arrivalTime
     *
     * @return RoutingQueryInterface
     */
    public function setArrivalTime(\DateTime $arrivalTime): RoutingQueryInterface
    {
        $this->arrival_time = $arrivalTime;

        return $this;
    }

    /**
     * @param string $waypoint
     *
     * @return RoutingQueryInterface
     */
    public function addWaypoint($waypoint): RoutingQueryInterface
    {
        $this->waypoints[] = $waypoint;

        return $this;
    }

    /**
     * @return ?array
     */
    public function getWaypoints(): ?array
    {
        return $this->waypoints;
    }

    /**
     * @return RoutingResponseInterface
     * @throws OriginException
     * @throws ResponseException
     * @throws WaypointException
     * @throws \GuzzleHttp\Exception\GuzzleException
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

    /**
     * @return string
     */
    abstract protected function buildRequest(): string;

    /**
     * @param ResponseInterface $response
     *
     * @return RoutingResponseInterface
     */
    abstract protected function buildResponse(ResponseInterface $response): RoutingResponseInterface;

    /**
     * @param string $method
     * @param string $url
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return ResponseInterface
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
     * @param DistanceMatrixResponseInterface $response
     *
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
     * @throws WaypointException
     */
    private function validateRequest(): void
    {
        if (empty($this->getWaypoints()) || count($this->getWaypoints()) < 2) {
            throw new WaypointException('At least two waypoints must be set.');
        }

        if (null !== $this->getDepartureTime() && null !== $this->getArrivalTime()) {
            throw new WaypointException('departure_time and arrival_time cannot be both specified at the same time.');
        }
    }
}
