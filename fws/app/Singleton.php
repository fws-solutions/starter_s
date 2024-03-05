<?php
declare(strict_types=1);

namespace FWS;


/**
 * Class Singleton.
 */
abstract class Singleton
{

    /**
     * Return singleton instance of this class.
     *
     * @return static
     */
    public static function getInstance(): self
    {
        static $instance = [];

        if (!isset($instance[static::class])) {
            $instance[static::class] = new static(); // @phpstan-ignore-line
        }

        return $instance[static::class];
    }


    /**
     * Execute singleton but only once,
     * can be overridden by descendant classes.
     */
    public static function init(): void
    {
        static::getInstance();
    }

}
