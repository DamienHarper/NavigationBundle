<?php

namespace DH\NavigationBundle\Provider\Here\DistanceMatrix;

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
     * @var array|Row[]
     */
    private $rows;

    public function __construct(ResponseInterface $response, array $origins, array $destinations)
    {
        $responseObject = json_decode($response->getBody()->getContents());
        $this->responseObject = $responseObject;
        $this->originAddresses = [];
        $this->destinationAddresses = [];
        $this->rows = [];
        $this->status = $response->getReasonPhrase();

        foreach ($origins as $origin) {
            $this->addOriginAddress(new Address($origin));
        }

        foreach ($destinations as $destination) {
            $this->addDestinationAddress(new Address($destination));
        }

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
        $startIndex = 0;
        $elements = [];
        foreach ($this->responseObject->response->matrixEntry as $element) {
            if ($startIndex !== $element->startIndex) {
                $this->addRow(new Row($elements));
                $startIndex = $element->startIndex;
                $elements = [];
            }

            $status = 'OK';
            $distance = new Distance(FormatHelper::formatDistance($element->summary->distance), $element->summary->distance);
            $duration = new Duration(FormatHelper::formatTime($element->summary->travelTime), $element->summary->travelTime);

            $elements[] = new Element($status, $duration, $distance);
        }
        $this->addRow(new Row($elements));
    }
}
