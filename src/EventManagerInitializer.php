<?php

declare(strict_types=1);

namespace FrankVerhoeven\EventManager;

use Interop\Container\ContainerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\Initializer\InitializerInterface;

/**
 * @author Frank Verhoeven <hi@frankverhoeven.me>
 */
final class EventManagerInitializer implements InitializerInterface
{
    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        if (!$instance instanceof EventManagerAwareInterface) {
            return;
        }

        $instance->setEventManager($container->get(EventManagerInterface::class));
    }
}
