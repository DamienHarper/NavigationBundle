<?php

namespace DH\NavigationBundle\Provider\Here\Routing;

use DH\NavigationBundle\Contract\Routing\AbstractRoutingQuery;
use DH\NavigationBundle\Contract\Routing\RoutingQueryInterface;
use DH\NavigationBundle\Contract\Routing\RoutingResponseInterface;
use Psr\Http\Message\ResponseInterface;

class RoutingQuery extends AbstractRoutingQuery
{
    /**
     * fastest | shortest | balanced.
     *
     * @var string
     */
    private $routingMode;

    /**
     * car | pedestrian | carHOV | publicTransport | publicTransportTimeTable | truck | bicycle.
     *
     * @var string
     */
    private $transportMode;

    /**
     * enabled | disabled.
     *
     * @var string
     */
    private $trafficMode;

    /**
     * @var string
     */
    private $avoid;

    /**
     * URL for API.
     */
    public const ENDPOINT_URL = 'https://route.api.here.com/routing/7.2/calculateroute.json';

    /**
     * URL for API (CIT).
     */
    public const CIT_ENDPOINT_URL = 'https://route.cit.api.here.com/routing/7.2/calculateroute.json';

    public const TRANSPORT_MODE_CAR = 'car';
    public const TRANSPORT_MODE_CAR_HOV = 'carHOV';
    public const TRANSPORT_MODE_PEDESTRIAN = 'pedestrian';
    public const TRANSPORT_MODE_BICYCLE = 'bicycle';
    public const TRANSPORT_MODE_TRUCK = 'truck';

    public const ROUTING_MODE_FASTEST = 'fastest';
    public const ROUTING_MODE_SHORTEST = 'shortest';
    public const ROUTING_MODE_BALANCED = 'balanced';

    public const TRAFFIC_MODE_ENABLED = 'enabled';
    public const TRAFFIC_MODE_DISABLED = 'disabled';
    public const TRAFFIC_MODE_DEFAULT = 'default';

    /**
     * @param string $mode
     */
    public function setRoutingMode($mode = self::ROUTING_MODE_FASTEST): RoutingQueryInterface
    {
        $this->routingMode = $mode;

        return $this;
    }

    public function getRoutingMode(): string
    {
        return $this->routingMode ?? self::ROUTING_MODE_FASTEST;
    }

    /**
     * @param string $mode
     */
    public function setTransportMode($mode = self::TRANSPORT_MODE_CAR): RoutingQueryInterface
    {
        $this->transportMode = $mode;

        return $this;
    }

    public function getTransportMode(): string
    {
        return $this->transportMode ?? self::TRANSPORT_MODE_CAR;
    }

    /**
     * @param string $mode
     */
    public function setTrafficMode($mode = self::TRAFFIC_MODE_DEFAULT): RoutingQueryInterface
    {
        $this->trafficMode = $mode;

        return $this;
    }

    public function getTrafficMode(): string
    {
        return $this->trafficMode ?? self::TRAFFIC_MODE_DEFAULT;
    }

    /**
     * @param string $avoid (for more values use | as separator)
     */
    public function setAvoid(string $avoid): RoutingQueryInterface
    {
        $this->avoid = $avoid;

        return $this;
    }

    public function getAvoid(): string
    {
        return $this->avoid;
    }

    /**
     * @see https://developer.here.com/documentation/routing/topics/resource-calculate-route.html
     *
     * {@inheritdoc}
     */
    protected function buildRequest(): string
    {
        $data = array_merge(
            $this->getProvider()->getCredentials(),
            [
                'language' => $this->getLanguage(),
                'avoid' => $this->avoid,
                'mode' => $this->getRoutingMode().';'.$this->getTransportMode().';traffic:'.$this->getTrafficMode(),
            ]
        );

        if (null !== $this->getArrivalTime()) {
            $data['arrival'] = $this->getArrivalTime()
                ->format('Y-m-d\TH:i:s')
            ;
        } elseif (null !== $this->getDepartureTime()) {
            $data['departure'] = $this->getDepartureTime()
                ->format('Y-m-d\TH:i:s')
            ;
        } else {
            $data['departure'] = 'now';
        }

        foreach ($this->waypoints as $i => $value) {
            $data['waypoint'.$i] = str_replace(' ', '', $value);
        }

        $data = array_filter($data, static function ($value) {
            return null !== $value;
        });

        $parameters = [];
        foreach ($data as $key => $value) {
            $parameters[] = $key.'='.$value;
        }
        $parameters = implode('&', $parameters);

        return ($this->getProvider()->isCitEnabled() ? self::CIT_ENDPOINT_URL : self::ENDPOINT_URL).'?'.$parameters;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildResponse(ResponseInterface $response): RoutingResponseInterface
    {
        return new RoutingResponse($response);
    }
}
