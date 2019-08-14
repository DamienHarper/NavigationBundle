<?php

namespace DH\NavigationBundle\Contract\DistanceMatrix;

interface DistanceMatrixQueryInterface
{
    /**
     * @return ?\DateTime
     */
    public function getDepartureTime(): ?\DateTime;

    /**
     * @param \DateTime $departure_time timestamp
     *
     * @return DistanceMatrixQueryInterface
     */
    public function setDepartureTime(\DateTime $departure_time): self;

    /**
     * @param string $origin
     *
     * @return DistanceMatrixQueryInterface
     */
    public function addOrigin($origin): self;

    /**
     * @return ?array
     */
    public function getOrigins(): ?array;

    /**
     * @param string $destination
     *
     * @return DistanceMatrixQueryInterface
     */
    public function addDestination($destination): self;

    /**
     * @return ?array
     */
    public function getDestinations(): ?array;

    /**
     * @param string $language
     *
     * @return DistanceMatrixQueryInterface
     */
    public function setLanguage(string $language): self;

    /**
     * @return string
     */
    public function getLanguage(): string;

    /**
     * @return DistanceMatrixResponseInterface
     */
    public function execute(): DistanceMatrixResponseInterface;
}
