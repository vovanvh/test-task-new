<?php

namespace base;

use helpers\Singleton;

/**
 * Class Config
 * Simple application config class
 * implements singleton pattern
 *
 * @package base
 */
class Config
{
    use Singleton;

    /**
     * Application configuration
     * @var array
     */
    protected $_cfg;

    /**
     * Object initialization method
     * @inheritdoc
     */
    protected function _init()
    {}

    /**
     * Set application configuration
     * @param $config
     * @throws \Exception
     */
    public function setConfig($config)
    {
        if (!is_array($config)) {
            throw new \Exception("Config must be an array");
        }
        $this->_cfg = $config;
    }

    /**
     * Get application configuration
     * @param null|string $key
     * @return mixed
     * @throws \Exception
     */
    public function getConfig($key = null)
    {
        if ($key === null) {
            return $this->_cfg;
        }
        if (isset($this->_cfg[$key])) {
            return $this->_cfg[$key];
        } else {
            throw new \Exception("Wrong config key");
        }
    }
}