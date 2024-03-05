<?php
declare(strict_types=1);

namespace FWS;


/**
 * Class SingletonHook.
 */
abstract class SingletonHook extends Singleton
{

    /**
     * Singleton constructor.
     */
    protected function __construct()
    {
        $this->hooks();
    }


    /**
     * Abstract placeholder for hooks() method.
     */
    abstract protected function hooks(): void;

}
