<?php

namespace db;

/**
 * Class Model
 * Base class for all models
 *
 * @package db
 */
abstract class BaseModel
{

    /**
     * @var array
     */
    protected $_errors = [];

    /**
     * @var array
     */
    protected $_values = [];

    /**
     * Model constructor.
     * @param array $values
     */
    public function __construct($values = [])
    {
        if (!empty($values)) {
            $this->_values = $values;
        }
    }

    /**
     * @param array $values
     * @return $this
     */
    public function setValues(array $values)
    {
        $this->_values = array_merge(
            $this->_values,
            array_map(
                'trim',
                array_map(
                    'htmlspecialchars',
                    $values
                )
            )
        );
        return $this;
    }

    /**
     * @param array $fieldValues
     * @param bool $update
     * @return bool
     * @throws \Exception
     */
    public function validate($fieldValues = [])
    {
        foreach ($this->getValidationRules() as $field => $rules) {
            $fieldValue = $fieldValues[$field] ?? $this->_values[$field];
            foreach ($rules as $rule) {
                $methodName = 'validate' . ucfirst($rule);
                if (method_exists($this, $methodName)) {
                    $this->$methodName($field, $fieldValue);
                } else {
                    throw new \Exception('Undefined validator');
                }
            }
        }
        return empty($this->_errors);
    }

    /**
     * @param $field
     * @return array|mixed
     */
    public function getErrorsByField($field)
    {
        return !empty($this->_errors[$field]) ? $this->_errors[$field] : [];
    }

    /**
     * Get array of validation rules
     * @return array
     */
    abstract protected function getValidationRules();

    /**
     * Validation method
     * @param string $field
     * @param mixed $value
     */
    protected function validateRequired($field, $value)
    {
        if (empty($value)) {
            $this->_errors[$field][] = "Can't be an empty";
        }
    }

    /**
     * Validation method
     * @param string $field
     * @param mixed $value
     */
    protected function validateEmail($field, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->_errors[$field][] = 'Invalid email';
        }
    }
}