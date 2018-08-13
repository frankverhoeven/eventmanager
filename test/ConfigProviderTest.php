<?php

declare(strict_types=1);

namespace FrankVerhoeven\EventManagerTest;

use FrankVerhoeven\EventManager\ConfigProvider;
use FrankVerhoeven\EventManager\EventManagerFactory;
use PHPUnit\Framework\TestCase;
use Zend\EventManager\EventManagerInterface;

/**
 * @author Frank Verhoeven <hi@frankverhoeven.me>
 */
final class ConfigProviderTest extends TestCase
{
    public function testReturnsConfigArray(): void
    {
        self::assertEquals(
            [
                'dependencies' => [
                    'factories' => [
                        EventManagerInterface::class => EventManagerFactory::class,
                    ],
                ],
                'eventmanager' => [
                    'listeners' => [],
                    'lazy_listeners' => [],
                ],
            ],
            (new ConfigProvider())()
        );
    }
}
