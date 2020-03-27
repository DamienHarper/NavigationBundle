<?php

namespace DH\NavigationBundle\Provider\GoogleMaps\DistanceMatrix;

use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixResponseInterface;
use DH\NavigationBundle\Model\Address;
use DH\NavigationBundle\Model\Distance;
use DH\NavigationBundle\Model\DistanceMatrix\Element;
use DH\NavigationBundle\Model\DistanceMatrix\Row;
use DH\NavigationBundle\Model\Duration;
use Psr\Http\Message\ResponseInterface;
use stdClass;

class DistanceMatrixResponse implements DistanceMatrixResponseInterface
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var stdClass
     */
    private $responseObject;

    /**
     * @var Address[]
     */
    private $originAddresses;

    /**
     * @var Address[]
     */
    private $destinationAddresses;

    /**
     * @var Row[]
     */
    private $rows;

    public function __construct(ResponseInterface $response)
    {
        $responseObject = json_decode($response->getBody()->getContents());
        $this->responseObject = $responseObject;
        $this->originAddresses = [];
        $this->destinationAddresses = [];
        $this->rows = [];
        $this->initialize();
    }

    private function addOriginAddress(Address $originAddress): void
    {
        $this->originAddresses[] = $originAddress;
    }

    private function addDestinationAddress(Address $destinationAddress): void
    {
        $this->destinationAddresses[] = $destinationAddress;
    }

    private function addRow(Row $row): void
    {
        $this->rows[] = $row;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseObject(): stdClass
    {
        return $this->responseObject;
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginAddresses(): array
    {
        return $this->originAddresses;
    }

    /**
     * {@inheritdoc}
     */
    public function getDestinationAddresses(): array
    {
        return $this->destinationAddresses;
    }

    /**
     * {@inheritdoc}
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * @throws \Exception
     */
    private function initialize(): void
    {
        $this->status = $this->responseObject->status;

        foreach ($this->responseObject->origin_addresses as $originAddress) {
            $this->addOriginAddress(new Address($originAddress));
        }

        foreach ($this->responseObject->destination_addresses as $destinationAddress) {
            $this->addDestinationAddress(new Address($destinationAddress));
        }

        foreach ($this->responseObject->rows as $row) {
            $elements = [];
            foreach ($row->elements as $element) {
                if (property_exists($element, 'status') && Element::STATUS_OK !== $element->status) {
                    $status = $element->status;
                    $distance = null;
                    $duration = null;
                } else {
                    $status = 'OK';
                    $distance = new Distance((int) $element->distance->value);
                    $duration = new Duration((int) $element->duration->value);
                }

                $elements[] = new Element($status, $duration, $distance);
            }
            $this->addRow(new Row($elements));
        }
    }
}
