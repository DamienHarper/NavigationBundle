<?php

namespace DH\NavigationBundle\Contract\Routing;

interface RoutingQueryInterface
{
    /**
     * @return ?\DateTime
     */
    public function getDepartureTime(): ?\DateTime;

    /**
     * @param \DateTime $departure_time timestamp
     *
     * @return RoutingQueryInterface
     */
    public function setDepartureTime(\DateTime $departure_time): self;

    /**
     * @return ?\DateTime
     */
    public function getArrivalTime(): ?\DateTime;

    /**
     * @param \DateTime $arrival_time timestamp
     *
     * @return RoutingQueryInterface
     */
    public function setArrivalTime(\DateTime $arrival_time): self;

    /**
     * @param string $language
     *
     * @return RoutingQueryInterface
     */
    public function setLanguage(string $language): self;

    /**
     * @return string
     */
    public function getLanguage(): string;

    /**
     * @param string $waypoint
     * @param mixed  $origin
     *
     * @return RoutingQueryInterface
     */
    public function addWaypoint($origin): self;

    /**
     * @return ?array
     */
    public function getWaypoints(): ?array;

    /**
     * @return RoutingResponseInterface
     */
    public function execute(): RoutingResponseInterface;
}
