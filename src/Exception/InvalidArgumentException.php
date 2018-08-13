<?php

declare(strict_types=1);

namespace FrankVerhoeven\EventManager\Exception;

/**
 * @author Frank Verhoeven <hi@frankverhoeven.me>
 */
final class InvalidArgumentException extends \InvalidArgumentException
{
    /**
     * @return InvalidArgumentException
     */
    public static function missingEventDefinition(): self
    {
        return new static('No event was defined');
    }

    /**
     * @return InvalidArgumentException
     */
    public static function missingListenerDefinition(): self
    {
        return new static('No listener was defined');
    }

    /**
     * @return InvalidArgumentException
     */
    public static function missingMethodDefinition(): self
    {
        return new static('No method was defined which is required for lazy listeners');
    }
}
