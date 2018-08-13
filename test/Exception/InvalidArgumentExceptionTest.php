<?php

declare(strict_types=1);

namespace FrankVerhoeven\EventManagerTest\Exception;

use FrankVerhoeven\EventManager\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @author Frank Verhoeven <hi@frankverhoeven.me>
 */
final class InvalidArgumentExceptionTest extends TestCase
{
    public function testIsThrowable(): void
    {
        self::assertInstanceOf(\Throwable::class, new InvalidArgumentException());
    }

    public function testMissingEventDefinition(): void
    {
        $exception = InvalidArgumentException::missingEventDefinition();

        self::assertInstanceOf(InvalidArgumentException::class, $exception);
        self::assertEquals('No event was defined', $exception->getMessage());
    }

    public function testMissingListenerDefinition(): void
    {
        $exception = InvalidArgumentException::missingListenerDefinition();

        self::assertInstanceOf(InvalidArgumentException::class, $exception);
        self::assertEquals('No listener was defined', $exception->getMessage());
    }

    public function testMissingMethodDefinition(): void
    {
        $exception = InvalidArgumentException::missingMethodDefinition();

        self::assertInstanceOf(InvalidArgumentException::class, $exception);
        self::assertEquals('No method was defined which is required for lazy listeners', $exception->getMessage());
    }
}
