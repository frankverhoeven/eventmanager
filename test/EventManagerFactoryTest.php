<?php

declare(strict_types=1);

namespace FrankVerhoeven\EventManagerTest;

use FrankVerhoeven\EventManager\EventManagerFactory;
use FrankVerhoeven\EventManager\Exception\InvalidArgumentException;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\LazyListener;

/**
 * @author Frank Verhoeven <hi@frankverhoeven.me>
 */
final class EventManagerFactoryTest extends TestCase
{
    /**
     * @var EventManagerFactory
     */
    private $factory;

    /**
     * @var ContainerInterface
     */
    private $container;

    protected function setUp(): void
    {
        $this->factory = new EventManagerFactory();
        $this->container = $this->createMock(ContainerInterface::class);
    }

    public function testWillReturnEventManagerWithoutEventsIfConfigIsMissing(): void
    {
        $this->container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn([]);

        $eventManager = ($this->factory)($this->container);

        self::assertInstanceOf(EventManagerInterface::class, $eventManager);
        self::assertEquals([], $this->attachedEvents($eventManager));
    }

    public function testCantAttachListenerWithoutEvent(): void
    {
        $this->container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn(
                [
                    'eventmanager' => [
                        'listeners' => [
                            [],
                        ],
                    ],
                ]
            );

        $this->expectException(InvalidArgumentException::class);

        ($this->factory)($this->container);
    }

    public function testCantAttachListenerWithoutListener(): void
    {
        $this->container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn(
                [
                    'eventmanager' => [
                        'listeners' => [
                            [
                                'event' => 'event',
                            ],
                        ],
                    ],
                ]
            );

        $this->expectException(InvalidArgumentException::class);

        ($this->factory)($this->container);
    }

    public function testCanAttachListenerWithoutPriority(): void
    {
        $this->container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn(
                [
                    'eventmanager' => [
                        'listeners' => [
                            [
                                'event' => 'event',
                                'listener' => $listener = function () {
                                },
                            ],
                        ],
                    ],
                ]
            );

        $eventManager = ($this->factory)($this->container);

        self::assertEquals(
            [
                'event' => [
                    1 => [
                        [
                            $listener,
                        ],
                    ],
                ],
            ],
            $this->attachedEvents($eventManager)
        );
    }

    public function testCanAttachListenerWithPriority(): void
    {
        $this->container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn(
                [
                    'eventmanager' => [
                        'listeners' => [
                            [
                                'event' => 'event',
                                'listener' => $listener = function () {
                                },
                                'priority' => 50,
                            ],
                        ],
                    ],
                ]
            );

        $eventManager = ($this->factory)($this->container);

        self::assertEquals(
            [
                'event' => [
                    50 => [
                        [
                            $listener,
                        ],
                    ],
                ],
            ],
            $this->attachedEvents($eventManager)
        );
    }

    public function testCantAttachLazyListenerWithoutEvent(): void
    {
        $this->container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn(
                [
                    'eventmanager' => [
                        'lazy_listeners' => [
                            [],
                        ],
                    ],
                ]
            );

        $this->expectException(InvalidArgumentException::class);

        ($this->factory)($this->container);
    }

    public function testCantAttachLazyListenerWithoutListener(): void
    {
        $this->container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn(
                [
                    'eventmanager' => [
                        'lazy_listeners' => [
                            [
                                'event' => 'event',
                            ],
                        ],
                    ],
                ]
            );

        $this->expectException(InvalidArgumentException::class);

        ($this->factory)($this->container);
    }

    public function testCantAttachLazyListenerWithoutMethod(): void
    {
        $this->container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn(
                [
                    'eventmanager' => [
                        'lazy_listeners' => [
                            [
                                'event' => 'event',
                                'listener' => 'listener',
                            ],
                        ],
                    ],
                ]
            );

        $this->expectException(InvalidArgumentException::class);

        ($this->factory)($this->container);
    }

    public function testCanAttachLazyListenerWithoutPriority(): void
    {
        $this->container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn(
                [
                    'eventmanager' => [
                        'lazy_listeners' => [
                            [
                                'event' => 'event',
                                'listener' => 'listener',
                                'method' => 'method',
                            ],
                        ],
                    ],
                ]
            );

        $eventManager = ($this->factory)($this->container);

        self::assertEquals(
            [
                'event' => [
                    1 => [
                        [
                            new LazyListener(
                                [
                                    'event' => 'event',
                                    'listener' => 'listener',
                                    'method' => 'method',
                                ],
                                $this->container
                            ),
                        ],
                    ],
                ],
            ],
            $this->attachedEvents($eventManager)
        );
    }

    public function testCanAttachLazyListenerWithPriority(): void
    {
        $this->container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn(
                [
                    'eventmanager' => [
                        'lazy_listeners' => [
                            [
                                'event' => 'event',
                                'listener' => 'listener',
                                'method' => 'method',
                                'priority' => 50,
                            ],
                        ],
                    ],
                ]
            );

        $eventManager = ($this->factory)($this->container);

        self::assertEquals(
            [
                'event' => [
                    50 => [
                        [
                            new LazyListener(
                                [
                                    'event' => 'event',
                                    'listener' => 'listener',
                                    'method' => 'method',
                                    'priority' => 50,
                                ],
                                $this->container
                            ),
                        ],
                    ],
                ],
            ],
            $this->attachedEvents($eventManager)
        );
    }

    /**
     * Retrieve attached events from the manager.
     *
     * @param EventManagerInterface $eventManager
     * @return array
     */
    private function attachedEvents(EventManagerInterface $eventManager): array
    {
        $reflection = new \ReflectionClass($eventManager);
        $property = $reflection->getProperty('events');
        $property->setAccessible(true);
        $value = $property->getValue($eventManager);

        return $value ?? [];
    }
}
