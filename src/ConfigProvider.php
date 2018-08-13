<?php

declare(strict_types=1);

namespace FrankVerhoeven\EventManager;

use Zend\EventManager\EventManagerInterface;

/**
 * @author Frank Verhoeven <hi@frankverhoeven.me>
 */
final class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'eventmanager' => $this->getEventManager(),
        ];
    }

    /**
     * @return array
     */
    private function getDependencies(): array
    {
        return [
            'factories' => [
                EventManagerInterface::class => EventManagerFactory::class,
            ],
        ];
    }

    /**
     * @return array
     */
    private function getEventManager(): array
    {
        return [
            'listeners' => [],
            'lazy_listeners' => [],
        ];
    }
}
