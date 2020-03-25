<?php

namespace DH\NavigationBundle\Contract\Routing;

use DateTime;

interface RoutingQueryInterface
{
    public function getDepartureTime(): ?DateTime;

    /**
     * @param DateTime $departure_time timestamp
     *
     * @return RoutingQueryInterface
     */
    public function setDepartureTime(DateTime $departure_time): self;

    public function getArrivalTime(): ?DateTime;

    /**
     * @param DateTime $arrival_time timestamp
     *
     * @return RoutingQueryInterface
     */
    public function setArrivalTime(DateTime $arrival_time): self;

    /**
     * @return RoutingQueryInterface
     */
    public function setLanguage(string $language): self;

    public function getLanguage(): string;

    /**
     * @return RoutingQueryInterface
     */
    public function addWaypoint(string $waypoint): self;

    public function getWaypoints(): array;

    public function execute(): RoutingResponseInterface;
}
