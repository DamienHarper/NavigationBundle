<?php

namespace DH\NavigationBundle\Provider\GoogleMaps\DistanceMatrix;

use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixResponseInterface;
use DH\NavigationBundle\Helper\FormatHelper;
use DH\NavigationBundle\Model\Address;
use DH\NavigationBundle\Model\Distance;
use DH\NavigationBundle\Model\Duration;
use DH\NavigationBundle\Model\Element;
use DH\NavigationBundle\Model\Row;
use Psr\Http\Message\ResponseInterface;

class DistanceMatrixResponse implements DistanceMatrixResponseInterface
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var \stdClass
     */
    private $responseObject;

    /**
     * @var Address[]|array
     */
    private $originAddresses;

    /**
     * @var Address[]|array
     */
    private $destinationAddresses;

    /**
     * @var Row[]|array
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
     * @return mixed
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return \stdClass
     */
    public function getResponseObject(): \stdClass
    {
        return $this->responseObject;
    }

    /**
     * @return array
     */
    public function getOriginAddresses(): array
    {
        return $this->originAddresses;
    }

    /**
     * @return array
     */
    public function getDestinationAddresses(): array
    {
        return $this->destinationAddresses;
    }

    /**
     * @return array
     */
    public function getRows(): array
    {
        return $this->rows;
    }

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
                if (0 === strcmp($element->status, Element::STATUS_ZERO_RESULTS)) { //avoid a crash when no route was found
                    //todo here happens something strange
                    $elements[] = new Element($element->status, new Duration(), new Distance());

                    continue;
                }

//                $duration = new Duration($element->duration->text, $element->duration->value);
//                $distance = new Distance($element->distance->text, $element->distance->value);
                $duration = new Duration(FormatHelper::formatTime($element->duration->value), $element->duration->value);
                $distance = new Distance(FormatHelper::formatDistance($element->distance->value), $element->distance->value);
                $elements[] = new Element($element->status, $duration, $distance);
            }
            $this->addRow(new Row($elements));
        }
    }
}
