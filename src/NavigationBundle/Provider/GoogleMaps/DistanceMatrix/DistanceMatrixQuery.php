<?php

namespace DH\NavigationBundle\Provider\GoogleMaps\DistanceMatrix;

use DH\NavigationBundle\Contract\DistanceMatrix\AbstractDistanceMatrixQuery;
use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixResponseInterface;
use Psr\Http\Message\ResponseInterface;

class DistanceMatrixQuery extends AbstractDistanceMatrixQuery
{
    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $units;

    /**
     * @var string
     */
    private $mode;

    /**
     * @var string
     */
    private $avoid;

    /**
     * @var \DateTime
     */
    private $arrival_time;

    /**
     * @var string
     */
    private $traffic_model;

    /**
     * @var array
     */
    private $transit_modes;

    /**
     * @var string
     */
    private $transit_routing_preference;

    /**
     * URL for API.
     */
    const ENDPOINT_URL = 'https://maps.googleapis.com/maps/api/distancematrix/json';

    const MODE_DRIVING = 'driving';
    const MODE_WALKING = 'walking';
    const MODE_BICYCLING = 'bicycling';
    const MODE_TRANSIT = 'transit';

    const UNITS_METRIC = 'metric';
    const UNITS_IMPERIAL = 'imperial';

    const AVOID_TOLLS = 'tolls';
    const AVOID_HIGHWAYS = 'highways';
    const AVOID_FERRIES = 'ferries';
    const AVOID_INDOOR = 'indoor';

    const TRAFFIC_MODE_BEST_GUESS = 'best_guess';
    const TRAFFIC_MODE_PESSIMISTIC = 'pessimistic';
    const TRAFFIC_MODE_OPTIMISTIC = 'optimistic';

    const TRANSIT_MODE_BUS = 'bus';
    const TRANSIT_MODE_SUBWAY = 'subway';
    const TRANSIT_MODE_TRAIN = 'train';
    const TRANSIT_MODE_TRAM = 'tram';
    const TRANSIT_MODE_RAIL = 'rail';

    const ROUTING_LESS_WALKING = 'less_walking';
    const ROUTING_FEWER_TRANSFERS = 'fewer_transfers';

    /**
     * @return string
     */
    public function getTransitRoutingPreference(): string
    {
        return $this->transit_routing_preference;
    }

    /**
     * @param string $transit_routing_preference
     *
     * @return DistanceMatrixQuery
     */
    public function setTransitRoutingPreference(string $transit_routing_preference): self
    {
        $this->transit_routing_preference = $transit_routing_preference;

        return $this;
    }

    /**
     * @return array
     */
    public function getTransitModes(): array
    {
        return $this->transit_modes;
    }

    /**
     * @param array $transit_modes
     *
     * @return DistanceMatrixQuery
     */
    public function setTransitModes($transit_modes): self
    {
        $this->transit_modes = [$transit_modes];

        return $this;
    }

    /**
     * @param $transit_mode
     *
     * @return DistanceMatrixQuery
     */
    public function addTransitMode($transit_mode): self
    {
        $this->transit_modes[] = $transit_mode;

        return $this;
    }

    /**
     * @return string
     */
    public function getTrafficModel(): string
    {
        return $this->traffic_model;
    }

    /**
     * @param string $traffic_model
     *
     * @return DistanceMatrixQuery
     */
    public function setTrafficModel(string $traffic_model = self::TRAFFIC_MODE_BEST_GUESS): self
    {
        $this->traffic_model = $traffic_model;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getArrivalTime(): \DateTime
    {
        return $this->arrival_time;
    }

    /**
     * @param \DateTime $arrival_time
     *
     * @return DistanceMatrixQuery
     */
    public function setArrivalTime(\DateTime $arrival_time): self
    {
        $this->arrival_time = $arrival_time;

        return $this;
    }

    /**
     * @param string $language
     *
     * @return DistanceMatrixQuery
     */
    public function setLanguage($language = 'en-US'): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $units
     *
     * @return DistanceMatrixQuery
     */
    public function setUnits($units = self::UNITS_METRIC): self
    {
        $this->units = $units;

        return $this;
    }

    /**
     * @return string
     */
    public function getUnits(): string
    {
        return $this->units;
    }

    /**
     * @param string $mode
     *
     * @return DistanceMatrixQuery
     */
    public function setMode($mode = self::MODE_DRIVING): self
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @param string $avoid (for more values use | as separator)
     *
     * @return DistanceMatrixQuery
     */
    public function setAvoid(string $avoid): self
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
     * @see https://developers.google.com/maps/documentation/distance-matrix/intro
     *
     * @return ResponseInterface
     */
    protected function buildRequest(): string
    {
        $data = array_merge(
            $this->getProvider()->getCredentials(),
            [
                'language' => $this->language,
                'origins' => \count($this->origins) > 1 ? implode('|', $this->origins) : $this->origins[0],
                'destinations' => \count($this->destinations) > 1 ? implode('|', $this->destinations) : $this->destinations[0],
                'mode' => $this->mode,
                'avoid' => $this->avoid,
                'units' => $this->units,
                'traffic_model' => $this->traffic_model,
                'transit_mode' => $this->transit_modes ? implode('|', $this->transit_modes) : ($this->transit_modes[0] ?? null),
                'transit_routing_preference' => $this->transit_routing_preference,
            ]
        );

        if (null !== $this->arrival_time) {
            $data['arrival_time'] = $this->arrival_time
                ->format('Y-m-d\TH:i:s')
            ;
        }

        if (null !== $this->getDepartureTime()) {
            $data['departure_time'] = $this->getDepartureTime()
                ->format('Y-m-d\TH:i:s')
            ;
        }

        $data = array_filter($data, function ($value) {
            return null !== $value;
        });

        $parameters = http_build_query($data);

        return self::ENDPOINT_URL.'?'.$parameters;
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
