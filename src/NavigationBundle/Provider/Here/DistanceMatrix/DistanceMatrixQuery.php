<?php

namespace DH\NavigationBundle\Provider\Here\DistanceMatrix;

use DH\NavigationBundle\Contract\DistanceMatrix\AbstractDistanceMatrixQuery;
use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixQueryInterface;
use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixResponseInterface;
use Psr\Http\Message\ResponseInterface;

class DistanceMatrixQuery extends AbstractDistanceMatrixQuery
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
    public const ENDPOINT_URL = 'https://matrix.route.api.here.com/routing/7.2/calculatematrix.json';

    /**
     * URL for API (CIT).
     */
    public const CIT_ENDPOINT_URL = 'https://matrix.route.cit.api.here.com/routing/7.2/calculatematrix.json';

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
     *
     * @return DistanceMatrixQueryInterface
     */
    public function setRoutingMode($mode = self::ROUTING_MODE_FASTEST): DistanceMatrixQueryInterface
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
     * @return DistanceMatrixQueryInterface
     */
    public function setTransportMode($mode = self::TRANSPORT_MODE_CAR): DistanceMatrixQueryInterface
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
     * @return DistanceMatrixQueryInterface
     */
    public function setTrafficMode($mode = self::TRAFFIC_MODE_DEFAULT): DistanceMatrixQueryInterface
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
     * @return DistanceMatrixQueryInterface
     */
    public function setAvoid(string $avoid): DistanceMatrixQueryInterface
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
     * @see https://developer.here.com/documentation/routing/topics/resource-calculate-matrix.html
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
                'summaryAttributes' => 'traveltime,distance,costfactor',
            ]
        );

        if (null !== $this->getDepartureTime()) {
            $data['departure'] = $this->getDepartureTime()
                ->format('Y-m-d\TH:i:s')
            ;
        } else {
            $data['departure'] = 'now';
        }

        $count = \count($this->origins);
        for ($i = 0; $i < $count; ++$i) {
            $data['start'.$i] = str_replace(' ', '', $this->origins[$i]);
        }

        $count = \count($this->destinations);
        for ($i = 0; $i < $count; ++$i) {
            $data['destination'.$i] = str_replace(' ', '', $this->destinations[$i]);
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
     * @return DistanceMatrixResponseInterface
     */
    protected function buildResponse(ResponseInterface $response): DistanceMatrixResponseInterface
    {
        return new DistanceMatrixResponse($response, $this->getOrigins(), $this->getDestinations());
    }
}
