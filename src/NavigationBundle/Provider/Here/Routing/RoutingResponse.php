<?php

namespace DH\NavigationBundle\Provider\Here\Routing;

use DH\NavigationBundle\Contract\Routing\RoutingResponseInterface;
use DH\NavigationBundle\Model\Routing\RouteSummary;
use Psr\Http\Message\ResponseInterface;

class RoutingResponse implements RoutingResponseInterface
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
     * @var RouteSummary
     */
    private $summary;

    public function __construct(ResponseInterface $response)
    {
        $responseObject = json_decode($response->getBody()->getContents());
        $this->responseObject = $responseObject;
        $this->status = $response->getReasonPhrase();

        $this->initialize();
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

    private function initialize(): void
    {
//        $startIndex = 0;
//        $elements = [];
//        foreach ($this->responseObject->response->matrixEntry as $element) {
//            if ($startIndex !== $element->startIndex) {
//                $this->addRow(new Row($elements));
//                $startIndex = $element->startIndex;
//                $elements = [];
//            }
//
//            $status = 'OK';
//            $distance = new Distance(FormatHelper::formatDistance($element->summary->distance), $element->summary->distance);
//            $duration = new Duration(FormatHelper::formatTime($element->summary->travelTime), $element->summary->travelTime);
//
//            $elements[] = new Element($status, $duration, $distance);
//        }
//        $this->addRow(new Row($elements));
    }

    public function getSummary(): int
    {
        return $this->responseObject->response->route[0]->summary;
    }
}
