<?php

declare(strict_types=1);

namespace FrankVerhoeven\EventManager;

use FrankVerhoeven\EventManager\Exception\InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\LazyListener;

/**
 * EventManagerFactory
 *
 * @author Frank Verhoeven <hi@frankverhoeven.me>
 */
final class EventManagerFactory
{
    /**
     * @param ContainerInterface $container
     * @return EventManagerInterface
     */
    public function __invoke(ContainerInterface $container): EventManagerInterface
    {
        $eventManager = new EventManager;

        $config = $container->get('config');
        $listeners = $config['eventmanager']['listeners'] ?? [];
        $lazyListeners = $config['eventmanager']['lazy_listeners'] ?? [];

        foreach ($listeners as $listener) {
            $this->attachListener($eventManager, $listener);
        }

        foreach ($lazyListeners as $lazyListener) {
            $this->attachLazyListener($eventManager, $lazyListener, $container);
        }

        return $eventManager;
    }

    /**
     * @param EventManagerInterface $eventManager
     * @param array $definition
     * @throws InvalidArgumentException
     */
    private function attachListener(EventManagerInterface $eventManager, array $definition): void
    {
        if (!isset($definition['event'])) {
            throw new InvalidArgumentException(\sprintf(
                '%s::%s requires $definition[\'event\']',
                static::class,
                __METHOD__
            ));
        }

        if (!isset($definition['listener'])) {
            throw new InvalidArgumentException(\sprintf(
                '%s::%s requires $definition[\'listener\']',
                static::class,
                __METHOD__
            ));
        }

        $priority = $definition['priority'] ?? null;

        $eventManager->attach($definition['event'], $definition['listener'], $priority);
    }

    /**
     * @param EventManagerInterface $eventManager
     * @param array $definition
     * @param ContainerInterface $container
     * @throws InvalidArgumentException
     */
    private function attachLazyListener(
        EventManagerInterface $eventManager,
        array $definition,
        ContainerInterface $container
    ): void {
        if (!isset($definition['event'])) {
            throw new InvalidArgumentException(\sprintf(
                '%s::%s requires $definition[\'event\']',
                static::class,
                __METHOD__
            ));
        }

        if (!isset($definition['listener'])) {
            throw new InvalidArgumentException(\sprintf(
                '%s::%s requires $definition[\'listener\'] to be callable',
                static::class,
                __METHOD__
            ));
        }

        if (!isset($definition['method'])) {
            throw new InvalidArgumentException(\sprintf(
                '%s::%s requires $definition[\'method\'] to be callable',
                static::class,
                __METHOD__
            ));
        }

        $priority = $definition['priority'] ?? null;
        $lazyListener = new LazyListener($definition, $container);

        $eventManager->attach($definition['event'], $lazyListener, $priority);
    }
}
