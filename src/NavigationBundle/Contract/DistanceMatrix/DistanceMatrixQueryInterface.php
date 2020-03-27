<?php

namespace DH\NavigationBundle\Contract\DistanceMatrix;

use DateTime;

interface DistanceMatrixQueryInterface
{
    public function getDepartureTime(): ?DateTime;

    /**
     * @param DateTime $departure_time timestamp
     *
     * @return DistanceMatrixQueryInterface
     */
    public function setDepartureTime(DateTime $departure_time): self;

    /**
     * @param string $origin
     *
     * @return DistanceMatrixQueryInterface
     */
    public function addOrigin($origin): self;

    public function getOrigins(): array;

    /**
     * @param string $destination
     *
     * @return DistanceMatrixQueryInterface
     */
    public function addDestination($destination): self;

    public function getDestinations(): array;

    /**
     * @return DistanceMatrixQueryInterface
     */
    public function setLanguage(string $language): self;

    public function getLanguage(): string;

    public function execute(): DistanceMatrixResponseInterface;
}
