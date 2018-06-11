# EventManager via Config

### ConfigProvider

How to setup events configuration.

```php
final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'eventmanager' => $this->getEventManager(),
        ];
    }

    private function getEventManager(): array
    {
        return [
            'listeners' => [
                [
                    'event' => 'event.post',
                    'listener' => '',
                    'priority' => 10,
                ],
            ],
            'lazy_listeners' => [
                [
                    'event' => 'event.post',
                    'listener' => '',
                    'method' => '',
                    'priority' => 10,
                ],
            ],
        ];
    }
}
```

### Inject EventManager

How to inject the EventManager in your service.

```php
final class ServiceFactory
{
    public function __invoke(ContainerInterface $container): ServiceInterface
    {
        return new Service(
            $container->get(\Zend\EventManager\EventManagerInterface::class)
        );
    }
}
```
