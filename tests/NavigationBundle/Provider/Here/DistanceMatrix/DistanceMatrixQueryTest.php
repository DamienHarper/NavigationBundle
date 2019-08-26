<?php

namespace DH\NavigationBundle\Tests\Provider\Here\DistanceMatrix;

use DH\DoctrineAuditBundle\Tests\BaseTest;
use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixResponseInterface;
use DH\NavigationBundle\Exception\DestinationException;
use DH\NavigationBundle\Exception\OriginException;
use DH\NavigationBundle\Model\DistanceMatrix\Element;

/**
 * @covers \DH\NavigationBundle\Contract\DistanceMatrix\AbstractDistanceMatrixQuery
 * @covers \DH\NavigationBundle\DependencyInjection\Compiler\AddProvidersPass
 * @covers \DH\NavigationBundle\DependencyInjection\Compiler\FactoryValidatorPass
 * @covers \DH\NavigationBundle\DependencyInjection\Configuration
 * @covers \DH\NavigationBundle\DependencyInjection\DHNavigationExtension
 * @covers \DH\NavigationBundle\DHNavigationBundle
 * @covers \DH\NavigationBundle\Exception\ProviderNotRegistered
 * @covers \DH\NavigationBundle\Helper\FormatHelper
 * @covers \DH\NavigationBundle\Model\Address
 * @covers \DH\NavigationBundle\Model\Distance
 * @covers \DH\NavigationBundle\Model\Duration
 * @covers \DH\NavigationBundle\Model\DistanceMatrix\Element
 * @covers \DH\NavigationBundle\Model\DistanceMatrix\Row
 * @covers \DH\NavigationBundle\NavigationManager
 * @covers \DH\NavigationBundle\Provider\AbstractFactory
 * @covers \DH\NavigationBundle\Provider\AbstractProvider
 * @covers \DH\NavigationBundle\Provider\GoogleMaps\GoogleMaps
 * @covers \DH\NavigationBundle\Provider\GoogleMaps\GoogleMapsFactory
 * @covers \DH\NavigationBundle\Provider\Here\DistanceMatrix\DistanceMatrixQuery
 * @covers \DH\NavigationBundle\Provider\Here\DistanceMatrix\DistanceMatrixResponse
 * @covers \DH\NavigationBundle\Provider\Here\Here
 * @covers \DH\NavigationBundle\Provider\Here\HereFactory
 * @covers \DH\NavigationBundle\Provider\ProviderAggregator
 */
class DistanceMatrixQueryTest extends BaseTest
{
    protected function checkCredentials(): void
    {
        if (!isset($_ENV['HERE_APP_ID'], $_ENV['HERE_APP_CODE'])) {
            $this->markTestSkipped('You need to configure the HERE_APP_ID and HERE_APP_CODE value in phpunit.xml');
        }
    }

    public function testDefaultLanguage(): void
    {
        $this->checkCredentials();

        $query = $this->manager
            ->using('here')
            ->createDistanceMatrixQuery()
        ;

        $this->assertSame('en-US', $query->getLanguage());
    }

    public function testCustomLanguage(): void
    {
        $this->checkCredentials();

        $query = $this->manager
            ->using('here')
            ->createDistanceMatrixQuery()
            ->setLanguage('fr-FR')
        ;

        $this->assertSame('fr-FR', $query->getLanguage());
    }

    public function testExecuteWithoutOrigin(): void
    {
        $this->checkCredentials();

        $this->expectException(OriginException::class);

        $query = $this->manager
            ->using('here')
            ->createDistanceMatrixQuery()
        ;
        $response = $query->execute();
    }

    /**
     * @depends testExecuteWithoutOrigin
     */
    public function testExecuteWithoutDestination(): void
    {
        $this->checkCredentials();

        $this->expectException(DestinationException::class);

        $query = $this->manager
            ->using('here')
            ->createDistanceMatrixQuery()
        ;
        $response = $query
            ->addOrigin('45.834278,1.260816')
            ->execute()
        ;
    }

    /**
     * @depends testExecuteWithoutDestination
     */
    public function testExecute(): void
    {
        $this->checkCredentials();

        $query = $this->manager
            ->using('here')
            ->createDistanceMatrixQuery()
        ;
        $response = $query
            ->addOrigin('45.834278,1.260816')
            ->addOrigin('46.110605,1.370078')
            ->addDestination('44.830109,-0.603649')
            ->addDestination('45.835475,1.242453')
            ->execute()
        ;

        $this->assertInstanceOf(DistanceMatrixResponseInterface::class, $response);

        $rows = $response->getRows();
        $this->assertCount(2, $rows);

        foreach ($rows as $row) {
            $elements = $row->getElements();
            $this->assertCount(2, $elements);
            foreach ($elements as $element) {
                $this->assertSame(Element::STATUS_OK, $element->getStatus());
            }
        }

        $this->assertCount(2, $response->getOriginAddresses());
        $this->assertCount(2, $response->getDestinationAddresses());
    }

    /**
     * @depends testExecuteWithoutDestination
     */
    public function testExecuteWithUnreachableWaypoints(): void
    {
        $this->checkCredentials();

        $query = $this->manager
            ->using('here')
            ->createDistanceMatrixQuery()
        ;
        $response = $query
            ->addOrigin('43.483112,-1.482307')
            ->addOrigin('45.834278,1.260816')
            ->addDestination('-3.853220,-41.595480')
            ->addDestination('44.830109,-0.603649')
            ->execute()
        ;

        $this->assertInstanceOf(DistanceMatrixResponseInterface::class, $response);

        $rows = $response->getRows();
        $this->assertCount(2, $rows);

        $row = $rows[0];
        $elements = $row->getElements();
        $this->assertCount(2, $elements);

        $element = $elements[0];
        $this->assertSame(Element::STATUS_FAILED, $element->getStatus());
        $element = $elements[1];
        $this->assertSame(Element::STATUS_OK, $element->getStatus());

        $row = $rows[1];
        $elements = $row->getElements();
        $this->assertCount(2, $elements);

        $element = $elements[0];
        $this->assertSame(Element::STATUS_FAILED, $element->getStatus());
        $element = $elements[1];
        $this->assertSame(Element::STATUS_OK, $element->getStatus());
    }
}
