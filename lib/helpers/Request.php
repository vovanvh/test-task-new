<?php

namespace helpers;

/**
 * Class Request
 *
 * @package helpers
 */
trait Request
{
    /**
     * If post request return true else return false
     * @return bool
     */
    public function getIsPostRequest()
    {
        return $this->getRequestMethod() == 'POST';
    }

    /**
     * Return request type, default GET
     * @return string
     */
    public function getRequestMethod()
    {
        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
        }

        if (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }

        return 'GET';
    }

    /**
     * Return POST parameter
     * @param string $name Request parameter name
     * @param mixed $default Default value
     * @return mixed
     */
    public function getPostParam($name, $default = null)
    {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }
        return $default;
    }

    /**
     * Return query parameter
     * @param string $name Request parameter name
     * @param mixed $default Default value
     * @return mixed
     */
    public function getParam($name, $default = null)
    {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }
        if (isset($_REQUEST[$name])) {
            return $_REQUEST[$name];
        }
        return $default;
    }
}