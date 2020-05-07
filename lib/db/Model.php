<?php

namespace db;

use helpers\DateTime;

/**
 * Class Model
 * Base class for all models
 *
 * @package db
 */
abstract class Model extends BaseModel
{
    use DateTime;

    /**
     * PDO instance
     * @var \PDO
     */
    protected $_db;

    protected $_context = 'insert';

    /**
     * Model constructor.
     * @param array $values
     */
    public function __construct($values = [])
    {
        parent::__construct($values);
        if ($this->_db === null) {
            $this->_db = Connection::getInstance()->getConnection();
        }
    }

    /**
     * Create new record
     *
     * @param bool $validate
     * @return bool
     * @throws \Exception
     */
    public function create($validate = true)
    {
        if (!isset($this->_values['created_at'])) {
            $this->_values['created_at'] = $this->getCurrentTimestamp();
        }
        if (!isset($this->_values['updated_at'])) {
            $this->_values['updated_at'] = $this->getCurrentTimestamp();
        }
        if ($this->validate() || !$validate) {
            $insertValues = array_values($this->_values);
            $placeholders = [];
            for ($i = 1; $i <= count($insertValues); $i++) {
                $placeholders[] = '?';
            }
            $sql = "INSERT INTO `" . $this->_getTableName() . "` (`" . implode('`, `', array_keys($this->_values)) . "`)"
                . " VALUES (" . implode(', ', $placeholders) . ")";
            $stm = $this->_db->prepare($sql);
            return $stm->execute(array_values($this->_values));
        }
        return false;
    }

    public function update($validate = true)
    {
        $this->_context = 'update';
        if ($this->validate() || !$validate) {
            $updateValues = $this->_values;
            unset($updateValues['id']);
            $placeholders = [];
            foreach ($updateValues as $field => $updateValue) {
                $placeholders[] = $field . '=?';
            }
            $sql = "UPDATE `" . $this->_getTableName() . "` SET " . implode(', ', $placeholders)
                . " WHERE id = " . $this->_values['id'];
            $stm = $this->_db->prepare($sql);
            return $stm->execute(array_values($updateValues));
        }
        return false;
    }

    /**
     * Return all records
     *
     * @param int|null $page
     * @param string|null $sort
     * @param int $limit
     * @return array
     */
    public function getAll($page = 1, $sort = null, $limit = 3)
    {
        $sql = "SELECT * FROM `" . $this->_getTableName() . "` ORDER BY " . ($sort === null ? 'id DESC' : $sort)
            . " LIMIT " . $limit . ' OFFSET ' . (($page - 1) * $limit);
        return $this->_db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get count
     * @return int
     */
    public function getCount()
    {
        $sql = "SELECT COUNT(*) as cnt FROM `" . $this->_getTableName() . "`";
        return (int) $this->_db->query($sql)->fetchColumn();
    }

    /**
     * @param int $id
     * @return array
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM `" . $this->_getTableName() . "` WHERE id = " . $id;
        return $this->_db->query($sql)->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Get model table name
     * @return string
     */
    abstract protected function _getTableName();

    /**
     * Validation method
     * @param string $field
     * @param mixed $value
     */
    protected function validateUnique($field, $value)
    {
        if ($this->_context == 'update') {
            return ;
        }
        $sql = "SELECT COUNT(*) as cnt FROM `" . $this->_getTableName() . "` WHERE " . $field . "='" . $value . "'";
        if ((int) $this->_db->query($sql)->fetchColumn() > 0) {
            $this->_errors[$field][] = "Task with such email already exists";
        }
    }
}