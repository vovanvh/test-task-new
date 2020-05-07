<?php

namespace db;

use base\Application;
use helpers\Singleton;

/**
 * Class Connection
 * implements singleton pattern
 *
 * @package db
 */
class Connection
{
    use Singleton;

    /**
     * MySQL server host name or ip
     * @var string
     */
    protected $_serverName = "localhost";

    /**
     * MySQL user name
     * @var string
     */
    protected $_userName;

    /**
     * MySQL user password
     * @var string
     */
    protected $_userPassword;

    /**
     * Application database name
     * @var string
     */
    protected $_dbName = 'test_simple_comments';

    /**
     * PDO connection
     * @var \PDO
     */
    protected $_connection;

    /**
     * Get pdo connection
     * @return \PDO
     */
    public function getConnection()
    {
        if ($this->_connection === null) {
            $this->_connection = $this->_setConnection();
        }
        return $this->_connection;
    }

    /**
     * Return pdo connection string
     * @return string
     */
    protected function _getConnectionString()
    {
        return 'mysql:host=' . $this->_serverName . ';dbname=' . $this->_dbName;
    }

    /**
     * Create pdo connection
     * @return \PDO
     */
    protected function _setConnection()
    {
        $conn = new \PDO(
            $this->_getConnectionString(),
            $this->_userName,
            $this->_userPassword
        );
        $conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    /**
     * Object initialization method
     * @inheritdoc
     * @throws \Exception
     */
    protected function _init()
    {
        $config = Application::getInstance()->getConfig('dbConnection');
        $this->_serverName = $config['serverName'];
        $this->_userName = $config['userName'];
        $this->_userPassword = $config['userPassword'];
        $this->_dbName = $config['dbName'];

        if (empty($this->_serverName) || empty($this->_userName)
            || empty($this->_userPassword) || empty($this->_dbName)) {
            
            throw new \Exception("Data base connection parameters error");
        }
    }
}