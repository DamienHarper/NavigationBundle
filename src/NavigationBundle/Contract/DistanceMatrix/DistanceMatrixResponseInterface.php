<?php

namespace DH\NavigationBundle\Contract\DistanceMatrix;

interface DistanceMatrixResponseInterface
{
    public const RESPONSE_STATUS_MAX_ELEMENTS_EXCEEDED = 'MAX_ELEMENTS_EXCEEDED';
    public const RESPONSE_STATUS_OK = 'OK';
    public const RESPONSE_STATUS_INVALID_REQUEST = 'INVALID_REQUEST';
    public const RESPONSE_STATUS_REQUEST_DENIED = 'REQUEST_DENIED';
    public const RESPONSE_STATUS_UNKNOWN_ERROR = 'UNKNOWN_ERROR';
    public const RESPONSE_STATUS_OVER_QUERY_LIMIT = 'OVER_QUERY_LIMIT';

    public const RESPONSE_STATUS = [
        self::RESPONSE_STATUS_OK,
        self::RESPONSE_STATUS_INVALID_REQUEST,
        self::RESPONSE_STATUS_MAX_ELEMENTS_EXCEEDED,
        self::RESPONSE_STATUS_OVER_QUERY_LIMIT,
        self::RESPONSE_STATUS_REQUEST_DENIED,
        self::RESPONSE_STATUS_UNKNOWN_ERROR,
    ];

    /**
     * @return mixed
     */
    public function getStatus(): string;

    /**
     * @return \stdClass
     */
    public function getResponseObject(): \stdClass;

    /**
     * @return array
     */
    public function getOriginAddresses(): array;

    /**
     * @return array
     */
    public function getDestinationAddresses(): array;

    /**
     * @return array
     */
    public function getRows(): array;
}