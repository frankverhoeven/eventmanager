<?php

declare(strict_types=1);

namespace FrankVerhoeven\EventManagerTest;

use FrankVerhoeven\EventManager\EventManagerInitializer;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

/**
 * @author Frank Verhoeven <hi@frankverhoeven.me>
 */
final class EventManagerInitializerTest extends TestCase
{
    public function testSetsEventManagerForEventManagerAwareInstances(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::once())
            ->method('get')
            ->with(EventManagerInterface::class)
            ->willReturn($eventManager = $this->createMock(EventManagerInterface::class));

        $instance = $this->createMock(EventManagerAwareInterface::class);
        $instance->expects(self::once())
            ->method('setEventManager')
            ->with($eventManager);

        (new EventManagerInitializer())($container, $instance);
    }

    public function testDoesNotSetEventManagerForNonEventManagerAwareInstances(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::never())
            ->method('get');

        $instance = $this->createMock(\stdClass::class);

        (new EventManagerInitializer())($container, $instance);
    }
}
