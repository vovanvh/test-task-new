<?php

namespace helpers;

/**
 * Class Singleton
 * Singleton pattern implementation
 *
 * @package helpers
 */
trait Singleton
{
    /**
     * Singleton instance
     * @var self
     */
    static private $instance = null;

    /**
     * Singleton constructor.
     */
    private function __construct() {
        $this->_init();
    }

    /**
     * Php magic method
     */
    private function __clone()
    {}

    /**
     * Class initialization method
     */
    abstract protected function _init();

    /**
     * Singleton create instance method
     * @return static
     */
    static public function getInstance()
    {
        return
            self::$instance === null
                ? self::$instance = new static()
                : self::$instance;
    }
}