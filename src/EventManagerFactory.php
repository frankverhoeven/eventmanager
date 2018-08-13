<?php

declare(strict_types=1);

namespace FrankVerhoeven\EventManager;

use FrankVerhoeven\EventManager\Exception\InvalidArgumentException;
use Interop\Container\ContainerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\LazyListener;

/**
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
            throw InvalidArgumentException::missingEventDefinition();
        }

        if (!isset($definition['listener'])) {
            throw InvalidArgumentException::missingListenerDefinition();
        }

        $priority = $definition['priority'] ?? 1;

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
            throw InvalidArgumentException::missingEventDefinition();
        }

        if (!isset($definition['listener'])) {
            throw InvalidArgumentException::missingListenerDefinition();
        }

        if (!isset($definition['method'])) {
            throw InvalidArgumentException::missingMethodDefinition();
        }

        $priority = $definition['priority'] ?? 1;
        $lazyListener = new LazyListener($definition, $container);

        $eventManager->attach($definition['event'], $lazyListener, $priority);
    }
}
