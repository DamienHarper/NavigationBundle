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
    const ENDPOINT_URL = 'https://route.api.here.com/routing/7.2/calculateroute.json';

    /**
     * URL for API (CIT).
     */
    const CIT_ENDPOINT_URL = 'https://route.cit.api.here.com/routing/7.2/calculateroute.json';

    const TRANSPORT_MODE_CAR = 'car';
    const TRANSPORT_MODE_CAR_HOV = 'carHOV';
    const TRANSPORT_MODE_PEDESTRIAN = 'pedestrian';
    const TRANSPORT_MODE_BICYCLE = 'bicycle';
    const TRANSPORT_MODE_TRUCK = 'truck';

    const ROUTING_MODE_FASTEST = 'fastest';
    const ROUTING_MODE_SHORTEST = 'shortest';
    const ROUTING_MODE_BALANCED = 'balanced';

    const TRAFFIC_MODE_ENABLED = 'enabled';
    const TRAFFIC_MODE_DISABLED = 'disabled';
    const TRAFFIC_MODE_DEFAULT = 'default';

    /**
     * @param string $mode
     *
     * @return RoutingQueryInterface
     */
    public function setRoutingMode($mode = self::ROUTING_MODE_FASTEST): RoutingQueryInterface
    {
        $this->routingMode = $mode;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoutingMode(): string
    {
        return $this->routingMode ?? self::ROUTING_MODE_FASTEST;
    }

    /**
     * @param string $mode
     *
     * @return RoutingQueryInterface
     */
    public function setTransportMode($mode = self::TRANSPORT_MODE_CAR): RoutingQueryInterface
    {
        $this->transportMode = $mode;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransportMode(): string
    {
        return $this->transportMode ?? self::TRANSPORT_MODE_CAR;
    }

    /**
     * @param string $mode
     *
     * @return RoutingQueryInterface
     */
    public function setTrafficMode($mode = self::TRAFFIC_MODE_DEFAULT): RoutingQueryInterface
    {
        $this->trafficMode = $mode;

        return $this;
    }

    /**
     * @return string
     */
    public function getTrafficMode(): string
    {
        return $this->trafficMode ?? self::TRAFFIC_MODE_DEFAULT;
    }

    /**
     * @param string $avoid (for more values use | as separator)
     *
     * @return RoutingQueryInterface
     */
    public function setAvoid(string $avoid): RoutingQueryInterface
    {
        $this->avoid = $avoid;

        return $this;
    }

    /**
     * @return string
     */
    public function getAvoid(): string
    {
        return $this->avoid;
    }

    /**
     * @see https://developer.here.com/documentation/routing/topics/resource-calculate-route.html
     *
     * @return string
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

        $count = \count($this->waypoints);
        for ($i = 0; $i < $count; ++$i) {
            $data['waypoint'.$i] = str_replace(' ', '', $this->waypoints[$i]);
        }

        $data = array_filter($data, static function ($value) {
            return null !== $value;
        });

        $parameters = [];
        foreach ($data as $key => $value) {
            $parameters[] = $key.'='.$value;
        }
        $parameters = implode('&', $parameters);
        $url = ($this->getProvider()->isCitEnabled() ? self::CIT_ENDPOINT_URL : self::ENDPOINT_URL).'?'.$parameters;

        return $url;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return RoutingResponseInterface
     */
    protected function buildResponse(ResponseInterface $response): RoutingResponseInterface
    {
        return new RoutingResponse($response);
    }
}
