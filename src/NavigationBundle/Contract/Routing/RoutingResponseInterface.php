<?php

namespace DH\NavigationBundle\Contract\Routing;

use stdClass;

interface RoutingResponseInterface
{
    public const RESPONSE_STATUS_MAX_ELEMENTS_EXCEEDED = 'MAX_ELEMENTS_EXCEEDED';
    public const RESPONSE_STATUS_OK = 'OK';
    public const RESPONSE_STATUS_INVALID_REQUEST = 'INVALID_REQUEST';
    public const RESPONSE_STATUS_REQUEST_DENIED = 'REQUEST_DENIED';
    public const RESPONSE_STATUS_UNKNOWN_ERROR = 'UNKNOWN_ERROR';
    public const RESPONSE_STATUS_OVER_QUERY_LIMIT = 'OVER_QUERY_LIMIT';

    public function getStatus(): string;

    public function getResponseObject(): stdClass;

    public function getRoutes(): array;
}
