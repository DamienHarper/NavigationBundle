<?php

namespace DH\NavigationBundle\Provider\GoogleMaps\DistanceMatrix;

use DateTime;
use DH\NavigationBundle\Contract\DistanceMatrix\AbstractDistanceMatrixQuery;
use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixResponseInterface;
use Psr\Http\Message\ResponseInterface;

class DistanceMatrixQuery extends AbstractDistanceMatrixQuery
{
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
     * @var DateTime
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
    public const ENDPOINT_URL = 'https://maps.googleapis.com/maps/api/distancematrix/json';

    public const MODE_DRIVING = 'driving';
    public const MODE_WALKING = 'walking';
    public const MODE_BICYCLING = 'bicycling';
    public const MODE_TRANSIT = 'transit';

    public const UNITS_METRIC = 'metric';
    public const UNITS_IMPERIAL = 'imperial';

    public const AVOID_TOLLS = 'tolls';
    public const AVOID_HIGHWAYS = 'highways';
    public const AVOID_FERRIES = 'ferries';
    public const AVOID_INDOOR = 'indoor';

    public const TRAFFIC_MODE_BEST_GUESS = 'best_guess';
    public const TRAFFIC_MODE_PESSIMISTIC = 'pessimistic';
    public const TRAFFIC_MODE_OPTIMISTIC = 'optimistic';

    public const TRANSIT_MODE_BUS = 'bus';
    public const TRANSIT_MODE_SUBWAY = 'subway';
    public const TRANSIT_MODE_TRAIN = 'train';
    public const TRANSIT_MODE_TRAM = 'tram';
    public const TRANSIT_MODE_RAIL = 'rail';

    public const ROUTING_LESS_WALKING = 'less_walking';
    public const ROUTING_FEWER_TRANSFERS = 'fewer_transfers';

    public function getTransitRoutingPreference(): string
    {
        return $this->transit_routing_preference;
    }

    /**
     * @return DistanceMatrixQuery
     */
    public function setTransitRoutingPreference(string $transit_routing_preference): self
    {
        $this->transit_routing_preference = $transit_routing_preference;

        return $this;
    }

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

    public function getTrafficModel(): string
    {
        return $this->traffic_model;
    }

    /**
     * @return DistanceMatrixQuery
     */
    public function setTrafficModel(string $traffic_model = self::TRAFFIC_MODE_BEST_GUESS): self
    {
        $this->traffic_model = $traffic_model;

        return $this;
    }

    public function getArrivalTime(): DateTime
    {
        return $this->arrival_time;
    }

    /**
     * @return DistanceMatrixQuery
     */
    public function setArrivalTime(DateTime $arrival_time): self
    {
        $this->arrival_time = $arrival_time;

        return $this;
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

    public function getAvoid(): string
    {
        return $this->avoid;
    }

    /**
     * @see https://developers.google.com/maps/documentation/distance-matrix/intro
     *
     * {@inheritdoc}
     */
    protected function buildRequest(): string
    {
        $data = array_merge(
            $this->getProvider()->getCredentials(),
            [
                'region' => $this->getProvider()->getRegion(),
                'language' => $this->getLanguage(),
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
            $data['arrival_time'] = $this->arrival_time->getTimestamp();
        }

        if (null !== $this->getDepartureTime()) {
            $data['departure_time'] = $this->getDepartureTime()->getTimestamp();
        }

        $data = array_filter($data, static function ($value) {
            return null !== $value;
        });

        $parameters = http_build_query($data);

        return self::ENDPOINT_URL.'?'.$parameters;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildResponse(ResponseInterface $response): DistanceMatrixResponseInterface
    {
        return new DistanceMatrixResponse($response);
    }
}
