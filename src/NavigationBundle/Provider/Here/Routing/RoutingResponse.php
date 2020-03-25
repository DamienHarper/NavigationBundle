<?php

namespace DH\NavigationBundle\Provider\Here\Routing;

use DH\NavigationBundle\Contract\Routing\RoutingResponseInterface;
use DH\NavigationBundle\Model\Routing\Leg;
use DH\NavigationBundle\Model\Routing\Route;
use DH\NavigationBundle\Model\Routing\Step;
use DH\NavigationBundle\Model\Routing\Summary;
use Psr\Http\Message\ResponseInterface;
use stdClass;

class RoutingResponse implements RoutingResponseInterface
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
     * @var array
     */
    private $routes;

    public function __construct(ResponseInterface $response)
    {
        $responseObject = json_decode($response->getBody()->getContents());
        $this->status = $response->getReasonPhrase();
        $this->responseObject = $responseObject;
        $this->routes = [];

        $this->initialize();
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

    private function initialize(): void
    {
        // routes, waypoints, legs, steps
        foreach ($this->responseObject->response->route as $routeElement) {
            $legs = [];

            foreach ($routeElement->leg as $legElement) {
                $steps = [];
                foreach ($legElement->maneuver as $stepElement) {
                    $steps[] = new Step([
                        'position' => (array) $stepElement->position,
                        'instruction' => $stepElement->instruction,
                        'duration' => $stepElement->travelTime,
                        'distance' => $stepElement->length,
                    ]);
                }

                $legs[] = new Leg([
                    'start' => (array) $legElement->start->originalPosition,
                    'end' => (array) $legElement->end->originalPosition,
                    'duration' => $legElement->travelTime,
                    'distance' => $legElement->length,
                    'steps' => $steps,
                ]);
            }

            $this->routes[] = new Route([
                'legs' => $legs,
                'summary' => new Summary((array) $routeElement->summary),
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
