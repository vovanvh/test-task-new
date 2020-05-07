<?php

namespace base;

use helpers\Request;
use helpers\Singleton;

/**
 * Class Application
 * implements singleton pattern
 *
 * @package base
 */
class Application
{
    use Singleton, Request;

    /**
     * Root application directory
     * @var string
     */
    protected $_root;

    /**
     * Config object
     * @var Config
     */
    protected $_config;

    /**
     * Default application controller
     * @var string
     */
    protected $_defaultController = 'site';

    /**
     * Default application action
     * @var string
     */
    protected $_defaultAction = 'index';

    /**
     * Object initialization method
     * @inheritdoc
     */
    protected function _init()
    {}

    /**
     * Run application method
     * @param string $root Root app path
     * @param string $config App configuration relation path
     * @throws \Exception
     */
    public function run($root, $config)
    {
        session_start();
        $this->_root = $root;
        $this->setConfig($root, $config);
        $this->_processRequest();
    }

    /**
     * Get application configuration
     * @param null|string $key
     * @return mixed
     * @throws \Exception
     */
    public function getConfig($key = null)
    {
        if ($this->_config === null) {
            throw new \Exception("Config not initialized yet");
        }
        return $this->_config->getConfig($key);
    }

    /**
     * Set application config
     * @param string $root Root app path
     * @param string $config App configuration relation path
     * @throws \Exception
     */
    public function setConfig($root, $config)
    {
        $configFile = $root . DIRECTORY_SEPARATOR . trim($config, DIRECTORY_SEPARATOR);
        if (!file_exists($configFile)) {
            throw new \Exception("Config file doesn't exists");
        }
        $config = include $configFile;
        $this->_config = Config::getInstance();
        $this->_config->setConfig($config);
    }

    /**
     * Process application request
     */
    protected function _processRequest()
    {
        /* @var Controller $controller */
        $controller = $this->_getController();
        $action = $this->_getAction();
        $controller->$action();
    }

    /**
     * Build and return controller object
     * @return Controller
     * @throws \Exception
     */
    protected function _getController()
    {
        $controller = $this->getParam('controller', null);
        if ($controller === null) {
            $controller = $this->_defaultController;
        }
        $controllerClass = 'controllers\\' . ucfirst($controller) . 'Controller';
        $controllerObject = new $controllerClass($this->_config);
        if (!$controllerObject instanceof Controller) {
            throw new \Exception("Controller must be an instance of \\base\\Controller");
        }
        $controllerObject->rootPath = $this->_root;
        $controllerObject->id = $controller;
        return $controllerObject;
    }

    /**
     * Get action method name
     * @return string
     */
    protected function _getAction()
    {
        $action = $this->getParam('action', null);
        if ($action === null) {
            $action = $this->_defaultAction;
        }
        return 'action' . ucfirst($action);
    }
}